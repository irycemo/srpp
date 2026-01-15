<?php

namespace App\Http\Services;

use App\Models\Propiedad;
use App\Models\MovimientoRegistral;
use App\Http\Services\MovimientoServiceInterface;

class FolioSimplificadoService implements MovimientoServiceInterface{

    public function crear(array $request):void
    {

        $propiedad = Propiedad::create($this->requestCrear($request));

        $propiedad->movimientoRegistral->update(['pase_a_folio' => false]);

    }

    public function obtenerUsuarioAsignado(array $request):int|null
    {

         return (new AsignacionService())->obtenerUsuarioPropiedad($request['folio_real'], $request['distrito'], $request['estado']);

    }

    public function obtenerSupervisorAsignado(array $request):int
    {

         return (new AsignacionService())->obtenerSupervisorInscripciones($request['distrito']);

    }

    public function corregir(MovimientoRegistral $movimientoRegistral):void
    {}

    public function requestCrear(array $request):array
    {

        $array = [];

        $fields = [
            'valor_propiedad',
            'numero_inmuebles',
        ];

        foreach($fields as $field){

            if(array_key_exists($field, $request)){

                $array[$field] = $request[$field];

            }

        }

        return $array +  [
            'servicio' => $request['servicio'],
            'movimiento_registral_id' => $request['movimiento_registral_id'],
        ];

    }

}