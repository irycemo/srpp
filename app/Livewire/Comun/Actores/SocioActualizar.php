<?php

namespace App\Livewire\Comun\Actores;

use App\Models\Actor;
use App\Models\Persona;
use Livewire\Component;
use App\Traits\ActoresTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SocioActualizar extends Component
{

    use ActoresTrait;

    protected function rules(){

        return $this->traitRules() +[
            'curp' => [
                'nullable',
                'unique:personas,curp,' . $this->actor->persona_id,
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'unique:personas,rfc,' . $this->actor->persona_id,
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'sub_tipo' => 'required'
        ];

    }

    public function actualizar(){

        $this->validate();

        try {

            DB::transaction(function (){

                $this->persona->update([
                    'nombre' => $this->nombre,
                    'multiple_nombre' => $this->multiple_nombre,
                    'ap_paterno' => $this->ap_paterno,
                    'ap_materno' => $this->ap_materno,
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'razon_social' => $this->razon_social,
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior,
                    'numero_interior' => $this->numero_interior,
                    'colonia' => $this->colonia,
                    'cp' => $this->cp,
                    'ciudad' => $this->ciudad,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'entidad' => $this->entidad,
                    'nacionalidad' => $this->nacionalidad,
                    'municipio' => $this->municipio,
                    'actualizado_por' => auth()->id()
                ]);

                $this->actor->update([
                    'tipo_socio' => $this->sub_tipo,
                    'actualizado_por' => auth()->id()
                ]);

            });

            $this->dispatch('mostrarMensaje', ['success', "La persona se actualizó con éxito."]);

            $this->dispatch('refresh');

            $this->modal = false;


        }  catch (\Throwable $th) {

            Log::error("Error al actualizar socio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if(isset($this->actor)){

            $this->persona = $this->actor->persona;
            $this->tipo_actor = $this->actor->tipo_actor;
            $this->tipo_persona = $this->actor->persona->tipo;
            $this->nombre = $this->actor->persona->nombre;
            $this->multiple_nombre = $this->actor->persona->multiple_nombre;
            $this->ap_paterno = $this->actor->persona->ap_paterno;
            $this->ap_materno = $this->actor->persona->ap_materno;
            $this->curp = $this->actor->persona->curp;
            $this->rfc = $this->actor->persona->rfc;
            $this->razon_social = $this->actor->persona->razon_social;
            $this->fecha_nacimiento = $this->actor->persona->fecha_nacimiento;
            $this->nacionalidad = $this->actor->persona->nacionalidad;
            $this->estado_civil = $this->actor->persona->estado_civil;
            $this->calle = $this->actor->persona->calle;
            $this->numero_exterior = $this->actor->persona->numero_exterior;
            $this->numero_interior = $this->actor->persona->numero_interior;
            $this->colonia = $this->actor->persona->colonia;
            $this->cp = $this->actor->persona->cp;
            $this->entidad = $this->actor->persona->entidad;
            $this->ciudad = $this->actor->persona->ciudad;
            $this->municipio = $this->actor->persona->municipio;

            $this->sub_tipo = $this->actor->tipo_socio;

        }else{

            $this->actor = Actor::make();

            $this->persona = Persona::make();

        }

    }

    public function render()
    {
        return view('livewire.comun.actores.socio-actualizar');
    }
}
