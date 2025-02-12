<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\File;
use App\Models\Vario;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;
use App\Traits\Inscripciones\Varios\VariosTrait;
use App\Http\Controllers\Varios\VariosController;
use Spatie\LivewireFilepond\WithFilePond;

class Varios extends Component
{

    use WithFileUploads;
    use VariosTrait;
    use WithFilePond;

    public $actos;

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
            'documento' => 'nullable|mimes:pdf|max:100000'
         ];
    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            if($this->vario->acto_contenido == 'SEGUNDO AVISO PREVENTIVO') $this->procesarSegundoAvisoPreventivo();

            DB::transaction(function () {

                $this->vario->estado = 'activo';
                $this->vario->actualizado_por = auth()->id();
                $this->vario->fecha_inscripcion = now()->toDateString();
                $this->vario->save();

                $this->vario->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->vario->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de varios']);

                (new VariosController())->caratula($this->vario);

            });

            return redirect()->route('varios');

        } catch (Exception $ex) {

            Log::error("Error al finalizar inscripcion de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $ex);

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                if($this->vario->movimientoRegistral->estado != 'correccion')
                    $this->vario->movimientoRegistral->estado = 'captura';

                $this->vario->movimientoRegistral->actualizado_por = auth()->id();
                $this->vario->save();

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar inscripción de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function procesarSegundoAvisoPreventivo(){

        $primerAviso = Vario::whereHas('movimientoRegistral', function($q){
                                        $q->where('folio_real', $this->vario->movimientoRegistral->folio_real);
                                    })
                                    ->where('acto_contenido', 'PRIMER AVISO PREVENTIVO')
                                    ->where('estado', 'activo')
                                    ->first();

        if($primerAviso){

            $primerAviso->update(['estado' => 'inactivo']);

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

        if($this->vario->acto_contenido == 'SEGUNDO AVISO PREVENTIVO'){

            $this->actos = ['SEGUNDO AVISO PREVENTIVO'];

        }else{

            $this->actos = Constantes::ACTOS_INSCRIPCION_VARIOS;

            $this->actos = array_flip($this->actos);

            unset($this->actos['PRIMER AVISO PREVENTIVO']);
            unset($this->actos['SEGUNDO AVISO PREVENTIVO']);
            unset($this->actos['CONSOLIDACIÓN DEL USUFRUCTO']);
            unset($this->actos['ACLARACIÓN ADMINISTRATIVA']);
            unset($this->actos['DONACIÓN / VENTA DE USUFRUCTO']);
            unset($this->actos['ESCRITURA ACLARATORIA']);

            $this->actos = array_flip($this->actos);

        }

    }

    public function render()
    {
        return view('livewire.varios.varios')->extends('layouts.admin');
    }
}
