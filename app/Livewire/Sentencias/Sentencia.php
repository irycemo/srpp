<?php

namespace App\Livewire\Sentencias;

use App\Models\File;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;
use App\Traits\Inscripciones\Sentencias\SentenciaTrait;

class Sentencia extends Component
{

    use SentenciaTrait;

    protected function rules(){
        return [
            'sentencia.acto_contenido' => 'required',
            'sentencia.descripcion' => 'required'
        ];

    }

    #[On('cambiarActo')]
    public function cambiarActo($acto){

        $this->sentencia->acto_contenido = $acto;

    }

    public function mount(){

        if(!$this->sentencia->movimientoRegistral->documentoEntrada()){

            try {

                $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                    ->accept('application/json')
                                    ->asForm()
                                    ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                        'año' => $this->sentencia->movimientoRegistral->año,
                                                                                        'tramite' => $this->sentencia->movimientoRegistral->tramite,
                                                                                        'usuario' => $this->sentencia->movimientoRegistral->usuario,
                                                                                        'estado' => 'nuevo'
                                                                                    ]);

                $data = collect(json_decode($response, true));

                if($response->status() == 200){

                    $contents = file_get_contents($data['url']);

                    $filename = basename($data['url']);

                    Storage::disk('documento_entrada')->put($filename, $contents);

                    File::create([
                        'fileable_id' => $this->sentencia->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $filename
                    ]);

                }

            } catch (ConnectionException $th) {

                Log::error("Error al cargar archivo en cancelación: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

                $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

            }

        }


        $this->actos = Constantes::ACTOS_INSCRIPCION_SENTENCIAS;

    }

    public function render()
    {
        return view('livewire.sentencias.sentencia')->extends('layouts.admin');
    }

}
