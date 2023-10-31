<?php

namespace App\Livewire\Admin;

use App\Models\Permiso;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;

class Permisos extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $areas = [];

    public Permiso $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.name' => 'required',
            'modelo_editar.area' => 'required'
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.name' => 'nombre',
        'area' => 'área',
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Permiso::make();
    }

    public function abrirModalEditar(Permiso $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function guardar(){

        $this->validate();

        try {

            $this->modelo_editar->creado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "El permiso se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear permiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        $this->validate();

        try{

            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "El permiso se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar permiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $permiso = Permiso::find($this->selected_id);

            $permiso->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El permiso se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar permiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->areas = Constantes::AREAS;

        sort($this->areas);

    }

    public function render()
    {

        $permisos = Permiso::with('creadoPor', 'actualizadoPor')
                                ->where('name', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('area', 'LIKE', '%' . $this->search . '%')
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        return view('livewire.admin.permisos', compact('permisos'))->extends('layouts.admin');

    }

}
