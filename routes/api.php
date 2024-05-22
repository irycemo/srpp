<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CertificacionController;
use App\Http\Controllers\Api\FolioRealController;
use App\Http\Controllers\Api\MovimientoRegistralController;

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

});

Route::fallback(function(){

    return response()->json([
        'message' => 'Página no encontrada.'
    ], 404);

});
