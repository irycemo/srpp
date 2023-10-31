<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Distrito;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Distritos extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Distrito $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.nombre' => 'required',
            'modelo_editar.clave' => 'required',
         ];
    }

    protected $validationAttributes  = [
        'role' => 'rol',
        'modelo_editar.ubicacion' => 'ubicación'
    ];

    public function crearModeloVacio(){
        $this->modelo_editar =  Distrito::make();
    }

    public function abrirModalEditar(Distrito $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->modelo_editar->creado_por = auth()->user()->id;
                $this->modelo_editar->save();

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El distrito se creó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al crear usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function actualizar(){

        $this->validate();

        try{

            DB::transaction(function () {

                $this->modelo_editar->actualizado_por = auth()->user()->id;
                $this->modelo_editar->save();

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El distrito se actualizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar distrito por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function borrar(){

        try{

            $distrito = Distrito::find($this->selected_id);

            $distrito->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El distrito se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar distrito por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function render()
    {

        $distritos = Distrito::with('creadoPor', 'actualizadoPor')
                            ->where('nombre', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('clave', 'LIKE', '%' . $this->search . '%')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.distritos', compact('distritos'))->extends('layouts.admin');

    }

}
