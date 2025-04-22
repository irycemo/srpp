<?php

use App\Http\Controllers\Api\AntecedentesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CertificacionController;
use App\Http\Controllers\Api\FolioRealController;
use App\Http\Controllers\Api\GravamenController;
use App\Http\Controllers\Api\MovimientoRegistralController;
use App\Http\Controllers\Api\VariosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('movimiento_registral', [MovimientoRegistralController::class, 'store']);

    Route::post('actualizar_registral', [MovimientoRegistralController::class, 'update']);

    Route::post('cambiar_tipo_servicio', [MovimientoRegistralController::class, 'cambiarTipoServicio']);

    Route::post('actualizar_paginas', [CertificacionController::class, 'actualizarPaginas']);

    Route::post('consultar_folio_real', [FolioRealController::class, 'consultarFolioReal']);

    Route::post('consultar_folio_real_persona_moral', [FolioRealController::class, 'consultarFolioRealPersonaMoral']);

    Route::post('consultar_folio_movimiento', [FolioRealController::class, 'consultarFolioMovimiento']);

    Route::post('consultar_gravamen', [GravamenController::class, 'consultarGravamen']);

    Route::post('consultar_antecedentes', [AntecedentesController::class, 'consultarAntecedentes']);

    Route::post('consultar_primer_aviso', [VariosController::class, 'consultarPrimerAvisoPreventivo']);

    Route::post('consultar_segundo_aviso', [VariosController::class, 'consultarSegundoAvisoPreventivo']);

});

Route::fallback(function(){

    return response()->json([
        'message' => 'Página no encontrada.'
    ], 404);

});
