<?php

use App\Livewire\Varios\Varios;
use App\Livewire\Varios\VariosIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Sentencias\Sentencia;
use App\Livewire\Gravamenes\GravamenIndex;
use App\Livewire\Cancelaciones\Cancelacion;
use App\Livewire\Sentencias\SentenciasIndex;
use App\Livewire\Cancelaciones\CancelacionIndex;
use App\Livewire\Gravamenes\GravamenInscripcion;
use App\Http\Controllers\Varios\VariosController;
use App\Livewire\Inscripciones\ConsultarInscripcion;
use App\Http\Controllers\Gravamen\GravamenController;
use App\Livewire\Inscripciones\Propiedad\Subdivisiones;
use App\Livewire\Inscripciones\Propiedad\PropiedadIndex;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Livewire\Inscripciones\Propiedad\Fraccionamientos;
use App\Livewire\Inscripciones\Propiedad\SubdivisionesIndex;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use App\Livewire\Inscripciones\Propiedad\Fideicomisos;
use App\Livewire\Inscripciones\Propiedad\FideicomisosIndex;
use App\Livewire\Inscripciones\Propiedad\PropiedadInscripcion;
use App\Livewire\Inscripciones\Propiedad\FraccionamientosIndex;

Route::group(['middleware' => ['auth', 'esta.activo']], function(){

    /* Consulta */
    Route::get('consulta_inscripcion', ConsultarInscripcion::class)->middleware('permission:Consultas inscripción')->name('consulta_inscripcion');

    /* Propiedad */
    Route::get('propiedad', PropiedadIndex::class)->middleware('permission:Propiedad')->name('propiedad');
    Route::get('fraccionamientos', FraccionamientosIndex::class)->middleware('permission:Fraccionamientos')->name('propiedad.fraccionamientos_index');
    Route::get('subdivisiones', SubdivisionesIndex::class)->middleware('permission:Subdivisiones')->name('propiedad.subdivisiones_index');
    Route::get('fideicomisos', FideicomisosIndex::class)->middleware('permission:Fideicomisos')->name('propiedad.fideicomisos_index');
    Route::get('fraccionamiento/{propiedad}', Fraccionamientos::class)->middleware('permission:Fraccionamientos')->name('propiedad.fraccionamiento');
    Route::get('subdivision/{propiedad}', Subdivisiones::class)->middleware('permission:Subdivisiones')->name('propiedad.subdivision');
    Route::get('fideicomiso/{fideicomiso}', Fideicomisos::class)->middleware('permission:Fideicomisos')->name('propiedad.fideicomiso');
    Route::get('propiedad/{propiedad}', PropiedadInscripcion::class)->middleware('permission:Propiedad inscripción')->name('propiedad.inscripcion');

    /* Gravamen */
    Route::get('gravamen', GravamenIndex::class)->middleware('permission:Gravamen')->name('gravamen');
    Route::get('gravamen/{gravamen}', GravamenInscripcion::class)->middleware('permission:Gravamen inscripción')->name('gravamen.inscripcion');
    Route::get('gravamen_pdf/{gravamen}', [GravamenController::class, 'acto'])->middleware('permission:Gravamen inscripción')->name('gravamen.inscripcion.acto');

    /* Sentencias */
    Route::get('sentencias', SentenciasIndex::class)->middleware('permission:Sentencias')->name('sentencias');
    Route::get('sentencias/{sentencia}', Sentencia::class)->middleware('permission:Sentencias inscripción')->name('sentencias.inscripcion');
    Route::get('sentencias_pdf/{sentencia}', [SentenciasController::class, 'pdf'])->middleware('permission:Sentencias inscripción')->name('sentencias.inscripcion.acto');

    /* Cancelaciones */
    Route::get('cancelacion', CancelacionIndex::class)->middleware('permission:Cancelaciones')->name('cancelacion');
    Route::get('cancelacion/{cancelacion}', Cancelacion::class)->middleware('permission:Cancelación inscripción')->name('cancelacion.inscripcion');
    Route::get('cancelacion_pdf/{cancelacion}', [CancelacionController::class, 'acto'])->middleware('permission:Cancelación inscripción')->name('cancelacion.inscripcion.acto');

    /* Varios */
    Route::get('varios', VariosIndex::class)->middleware('permission:Varios')->name('varios');
    Route::get('varios/{vario}', Varios::class)->middleware('permission:Varios inscripción')->name('varios.inscripcion');
    Route::get('varios_pdf/{vario}', [VariosController::class, 'acto'])->middleware('permission:Varios inscripción')->name('varios.inscripcion.acto');

});
