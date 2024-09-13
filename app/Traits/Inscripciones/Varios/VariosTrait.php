<?php

namespace App\Traits\Inscripciones\Varios;

use App\Models\File;
use App\Models\Vario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

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

    public function consultarArchivo(){

        try {

            $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                ->accept('application/json')
                                ->asForm()
                                ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                    'año' => $this->vario->movimientoRegistral->año,
                                                                                    'tramite' => $this->vario->movimientoRegistral->tramite,
                                                                                    'usuario' => $this->vario->movimientoRegistral->usuario,
                                                                                    'estado' => 'nuevo'
                                                                                ]);

            $data = collect(json_decode($response, true));

            if($response->status() == 200){

                $this->dispatch('ver_documento', ['url' => $data['url']]);

            }else{

                $this->dispatch('mostrarMensaje', ['error', "No se encontro el documento."]);

            }

        } catch (ConnectionException $th) {

            Log::error("Error al cargar archivo en varios: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

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

                if(env('LOCAL') == "0"){

                    $pdf = $this->documento->store('srpp/documento_entrada', 's3');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada_s3',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "1"){

                    $pdf = $this->documento->store('/', 'documento_entrada');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "2"){

                    $pdf = $this->documento->store('srpp/documento_entrada', 's3');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada_s3',
                        'url' => $pdf
                    ]);

                    $pdf = $this->documento->store('/', 'documento_entrada');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $pdf
                    ]);

                }

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de cancelación por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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
