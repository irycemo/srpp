<?php

use App\Http\Controllers\Certificaciones\CertificadoGravamenController;
use App\Livewire\Admin\Roles;
use App\Livewire\Admin\Ranchos;
use App\Livewire\Admin\Permisos;
use App\Livewire\Admin\Usuarios;
use App\Livewire\Admin\Auditoria;
use App\Livewire\Admin\Distritos;
use App\Livewire\Admin\Tenencias;
use App\Livewire\Admin\Municipios;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManualController;
use App\Livewire\Certificaciones\Consultas;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SetPasswordController;
use App\Livewire\Certificaciones\CopiasSimples;
use App\Livewire\Certificaciones\CopiasCertificadas;
use App\Http\Controllers\Certificaciones\CopiasController;
use App\Http\Controllers\InscripcionesPropiedad\TraslativoController;
use App\Http\Controllers\PaseFolio\PaseFolioController;
use App\Livewire\Certificaciones\CertificadoGravamen;
use App\Livewire\Certificaciones\ConsultasCertificaciones;
use App\Livewire\Consulta\Consulta;
use App\Livewire\Gravamenes\GravamenIndex;
use App\Livewire\Gravamenes\GravamenInscripcion;
use App\Livewire\Inscripciones\Propiedad\PropiedadIndex;
use App\Livewire\Inscripciones\Propiedad\PropiedadInscripcion;
use App\Livewire\PaseFolio\Elaboracion;
use App\Livewire\PaseFolio\PaseFolio;

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

    /* Administración */
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('roles', Roles::class)->middleware('permission:Lista de roles')->name('roles');

    Route::get('permisos', Permisos::class)->middleware('permission:Lista de permisos')->name('permisos');

    Route::get('usuarios', Usuarios::class)->middleware('permission:Lista de usuarios')->name('usuarios');

    Route::get('distritos', Distritos::class)->middleware('permission:Lista de distritos')->name('distritos');

    Route::get('municipios', Municipios::class)->middleware('permission:Lista de municipios')->name('municipios');

    Route::get('tenencias', Tenencias::class)->middleware('permission:Lista de tenencias')->name('tenencias');

    Route::get('ranchos', Ranchos::class)->middleware('permission:Lista de ranchos')->name('ranchos');

    Route::get('auditoria', Auditoria::class)->middleware('permission:Auditoria')->name('auditoria');

    /* Pase a folio */
    Route::get('pase_folio', PaseFolio::class)->middleware('permission:Pase a folio')->name('pase_folio');
    Route::get('pase_folio/{folioReal}', [PaseFolioController::class, 'caratula'])->name('pase_folio_caratula');

    Route::get('elaboracion_folio/{movimientoRegistral}', Elaboracion::class)->middleware('permission:Pase a folio')->name('elaboracion_folio');

    /* Certificaciones */
    Route::get('copias_simples', CopiasSimples::class)->middleware('permission:Copias Simples')->name('copias_simples');
    Route::get('copia_simple/{certificacion}', [CopiasController::class, 'copiaSimple'])->name('copia_simple');

    Route::get('copias_certificadas', CopiasCertificadas::class)->middleware('permission:Copias Certificadas')->name('copias_certificadas');
    Route::get('copia_certificada/{certificacion}', [CopiasController::class, 'copiaCertificada'])->name('copia_certificada');

    Route::get('indices_y_tomos', Consultas::class)->middleware('permission:Indices y tomos')->name('indices_y_tomos');

    Route::get('consultas_certificaciones', ConsultasCertificaciones::class)->middleware('permission:Consultas certificaciones')->name('consulta_certificaciones');

    Route::get('certificado_gravamen', CertificadoGravamen::class)->middleware('permission:Certificado gravamen')->name('certificado_gravamen');
    Route::get('acto/{movimientoRegistral}', [CertificadoGravamenController::class, 'certificadoGravamen'])->middleware('permission:Certificado gravamen')->name('certificado_gravamen_pdf');

    /* Inscripciones */
    Route::get('propiedad', PropiedadIndex::class)->middleware('permission:Propiedad')->name('propiedad');
    Route::get('propiedad/{propiedad}', PropiedadInscripcion::class)->middleware('permission:Propiedad inscripción')->name('propiedad.inscripcion');
    Route::get('boleta_presentacion/{propiedad}', [TraslativoController::class, 'boleta_presentacion'])->middleware('permission:Propiedad inscripción')->name('propiedad.inscripcion.boleta_presentacion');
    Route::get('acto/{propiedad}', [TraslativoController::class, 'acto'])->middleware('permission:Propiedad inscripción')->name('propiedad.inscripcion.acto');

    /* Gravamen */
    Route::get('gravamen', GravamenIndex::class)->middleware('permission:Gravamen')->name('gravamen');
    Route::get('gravamen/{gravamen}', GravamenInscripcion::class)->middleware('permission:Gravamen inscripción')->name('gravamen.inscripcion');

    /* Consultas */
    Route::get('consultas', Consulta::class)->middleware('permission:Consultas')->name('consultas');

    Route::get('manual', ManualController::class)->name('manual');

});

Route::get('setpassword/{email}', [SetPasswordController::class, 'create'])->name('setpassword');
Route::post('setpassword', [SetPasswordController::class, 'store'])->name('setpassword.store');

/* Route::get('validacion/{id}', [ValidacionController::class, 'validar'])->name('validar.documento'); */
