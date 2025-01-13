<?php

namespace App\Livewire\Comun\Actores;

use Exception;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Traits\ActoresTrait;

class Propietario extends Component
{

    use ActoresTrait;

    public function guardar(){

        $this->validate();

        try {

            if($this->tipo_actor == 'propietario'){

                if($this->porcentaje_propiedad === 0 && $this->porcentaje_nuda === 0 && $this->porcentaje_usufructo === 0){

                    throw new Exception("La suma de los porcentajes no puede ser 0.");

                }

                $this->revisarProcentajes();

            }

            $personaId = $this->buscarOCreartPersona();

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    public function buscarOCreartPersona(){

        $persona = null;

        if($this->rfc){

            $persona = Persona::where('rfc', $this->rfc)->first();

        }elseif($this->curp){

            $persona = Persona::where('curp', $this->curp)->first();

        }else{

            if($this->tipo_persona == 'FISICA'){

                $persona = Persona::query()
                            ->where('nombre', $this->nombre)
                            ->where('ap_paterno', $this->ap_paterno)
                            ->where('ap_materno', $this->ap_materno)
                            ->first();

            }else{

                $persona = Persona::where('razon_social', $this->razon_social)->first();

            }

        }

        if($persona && $this->tipo_actor == 'propietario'){

            foreach ($this->predio->propietarios() as $propietario) {

                if($persona->id == $propietario->persona_id) throw new Exception("La persona ya es un adquiriente.");

            }

        }

        if($persona != null){

            $persona->update([
                'estado_civil' => $this->estado_civil,
                'calle' => $this->calle,
                'numero_exterior' => $this->numero_exterior,
                'numero_interior' => $this->numero_interior,
                'colonia' => $this->colonia,
                'cp' => $this->cp,
                'entidad' => $this->entidad,
                'municipio' => $this->municipio,
                'actualizado_por' => auth()->id()
            ]);

        }else{

            $persona = Persona::create([
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
                'municipio' => $this->municipio,
                'creado_por' => auth()->id()
            ]);

        }

        return $persona->id;

    }
    public function revisarProcentajes($id = null){

        $pp = $pn = $pu = 0;

        foreach($this->predio->propietarios() as $propietario){

            if($id == $propietario->id) continue;

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        $pp = $pp + (float)$this->porcentaje_propiedad;

        $pn = $pn + (float)$this->porcentaje_nuda + $pp;

        $pu = $pu + (float)$this->porcentaje_usufructo + $pp;

        if($pn > 100 || $pu > 100) throw new Exception("La suma de los porcentajes no puede exceder el 100%.");

    }

    public function mount(){

        if(isset($this->actor)){

            if($this->actor->tipo_actor == 'propietario'){

                $this->porcentaje_propiedad = $this->actor->porcentaje_propiedad;
                $this->porcentaje_nuda = $this->actor->porcentaje_nuda;
                $this->porcentaje_usufructo = $this->actor->porcentaje_usufructo;

                $this->predio = Predio::find($this->predio_id);

            }

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

        }else{

            $this->actor = ModelActor::make();

        }

    }

    public function render()
    {
        return view('livewire.comun.actores.propietario');
    }
}
