<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Municipio;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Municipios extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $distritos;

    public Municipio $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.nombre' => 'required',
            'modelo_editar.distrito_id' => 'required|exists:distritos',
         ];
    }

    protected $validationAttributes  = [
        'role' => 'rol',
        'modelo_editar.ubicacion' => 'ubicación'
    ];

    public function crearModeloVacio(){
        $this->modelo_editar =  Municipio::make();
    }

    public function abrirModalEditar(Municipio $modelo){

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

                $this->dispatch('mostrarMensaje', ['success', "El municipio se creó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al crear municipio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                $this->dispatch('mostrarMensaje', ['success', "El municipio se actualizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar municipio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function borrar(){

        try{

            $municipio = Municipio::find($this->selected_id);

            $municipio->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El municipio se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar municipio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->distritos = Distritos::orderBy('nombre')->get();

    }

    public function render()
    {

        $municipios = Municipio::with('creadoPor', 'actualizadoPor', 'distrito')
                            ->where('nombre', 'LIKE', '%' . $this->search . '%')
                            ->where('distrito_id', 'LIKE', '%' . $this->search . '%')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.municipios', compact('municipios'))->extends('layouts.admin');

    }

}
