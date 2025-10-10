<?php

namespace App\Traits\Inscripciones\Sentencias;

use App\Models\File;
use App\Models\Sentencia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait SentenciaTrait{

    public $modalContraseña = false;
    public $modalDocumento = false;
    public $modalPersona = false;
    public $link;
    public $documento;
    public $contraseña;
    public $actos;

    public Sentencia $sentencia;

    public $modal = false;
    public $crear = false;
    public $editar = false;

    public $actor;

    public function updatedSentenciaActoContenido(){

        $this->dispatch('cambiarActo', $this->sentencia->acto_contenido);

    }

    public function finalizar(){

        $this->validate();

        if(!$this->sentencia->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        $this->modalContraseña = true;

    }

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

                    $pdf = Str::random(40) . '.pdf';

                    $this->documento->store(config('services.ses.ruta_documento_entrada'), $pdf, 's3');

                }else{

                    $pdf = $this->documento->store('/', 'documento_entrada');

                }

                File::create([
                    'fileable_id' => $this->sentencia->movimientoRegistral->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en inscripción de sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarPredio(){

        if($this->sentencia->predio){

            $this->sentencia->predio->colindancias()->delete();

            foreach ($this->sentencia->predio->propietarios() as $propietario) {
                $propietario->delete();
            }

            $this->sentencia->predio->delete();

            $this->sentencia->predio_id = null;

        }

    }

}
