<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Traits\Inscripciones\Varios\VariosTrait;
use Illuminate\Http\Client\ConnectionException;

class Varios extends Component
{

    use WithFileUploads;
    use VariosTrait;

    public $actos;

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
         ];
    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->vario->estado = 'activo';
                $this->vario->actualizado_por = auth()->id();
                $this->vario->fecha_inscripcion = now()->toDateString();
                $this->vario->save();

                $this->vario->movimientoRegistral->update(['estado' => 'elaborado']);

            });

            return redirect()->route('varios');

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                $this->vario->movimientoRegistral->update(['estado' => 'captura']);

                $this->vario->save();

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar inscripción de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        if(!$this->vario->movimientoRegistral->documentoEntrada()){

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

                    $contents = file_get_contents($data['url']);

                    $filename = basename($data['url']);

                    Storage::disk('documento_entrada')->put($filename, $contents);

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
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

        $this->actos = Constantes::ACTOS_INSCRIPCION_VARIOS;

    }

    public function render()
    {
        return view('livewire.varios.varios')->extends('layouts.admin');
    }
}
