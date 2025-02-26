<?php

namespace App\Traits\Inscripciones;

use App\Models\FirmaElectronica;
use App\Models\FolioReal;
use App\Models\Persona;
use App\Models\MovimientoRegistral;

trait RecuperarPropietariosTrait{

    public function obtenerMovimientoConPropietarios(MovimientoRegistral $movimientoRegistral){

        if($movimientoRegistral->folio == 1){

            $propietarios = $this->recuperarPropietarios($movimientoRegistral->folioReal->firmaElectronica);

            $this->restaurarPropietarios($movimientoRegistral->folioReal, $propietarios);

        }else{

            $folio = $movimientoRegistral->folio - 1;

            $movimiento = $movimientoRegistral->folioReal->movimientosRegistrales()->where('folio', $folio)->first();

            if($movimiento->firmaElectronica){

                $propietarios = $this->recuperarPropietarios($movimiento->firmaElectronica);

            }else{

                $propietarios = null;
            }

            while($propietarios == null){

                $folio = $folio - 1;

                if($folio < 1){

                    $propietarios = $this->recuperarPropietarios($movimientoRegistral->folioReal->firmaElectronica);

                    $this->restaurarPropietarios($movimientoRegistral->folioReal, $propietarios);

                    return;

                }

                $movimiento = $movimientoRegistral->folioReal->movimientosRegistrales()->where('folio', $folio)->first();

                $propietarios = $this->recuperarPropietarios($movimiento->firmaElectronica);

            }

            $this->restaurarPropietarios($movimientoRegistral->folioReal, $propietarios);

        }

    }

    public function recuperarPropietarios(FirmaElectronica $firmaElectronica){

        $objeto = json_decode($firmaElectronica->cadena_original);

        return $objeto->predio->propietarios;

    }

    public function restaurarPropietarios(FolioReal $folioReal, $propietarios){

        foreach($folioReal->predio->propietarios() as $propietario) {

            $propietario->delete();

        }

        foreach($propietarios as $propietario) {

            $persona = Persona::firstOrCreate(
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

            $folioReal->predio->actores()->create([
                'tipo_actor' => 'propietario',
                'persona_id' => $persona->id,
                'porcentaje_propiedad' => $propietario->porcentaje_propiedad,
                'porcentaje_nuda' => $propietario->porcentaje_nuda,
                'porcentaje_usufructo' => $propietario->porcentaje_usufructo,
            ]);

        }

    }

}
