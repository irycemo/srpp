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

Artisan::command('folios_reales', function(){

    $count = 0;

    $folios_reales = FolioReal::with('predio:id,folio_real,descripcion,observaciones')
                            ->whereKey([
13622,
13689,
21352,
22028,
23300,
24724,
26429,
26778,
26781,
26783,
26791,
26792,
26838,
26933,
26934,
26935,
26936,
26937,
26938,
26939,
26940,
26941,
26942,
26943,
26944,
26945,
26946,
26947,
26948,
26949,
26950,
26951,
26952,
26953,
26954,
26955,
26956,
26957,
26958,
26959,
26960,
26961,
26962,
26963,
26964,
26965,
26966,
26967,
26968,
26969,
26970,
26971,
26972,
26973,
26974,
26975,
26976,
26977,
26978,
26979,
26980,
26981,
26982,
26983,
26984,
26985,
26986,
26987,
26988,
26989,
26990,
26991,
26992,
26993,
26994,
26995,
26996,
26997,
26998,
26999,
27000,
27001,
27002,
27003,
27004,
27005,
27006,
27007,
27008,
27009,
27010,
27011,
27012,
27013,
27014,
27015,
27016,
27017,
27018,
27019,
27020,
27021,
27022,
27023,
27024,
27025,
27026,
27027,
27028,
27029,
27030,
27031,
27032,
27033,
27034,
27035,
27036,
27037,
27038,
27039,
27040,
27041,
27042,
27043,
27044,
27045,
27046,
27047,
27048,
27049,
27050,
27051,
27052,
27053,
27054,
27055,
27056,
27057,
27058,
27059,
27060,
27061,
27062,
27063,
27064,
27065,
27066,
27067,
27068,
27069,
27070,
27071,
27072,
27073,
27074,
27075,
27076,
27077,
27078,
27079,
27080,
27081,
27082,
27083,
27084,
27085,
27086,
27087,
27088,
27089,
27090,
27091,
27092,
27093,
27094,
27095,
27096,
27097,
27098,
27099,
27100,
27101,
27102,
27103,
27104,
27105,
27106,
27107,
27108,
27109,
27110,
27111,
27112,
27113,
27114,
27115,
27116,
27117,
27118,
27119,
27120,
27121,
27122,
27123,
27124,
27125,
27126,
27127,
27128,
27129,
27130,
27131,
27132,
27133,
27134,
27135,
27136,
27137,
27138,
27139,
27140,
27141,
27142,
27143,
27144,
27145,
27146,
27147,
27148,
27149,
27150,
27151,
27152,
27153,
27154,
27155,
27156,
27157,
27158,
27159,
27160,
27161,
27162,
27163,
27164,
27165,
27166,
27167,
27168,
27169,
27170,
27171,
27172,
27173,
27174,
27175,
27176,
27177,
27178,
27179,
27180,
27181,
27182,
27183,
27184,
27185,
27186,
27187,
27188,
27189,
27190,
27191,
27192,
27193,
27194,
27195,
27196,
27197,
27198,
27199,
27200,
27201,
27202,
27203,
27204,
27205,
27206,
27207,
27208,
27209,
27210,
27211,
27212,
27213,
27214,
27215,
27216,
27217,
27218,
27219,
27220,
27221,
27222,
27223,
27224,
27225,
27226,
27227,
27228,
27229,
27230,
27231,
27232,
27233,
27234,
27235,
27236,
27237,
27238,
27239,
27240,
27241,
27242,
27243,
27244,
27245,
27246,
27247,
27248,
27249,
27250,
27251,
27252,
27253,
27254,
27255,
27256,
27257,
27258,
27259,
27260,
27261,
27262,
27263,
27264,
27265,
27266,
27267,
27268,
27269,
27270,
27271,
27272,
27273,
27274,
27275,
27276,
27277,
27278,
27279,
27280,
27281,
27282,
27283,
27284,
27285,
27286,
27287,
27288,
27289,
                            ])
                        ->get();

    $progressbar = $this->output->createProgressBar($folios_reales->count());

    $progressbar->start();

    $path = storage_path('app/public/folios.csv');

    $file = fopen($path, 'w');

    $columns = array('Folio real', 'Tomo', 'Registro', 'Numero de propiedad', 'Primer propietario', 'Descripcion');

    fputcsv($file, $columns);

    foreach($folios_reales as $folio_real){

        try {

            $data = [
                'Folio real' => $folio_real->folio,
                'Tomo' => $folio_real->tomo_antecedente,
                'Registro' => $folio_real->registro_antecedente,
                'Numero de propiedad' => $folio_real->numero_propiedad_antecedente,
                'Primer propietario' => $folio_real->predio->primerPropietario(),
                'Descripcion' => $folio_real->predio->descripcion . ' Observaciones: ' . $folio_real->predio->observaciones,
            ];

            fputcsv($file, $data);

            $progressbar->advance();

            $count ++;

        } catch (\Throwable $th) {
            $this->info($th);

        }

    }

    fclose($file);

    $progressbar->finish();

    $this->info($count);

});
