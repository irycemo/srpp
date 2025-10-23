<?php

namespace App\Http\Controllers;

use App\Models\FirmaElectronica;

class VerificacionController extends Controller
{

    public function __invoke(FirmaElectronica $firma_electronica){

        if($firma_electronica->estado != 'activo'){

            $firma_electronica->load('movimientoregistral');

            return view('verificacion', compact('firma_electronica'));

        }

        return redirect($firma_electronica->movimientoRegistral->caratula());

    }

}
