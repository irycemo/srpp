<?php

use App\Livewire\Consulta\Consulta;
use App\Livewire\PaseFolio\PaseFolio;
use Illuminate\Support\Facades\Route;
use App\Livewire\PaseFolio\Elaboracion;
use App\Livewire\PersonaMoral\Reformas;
use App\Livewire\Consulta\IndicesVarios;
use App\Http\Controllers\ManualController;
use App\Livewire\Consulta\IndicesGravamen;
use App\Livewire\PersonaMoral\Asiganacion;
use App\Livewire\Consulta\IndicesPropiedad;
use App\Livewire\Consulta\IndicesSentencia;
use App\Livewire\PersonaMoral\ReformasIndex;
use App\Http\Controllers\DashboardController;
use App\Livewire\Consulta\IndicesCancelacion;
use App\Http\Controllers\SetPasswordController;
use App\Livewire\PersonaMoral\PaseFolioPersonaMoral;
use App\Http\Controllers\PaseFolio\PaseFolioController;

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
    Route::get('indices_propiedad', IndicesPropiedad::class)->middleware('permission:Consultas')->name('indices.propiedad');
    Route::get('indices_gravamen', IndicesGravamen::class)->middleware('permission:Consultas')->name('indices.gravamen');
    Route::get('indices_sentencia', IndicesSentencia::class)->middleware('permission:Consultas')->name('indices.sentencia');
    Route::get('indices_cancelacion', IndicesCancelacion::class)->middleware('permission:Consultas')->name('indices.cancelacion');
    Route::get('indices_varios', IndicesVarios::class)->middleware('permission:Consultas')->name('indices.varios');

    /* Manual */
    Route::get('manual', ManualController::class)->name('manual');

});

/* Actualización de contraseña */
Route::get('setpassword/{email}', [SetPasswordController::class, 'create'])->name('setpassword');
Route::post('setpassword', [SetPasswordController::class, 'store'])->name('setpassword.store');

