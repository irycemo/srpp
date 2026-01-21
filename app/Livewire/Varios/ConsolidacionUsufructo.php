<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\Actor;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\Inscripciones\Varios\VariosTrait;
use App\Http\Controllers\Varios\VariosController;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Traits\Inscripciones\DocumentoEntradaTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;
use Spatie\LivewireFilepond\WithFilePond;

class ConsolidacionUsufructo extends Component
{

    use VariosTrait;
    use WithFilePond;
    use DocumentoEntradaTrait;
    use GuardarDocumentoEntradaTrait;
    use ConsultarArchivoTrait;

    public $porcentaje_propiedad = 0;
    public $porcentaje_nuda = 0;
    public $porcentaje_usufructo = 0;

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
            'documento_entrada_pdf' => 'nullable|mimes:pdf|max:100000',
            'porcentaje_propiedad' => 'required|numeric|min:0',
            'porcentaje_nuda' => 'required|numeric|min:0',
            'porcentaje_usufructo' => 'required|numeric|min:0',
            'tipo_documento' => 'required',
            'autoridad_cargo' => 'required',
            'autoridad_nombre' => 'required',
            'numero_documento' => 'nullable',
            'fecha_emision' => 'required',
            'procedencia' => 'nullable',

         ];
    }

    public function updated($property, $value){

        if(in_array($property, ['porcentaje_nuda', 'porcentaje_usufructo', 'porcentaje_propiedad']) && $value == ''){

            $this->$property = 0;

        }

        if(in_array($property, ['porcentaje_nuda', 'porcentaje_usufructo'])){

            $this->reset('porcentaje_propiedad');

        }elseif($property == 'porcentaje_propiedad'){

            $this->reset(['porcentaje_nuda', 'porcentaje_usufructo']);

        }

    }

    public function abrirModalEditarPropietario(Actor $actor){

        $this->actor = $actor;

        $this->porcentaje_propiedad = $actor->porcentaje_propiedad;
        $this->porcentaje_nuda = $actor->porcentaje_nuda;
        $this->porcentaje_usufructo = $actor->porcentaje_usufructo;

        $this->modalPersona = true;

    }

    public function actualizarPorcentajes(){

        $this->validate();

        try {

            $this->actor->update([
                'porcentaje_propiedad' => $this->porcentaje_propiedad,
                'porcentaje_nuda' => $this->porcentaje_nuda,
                'porcentaje_usufructo' => $this->porcentaje_usufructo,
            ]);

            $this->vario->load('actores.persona');

            $this->modalPersona = false;

            $this->dispatch('mostrarMensaje', ['success', "El actor se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar propietario en consolidación de usufructo por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                $this->vario->estado = 'activo';
                $this->vario->actualizado_por = auth()->id();
                $this->vario->fecha_inscripcion = now()->toDateString();
                $this->vario->save();

                $this->actualizarDocumentoEntrada($this->vario->movimientoRegistral);

                $this->vario->movimientoRegistral->update(['estado' => 'elaborado']);

                $this->actualizarPropietarios();

                (new VariosController())->caratula($this->vario);

            });

            return redirect()->route('varios');

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function actualizarPropietarios(){

        $this->revisarPorcentajes();

        foreach ($this->vario->actores as $actor){

            $propietario = $this->vario->movimientoRegistral->folioReal->predio->propietarios()->where('persona_id', $actor->persona_id)->first();

            if($actor->porcentaje_propiedad == 0 && $actor->porcentaje_nuda == 0 && $actor->porcentaje_usufructo){

                $propietario->delete();

            }else{

                $propietario->update([
                    'porcentaje_propiedad' => $actor->porcentaje_propiedad,
                    'porcentaje_nuda' => $actor->porcentaje_nuda,
                    'porcentaje_usufructo' => $actor->porcentaje_usufructo,
                ]);

            }

        }

    }

    public function revisarPorcentajes(){

        $pn = 0;

        $pu = 0;

        $pp = 0;

        foreach($this->vario->actores as $propietario){

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        if($pp == 0){

            if($pn < 99.9999){

                throw new Exception("El porcentaje de nuda propiedad no es el 100%.");

            }

            if($pu < 99.9999){

                throw new Exception("El porcentaje de usufructo no es el 100%.");

            }

        }else{


            if(($pn + $pp) < 99.9999){

                throw new Exception("El porcentaje de nuda propiedad no es el 100%.");

            }

            if(($pu + $pp) < 99.9999){

                throw new Exception("El porcentaje de usufructo no es el 100%.");

            }

        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                if($this->vario->movimientoRegistral->estado != 'correccion')
                    $this->vario->movimientoRegistral->estado = 'captura';

                $this->vario->movimientoRegistral->actualizado_por = auth()->id();

                $this->vario->save();

                $this->actualizarDocumentoEntrada($this->vario->movimientoRegistral);

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

        $this->vario->acto_contenido = 'CONSOLIDACIÓN DEL USUFRUCTO';

        if($this->vario->actores()->count() == 0){

            foreach ($this->vario->movimientoRegistral->folioReal->predio->propietarios() as $propietario) {

                $this->vario->actores()->create([
                    'persona_id' => $propietario->persona_id,
                    'tipo_actor' => 'propietario',
                    'porcentaje_propiedad' => $propietario->porcentaje_propiedad,
                    'porcentaje_nuda' => $propietario->porcentaje_nuda,
                    'porcentaje_usufructo' => $propietario->porcentaje_usufructo,
                ]);

            }

        }

        $this->vario->load('actores.persona');

        $this->cargarDocumentoEntrada($this->vario->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.varios.consolidacion-usufructo');
    }
}
