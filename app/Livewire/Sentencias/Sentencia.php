<?php

namespace App\Livewire\Sentencias;

use Livewire\Component;
use App\Http\Services\FolioRealService;
use App\Http\Controllers\Sentencias\SentenciasController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;
use App\Traits\Inscripciones\DocumentoEntradaTrait;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Traits\Inscripciones\Sentencias\SentenciaTrait;
use Spatie\LivewireFilepond\WithFilePond;

class Sentencia extends Component
{

    use SentenciaTrait;
    use ConsultarArchivoTrait;
    use DocumentoEntradaTrait;
    use GuardarDocumentoEntradaTrait;
    use WithFilePond;

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

    #[On('cambiarActo')]
    public function cambiarActo($acto){

        $this->sentencia->acto_contenido = $acto;

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

                $this->sentencia->estado = 'activo';
                $this->sentencia->actualizado_por = auth()->id();
                $this->sentencia->fecha_inscripcion = now()->toDateString();
                $this->sentencia->save();

                $this->actualizarDocumentoEntrada($this->sentencia->movimientoRegistral);

                $this->sentencia->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id(), 'fecha_elaboracion' => now()->toDateString()]);

                $this->sentencia->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de sentencia']);

                (new SentenciasController())->caratula($this->sentencia);

                (new FolioRealService())->revisarCertificadosGravamenPendientes($this->sentencia->movimientoRegistral);

            });

            return redirect()->route('sentencias');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function finalizar(){

        $this->validate();

        if(!$this->sentencia->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['warning', "Debe subir el documento de entrada."]);

            return;

        }

        $this->modalContraseña = true;

    }

    public function mount(){

        $this->consultarArchivo($this->sentencia->movimientoRegistral);

        $this->actos = Constantes::ACTOS_INSCRIPCION_SENTENCIAS;

        $this->movimientoRegistral = $this->sentencia->movimientoRegistral;

        $this->cargarDocumentoEntrada($this->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.sentencias.sentencia')->extends('layouts.admin');
    }

}
