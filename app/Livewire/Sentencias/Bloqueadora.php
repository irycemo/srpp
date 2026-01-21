<?php

namespace App\Livewire\Sentencias;

use Livewire\Component;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\Inscripciones\Sentencias\SentenciaTrait;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Traits\Inscripciones\DocumentoEntradaTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;
use Spatie\LivewireFilepond\WithFilePond;

class Bloqueadora extends Component
{

    use SentenciaTrait;
    use WithFilePond;
    use DocumentoEntradaTrait;
    use GuardarDocumentoEntradaTrait;
    use ConsultarArchivoTrait;

    public $movimientoRegistral;

    protected function rules(){
        return [
            'sentencia.acto_contenido' => 'required',
            'sentencia.descripcion' => 'required',
            'sentencia.tipo' => 'required',
            'sentencia.hojas' => 'nullable',
            'sentencia.expediente' => 'nullable',
            'sentencia.tomo' => 'nullable',
            'sentencia.registro' => 'nullable',
            'documento_entrada_pdf' => 'nullable|mimes:pdf|max:100000',
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

                $this->sentencia->movimientoRegistral->folioReal->update(['estado' => 'bloqueado']);

                $this->sentencia->movimientoRegistral->folioReal->bloqueos()->create([
                                                                                       'folio_real_id' => $this->sentencia->movimientoRegistral->folio_real,
                                                                                       'tipo' => 'bloqueado',
                                                                                       'estado' => 'activo',
                                                                                       'observaciones' => 'Se bloquea folio real mediante sentencia con folio: ' . $this->sentencia->movimientoRegistral->folio,
                                                                                       'creado_por' => auth()->id()
                                                                                    ]);

                $this->sentencia->movimientoRegistral->update(['estado' => 'finalizado', 'actualizado_por' => auth()->id()]);

                $this->sentencia->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de sentencia']);

                (new SentenciasController())->caratula($this->sentencia);

            });

            $this->dispatch('imprimir_documento', ['caratula' => $this->sentencia->id]);

            sleep(2);

            return redirect()->route('sentencias');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->movimientoRegistral = $this->sentencia->movimientoRegistral;

        $this->consultarArchivo($this->movimientoRegistral);

        $this->actos = Constantes::ACTOS_INSCRIPCION_SENTENCIAS;

        $this->cargarDocumentoEntrada($this->sentencia->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.sentencias.bloqueadora');
    }
}
