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

Artisan::command('notario', function(){

    $count = 0;

    $folios_reales = FolioReal::with('predio.escritura')
                        ->whereIn('folio', [
                            18202,
                            18213,
                            18217,
                            18219,
                            18221,
                            18222,
                            18225,
                            18230,
                            18231,
                            18232,
                            18233,
                            18234,
                            18238,
                            18239,
                            18240,
                            18241,
                            18242,
                            18243,
                            18244,
                            18245,
                            18246,
                            18247,
                            18249,
                            18250,
                            18251,
                            18252,
                            18253,
                            18255,
                            18256,
                            18257,
                            18258,
                            18260,
                            18261,
                            18262,
                            18263,
                            18264,
                            18265,
                            18266,
                            18267,
                            18268,
                            18269,
                            18270,
                            18271,
                            18272,
                            18273,
                            18274,
                            18275,
                            18276,
                            18277,
                            18278,
                            18279,
                            18280,
                            18281,
                            18282,
                            18283,
                            18284,
                            18285,
                            18286,
                            18287,
                            18288,
                            18289,
                            18290,
                            18291,
                            18292,
                            18293,
                            18294,
                            18295,
                            18296,
                            18297,
                            18298,
                            18299,
                            18300,
                            18301,
                            18302,
                            18303,
                            18304,
                            18305,
                            18306,
                            18307,
                            18308,
                            18309,
                            18310,
                            18311,
                            18312,
                            18313,
                            18314,
                            18315,
                            18316,
                            18317,
                            18318,
                            18319,
                            18320,
                            18321,
                            18322,
                            18323,
                            18324,
                            18325,
                            18326,
                            18327,
                            18328,
                            18329,
                            18330,
                            18331,
                            18332,
                            18333,
                            18334,
                            18335,
                        ])
                        ->get();

    $progressbar = $this->output->createProgressBar($folios_reales->count());

    $progressbar->start();

    foreach($folios_reales as $folio_real){

        try {

            $folio_real->predio->escritura->update(['estado_notario' => 'SINALOA']);

            $progressbar->advance();

            $count ++;

        } catch (\Throwable $th) {
            $this->info($th);

        }

    }

    $progressbar->finish();

    $this->info($count);

});