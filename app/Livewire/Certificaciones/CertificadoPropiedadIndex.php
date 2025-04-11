<?php

namespace App\Livewire\Certificaciones;

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

class CertificadoPropiedadIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use CalcularDiaElaboracionTrait;
    use RevisarMovimientosPosterioresTrait;

    public MovimientoRegistral $modelo_editar;

    public $modalFinalizar = false;

    public $modalRechazar = false;

    public $actual;

    public $observaciones;

    public $motivos;
    public $motivo;

    public $años;
    public $filters = [
        'año' => '',
        'tramite' => '',
        'usuario' => '',
        'folio_real' => '',
        'folio' => '',
        'estado' => ''
    ];

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

        $movimientoAsignados = MovimientoRegistral::whereIn('estado', ['nuevo'])
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

        }

        return redirect()->route('certificado_propiedad', $movimientoRegistral->certificacion);

    }

    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        if($movimientoRegistral->certificacion->tipo_certificado == 1){

            $this->dispatch('imprimir_negativo_propiedad', ['certificacion' => $movimientoRegistral->id]);

        }elseif($movimientoRegistral->certificacion->tipo_certificado == 2){

            $this->dispatch('imprimir_propiedad', ['certificacion' => $movimientoRegistral->id]);

        }if($movimientoRegistral->certificacion->tipo_certificado == 3){

            $this->dispatch('imprimir_unico_propiedad', ['certificacion' => $movimientoRegistral->id]);

        }if($movimientoRegistral->certificacion->tipo_certificado == 5){

            $this->dispatch('imprimir_negativo', ['certificacion' => $movimientoRegistral->id]);

        }if($movimientoRegistral->certificacion->tipo_certificado == 4){

            $this->dispatch('imprimir_certificado_colindancias', ['certificacion' => $movimientoRegistral->id]);

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

        $this->filters['año'] = now()->format('Y');

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Certificador Propiedad', 'Certificador Oficialia', 'Certificador Juridico'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->where(function($q){
                                                    $q->whereNotNull('tomo')
                                                        ->whereNotNull('registro')
                                                        ->whereNotNull('numero_propiedad')
                                                        ->whereNotNull('folio_real')
                                                        ->orWhere(function($q){
                                                            $q->whereNull('tomo')
                                                                ->whereNull('registro')
                                                                ->whereNull('numero_propiedad')
                                                                ->whereNull('folio_real');
                                                        });
                                                })
                                                ->where('usuario_asignado', auth()->id())
                                                ->whereIn('estado', ['nuevo', 'correccion'])
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DL10', 'DL11'])
                                                        ->whereNull('finalizado_en');
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor certificaciones', 'Supervisor uruapan'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->where(function($q){
                                                    $q->whereNotNull('tomo')
                                                        ->whereNotNull('registro')
                                                        ->whereNotNull('numero_propiedad')
                                                        ->whereNotNull('folio_real')
                                                        ->orWhere(function($q){
                                                            $q->whereNull('tomo')
                                                                ->whereNull('registro')
                                                                ->whereNull('numero_propiedad')
                                                                ->whereNull('folio_real');
                                                        });
                                                })
                                                ->whereIn('estado', ['nuevo', 'elaborado', 'correccion'])
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DL10', 'DL11'])
                                                        ->whereNull('finalizado_en')
                                                        ->whereNull('folio_carpeta_copias');
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->where(function($q){
                                                    $q->whereNotNull('tomo')
                                                        ->whereNotNull('registro')
                                                        ->whereNotNull('numero_propiedad')
                                                        ->whereNotNull('folio_real')
                                                        ->orWhere(function($q){
                                                            $q->whereNull('tomo')
                                                                ->whereNull('registro')
                                                                ->whereNull('numero_propiedad')
                                                                ->whereNull('folio_real');
                                                        });
                                                })
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DL10', 'DL11']);
                                                })
                                                ->whereIn('estado', ['nuevo', 'elaborado', 'correccion'])
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->where(function($q){
                                                    $q->whereNotNull('tomo')
                                                        ->whereNotNull('registro')
                                                        ->whereNotNull('numero_propiedad')
                                                        ->whereNotNull('folio_real')
                                                        ->orWhere(function($q){
                                                            $q->whereNull('tomo')
                                                                ->whereNull('registro')
                                                                ->whereNull('numero_propiedad')
                                                                ->whereNull('folio_real');
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
                                                        $q->where('folio', $this->filters['folio_real']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Regional'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->where('estado', 'elaborado')
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela', 'bloqueado']);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 1', function($q){
                                                    $q->whereIn('distrito', [3, 9]);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 2', function($q){
                                                    $q->whereIn('distrito', [12, 19]);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 3', function($q){
                                                    $q->whereIn('distrito', [4, 17]);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 4', function($q){
                                                    $q->whereIn('distrito', [2, 18]);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 5', function($q){
                                                    $q->where('distrito', 13);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 6', function($q){
                                                    $q->where('distrito', 15);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 7', function($q){
                                                    $q->whereIn('distrito', [5, 14, 8]);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DL10', 'DL11']);
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real']);
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
