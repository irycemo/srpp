<?php

use App\Models\Actor;
use App\Models\Representado;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Certificaciones\CertificadoPropiedadController;
use App\Models\FolioReal;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('usuario', function(){

    $movimientosRegistrales = MovimientoRegistral::all();

    foreach ($movimientosRegistrales as $movimientoRegistral) {

        $tramite = DB::connection('mysql2')->table('tramites')->where('año', $movimientoRegistral->año)->where('numero_control', $movimientoRegistral->tramite)->first();

        if($tramite)
            $movimientoRegistral->update(['usuario' =>  $tramite->usuario]);

    }

});

Artisan::command('representanes', function(){

    $representados = Actor::whereNotNull('representado_por')->get();

    $this->info('Incian ' . $representados->count());

    foreach($representados as $representado){

        Representado::create([
            'representante_id' => $representado->representado_por,
            'representado_id' => $representado->id,
        ]);

    }

});

Artisan::command('bienestar', function(){

    $cert_bienestar = MovimientoRegistral::with('firmaElectronica')->where(
        "servicio_nombre",
        "Certificado negativo de vivienda bienestar"
      )
        ->where("updated_at", "<", now()->startOfDay())
        ->get();

        foreach($cert_bienestar as $cert)
            (new CertificadoPropiedadController())->test($cert);


    info("Proceso de genrar imagen de caratulas finalizado.");

});

Artisan::command('pase_a_folio', function(){

    DB::transaction(function () {

        $folio_real_ids = FolioReal::pluck('id');

        DB::table('movimiento_registrals')->where('folio', 1)->whereIn('folio_real', $folio_real_ids)->update(['pase_a_folio' => true]);

        DB::table('movimiento_registrals')->join('propiedads', 'movimiento_registrals.id', '=', 'propiedads.movimiento_registral_id')->where('folio', 1)->whereNull('folio_real')->update(['pase_a_folio' => true]);

        DB::table('movimiento_registrals')->join('cancelacions', 'movimiento_registrals.id', '=', 'cancelacions.movimiento_registral_id')->where('folio', 1)->whereNull('folio_real')->update(['pase_a_folio' => true]);

        DB::table('movimiento_registrals')->join('gravamens', 'movimiento_registrals.id', '=', 'gravamens.movimiento_registral_id')->where('folio', 1)->whereNull('folio_real')->update(['pase_a_folio' => true]);

        DB::table('movimiento_registrals')->join('sentencias', 'movimiento_registrals.id', '=', 'sentencias.movimiento_registral_id')->where('folio', 1)->whereNull('folio_real')->update(['pase_a_folio' => true]);

        DB::table('movimiento_registrals')->join('varios', 'movimiento_registrals.id', '=', 'varios.movimiento_registral_id')->where('folio', 1)->whereNull('folio_real')->update(['pase_a_folio' => true]);

        DB::table('movimiento_registrals')->join('certificacions', 'movimiento_registrals.id', '=', 'certificacions.movimiento_registral_id')
                                            ->where('certificacions.servicio', 'DL07')
                                            ->where('folio', 1)
                                            ->whereNull('movimiento_registrals.folio_real')
                                            ->update(['pase_a_folio' => true]);

        DB::table('movimiento_registrals')->join('certificacions', 'movimiento_registrals.id', '=', 'certificacions.movimiento_registral_id')
                                            ->where('certificacions.servicio', 'DL10')
                                            ->where('folio', 1)
                                            ->whereNull('movimiento_registrals.folio_real')
                                            ->whereNotNull('tomo')
                                            ->whereNotNull('registro')
                                            ->whereNotNull('numero_propiedad')
                                            ->update(['pase_a_folio' => true]);

    });

    info("Proceso actualizar condicion de pase a folio finalizado.");

});
