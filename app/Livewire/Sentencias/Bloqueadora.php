<?php

namespace App\Livewire\Sentencias;

use App\Models\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;
use App\Traits\Inscripciones\Sentencias\SentenciaTrait;
use App\Http\Controllers\Sentencias\SentenciasController;

class Bloqueadora extends Component
{

    use SentenciaTrait;
    use WithFileUploads;

    protected function rules(){
        return [
            'sentencia.acto_contenido' => 'required',
            'sentencia.descripcion' => 'required',
            'sentencia.tipo' => 'required',
            'sentencia.hojas' => 'nullable',
            'sentencia.expediente' => 'nullable',
            'sentencia.tomo' => 'nullable',
            'sentencia.registro' => 'nullable',
        ];

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                if($this->sentencia->movimientoRegistral->estado != 'correccion')
                    $this->sentencia->movimientoRegistral->estado = 'captura';

                $this->sentencia->movimientoRegistral->actualizado_por = auth()->id();
                $this->sentencia->movimientoRegistral->save();

                $this->sentencia->save();

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->borrarPredio();

                $this->sentencia->estado = 'activo';
                $this->sentencia->actualizado_por = auth()->id();
                $this->sentencia->fecha_inscripcion = now()->toDateString();
                $this->sentencia->save();

                $this->sentencia->movimientoRegistral->folioReal->update(['estado' => 'bloqueado']);

                $this->sentencia->movimientoRegistral->folioReal->bloqueos()->create([
                                                                                       'folio_real_id' => $this->sentencia->movimientoRegistral->folio_real,
                                                                                       'tipo' => 'bloqueado',
                                                                                       'estado' => 'activo',
                                                                                       'observaciones' => 'Se bloquea folio real mediante sentencia con folio: ' . $this->sentencia->movimientoRegistral->folio,
                                                                                       'creado_por' => auth()->id()
                                                                                    ]);

                $this->sentencia->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->sentencia->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de sentencia']);

                (new SentenciasController())->caratula($this->sentencia);

            });

            return redirect()->route('sentencias');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

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
        return view('livewire.sentencias.bloqueadora');
    }
}
