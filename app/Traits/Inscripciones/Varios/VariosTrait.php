<?php

namespace App\Traits\Inscripciones\Varios;

use App\Models\Vario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

trait VariosTrait{

    public $modalContraseña = false;
    public $modalDocumento = false;
    public $modalPersona = false;
    public $link;
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

}
