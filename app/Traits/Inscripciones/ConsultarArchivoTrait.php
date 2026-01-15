<?php

namespace App\Traits\Inscripciones;

use App\Models\File;
use Illuminate\Support\Str;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

trait ConsultarArchivoTrait{

    public function consultarArchivo(MovimientoRegistral $movimientoRegistral){

        if(!$movimientoRegistral->documentoEntrada()){

            try {

                $response = Http::withToken(config('services.sistema_tramites.token'))
                                    ->accept('application/json')
                                    ->asForm()
                                    ->post(
                                        config('services.sistema_tramites.consultar_archivo'),
                                        [
                                            'año' => $movimientoRegistral->año,
                                            'tramite' => $movimientoRegistral->tramite,
                                            'usuario' => $movimientoRegistral->usuario
                                        ]
                                    );

                $data = collect(json_decode($response, true));

                if($response->status() == 200){

                    $contents = file_get_contents($data['url']);

                    $filename =  Str::random(40) . '.pdf';

                    if(app()->isProduction()){

                        Storage::disk('s3')->put(config('services.ses.ruta_documento_entrada'). '/'. $filename, $contents);

                    }else{

                        Storage::disk('documento_entrada')->put($filename, $contents);

                    }

                    File::create([
                        'fileable_id' => $movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $filename
                    ]);

                }

            } catch (\Throwable $th) {

                Log::error("Error al cargar archivo en inscripcion: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

                $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

            }

        }

    }

}