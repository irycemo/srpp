<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\Permiso;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Roles extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $permisos;

    public Role $modelo_editar;
    public $listaDePermisos = [];

    protected function rules(){
        return [
            'modelo_editar.name' => 'required'
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.name' => 'nombre',
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Role::make();
    }

    public function abrirModalEditar(Role $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        foreach($modelo['permissions'] as $permission){
            array_push($this->listaDePermisos, (string)$permission['id']);
        }

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->modelo_editar->creado_por = auth()->user()->id;
                $this->modelo_editar->save();

                $this->modelo_editar->permissions()->sync($this->listaDePermisos);

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El rol se creó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al crear rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                $this->modelo_editar->permissions()->sync($this->listaDePermisos);

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El rol se actualizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualzar rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $role = Role::find($this->selected_id);

            $role->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El rol se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

        $this->crearModeloVacio();

        array_push($this->fields, 'listaDePermisos');

        $permisos = Permiso::all();

        $this->permisos = $permisos->groupBy(function($permiso) {
            return $permiso->area;
        })->all();

    }

    public function render()
    {

        $roles = Role::with('creadoPor', 'actualizadoPor', 'permissions')
                            ->where('name', 'LIKE', '%' . $this->search . '%')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.roles', compact('roles'))->extends('layouts.admin');
    }

}
