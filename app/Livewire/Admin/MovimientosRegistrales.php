<?php

namespace App\Livewire\Admin;

use App\Constantes\Constantes;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use App\Http\Controllers\Certificaciones\CertificadoGravamenController;
use App\Http\Controllers\Gravamen\GravamenController;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Http\Controllers\Subdivisiones\SubdivisionesController;
use App\Http\Controllers\Varios\VariosController;
use App\Models\MovimientoRegistral;
use App\Models\User;
use App\Traits\ComponentesTrait;
use App\Traits\Inscripciones\EnviarMovimientoCorreccion;
use App\Traits\Inscripciones\RechazarMovimientoTrait;
use App\Traits\MovimientoRegistral\CambiarAntecedenteTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class MovimientosRegistrales extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use RechazarMovimientoTrait;
    use EnviarMovimientoCorreccion;
    use CambiarAntecedenteTrait;

    public MovimientoRegistral $modelo_editar;

    public $distritos;
    public $usuarios = [];
    public $usuarios_filtro;
    public $supervisores = [];
    public $años;

    public $modalReasignarUsuario = false;
    public $modalReasignarSupervisor = false;

    public $mensaje;

    public $filters = [
        'año' => '',
        'tramite' => '',
        'usuario' => '',
        'folio_real' => '',
        'folio' => '',
        'estado' => '',
        'tomo' => '',
        'registro' => '',
        'numero_propiedad' => '',
        'distrito' => '',
        'usuario_asignado'=> '',
    ];

    protected function rules(){
        return [
            'modelo_editar.usuario_asignado' => 'required',
            'modelo_editar.usuario_supervisor' => 'required',
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.usuario_asignado' => 'usuario asignado',
        'modelo_editar.usuario_supervisor' => 'usuario supervisor',
    ];

    public function updatedFilters() { $this->resetPage(); }

    public function crearModeloVacio(){

        $this->modelo_editar = MovimientoRegistral::make();

    }

    public function abrirModalReasignarSupervisor(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignarSupervisor = true;

    }

    public function abrirModalReasignarUsuario(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if($this->modelo_editar->inscripcionPropiedad){

            $this->cargarUsuarios(['Propiedad', 'Registrador Propiedad', 'Pase a folio']);

            $this->cargarSupervisores(['Supervisor inscripciones']);

        }

        if($this->modelo_editar->gravamen){

            $this->cargarUsuarios(['Gravamen', 'Registrador Gravamen', 'Pase a folio']);

            $this->cargarSupervisores(['Supervisor inscripciones']);

        }

        if($this->modelo_editar->vario){

            $this->cargarUsuarios(['Varios', 'Registrador Varios', 'Pase a folio', 'Aclaraciones administrativas', 'Avisos preventivos']);

            $this->cargarSupervisores(['Supervisor inscripciones']);

        }

        if($this->modelo_editar->cancelacion){

            $this->cargarUsuarios(['Cancelación', 'Registrador cancelación', 'Pase a folio']);

            $this->cargarSupervisores(['Supervisor inscripciones']);

        }

        if($this->modelo_editar->sentencia){

            $this->cargarUsuarios(['Sentencias', 'Registrador sentencias', 'Pase a folio']);

            $this->cargarSupervisores(['Supervisor inscripciones']);

        }

        if($this->modelo_editar->reformaMoral){

            $this->cargarUsuarios(['Folio real moral']);

            $this->cargarSupervisores(['Supervisor inscripciones']);

        }

        if($this->modelo_editar->certificacion){

            if($this->modelo_editar->certificacion->servicio == 'DL07'){

                $this->cargarUsuarios(['Certificador Gravamen', 'Pase a folio']);

                $this->cargarSupervisores(['Supervisor certificaciones']);

            }elseif(in_array($this->modelo_editar->certificacion->servicio, ['DL11', 'DL10'])){

                $this->cargarUsuarios(['Certificador Propiedad', 'Pase a folio']);

                $this->cargarSupervisores(['Supervisor certificaciones']);

            }

        }

        if(!count($this->usuarios)){

            $this->dispatch('mostrarMensaje', ['warning', "No hay usuarios activos para reasignar el movimiento registral."]);

        }else{

            $this->modalReasignarUsuario = true;

        }

    }

    public function reasignarUsuario(){

        $this->validate();

        try {

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

    public function reasignarUsuarioAleatoriamente(){

        try {

            $id = $this->usuarios->when($this->modelo_editar->getRawOriginal('distrito') == 'Regional 4', function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($this->modelo_editar->getRawOriginal('distrito') != 'Regional 4', function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->random()
                                ->id;

            $this->modelo_editar->usuario_asignado = $id;
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

    public function reasignarSupervisor(){

        $this->validate();

        try {

            $this->modelo_editar->actualizado_por = auth()->id();
            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó supervisor']);

            $this->dispatch('mostrarMensaje', ['success', "El supervisor se reasignó con éxito."]);

            $this->modalReasignarSupervisor = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar supervisor a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function cargarUsuarios($roles){

        $this->usuarios = User::whereHas('roles', function($q) use($roles){
                                    $q->whereIn('name', $roles);
                                })
                                ->where('status', 'activo')
                                ->when($this->modelo_editar->getRawOriginal('distrito') == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($this->modelo_editar->getRawOriginal('distrito') != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->orderBy('name')
                                ->get();

    }

    public function cargarSupervisores($roles){

        $this->supervisores = User::whereHas('roles', function($q)use($roles){
                                    $q->whereIn('name', $roles);
                                })
                                ->where('status', 'activo')
                                ->orderBy('name')
                                ->get();

    }

    public function imprimirCaratulaMovimiento(MovimientoRegistral $modelo){

        $movimientoRegistral = $modelo->folioReal->movimientosRegistrales()->where('folio', $modelo->folio)->first();

        try {

            if(in_array($movimientoRegistral->inscripcionPropiedad->servicio, ['D127', 'D121', 'D120', 'D123', 'D122', 'D119', 'D124', 'D125', 'D126'])){

                $pdf = (new SubdivisionesController())->reimprimir($movimientoRegistral->firmaElectronica);

            }else{

                $pdf = (new PropiedadController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->gravamen){

                $pdf = (new GravamenController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->vario){

                $pdf = (new VariosController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->cancelacion){

                $pdf = (new CancelacionController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->sentencia){

                $pdf = (new SentenciasController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->certificacion){

                $pdf = (new CertificadoGravamenController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {
            Log::error("Error al reimiprimir caratula de inscripción movimientos regsitrales por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(): void
    {

        $this->crearModeloVacio();

        $this->usuarios_filtro = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereNotIn('name', ['Administrador']);
                                        })
                                        ->orderBy('name')
                                        ->get();

        $this->distritos = Constantes::DISTRITOS;

        $this->años = Constantes::AÑOS;

        $this->motivos_rechazo = Constantes::RECHAZO_MOTIVOS;

    }

    public function render()
    {

        $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'tomo', 'registro', 'distrito', 'numero_propiedad', 'servicio_nombre', 'usuario_asignado', 'usuario_supervisor', 'created_at', 'updated_at', 'actualizado_por', 'estado')
                            ->with('actualizadoPor:id,name', 'folioReal:id,folio,estado,matriz', 'asignadoA:id,name', 'supervisor:id,name')
                            ->withCount('rechazos')
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
                            ->when($this->filters['tomo'], fn($q, $tomo) => $q->where('tomo', $tomo))
                            ->when($this->filters['registro'], fn($q, $registro) => $q->where('registro', $registro))
                            ->when($this->filters['distrito'], fn($q, $distrito) => $q->where('distrito', $distrito))
                            ->when($this->filters['usuario_asignado'], fn($q, $usuario_asignado) => $q->where('usuario_asignado', $usuario_asignado))
                            ->whereNotNull('folio')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.movimientos-registrales', compact('movimientos'))->extends('layouts.admin');
    }

}
