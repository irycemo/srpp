<?php

namespace App\Livewire\Certificaciones;

use App\Constantes\Constantes;
use App\Models\User;
use Livewire\Component;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;
use Livewire\WithPagination;

class ConsultasCertificaciones extends Component
{

    use ComponentesTrait;
    use WithPagination;

    public $certificacion;
    public $año;
    public $tramite;
    public $modal2;
    public $modalRechazar;
    public $paginas;
    public $observaciones;
    public $usuarios;
    public $usuario;
    public $años;

    public function save(){

        $this->validate(['paginas' => 'required']);

        if($this->certificacion->estado != 'nuevo'){

            $this->dispatch('mostrarMensaje', ['error', "El trámite esta concluido."]);

            $this->modal = false;

            $this->reset('paginas');

            return;

        }

        if($this->paginas >= $this->certificacion->certificacion->numero_paginas){

            $this->dispatch('mostrarMensaje', ['error', "El número de paginas no puede ser mayor o igual al registrado."]);

            $this->modal = false;

            $this->reset('paginas');

            return;

        }

        $this->certificacion->certificacion->update(['numero_paginas' => $this->paginas]);

        $this->dispatch('mostrarMensaje', ['success', "Se actualizó la información con éxito."]);

        $this->modal = false;

        $this->reset('paginas');

    }

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->certificacion->año, $this->certificacion->tramite, $observaciones);

                $this->certificacion->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->certificacion->actualizado_por = auth()->user()->id;

                $this->certificacion->certificacion->observaciones = $this->certificacion->certificacion->observaciones . $observaciones;

                $this->certificacion->certificacion->save();

                $this->certificacion->save();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

                $this->modalRechazar = false;

            });

        } catch (\Throwable $th) {
            Log::error("Error al rechazar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function reasignar(){

        $this->validate([
            'usuario' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $this->certificacion->update(['usuario_asignado' => $this->usuario]);

                $this->dispatch('mostrarMensaje', ['success', "El trámite se reasigno con éxito."]);

                $this->modal2 = false;

            });

        } catch (\Throwable $th) {
            Log::error("Error al reasignar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function consultar(){

        $this->validate([
            'tramite' => 'required',
            'año' => 'required'
        ]);

        $this->certificacion = MovimientoRegistral::where('año', $this->año)->where('tramite', $this->tramite)->first();

        if(!$this->certificacion){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el trámite."]);

        }

    }

    public function mount(){

        $this->años = Constantes::AÑOS;

        $this->año = now()->format('Y');

        $this->usuarios = User::whereHas('roles', function($q){
                                        $q->where('name', 'Certificador');
                                    })
                                    ->orderBy('name')
                                    ->get();

    }

    public function render()
    {
        return view('livewire.certificaciones.consultas-certificaciones')->extends('layouts.admin');
    }

}
