<?php

use App\Models\Actor;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use App\Models\Representado;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

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
