<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\File;
use App\Models\Actor;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\Inscripciones\Varios\VariosTrait;

class ConsolidacionUsufructo extends Component
{

    use WithFileUploads;
    use VariosTrait;

    public $porcentaje_propiedad;
    public $porcentaje_nuda;
    public $porcentaje_usufructo;

    public $documento;

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required'
         ];
    }

    public function abrirModalFinalizar(){

        $this->reset('documento');

        $this->dispatch('removeFiles');

        $this->modalDocumento = true;

    }

    public function guardarDocumento(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                if(env('LOCAL') == "1"){

                    $pdf = $this->documento->store('srpp/documento_entrada', 's3');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada_s3',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "0"){

                    $pdf = $this->documento->store('/', 'documento_entrada');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "2"){

                    $pdf = $this->documento->store('srpp/documento_entrada', 's3');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada_s3',
                        'url' => $pdf
                    ]);

                    $pdf = $this->documento->store('/', 'documento_entrada');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $pdf
                    ]);

                }

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de cancelación por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

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

    public function finalizar(){

        $this->validate();

        if(!$this->vario->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        $this->modalContraseña = true;

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

                $this->actualizarPropietarios();

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

            if($pn <= 99.99){

                throw new Exception("El porcentaje de nuda propiedad no es el 100%.");

            }

            if($pu <= 99.99){

                throw new Exception("El porcentaje de usufructo no es el 100%.");

            }

        }else{


            if(($pn + $pp) <= 99.99){

                throw new Exception("El porcentaje de nuda propiedad no es el 100%.");

            }

            if(($pu + $pp) <= 99.99){

                throw new Exception("El porcentaje de usufructo no es el 100%.");

            }

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

    }

    public function render()
    {
        return view('livewire.varios.consolidacion-usufructo');
    }
}
