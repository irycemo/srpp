<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\FolioReal;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Centinela extends Component
{

    public $folio;
    public $folioReal;

    public $modal = false;
    public $tipo;
    public $observaciones;

    public function buscarFolioReal(){

        $this->validate(['folio' => 'required']);

        $this->folioReal = FolioReal::with('bloqueos.creadoPor', 'bloqueos.actualizadoPor')->where('folio', $this->folio)->first();

        if(!$this->folioReal){

            $this->dispatch('mostrarMensaje', ['warning', "El folio real no existe."]);

        }

        $this->reset('folio');

    }

    public function abrirModal(){

        $this->reset(['observaciones', 'tipo']);

        $this->modal = true;

    }

    public function procesar(){

        $this->validate([
                        'tipo' => Rule::requiredIf($this->folioReal->estado == 'activo'),
                        'observaciones' => 'required'
                        ]);

        try{

            DB::transaction(function () {

                if($this->folioReal->estado == 'activo'){

                    $this->folioReal->bloqueos()->create([
                        'tipo' => $this->tipo,
                        'estado' => 'activo',
                        'observaciones' => $this->observaciones,
                        'creado_por' => auth()->id()
                    ]);

                    $this->folioReal->update(['estado' => $this->tipo]);

                }elseif(in_array($this->folioReal->estado, ['bloqueado', 'centinela'])){

                    $this->folioReal->bloqueos()->where('estado', 'activo')->first()->update([
                        'estado' => 'inactivo',
                        'observaciones_desbloqueo' => $this->observaciones,
                        'actualizado_por' => auth()->id()
                    ]);

                    $this->folioReal->update(['estado' => 'activo']);

                }

            });

            $this->folioReal->refresh();

            $this->folioReal->load('bloqueos.creadoPor', 'bloqueos.actualizadoPor');

            $this->reset(['modal', 'tipo', 'observaciones']);

        } catch (\Throwable $th) {

            Log::error("Error al bloquear folio real en centinela por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function render()
    {
        return view('livewire.admin.centinela')->extends('layouts.admin');
    }
}
