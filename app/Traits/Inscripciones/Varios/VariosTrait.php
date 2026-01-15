<?php

namespace App\Traits\Inscripciones\Varios;

use App\Models\File;
use App\Models\Vario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait VariosTrait{

    public $modalContraseña = false;
    public $modalDocumento = false;
    public $modalPersona = false;
    public $link;
    public $documento;
    public $contraseña;

    public Vario $vario;

    public $modal = false;
    public $crear = false;
    public $editar = false;

    public $actor;

    public function abrirModalFinalizar(){

        $this->reset('documento');

        $this->dispatch('removeFiles');

        $this->modalDocumento = true;

    }

    public function guardarDocumento(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                if(app()->isProduction()){

                    $pdf = $this->documento->store(config('services.ses.ruta_documento_entrada'), 's3');

                }else{

                    $pdf = $this->documento->store('/', 'documento_entrada');

                }

                File::create([
                    'fileable_id' => $this->vario->movimientoRegistral->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizar(){

        $this->validate();

        if(!$this->vario->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        $this->modalContraseña = true;

    }

}
