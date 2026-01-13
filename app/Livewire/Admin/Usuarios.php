<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Usuarios extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $roles;
    public $ubicaciones;
    public $areas_adscripcion;

    public User $modelo_editar;
    public $role;

    public $filters = [
        'rol' => '',
    ];

    protected function rules(){
        return [
            'modelo_editar.name' => 'required',
            'modelo_editar.email' => 'required|email|unique:users,email,' . $this->modelo_editar->id,
            'modelo_editar.status' => 'required|in:activo,inactivo',
            'role' => 'required',
            'modelo_editar.ubicacion' => 'required',
            'modelo_editar.area' => 'required'
         ];
    }

    protected $validationAttributes  = [
        'role' => 'rol',
        'modelo_editar.ubicacion' => 'ubicación'
    ];

    public function crearModeloVacio(){
        $this->modelo_editar =  User::make();
    }

    public function abrirModalEditar(User $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if(isset($modelo['roles'][0]))
            $this->role = $modelo->roles()->pluck('id');

    }

    public function guardar(){

        $this->validate();

        if($this->revisarUsuariosUnicos()) return;

        if(User::where('name', $this->modelo_editar->name)->first()){

            $this->dispatch('mostrarMensaje', ['error', "El usuario " . $this->modelo_editar->name . " ya esta registrado."]);

            $this->resetearTodo();

            return;

        }

        try {

            DB::transaction(function () {

                $this->modelo_editar->password = bcrypt('sistema');
                $this->modelo_editar->creado_por = auth()->user()->id;
                $this->modelo_editar->save();

                $this->modelo_editar->auditAttach('roles', $this->role);

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El usuario se creó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al crear usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function actualizar(){

        $this->validate();

        if($this->revisarUsuariosUnicos()) return;

        try{

            DB::transaction(function () {

                $this->modelo_editar->actualizado_por = auth()->user()->id;
                $this->modelo_editar->save();

                $this->modelo_editar->auditSync('roles', $this->role);

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El usuario se actualizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function borrar(){

        try{

            $usuario = User::find($this->selected_id);

            $usuario->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function resetearPassword($id){

        try{

            $usuario = User::find($id);

            $usuario->password = bcrypt('sistema');
            $usuario->save();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "La contraseña se reestableció con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al resetear contraseña por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function revisarUsuariosUnicos(){

        $role = Role::find($this->role)->first();

        if($role->name == 'Jefe de departamento inscripciones' && User::where('status', 'activo')->whereHas('roles', function($q){ $q->where('name', 'Jefe de departamento inscripciones'); })->first()){

            $this->dispatch('mostrarMensaje', ['error', "Solo puede haber un Jefe de departamento inscripciones activo a la vez."]);

            return true;

        }

        if($role->name == 'Jefe de departamento certificaciones' && User::where('status', 'activo')->whereHas('roles', function($q){ $q->where('name', 'Jefe de departamento certificaciones'); })->first()){

            $this->dispatch('mostrarMensaje', ['error', "Solo puede haber un Jefe de departamento certificaciones activo a la vez."]);

            return true;

        }

        if($role->name == 'Director' && User::where('status', 'activo')->whereHas('roles', function($q){ $q->where('name', 'Director'); })->first()){

            $this->dispatch('mostrarMensaje', ['error', "Solo puede haber un Director activo a la vez."]);

            return true;

        }

    }

    #[Computed]
    public function usuarios(){

        return User::with('creadoPor:id,name', 'actualizadoPor:id,name')
                        ->where(function($q){
                            $q->where('name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('email', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('ubicacion', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('area', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('status', 'LIKE', '%' . $this->search . '%')
                            ->orWhere(function($q){
                                return $q->whereHas('roles', function($q){
                                    return $q->select('id', 'name')
                                                ->where('name', 'LIKE', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->when($this->filters['rol'] && $this->filters['rol'] != '', fn($q) => $q->whereHas('roles', function($q) { $q->where('name', $this->filters['rol']); }))
                        ->orderBy($this->sort, $this->direction)
                        ->paginate($this->pagination);

    }

    public function mount(){

        $this->crearModeloVacio();

        array_push($this->fields, 'role');

        $this->roles = Role::where('id', '!=', 1)->select('id', 'name')->orderBy('name')->get();

        $this->ubicaciones = Constantes::UBICACIONES;

        sort($this->ubicaciones);

        $this->areas_adscripcion = Constantes::AREAS_ADSCRIPCION;

        sort($this->areas_adscripcion);

    }

    public function render()
    {
        return view('livewire.admin.usuarios')->extends('layouts.admin');
    }

}
