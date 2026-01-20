<?php

namespace App\Traits\Inscripciones;

use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

trait FinalizarInscripcionTrait{

    public $modal_finalizar = false;

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modal_finalizar = true;

    }

    public function finalizar(){

        try {

            DB::transaction(function (){

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'finalizado';

                $this->modelo_editar->save();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Finalizó inscripción']);

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

            $this->modal_finalizar = false;

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

}