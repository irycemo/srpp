<?php

namespace App\Livewire\Varios\SinPropiedad;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Varios\VariosController;
use App\Models\VariosFolio;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;
use App\Traits\Inscripciones\Varios\VariosTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\LivewireFilepond\WithFilePond;

class SinPropiedad extends Component
{

    use WithFileUploads;
    use VariosTrait;
    use WithFilePond;
    use ConsultarArchivoTrait;
    use GuardarDocumentoEntradaTrait;

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
            'documento_entrada_pdf' => 'nullable|mimes:pdf|max:100000',
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

                VariosFolio::create([
                                    'folio' => (VariosFolio::max('folio') ?? 0) + 1,
                                    'movimiento_registral_id' => $this->vario->movimientoRegistral->id
                                ]);

                $this->vario->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de varios']);

                (new VariosController())->caratulaSinPropiedad($this->vario);

            });

            return redirect()->route('varios_sin_porpiead');

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de varios sin propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

    public function mount(){

        $this->movimientoRegistral = $this->vario->movimientoRegistral;

        $this->consultarArchivo($this->vario->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.varios.sin-propiedad.sin-propiedad')->extends('layouts.admin');
    }
}
