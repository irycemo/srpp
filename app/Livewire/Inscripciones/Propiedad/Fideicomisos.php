<?php

namespace App\Livewire\Inscripciones\Propiedad;

use Exception;
use App\Models\File;
use App\Models\Actor;
use Livewire\Component;
use App\Models\Fideicomiso;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Spatie\LivewireFilepond\WithFilePond;
use App\Http\Controllers\InscripcionesPropiedad\FideicomisoController;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Traits\Inscripciones\DocumentoEntradaTrait;

class Fideicomisos extends Component
{

    use WithFilePond;
    use DocumentoEntradaTrait;
    use ConsultarArchivoTrait;

    public Fideicomiso $fideicomiso;

    public $actores;
    public $actos;

    public $modalDocumento = false;
    public $modalContraseña = false;
    public $contraseña;

    public $documento;

    protected $listeners = ['refresh'];

    protected function rules(){
        return [
            'fideicomiso.estado' => 'required',
            'fideicomiso.tipo' => 'required',
            'fideicomiso.objeto' => 'required',
            'fideicomiso.objeto' => 'required',
            'fideicomiso.fecha_vencimiento' => 'nullable|date',
        ];

    }

    public function refresh(){

        $this->fideicomiso->load('actores.persona');

    }

    public function abrirModalDocumento(){

        $this->reset('documento');

        $this->modalDocumento = true;

    }

    public function eliminarActor(Actor $actor){

        $this->authorize('update', $this->fideicomiso->movimientoRegistral);

        try {

            $actor->delete();

            $this->refresh();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->fideicomiso->movimientoRegistral->update(['estado' => 'captura']);

                $this->fideicomiso->save();

                $this->actualizarDocumentoEntrada($this->fideicomiso->movimientoRegistral);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                if($this->fideicomiso->tipo == 'FIDEICOMISO TRASLATIVO'){

                    $this->procesarPropietarios();

                    $this->fideicomiso->estado = 'concluido';

                }else{

                    $this->fideicomiso->estado = 'activo';

                }

                $this->fideicomiso->actualizado_por = auth()->id();
                $this->fideicomiso->fecha_inscripcion = now()->toDateString();
                $this->fideicomiso->save();

                $this->actualizarDocumentoEntrada($this->fideicomiso->movimientoRegistral);

                $this->fideicomiso->movimientoRegistral->update(['estado' => 'elaborado']);

                (new FideicomisoController())->caratula($this->fideicomiso);

            });

            return redirect()->route('propiedad.fideicomisos_index');

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardarDocumento(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                if(app()->isProduction()){

                    $pdf = $this->documento->store(config('services.ses.ruta_documento_entrada'), 's3');

                }else{

                    $pdf = $this->documento->store('/', 'documento_entrada');

                }

                File::create([
                    'fileable_id' => $this->fideicomiso->movimientoRegistral->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizar(){

        $this->validate();

        if(!$this->fideicomiso->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        if($this->fideicomiso->movimientoRegistral->FolioReal->fideicomisoActivo()){

            $this->dispatch('mostrarMensaje', ['error', "El folio real tiene un fideicomiso activo."]);

            return;

        }

        if($this->fideicomiso->fiduciarias()->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar al menos una fiduciaria."]);

            return;

        }

        if($this->fideicomiso->fideicomitentes()->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar al menos un fideicomitente."]);

            return;

        }

        if($this->fideicomiso->fideicomisarios()->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar al menos un fideicomisario."]);

            return;

        }

        $this->modalContraseña = true;

    }

    public function procesarPropietarios(){

        foreach ($this->fideicomiso->movimientoRegistral->folioReal->predio->propietarios() as $propietario) {

            $propietario->delete();

        }

        foreach ($this->fideicomiso->fiduciarias() as $fiduciaria) {

            $this->fideicomiso->movimientoRegistral->folioReal->predio->actores()->create([
                'persona_id' => $fiduciaria->persona_id,
                'tipo_actor' => 'propietario',
                'porcentaje_propiedad' => (100 / $this->fideicomiso->fiduciarias()->count())
            ]);

        }

    }

    public function mount(){

        $this->consultarArchivo($this->fideicomiso->movimientoRegistral);

        $this->actos = [
            'FIDEICOMISO TRASLATIVO',
            'FIDEICOMISO DE ADMINISTRACIÓN',
            'FIDEICOMISO DE GARANTIA',
        ];

        $this->actores = Constantes::ACTORES_FIDEICOMISO;

        $this->cargarDocumentoEntrada($this->fideicomiso->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.inscripciones.propiedad.fideicomisos')->extends('layouts.admin');
    }
}
