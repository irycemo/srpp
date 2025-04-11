<?php

namespace App\Traits;

use App\Models\MovimientoRegistral;
use App\Exceptions\InscripcionesServiceException;

trait RevisarMovimientosPosterioresTrait{

    public function revisarMovimientosPosteriores(MovimientoRegistral $movimientoRegistral){

        $movimiento = $movimientoRegistral->folioReal
                ->movimientosRegistrales()
                ->where('folio', ($movimientoRegistral->folio + 1))
                ->whereNotIn('estado', ['nuevo', 'correccion', 'pase_folio'])
                ->first();

        if($movimiento) throw new InscripcionesServiceException("El folio real tiene movimientos registrales posteriores ya elaborados.");

    }

}