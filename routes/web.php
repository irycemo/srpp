<?php

use App\Livewire\Consulta\Consulta;
use App\Livewire\PaseFolio\PaseFolio;
use Illuminate\Support\Facades\Route;
use App\Livewire\PaseFolio\Elaboracion;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SetPasswordController;
use App\Livewire\PersonaMoral\PaseFolioPersonaMoral;
use App\Http\Controllers\PaseFolio\PaseFolioController;
use App\Livewire\PersonaMoral\Asiganacion;
use App\Livewire\PersonaMoral\Reformas;
use App\Livewire\PersonaMoral\ReformasIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::group(['middleware' => ['auth', 'esta.activo']], function(){

    /* Dashboard */
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    /* Pase a folio */
    Route::get('pase_folio', PaseFolio::class)->middleware('permission:Pase a folio')->name('pase_folio');
    Route::get('pase_folio/{folioReal}', [PaseFolioController::class, 'caratula'])->name('pase_folio_caratula');
    Route::get('elaboracion_folio/{movimientoRegistral}', Elaboracion::class)->middleware('permission:Pase a folio')->name('elaboracion_folio');

    /* Personas morales */
    Route::get('pase_folio_persona_moral', PaseFolioPersonaMoral::class)->middleware('permission:Personas morales')->name('pase_folio_personas_morales');
    Route::get('reformas', ReformasIndex::class)->middleware('permission:Reformas')->name('reformas');
    Route::get('asignacion/{movimientoRegistral?}', Asiganacion::class)->middleware('permission:Asignación')->name('asignacion');
    Route::get('reformas/{reformaMoral}', Reformas::class)->middleware('permission:Reformas inscripción')->name('reformas.inscripcion');

    /* Consultas */
    Route::get('consultas', Consulta::class)->middleware('permission:Consultas')->name('consultas');

    /* Manual */
    Route::get('manual', ManualController::class)->name('manual');

});

/* Actualización de contraseña */
Route::get('setpassword/{email}', [SetPasswordController::class, 'create'])->name('setpassword');
Route::post('setpassword', [SetPasswordController::class, 'store'])->name('setpassword.store');

