<?php

namespace App\Traits\Inscripciones;

use App\Models\Persona;
use App\Models\MovimientoRegistral;

trait RecuperarPropietariosTrait{

    public function obtenerMovimiento(){



    }

    public function recuperarPropietarios(MovimientoRegistral $movimientoRegistral){

        foreach($movimientoRegistral->folioReal->predio->propietarios() as $propietario) {
            $propietario->delete();
        }

        $objeto = json_decode($movimientoRegistral->firmaElectronica->cadena_original);

        $propietarios = $objeto->predio->propietarios;

        foreach($propietarios as $propietario) {

            $persona = Persona::fristOrCreate(
                [
                    'nombre' => $propietario->nombre,
                    'ap_paterno' => $propietario->ap_paterno,
                    'ap_materno' => $propietario->ap_materno,
                    'razon_social' => $propietario->razon_social,
                ],
                [
                    'nombre' => $propietario->nombre,
                    'ap_paterno' => $propietario->ap_paterno,
                    'ap_materno' => $propietario->ap_materno,
                    'razon_social' => $propietario->razon_social,
                ]
            );

            $movimientoRegistral->folioReal->predio->actor()->create([
                'tipo_actor' => 'propietario',
                'persona_id' => $persona->id,
                'porcentaje_propiedad' => $propietario->porcentaje_propiedad,
                'porcentaje_nuda' => $propietario->porcentaje_nuda,
                'porcentaje_usufructo' => $propietario->porcentaje_usufructo,
            ]);

        }

    }

}
