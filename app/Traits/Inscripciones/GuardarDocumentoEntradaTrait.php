<?php

namespace App\Traits\Inscripciones;

use App\Models\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait GuardarDocumentoEntradaTrait{

    public $modal_documento_entrada = false;
    public $documento_entrada_pdf;

    public function abrirModalDocumentoEntrada(){

        $this->reset('documento_entrada_pdf');

        $this->dispatch('removeFiles');

        $this->modal_documento_entrada = true;

    }

    public function guardarDocumentoEntradaPdf(){

        $this->validate(['documento_entrada_pdf' => 'required']);

        try {

            DB::transaction(function (){

                if(app()->isProduction()){

                    $pdf = $this->documento_entrada_pdf->store(config('services.ses.ruta_documento_entrada'), 's3');

                }else{

                    $pdf = $this->documento_entrada_pdf->store('/', 'documento_entrada');

                }

                File::create([
                    'fileable_id' => $this->movimientoRegistral->folioReal->id,
                    'fileable_type' => 'App\Models\FolioReal',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El documento de entrada se guardó con éxitos."]);

                $this->modal_documento_entrada = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

}
