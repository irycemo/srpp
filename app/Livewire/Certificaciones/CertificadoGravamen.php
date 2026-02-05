<?php

namespace App\Livewire\Certificaciones;

use App\Models\User;
use App\Models\Predio;
use App\Traits\QrTrait;
use Livewire\Component;
use App\Models\Gravamen;
use Livewire\WithPagination;
use App\Models\Certificacion;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Traits\CalcularDiaElaboracionTrait;
use App\Http\Services\SistemaTramitesService;
use App\Exceptions\InscripcionesServiceException;
use App\Traits\Inscripciones\ReasignarmeMovimientoTrait;
use App\Traits\RevisarMovimientosPosterioresTrait;

class CertificadoGravamen extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;
    use QrTrait;
    use CalcularDiaElaboracionTrait;
    use RevisarMovimientosPosterioresTrait;
    use ReasignarmeMovimientoTrait;

    public Certificacion $modelo_editar;

    public $moviminetoRegistral;

    public $predio;

    public $gravamenes;

    public $director;

    public $modalRechazar = false;
    public $modalReasignarUsuario = false;
    public $modalFinalizar = false;

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
        'estado' => '',
        'usuario_asignado' => ''
    ];

    protected function rules()
    {

        return ['usuario_asignado' => 'required'];

    }

    public function crearModeloVacio(){
        $this->modelo_editar = Certificacion::make();
    }

    public function updatedFilters() { $this->resetPage(); }

    public function abrirModalRechazar(Certificacion $modelo){

        $this->resetearTodo();
            $this->modalRechazar = true;
            $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function abrirModalReasignar(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignarUsuario = true;

    }

    public function reasignarUsuario(){

        try {

            $this->modelo_editar->movimientoRegistral->usuario_asignado = $this->usuario_asignado;

            $this->modelo_editar->movimientoRegistral->save();

            $this->modelo_editar->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            Log::error("Error al reasignar movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignarUsuarioAleatoriamente(){

        $cantidad = $this->modelo_editar->movimientoRegistral->audits()->where('tags', 'Reasignó usuario')->count();

        if($cantidad >= 2){

            $this->dispatch('mostrarMensaje', ['warning', "Ya se ha reasignado multiples veces."]);

            return;

        }

        try {

            $this->modelo_editar->movimientoRegistral->usuario_asignado = $this->usuarios->where('ubicacion', 'Regional 1')->random()->id;
            $this->modelo_editar->movimientoRegistral->actualizado_por = auth()->id();
            $this->modelo_editar->movimientoRegistral->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function visualizarGravamenes(Certificacion $modelo){

        $this->modelo_editar = $modelo;

        $this->moviminetoRegistral = $modelo->movimientoRegistral;

        if($this->moviminetoRegistral->getRawOriginal('distrito') != 2 && !auth()->user()->hasRole(['Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($this->moviminetoRegistral)) return;

        }

        $movimientoAsignados = MovimientoRegistral::whereIn('estado', ['nuevo'])
                                                        ->where('usuario_Asignado', auth()->id())
                                                        ->withWhereHas('folioReal', function($q){
                                                            $q->where('estado', 'activo');
                                                        })
                                                        ->whereHas('certificacion', function($q){
                                                            $q->where('servicio', 'DL07');
                                                        })
                                                        ->orderBy('created_at')
                                                        ->get();

        if(! auth()->user()->ubicacion == 'Regional 4'){

            foreach($movimientoAsignados as $movimiento){

                if($movimiento->tipo_servicio == 'ordinario'){

                    if($movimiento->fecha_entrega <= now()){

                        if($this->moviminetoRegistral->id == $movimiento->id){

                            break;

                        }else{

                            $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $movimiento->folioReal->folio . '-' . $movimiento->folio . ' primero.']);

                            return;

                        }

                    }else{

                        continue;

                    }

                }else{

                    if($this->moviminetoRegistral->id != $movimiento->id){

                        $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $movimiento->folioReal->folio . '-' . $movimiento->folio . ' primero.']);

                        return;

                    }else{

                        break;

                    }

                }

            }

        }

        $this->predio = Predio::where('folio_real', $this->moviminetoRegistral->folio_real)->first();

        $this->gravamenes = Gravamen::with('deudores',  'acreedores')
                                        ->withWhereHas('movimientoRegistral', function($q) {
                                            $q->where('folio_real', $this->moviminetoRegistral->folio_real);
                                        })
                                        ->where('estado', 'activo')
                                        ->get();

        $this->modal = true;

    }

    public function generarCertificado(){

        if(!auth()->user()->hasRole(['Jefe de departamento certificaciones']) && $this->modelo_editar->movimientoRegistral->distrito != '02 Uruapan'){

            if($this->calcularDiaElaboracion($this->modelo_editar->movimientoRegistral)) return;

        }

        $this->modal = false;

        try{

            DB::transaction(function (){

                $this->moviminetoRegistral->estado = 'elaborado';

                $this->moviminetoRegistral->save();

                if(auth()->user()->hasRole(['Supervisor certificaciones', 'Supervisor uruapan'])){

                    $this->modelo_editar->reimpreso_en = now();

                }

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->save();

                if($this->modelo_editar->movimientoRegistral->fecha_entrega > now()){

                    $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Generó certificado anticipadamente.']);

                }

                $this->dispatch('imprimir_documento', ['gravamen' => $this->moviminetoRegistral->id]);

                $this->modal = false;

                $this->reset('predio');

            });

            if($this->moviminetoRegistral->usuario_tramites_linea_id){

                Cache::forget('estadisticas_tramites_en_linea_' . $this->moviminetoRegistral->usuario_tramites_linea_id);

            }

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function abrirModalFinalizar(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function finalizarSupervisor(){

        try {

            DB::transaction(function (){

                $this->modelo_editar->finalizado_en = now();

                $this->modelo_editar->firma = now();

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->movimientoRegistral->estado = 'concluido';

                $this->modelo_editar->movimientoRegistral->save();

                $this->modelo_editar->save();

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, 'concluido');

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, $this->motivo . ' ' . $observaciones);

                $this->modelo_editar->movimientoRegistral->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

            });

            if($this->moviminetoRegistral->usuario_tramites_linea_id){

                Cache::forget('estadisticas_tramites_en_linea_' . $this->moviminetoRegistral->usuario_tramites_linea_id);

            }

            $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

            $this->modalRechazar = false;

            $pdf = Pdf::loadView('rechazos.rechazo', [
                'movimientoRegistral' => $this->modelo_editar->movimientoRegistral,
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

    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        try {

            $movimientoRegistral->certificacion->update(['reimpreso_en' => now()]);

            $firmaElectronica = $movimientoRegistral->firmaElectronica;

            $objeto = json_decode($firmaElectronica->cadena_original);

            if(auth()->user()->hasRole('Regional')){

                $firma_electronica = base64_encode($firmaElectronica->cadena_encriptada);

            }else{

                $firma_electronica = false;
            }

            $pdf = Pdf::loadView('certificaciones.certificadoGravamen', [
                'predio' => $objeto->predio,
                'director' => $objeto->director,
                'gravamenes' => $objeto->gravamenes,
                'folioReal' => $objeto->folioReal,
                'varios' => $objeto->varios,
                'sentencias' => $objeto->sentencias,
                'datos_control' => $objeto->datos_control,
                'firma_electronica' => $firma_electronica,
                'qr'=> $this->generadorQr($firmaElectronica->uuid)
            ]);

            $pdf->render();

            $dom_pdf = $pdf->getDomPDF();

            $canvas = $dom_pdf->get_canvas();

            $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1,1,1));

            $canvas->page_text(35, 745, $movimientoRegistral->folioReal->folio . '-' .$movimientoRegistral->folio, null, 9, array(1, 1, 1));

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al reimiprimir certificado de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function seleccionarMotivo($key){

        $this->motivo = $this->motivos[$key];

    }

    public function corregir(MovimientoRegistral $movimientoRegistral){

        try {

            $this->revisarMovimientosPosteriores($movimientoRegistral);

            DB::transaction(function () use ($movimientoRegistral){

                $movimientoRegistral->update([
                    'estado' => 'correccion',
                    'actualizado_por' => auth()->id()
                ]);

                $movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Cambio estado a corrección']);

            });

            if($movimientoRegistral->usuario_tramites_linea_id){

                Cache::forget('estadisticas_tramites_en_linea_' . $movimientoRegistral->usuario_tramites_linea_id);

            }

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

        $this->años = Constantes::AÑOS;

        $this->año = now()->year;

        $this->director = User::where('status', 'activo')
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Director');
                                })->first();

        if(!$this->director) abort(500, message:"Es necesario registrar al director.");

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

        $this->usuarios_regionales = Constantes::USUARIOS_REGIONALES;

        $this->usuarios = User::where('status', 'activo')
                                ->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Certificador Gravamen']);
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

        if(auth()->user()->hasRole(['Certificador Gravamen', 'Certificador Oficialia', 'Certificador Juridico'])){

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->where('usuario_asignado', auth()->id())
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela']);
                                                })
                                                ->whereIn('estado', ['nuevo', 'correccion'])
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL07')
                                                        ->whereNull('finalizado_en');
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['usuario_asignado'], fn($q, $usuario_asignado) => $q->where('usuario_asignado', $usuario_asignado))
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

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela']);
                                                })
                                                ->whereIn('estado', ['nuevo', 'elaborado', 'correccion'])
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL07');
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['usuario_asignado'], fn($q, $usuario_asignado) => $q->where('usuario_asignado', $usuario_asignado))
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

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela']);
                                                })
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL07');
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['usuario_asignado'], fn($q, $usuario_asignado) => $q->where('usuario_asignado', $usuario_asignado))
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

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela', 'bloqueado']);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL07');
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['usuario_asignado'], fn($q, $usuario_asignado) => $q->where('usuario_asignado', $usuario_asignado))
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

            $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                                ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                                ->when(auth()->user()->ubicacion === 'Regional 1', function($q){
                                                    $q->where(function($q){
                                                        $q->whereIn('distrito', [3, 9]);

                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 2', function($q){
                                                    $q->where(function($q){
                                                        $q->whereIn('distrito', [12, 19]);

                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 3', function($q){
                                                    $q->where(function($q){
                                                        $q->whereIn('distrito', [4, 17]);

                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 4', function($q){
                                                    $q->where(function($q){
                                                        $q->whereIn('distrito', [2, 18]);

                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 5', function($q){
                                                    $q->where(function($q){
                                                        $q->where('distrito', 13);

                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 6', function($q){
                                                    $q->where(function($q){
                                                        $q->where('distrito', 15);

                                                    });
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 7', function($q){
                                                    $q->where(function($q){
                                                       $q->whereIn('distrito', [5, 14, 8]);

                                                    });
                                                })
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela', 'bloqueado']);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL07');
                                                })
                                                ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                ->when($this->filters['usuario_asignado'], fn($q, $usuario_asignado) => $q->where('usuario_asignado', $usuario_asignado))
                                                ->when($this->filters['folio_real'], function($q){
                                                    $q->whereHas('folioreal', function ($q){
                                                        $q->where('folio', $this->filters['folio_real']);
                                                    });
                                                })
                                                ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                ->whereNotNull('folio_real')
                                                ->whereNotIn('estado', ['nuevo', 'correccion'])
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);



        }

        return view('livewire.certificaciones.certificado-gravamen', compact('certificados'))->extends('layouts.admin');
    }

}
