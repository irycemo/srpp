<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

class MovimientosRegistrales extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public MovimientoRegistral $modelo_editar;

    public $distritos;
    public $usuarios = [];
    public $supervisores = [];
    public $años;

    public $modalReasignarUsuario = false;
    public $modalReasignarSupervisor = false;
    public $modalCorreccion = false;

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

    public function abrirModalReasignarUsuario(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignarUsuario = true;

        if($this->modelo_editar->inscripcionPropiedad){

            $this->cargarUsuarios(['Propiedad', 'Registrador Propiedad', 'Pase a folio']);

            $this->cargarSupervisores(['Supervisor propiedad']);

        }

        if($this->modelo_editar->gravamen){

            $this->cargarUsuarios(['Gravamen', 'Registrador Gravamen', 'Pase a folio']);

            $this->cargarSupervisores(['Supervisor gravamen']);

        }

        if($this->modelo_editar->vario){

            $this->cargarUsuarios(['Varios', 'Registrador Varios', 'Pase a folio', 'Aclaraciones administrativas', 'Avisos preventivos']);

            $this->cargarSupervisores(['Supervisor Varios']);

        }

        if($this->modelo_editar->cancelacion){

            $this->cargarUsuarios(['Cancelación', 'Registrador cancelación', 'Pase a folio']);

            $this->cargarSupervisores(['Supervisor Cancelación']);

        }

        if($this->modelo_editar->sentencia){

            $this->cargarUsuarios(['Sentencias', 'Registrador sentencias', 'Pase a folio']);

            $this->cargarSupervisores(['Supervisor Sentencias']);

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

    public function abrirModalReasignarSupervisor(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignarSupervisor = true;

    }

    public function abrirModalCorreccion(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalCorreccion = true;

    }

    public function correccion(){

        /* $movimiento = $this->modelo_editar->folioReal->movimientosRegistrales()
                                                        ->whereIn('estado', ['finalizado', 'concluido'])
                                                        ->where('folio', '>', $this->modelo_editar->folio)
                                                        ->first();

        if($movimiento){

            $this->dispatch('mostrarMensaje', ['warning', "El folio real ya tiene movimientos registrales posteriores finalizados."]);

            return;

        } */

        /* if($this->modelo_editar->fecha_entrega->addDays(30) < now()){

            $this->dispatch('mostrarMensaje', ['warning', "Han pasado 30 dias desde su fecha de entrega no es posible enviar a corrección."]);

            return;

        } */

        try {

            $this->modelo_editar->estado = 'correccion';
            $this->modelo_editar->actualizado_por = auth()->id();
            $this->modelo_editar->save();

            $this->revisarMovimientoCorreccion();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Cambio estado a corrección']);

            $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito."]);

            $this->modalCorreccion = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al enviar a corrección movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

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
                                ->orderBy('name')
                                ->get();

    }

    public function cargarSupervisores($roles){

        $this->supervisores = User::whereHas('roles', function($q)use($roles){
                                    $q->whereIn('name', $roles);
                                })
                                ->orderBy('name')
                                ->get();

    }

    public function revisarMovimientoCorreccion(){

        if($this->modelo_editar->sentencia){

            if($this->modelo_editar->sentencia->acto_contenido == 'CANCELACIÓN DE SENTENCIA'){

                $cancelado = MovimientoRegistral::where('movimiento_padre', $this->modelo_editar->id)->first();

                $cancelado->update(['movimiento_padre' => null]);

                $cancelado->sentencia->update(['estado' => 'activo']);

            }

        }

    }

    public function mount(): void
    {

        $this->crearModeloVacio();

        $this->distritos = Constantes::DISTRITOS;

        $this->años = Constantes::AÑOS;

    }

    public function render()
    {

        $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal:id,folio', 'asignadoA:id,name', 'supervisor:id,name')
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
