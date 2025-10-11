<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FirmaElectronica;
use Illuminate\Support\Facades\Storage;

class VerificacionController extends Controller
{

    public function __invoke(FirmaElectronica $firma_electronica){

        if($firma_electronica->estado != 'activo'){

            $firma_electronica->load('movimientoregistral');

            return view('verificacion', compact('firma_electronica'));

        }

        if(app()->isProduction()){

            return redirect(Storage::disk('s3')->temporaryUrl(config('services.ses.ruta_caratulas') . $firma_electronica->avaluo->caratula(), now()->addMinutes(10)));


        }else{

            return redirect(Storage::disk('caratulas')->url($firma_electronica->avaluo->caratula()));

        }

    }

}
