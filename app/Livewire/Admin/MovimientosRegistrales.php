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
    public $usuarios;
    public $años;

    public $modalReasignar = false;

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
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.usuario_asignado' => 'usuario asignado',
    ];

    public function updatedFilters() { $this->resetPage(); }

    public function crearModeloVacio(){

        $this->modelo_editar = MovimientoRegistral::make();

    }

    public function abrirModalReasignar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignar = true;

    }

    public function reasignar(){

        $this->validate();

        try {

            $this->modelo_editar->actualizado_por = auth()->id();
            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modalReasignar = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function mount(): void
    {

        $this->crearModeloVacio();

        $this->distritos = Constantes::DISTRITOS;

        $this->años = Constantes::AÑOS;

        $this->usuarios = User::whereHas('roles', function($q){
                                    $q->whereIn('name', [
                                                            'Registrador Sentencias',
                                                            'Registrador Varios',
                                                            'Registrador Cancelación',
                                                            'Registrador Gravamen',
                                                            'Registrador Propiedad',
                                                            'Propiedad',
                                                            'Gravamen',
                                                            'Sentencias',
                                                            'Cancelación',
                                                            'Varios',
                                                            'Certificador Propiedad',
                                                            'Certificador Gravamen',
                                                            'Certificador Oficialia',
                                                            'Certificador Juridico',
                                                            'Certificador'
                                                        ]);
                                })
                                ->orderBy('name')
                                ->get();

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
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.movimientos-registrales', compact('movimientos'))->extends('layouts.admin');
    }
}