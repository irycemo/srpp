<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Tenencia;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Tenencias extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $distritos;

    public Tenencia $modelo_editar;

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
        $this->modelo_editar =  Tenencia::make();
    }

    public function abrirModalEditar(Tenencia $modelo){

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

                $this->dispatch('mostrarMensaje', ['success', "El tenencia se creó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al crear tenencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                $this->dispatch('mostrarMensaje', ['success', "El tenencia se actualizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar tenencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function borrar(){

        try{

            $tenencia = Tenencia::find($this->selected_id);

            $tenencia->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El tenencia se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar tenencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

        $tenencias = Tenencia::with('creadoPor', 'actualizadoPor', 'distrito')
                            ->where('nombre', 'LIKE', '%' . $this->search . '%')
                            ->where('distrito_id', 'LIKE', '%' . $this->search . '%')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.tenencias', compact('tenencias'))->extends('layouts.admin');

    }

}
