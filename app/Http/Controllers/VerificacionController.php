<?php

namespace App\Http\Controllers;

use App\Models\FirmaElectronica;

class VerificacionController extends Controller
{

    public function __invoke(FirmaElectronica $firma_electronica){

        if($firma_electronica->estado != 'activo'){

            $firma_electronica->load('movimientoregistral', 'folioReal');

            return view('verificacion', compact('firma_electronica'));

        }

        if($firma_electronica->movimientoRegistral){

            return redirect($firma_electronica->movimientoRegistral->caratula());

        }elseif($firma_electronica->movimientoRegistral){

            return redirect($firma_electronica->movimientoRegistral->caratula());

        }

    }

}
