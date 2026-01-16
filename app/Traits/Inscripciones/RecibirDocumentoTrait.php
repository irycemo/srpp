<?php

namespace App\Traits\Inscripciones;

use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

trait RecibirDocumentoTrait{

    public $modal_recibir_documento = false;
    public $contraseña;

    public function abrirModalRecibirDocumentacion(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modal_recibir_documento = true;

    }

    public function recibirDocumentacion(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['warning', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                if(auth()->user()->hasRole('Jefe de departamento inscripciones')){

                    $this->modelo_editar->usuario_asignado = auth()->id();

                }

                $this->modelo_editar->estado = 'nuevo';

                $this->modelo_editar->actualizado_por = auth()->id();

                $this->modelo_editar->save();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Recibió documentación']);

            });

            $this->modal_recibir_documento = false;

            $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al recibir documento de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

}