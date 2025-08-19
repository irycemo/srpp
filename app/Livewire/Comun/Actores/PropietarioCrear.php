<?php

namespace App\Livewire\Comun\Actores;

use App\Exceptions\ActoresException;
use App\Models\Actor;
use App\Models\Persona;
use Livewire\Component;
use App\Traits\ActoresTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\PersonaService;

class PropietarioCrear extends Component
{

    use ActoresTrait;

    public $predio;

    protected function rules(){

        return $this->traitRules() +[
            'curp' => [
                'nullable',
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'sub_tipo' => 'nullable',
            'porcentaje_propiedad' => 'nullable|numeric|min:0|max:100',
            'porcentaje_nuda' => 'nullable|numeric|min:0|max:100',
            'porcentaje_usufructo' => 'nullable|numeric|min:0|max:100',
        ];

    }

    public function revisarProcentajesSinTransmitentes(){

        $pp = 0;

        $pn = 0;

        $pu = 0;

        foreach($this->modelo->propietarios() as $propietario){

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        $pp = $pp + (float)$this->porcentaje_propiedad;

        $pn = $pn + (float)$this->porcentaje_nuda + $pp;

        $pu = $pu + (float)$this->porcentaje_usufructo + $pp;

        if((float)$pn > 100 || (float)$pu > 100) throw new ActoresException("La suma de los porcentajes no puede exceder el 100%.");

    }

    public function revisarProcentajesConTransmitentes(){

        $pp_transmitentes = 0;

        $pp_adquirientes = 0;

        $pn_transmitentes = 0;

        $pn_adquirientes = 0;

        $pu_transmitentes = 0;

        $pu_adquirientes = 0;

        foreach($this->modelo->transmitentes() as $transmitente){

            $pn_transmitentes = $pn_transmitentes + $transmitente->porcentaje_nuda;

            $pu_transmitentes = $pu_transmitentes + $transmitente->porcentaje_usufructo;

            $pp_transmitentes = $pp_transmitentes + $transmitente->porcentaje_propiedad;

        }

        foreach($this->modelo->propietarios() as $propietario){

            $pn_adquirientes = $pn_adquirientes + $propietario->porcentaje_nuda;

            $pu_adquirientes = $pu_adquirientes + $propietario->porcentaje_usufructo;

            $pp_adquirientes = $pp_adquirientes + $propietario->porcentaje_propiedad;

        }

        if($pp_transmitentes == 0){

            /* if(($this->porcentaje_propiedad + $pp_adquirientes - 0.01) > $pp_transmitentes)
                throw new ActoresException("La suma de los porcentajes de propiedad no puede exceder el " . $pp_transmitentes . '%.'); */

            if(($this->porcentaje_nuda + $pn_adquirientes - 0.01) > $pn_transmitentes)
                throw new ActoresException("La suma de los porcentajes de nuda no puede exceder el " . $pn_transmitentes . '%.');

            if(($this->porcentaje_usufructo + $pu_adquirientes - 0.01) > $pu_transmitentes)
                throw new ActoresException("La suma de los porcentajes de usufructo no puede exceder el " . $pu_transmitentes . '%.');

        }else{

            if(($this->porcentaje_propiedad + $pp_adquirientes - 0.01) > $pp_transmitentes)
                throw new ActoresException("La suma de los porcentajes de propiedad no puede exceder el " . $pp_transmitentes . '%.');

            if(($this->porcentaje_nuda + $pn_adquirientes + $pp_adquirientes - 0.01) > $pp_transmitentes)
                throw new ActoresException("La suma de los porcentajes de propiedad no puede exceder el " . $pp_transmitentes . '%.');

            if(($this->porcentaje_usufructo + $pu_adquirientes + $pp_adquirientes - 0.01) > $pp_transmitentes)
                throw new ActoresException("La suma de los porcentajes de usufructo no puede exceder el " . $pu_transmitentes . '%.');

        }

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

    public function validaciones(){

        if($this->porcentaje_propiedad === 0 && $this->porcentaje_nuda === 0 && $this->porcentaje_usufructo === 0){

            throw new ActoresException("La suma de los porcentajes no puede ser 0.");

        }

        if($this->predio){

            $this->revisarProcentajesConTransmitentes();

        }else{

            $this->revisarProcentajesSinTransmitentes();

        }

    }

    public function guardar(){

        $this->validate();

        $personaService = new PersonaService();

        try {

            if($this->partes_iguales) {

                $this->procesarPartesIguales();

            }else{

                $this->validaciones();

            }

            $persona = $personaService->buscarPersona($this->rfc, $this->curp, $this->tipo_persona, $this->nombre, $this->ap_materno, $this->ap_paterno, $this->razon_social);

            if($this->persona->getKey() && $persona){

                foreach($this->modelo->propietarios() as $actor){

                    if($actor->persona_id == $persona->id) throw new ActoresException('La persona ya es un actor.');

                }

                $actor = $this->modelo->actores()->create([
                    'persona_id' => $persona->id,
                    'tipo_actor' => 'propietario',
                    'porcentaje_propiedad' => $this->porcentaje_propiedad,
                    'porcentaje_nuda' => $this->porcentaje_nuda,
                    'porcentaje_usufructo' => $this->porcentaje_usufructo,
                    'creado_por' => auth()->id()
                ]);

            }elseif($persona){

                foreach($this->modelo->propietarios() as $actor){

                    if($actor->persona_id == $persona->id) throw new ActoresException('La persona ya es un actor.');

                }

                throw new ActoresException('Ya existe un persona registrada con la información ingresada.');

            }else{

                DB::transaction(function () use($personaService, &$actor){

                    $persona = $personaService->crearPersona([
                        'tipo' => $this->tipo_persona,
                        'nombre' => $this->nombre,
                        'multiple_nombre' => $this->multiple_nombre,
                        'ap_paterno' => $this->ap_paterno,
                        'ap_materno' => $this->ap_materno,
                        'curp' => $this->curp,
                        'rfc' => $this->rfc,
                        'razon_social' => $this->razon_social,
                        'fecha_nacimiento' => $this->fecha_nacimiento,
                        'nacionalidad' => $this->nacionalidad,
                        'estado_civil' => $this->estado_civil,
                        'calle' => $this->calle,
                        'numero_exterior' => $this->numero_exterior,
                        'numero_interior' => $this->numero_interior,
                        'colonia' => $this->colonia,
                        'cp' => $this->cp,
                        'entidad' => $this->entidad,
                        'ciudad' => $this->ciudad,
                        'municipio' => $this->municipio,
                    ]);

                    $actor = $this->modelo->actores()->create([
                        'persona_id' => $persona->id,
                        'tipo_actor' => 'propietario',
                        'porcentaje_propiedad' => $this->porcentaje_propiedad,
                        'porcentaje_nuda' => $this->porcentaje_nuda,
                        'porcentaje_usufructo' => $this->porcentaje_usufructo,
                        'creado_por' => auth()->id()
                    ]);

                });

            }

            $this->modelo->update(['partes_iguales' => $this->partes_iguales]);

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "El adquiriente se creó con éxito."]);

            $this->dispatch('refresh');

            $this->dispatch('recargarActores', [
                'id' => $actor->id,
                'description' => $actor->persona->nombre . ' ' .
                                    $actor->persona->ap_paterno . ' ' .
                                    $actor->persona->ap_materno . ' ' .
                                    $actor->persona->razon_social
            ]);


        } catch (ActoresException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al crear propietario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function actualizar(){

        $this->validate();

        $personaService = new PersonaService();

        try {

            $persona = $personaService->buscarPersona($this->rfc, $this->curp, $this->tipo_persona, $this->nombre, $this->ap_materno, $this->ap_paterno, $this->razon_social);

            if($persona && ($persona->id != $this->persona->id)){

                throw new ActoresException("Ya existe una persona con el RFC o CURP ingresada.");

            }else{

                $this->persona->update([
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior,
                    'numero_interior' => $this->numero_interior,
                    'colonia' => $this->colonia,
                    'cp' => $this->cp,
                    'ciudad' => $this->ciudad,
                    'entidad' => $this->entidad,
                    'nacionalidad' => $this->nacionalidad,
                    'municipio' => $this->municipio,
                    'actualizado_por' => auth()->id()
                ]);

                dd($this->partes_iguales);

                $this->modelo->update(['partes_iguales' => $this->partes_iguales]);

            }

            $this->dispatch('mostrarMensaje', ['success', "El adquiriente se actualizó con éxito."]);

            $this->dispatch('refresh');


        } catch (ActoresException $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar propietario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function procesarPartesIguales(){

        $this->porcentaje_propiedad = 0;

        $this->porcentaje_propiedad = 100 / ($this->modelo->propietarios()->count() == 0 ? 1 : $this->modelo->propietarios()->count() + 1);

        DB::transaction(function (){

            foreach($this->modelo->propietarios() as $propietario){

                $propietario->update([
                    'porcentaje_propiedad' => $this->porcentaje_propiedad,
                    'porcentaje_nuda' => 0,
                    'porcentaje_usufructo' => 0,
                ]);

            }

        });

    }

    public function mount(){

        $this->tipo_actor = 'propietario';

        $this->actor = Actor::make();

        $this->persona = Persona::make();

    }

    public function render()
    {
        return view('livewire.comun.actores.propietario-crear');
    }
}
