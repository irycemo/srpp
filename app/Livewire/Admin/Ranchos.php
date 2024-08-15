<?php

namespace App\Livewire\Admin;

use App\Models\Rancho;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Ranchos extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $distritos;

    public Rancho $modelo_editar;

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
        $this->modelo_editar =  Rancho::make();
    }

    public function abrirModalEditar(Rancho $modelo){

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

                $this->dispatch('mostrarMensaje', ['success', "El rancho se creó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al crear rancho por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                $this->dispatch('mostrarMensaje', ['success', "El rancho se actualizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar rancho por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function borrar(){

        try{

            $rancho = Rancho::find($this->selected_id);

            $rancho->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El rancho se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar rancho por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

        $ranchos = Rancho::with('creadoPor', 'actualizadoPor', 'distrito')
                            ->where('nombre', 'LIKE', '%' . $this->search . '%')
                            ->where('distrito_id', 'LIKE', '%' . $this->search . '%')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.ranchos', compact('ranchos'))->extends('layouts.admin');

    }

}
