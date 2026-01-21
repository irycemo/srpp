<?php

namespace App\Livewire\Sentencias;

use App\Models\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;
use App\Traits\Inscripciones\Sentencias\SentenciaTrait;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Traits\Inscripciones\DocumentoEntradaTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;
use Spatie\LivewireFilepond\WithFilePond;

class Cancelatoria extends Component
{

    use SentenciaTrait;
    use WithFileUploads;
    use WithFilePond;
    use DocumentoEntradaTrait;
    use GuardarDocumentoEntradaTrait;

    public $folio_movimiento;

    public $folio_real;

    public $movimientoCancelar;

    protected function rules(){
        return [
            'sentencia.acto_contenido' => 'required',
            'sentencia.descripcion' => 'required',
            'sentencia.tipo' => 'required',
            'sentencia.hojas' => 'nullable',
            'sentencia.expediente' => 'nullable',
            'sentencia.tomo' => 'nullable',
            'sentencia.registro' => 'nullable',
            'documento' => 'nullable|mimes:pdf|max:100000',
            'tipo_documento' => 'required',
            'autoridad_cargo' => 'required',
            'autoridad_nombre' => 'required',
            'numero_documento' => 'nullable',
            'fecha_emision' => 'required',
            'procedencia' => 'nullable',
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

                $this->actualizarDocumentoEntrada($this->sentencia->movimientoRegistral);

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

                $this->actualizarDocumentoEntrada($this->sentencia->movimientoRegistral);

                $this->movimientoCancelar->sentencia->update([
                    'estado' => 'cancelado',
                    'actualizado_por' => auth()->id()
                ]);

                $this->movimientoCancelar->update(['movimiento_padre' => $this->sentencia->movimientoRegistral->id]);

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

    public function buscarMovimiento(){

        $this->movimientoCancelar = MovimientoRegistral::where('folio_real', $this->sentencia->movimientoRegistral->folioReal->id)
                                                        ->where('folio', $this->folio_movimiento)
                                                        ->where('folio', '!=', $this->sentencia->movimientoRegistral->folio)
                                                        ->first();

        if(!$this->movimientoCancelar){

            $this->movimientoCancelar = null;

            $this->dispatch('mostrarMensaje', ['warning', 'No se encontró el movimiento registral.']);

            return;

        }

        if(!$this->movimientoCancelar->sentencia){

            $this->movimientoCancelar = null;

            $this->dispatch('mostrarMensaje', ['warning', 'El movimiento registral no es una sentencia.']);

            return;
        }

        if($this->movimientoCancelar->sentencia->estado != 'activo'){

            $this->movimientoCancelar = null;

            $this->dispatch('mostrarMensaje', ['warning', 'El movimiento registral no esta activo.']);

            return;

        }

    }

    public function finalizar(){

        $this->validate();

        if(!$this->sentencia->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        if(!$this->movimientoCancelar){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar el movimiento a cancelar."]);

            return;

        }

        $this->modalContraseña = true;

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

        $this->cargarDocumentoEntrada($this->sentencia->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.sentencias.cancelatoria');
    }
}
