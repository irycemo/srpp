<?php

namespace App\Http\Services;

use App\Models\Persona;
use Exception;

class PersonaService{

    public function buscarPersona($rfc, $curp, $tipo_persona, $nombre, $ap_materno, $ap_paterno, $razon_social):Persona|null
    {

        $persona = null;

        if($rfc && $curp){

            $personaRfc = Persona::where('rfc', $rfc)->first();

            $personaCurp = Persona::where('curp', $curp)->first();

            if($personaRfc?->id != $personaCurp?->id){

                throw new Exception('Ya esta registrada otra persona con la misma CURP o RFC');

            }else{

                $persona = $personaRfc;

            }

        }elseif($rfc){

            $persona = Persona::where('rfc', $rfc)->first();

        }elseif($curp){

            $persona = Persona::where('curp', $curp)->first();

        }else{

            if($tipo_persona == 'FISICA'){

                $persona = Persona::query()
                            ->where('nombre', $nombre)
                            ->where('ap_paterno', $ap_paterno)
                            ->where('ap_materno', $ap_materno)
                            ->first();

            }else{

                $persona = Persona::where('razon_social', $razon_social)->first();

            }

        }

        return $persona;

    }

    public function crearPersona($fields):Persona
    {

        return Persona::create($fields);

    }

    public function actualizarPersona(Persona $persona, $fields):void
    {

        $persona->update($fields);

    }

}
