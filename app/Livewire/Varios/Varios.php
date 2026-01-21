<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\Vario;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\Inscripciones\Varios\VariosTrait;
use App\Http\Controllers\Varios\VariosController;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;
use App\Traits\Inscripciones\ReasignarmeMovimientoTrait;
use Spatie\LivewireFilepond\WithFilePond;

class Varios extends Component
{

    use WithFileUploads;
    use VariosTrait;
    use WithFilePond;
    use ConsultarArchivoTrait;
    use GuardarDocumentoEntradaTrait;
    use ReasignarmeMovimientoTrait;

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

        $this->consultarArchivo($this->vario->movimientoRegistral);

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

            $this->actos = array_flip($this->actos);

        }

    }

    public function render()
    {
        return view('livewire.varios.varios')->extends('layouts.admin');
    }
}
