<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Regional;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;

class Regionales extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $areas = [];

    public Regional $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.numero' => 'required',
            'modelo_editar.titular' => 'required',
            'modelo_editar.ciudad' => 'required',
            'modelo_editar.nombre' => 'required',
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.numero' => 'número',
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Regional::make();
    }

    public function abrirModalEditar(Regional $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function guardar(){

        $this->validate();

        try {

            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La regional se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear regional por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        $this->validate();

        try{

            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La regional se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar regional por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $regional = Regional::find($this->selected_id);

            $regional->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "La regional se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar regional por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function render()
    {

        $regionales = Regional::orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        return view('livewire.admin.regionales', compact('regionales'))->extends('layouts.admin');
    }
}
