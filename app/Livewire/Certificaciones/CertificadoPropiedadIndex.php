<?php

namespace App\Livewire\Certificaciones;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Traits\CalcularDiaElaboracionTrait;
use App\Http\Services\SistemaTramitesService;
use App\Exceptions\InscripcionesServiceException;
use App\Traits\RevisarMovimientosPosterioresTrait;
use App\Http\Controllers\Certificaciones\CertificadoPropiedadController;

class CertificadoPropiedadIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use CalcularDiaElaboracionTrait;
    use RevisarMovimientosPosterioresTrait;

    public MovimientoRegistral $modelo_editar;

    public $modalFinalizar = false;

    public $modalRechazar = false;
    public $modalReasignarUsuario = false;

    public $actual;

    public $observaciones;

    public $motivos;
    public $motivo;

    public $usuarios;
    public $usuarios_regionales;
    public $usuarios_regionales_fliped;
    public $usuario_asignado;

    public $años;
    public $filters = [
        'año' => '',
        'tramite' => '',
        'usuario' => '',
        'folio_real' => '',
        'folio' => '',
        'estado' => ''
    ];

    protected function rules()
    {

        return ['usuario_asignado' => 'required'];

    }

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function updatedFilters() { $this->resetPage(); }


    public function estaBloqueado(){

        $movimientos = $this->actual->folioReal->movimientosRegistrales()->whereIn('estado', ['nuevo', 'captura'])->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($this->actual->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: (" . $this->actual->folioReal->folio . '-' . $primerMovimiento->folio . ') debe elaborarce primero.']);

                return true;

            }else{

               return false;

            }

        }else{

            return false;

        }

    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        if($movimientoRegistral->getRawOriginal('distrito') != 2 && !auth()->user()->hasRole(['Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($movimientoRegistral)) return;

        }

        /* $movimientoAsignados = MovimientoRegistral::whereIn('estado', ['nuevo'])
                                                        ->where('usuario_Asignado', auth()->id())
                                                        ->withWhereHas('folioReal', function($q){
                                                            $q->where('estado', 'activo');
                                                        })
                                                        ->whereHas('certificacion', function($q){
                                                            $q->whereIn('servicio', ['DL10', 'DL11']);
                                                        })
                                                        ->orderBy('created_at')
                                                        ->get();

        foreach($movimientoAsignados as $movimiento){

            if($movimiento->tipo_servicio == 'ordinario'){

                if($movimiento->fecha_entrega <= now()){

                    if($movimientoRegistral->id == $movimiento->id){

                        break;

                    }else{

                        $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $movimiento->folioReal->folio . '-' . $movimiento->folio . ' primero.']);

                        return;

                    }

                }else{

                    continue;

                }

            }else{

                if($movimientoRegistral->id != $movimiento->id){

                    $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $movimiento->folioReal->folio . '-' . $movimiento->folio . ' primero.']);

                    return;

                }else{

                    break;

                }

            }

        } */

        return redirect()->route('certificado_propiedad', $movimientoRegistral->certificacion);

    }

    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        try {

            if($movimientoRegistral->certificacion->tipo_certificado == 1){

                /* $this->dispatch('imprimir_negativo_propiedad', ['certificacion' => $movimientoRegistral->id]); */

                $pdf = (new CertificadoPropiedadController())->reimprimircertificadoNegativoPropiedad($movimientoRegistral->firmaElectronica);

            }elseif($movimientoRegistral->certificacion->tipo_certificado == 2){

                /* $this->dispatch('imprimir_propiedad', ['certificacion' => $movimientoRegistral->id]); */

                $pdf = (new CertificadoPropiedadController())->reimprimircertificadoPropiedad($movimientoRegistral->firmaElectronica);

            }if($movimientoRegistral->certificacion->tipo_certificado == 3){

                /* $this->dispatch('imprimir_unico_propiedad', ['certificacion' => $movimientoRegistral->id]); */

                $pdf = (new CertificadoPropiedadController())->reimprimircertificadoUnicoPropiedad($movimientoRegistral->firmaElectronica);

            }if($movimientoRegistral->certificacion->tipo_certificado == 5){

                /* $this->dispatch('imprimir_negativo', ['certificacion' => $movimientoRegistral->id]); */

                if($movimientoRegistral->servicio_nombre == 'Certificado negativo de vivienda bienestar'){

                    $bienestar = true;

                }else{

                    $bienestar = false;

                }

                $pdf = (new CertificadoPropiedadController())->reimprimircertificadoNegativo($movimientoRegistral->firmaElectronica, $bienestar);

            }if($movimientoRegistral->certificacion->tipo_certificado == 4){

                $this->dispatch('imprimir_certificado_colindancias', ['certificacion' => $movimientoRegistral->id]);

            }

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al reimiprimir caratula de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function abrirModalRechazar(MovimientoRegistral $modelo){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia', 'Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($modelo)) return;

        }

        $this->resetearTodo();
            $this->modalRechazar = true;
            $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function abrirModalReasignar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignarUsuario = true;

    }

    public function reasignarUsuario(){

        $this->validate();

        try {

            $this->modelo_editar->update(['usuario_asignado' => $this->usuario_asignado]);

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            Log::error("Error al reasignar movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignarUsuarioAleatoriamente(){

        $cantidad = $this->modelo_editar->audits()->where('tags', 'Reasignó usuario')->count();

        if($cantidad >= 2){

            $this->dispatch('mostrarMensaje', ['warning', "Ya se ha reasignado multiples veces."]);

            return;

        }

        try {

            $this->modelo_editar->usuario_asignado = $this->usuarios->random()->id;
            $this->modelo_editar->actualizado_por = auth()->id();
            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function finalizarMovimientoFolio(){

        try {

            DB::transaction(function () {

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'concluido';

                $this->modelo_editar->save();

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizar(MovimientoRegistral $modelo){

        try {

            DB::transaction(function () use ($modelo){

                $modelo->actualizado_por = auth()->user()->id;

                $modelo->estado = 'concluido';

                $modelo->save();

                (new SistemaTramitesService())->finaliarTramite($modelo->año, $modelo->tramite, $modelo->usuario, 'concluido');

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizarSupervisor(){

        try {

            DB::transaction(function (){

                $this->modelo_editar->certificacion->finalizado_en = now();

                $this->modelo_editar->certificacion->firma = now();

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'concluido';

                $this->modelo_editar->save();

                $this->modelo_editar->save();

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar certificado de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function () {

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, $this->motivo . ' ' . $observaciones);

                $this->modelo_editar->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

            $this->modalRechazar = false;

            $pdf = Pdf::loadView('rechazos.rechazo', [
                'movimientoRegistral' => $this->modelo_editar,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones
            ])->output();

            return response()->streamDownload(
                fn () => print($pdf),
                'rechazo.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al rechazar certificado de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function seleccionarMotivo($key){

        $this->motivo = $this->motivos[$key];

    }

    public function corregir(MovimientoRegistral $movimientoRegistral){

        if($this->modelo_editar->isNot($movimientoRegistral))
            $this->modelo_editar = $movimientoRegistral;

        try {

            if($this->modelo_editar->folio_real) $this->revisarMovimientosPosteriores($this->modelo_editar);

            DB::transaction(function (){

                $this->modelo_editar->update([
                    'estado' => 'correccion',
                    'actualizado_por' => auth()->id()
                ]);

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Cambio estado a corrección']);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (InscripcionesServiceException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al enviar a corrección certificado de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

        $this->años = Constantes::AÑOS;

        $this->usuarios_regionales = Constantes::USUARIOS_REGIONALES;

        $this->usuarios = User::where('status', 'activo')
                                ->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Certificador Propiedad']);
                                })
                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->orderBy('name')
                                ->get();

        if(auth()->user()->hasRole(['Regional'])){

            $regional = auth()->user()->ubicacion[-1];

            $this->usuarios_regionales_fliped = array_keys($this->usuarios_regionales, $regional);

        }

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Certificador Propiedad', 'Certificador Oficialia', 'Certificador Juridico'])){

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->where('usuario_asignado', auth()->id())
                                                ->whereIn('estado', ['nuevo', 'correccion'])
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DL10', 'DL11']);
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real'])
                                                            ->whereIn('estado', ['activo', 'centinela']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor certificaciones', 'Supervisor uruapan'])){

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->whereIn('estado', ['nuevo', 'elaborado', 'correccion'])
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DL10', 'DL11']);
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real'])
                                                            ->whereIn('estado', ['activo', 'centinela']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones'])){

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DL10', 'DL11']);
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real'])
                                                            ->whereIn('estado', ['activo', 'centinela']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico'])){

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real'])
                                                            ->whereIn('estado', ['activo', 'centinela']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DL10', 'DL11']);
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Regional'])){

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->where('estado', 'elaborado')
                                                ->when(auth()->user()->ubicacion === 'Regional 1', function($q){
                                                    $q->where(function($q){
                                                        $q->whereIn('distrito', [3, 9])
                                                         ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 2', function($q){
                                                    $q->where(function($q){
                                                        $q->whereIn('distrito', [12, 19])
                                                         ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 3', function($q){
                                                    $q->where(function($q){
                                                        $q->whereIn('distrito', [4, 17])
                                                         ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 4', function($q){
                                                    $q->where(function($q){
                                                        $q->whereIn('distrito', [2, 18])
                                                         ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 5', function($q){
                                                    $q->where(function($q){
                                                        $q->where('distrito', 13)
                                                         ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 6', function($q){
                                                    $q->where(function($q){
                                                        $q->where('distrito', 15)
                                                         ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 7', function($q){
                                                    $q->where(function($q){
                                                        $q->whereIn('distrito', [5, 14, 8])
                                                         ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    });
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DL10', 'DL11']);
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real'])
                                                            ->whereIn('estado', ['activo', 'centinela']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }

        return view('livewire.certificaciones.certificado-propiedad-index', compact('certificados'))->extends('layouts.admin');
    }

}
