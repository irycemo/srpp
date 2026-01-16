<?php

namespace App\Traits\Inscripciones;

use App\Models\User;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

trait ReasignarUsuarioTrait{

    public $modal_reasignar_usuario = false;
    public $usuarios = [];

    public function abrirModalReasignarUsuario(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if($this->seleccionarRoleUsuarios()) return;

        $this->modal_reasignar_usuario = true;

    }

    public function reasignarUsuario(){

        $this->validate();

        try {

            $this->modelo_editar->actualizado_por = auth()->id();
            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modal_reasignar_usuario = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function reasignarUsuarioAleatoriamente(){

        try {

            $this->modelo_editar->usuario_asignado = $this->usuarios->random()->id;
            $this->modelo_editar->actualizado_por = auth()->id();
            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modal_reasignar_usuario = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function cargarUsuarios($roles){

        $this->usuarios = User::whereHas('roles', function($q) use($roles){
                                    $q->whereIn('name', $roles);
                                })
                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->orderBy('name')
                                ->get();

        if(!$this->usuarios->count()){

            $this->dispatch('mostrarMensaje', ['warning', "No hay usuarios activos para el rol " . $roles[0]]);

            return false;

        }

    }

    public function seleccionarRoleUsuarios($no_pase_a_folio = false){

        if($this->modelo_editar->inscripcionPropiedad){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Propiedad', 'Registrador Propiedad']);

            }else{

                $this->cargarUsuarios(['Propiedad', 'Registrador Propiedad', 'Pase a folio']);

            }

        }

        if($this->modelo_editar->gravamen){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Gravamen', 'Registrador Gravamen']);

            }else{

                $this->cargarUsuarios(['Gravamen', 'Registrador Gravamen', 'Pase a folio']);

            }

        }

        if($this->modelo_editar->vario){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Varios', 'Registrador Varios', 'Aclaraciones administrativas', 'Avisos preventivos']);

            }else{

                $this->cargarUsuarios(['Varios', 'Registrador Varios', 'Pase a folio', 'Aclaraciones administrativas', 'Avisos preventivos']);

            }

        }

        if($this->modelo_editar->cancelacion){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Cancelación', 'Registrador cancelación']);

            }else{

                $this->cargarUsuarios(['Cancelación', 'Registrador cancelación', 'Pase a folio']);

            }

        }

        if($this->modelo_editar->sentencia){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Sentencias', 'Registrador sentencias']);

            }else{

                $this->cargarUsuarios(['Sentencias', 'Registrador sentencias', 'Pase a folio']);

            }

        }

        if($this->modelo_editar->reformaMoral){

            $this->cargarUsuarios(['Folio real moral']);

        }

        if($this->modelo_editar->certificacion){

            if($this->modelo_editar->certificacion->servicio == 'DL07'){

                if($no_pase_a_folio){

                    $this->cargarUsuarios(['Certificador Gravamen']);

                }else{

                    $this->cargarUsuarios(['Certificador Gravamen', 'Pase a folio']);

                }

            }elseif(in_array($this->modelo_editar->certificacion->servicio, ['DL11', 'DL10'])){

                if($no_pase_a_folio){

                    $this->cargarUsuarios(['Certificador Propiedad']);

                }else{

                    $this->cargarUsuarios(['Certificador Propiedad']);

                }

            }

        }

    }

}