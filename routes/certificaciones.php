<?php

use App\Http\Controllers\Certificaciones\CertificadoGravamenController;
use App\Http\Controllers\Certificaciones\CertificadoPropiedadController;
use App\Http\Controllers\Certificaciones\CopiasController;
use App\Livewire\Certificaciones\CertificadoBienestarIndex;
use App\Livewire\Certificaciones\CertificadoGravamen;
use App\Livewire\Certificaciones\CertificadoNegativo\CertificadoBienestar;
use App\Livewire\Certificaciones\CertificadoPropiedad;
use App\Livewire\Certificaciones\CertificadoPropiedadIndex;
use App\Livewire\Certificaciones\Consultas;
use App\Livewire\Certificaciones\ConsultasCertificaciones;
use App\Livewire\Certificaciones\Copiador;
use App\Livewire\Certificaciones\CopiasCertificadas;
use App\Livewire\Certificaciones\CopiasSimples;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'esta.activo']], function(){

    /* Copiador */
    Route::get('copiador', Copiador::class)->middleware('permission:Copiador')->name('copiador');

    /* Copias simples */
    Route::get('copias_simples', CopiasSimples::class)->middleware('permission:Copias Simples')->name('copias_simples');
    Route::get('copia_simple/{certificacion}', [CopiasController::class, 'copiaSimple'])->name('copia_simple');

    /* Copias certificadas */
    Route::get('copias_certificadas', CopiasCertificadas::class)->middleware('permission:Copias Certificadas')->name('copias_certificadas');
    Route::get('copia_certificada/{certificacion}', [CopiasController::class, 'copiaCertificada'])->name('copia_certificada');

    /* Indices */
    Route::get('indices_y_tomos', Consultas::class)->middleware('permission:Indices y tomos')->name('indices_y_tomos');

    /* Cosultas */
    Route::get('consultas_certificaciones', ConsultasCertificaciones::class)->middleware('permission:Consultas certificaciones')->name('consulta_certificaciones');

    /* Certificado de gravamen */
    Route::get('certificado_gravamen', CertificadoGravamen::class)->middleware('permission:Certificado gravamen')->name('certificado_gravamen');
    Route::get('certificado_gravamen_pdf/{movimientoRegistral}', [CertificadoGravamenController::class, 'certificadoGravamen'])->middleware('permission:Certificado gravamen')->name('certificado_gravamen_pdf');

    /* Certificado de propiedad */
    Route::get('certificado_bienestar', CertificadoBienestarIndex::class)->middleware('permission:Certificado propiedad')->name('certificados_bienestar');
    Route::get('certificado_propiedad', CertificadoPropiedadIndex::class)->middleware('permission:Certificado propiedad')->name('certificados_propiedad');
    Route::get('certificado_bienestar/{certificacion}', CertificadoBienestar::class)->middleware('permission:Certificado propiedad')->name('certificado_bienestar');
    Route::get('certificado_propiedad/{certificacion}', CertificadoPropiedad::class)->middleware('permission:Certificado propiedad')->name('certificado_propiedad');
    Route::get('certificado_negativo_propiedad_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoNegativoPropiedad'])->middleware('permission:Certificado propiedad')->name('certificado_negativo_propiedad_pdf');
    Route::get('certificado_propiedad_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoPropiedad'])->middleware('permission:Certificado propiedad')->name('certificado_propiedad_pdf');
    Route::get('certificado_unico_propiedad_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoUnicoPropiedad'])->middleware('permission:Certificado propiedad')->name('certificado_unico_propiedad_pdf');
    Route::get('certificado_propiedad_colindancias_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoPropiedadColindancias'])->middleware('permission:Certificado propiedad')->name('certificado_propiedad_colindancias_pdf');
    Route::get('certificado_negativo_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoNegativo'])->middleware('permission:Certificado propiedad')->name('certificado_negativo_pdf');


});
