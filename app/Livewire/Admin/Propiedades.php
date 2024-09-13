<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Propiedadold;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;

class Propiedades extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $distritos;

    public $distrito;
    public $tomo;
    public $registro;
    public $numero_propiedad;

    public Propiedadold $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.status' => 'required',
        ];
    }

    public function crearModeloVacio(){
        $this->modelo_editar =  Propiedadold::make();
    }

    public function abrirModalEditar(Propiedadold $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function consultar(){

        $this->validate([
            'distrito' => 'required',
            'tomo' => 'required',
            'registro' => 'required',
            'numero_propiedad' => 'required',
        ]);

        $propiedad = Propiedadold::where('distrito', $this->distrito)
                                                ->where('tomo', $this->tomo)
                                                ->where('registro', $this->registro)
                                                ->where('noprop', $this->numero_propiedad)
                                                ->first();

        if(!$propiedad){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro la propiedad."]);

            return;

        }else{

            $this->modelo_editar = $propiedad;
        }

    }

    public function save(){

        $this->validate();

        try{

            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Actualizó estado']);

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La propiedad se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.admin.propiedades')->extends('layouts.admin');
    }
}
