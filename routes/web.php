<?php

use App\Livewire\Admin\Roles;
use App\Livewire\Admin\Efirmas;
use App\Livewire\Admin\Ranchos;
use App\Livewire\Varios\Varios;
use App\Livewire\Admin\Permisos;
use App\Livewire\Admin\Usuarios;
use App\Livewire\Admin\Auditoria;
use App\Livewire\Admin\Centinela;
use App\Livewire\Admin\Distritos;
use App\Livewire\Admin\Tenencias;
use App\Livewire\Admin\Municipios;
use App\Livewire\Admin\Propiedades;
use App\Livewire\Consulta\Consulta;
use App\Livewire\Admin\FoliosReales;
use App\Livewire\Varios\VariosIndex;
use App\Livewire\PaseFolio\PaseFolio;
use Illuminate\Support\Facades\Route;
use App\Livewire\Sentencias\Sentencia;
use App\Livewire\PaseFolio\Elaboracion;
use App\Http\Controllers\ManualController;
use App\Livewire\Gravamenes\GravamenIndex;
use App\Livewire\Cancelaciones\Cancelacion;
use App\Livewire\Certificaciones\Consultas;
use App\Livewire\Sentencias\SentenciasIndex;
use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\MovimientosRegistrales;
use App\Http\Controllers\SetPasswordController;
use App\Livewire\Certificaciones\CopiasSimples;
use App\Livewire\Cancelaciones\CancelacionIndex;
use App\Livewire\Gravamenes\GravamenInscripcion;
use App\Http\Controllers\Varios\VariosController;
use App\Livewire\Certificaciones\CopiasCertificadas;
use App\Livewire\Inscripciones\ConsultarInscripcion;
use App\Http\Controllers\Gravamen\GravamenController;
use App\Livewire\Certificaciones\CertificadoGravamen;
use App\Livewire\Certificaciones\CertificadoPropiedad;
use App\Http\Controllers\PaseFolio\PaseFolioController;
use App\Livewire\Inscripciones\Propiedad\Subdivisiones;
use App\Livewire\Inscripciones\Propiedad\PropiedadIndex;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Http\Controllers\Certificaciones\CopiasController;
use App\Livewire\Certificaciones\ConsultasCertificaciones;
use App\Livewire\Certificaciones\CertificadoPropiedadIndex;
use App\Livewire\Inscripciones\Propiedad\SubdivisionesIndex;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use App\Livewire\Inscripciones\Propiedad\PropiedadInscripcion;
use App\Http\Controllers\Certificaciones\CertificadoGravamenController;
use App\Http\Controllers\Certificaciones\CertificadoPropiedadController;
use App\Livewire\PersonaMoral\PaseAFolio;

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

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    /* Administración */
    Route::get('roles', Roles::class)->middleware('permission:Lista de roles')->name('roles');

    Route::get('permisos', Permisos::class)->middleware('permission:Lista de permisos')->name('permisos');

    Route::get('usuarios', Usuarios::class)->middleware('permission:Lista de usuarios')->name('usuarios');

    Route::get('e_firmas', Efirmas::class)->middleware('permission:Lista de efirmas')->name('e_firmas');

    Route::get('folios_reales', FoliosReales::class)->middleware('permission:Lista de folios reales')->name('folios_reales');

    Route::get('movimientos_registrales', MovimientosRegistrales::class)->middleware('permission:Lista de movimientos registrales')->name('movimientos_registrales');

    Route::get('centinela', Centinela::class)->middleware('permission:Centinela')->name('centinela');

    Route::get('propiedades', Propiedades::class)->middleware('permission:Lista de propiedades')->name('propiedades');

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
    Route::get('certificado_propiedad', CertificadoPropiedadIndex::class)->middleware('permission:Certificado propiedad')->name('certificados_propiedad');
    Route::get('certificado_propiedad/{certificacion}', CertificadoPropiedad::class)->middleware('permission:Certificado propiedad')->name('certificado_propiedad');
    Route::get('certificado_negativo_propiedad_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoNegativoPropiedad'])->middleware('permission:Certificado propiedad')->name('certificado_negativo_propiedad_pdf');
    Route::get('certificado_propiedad_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoPropiedad'])->middleware('permission:Certificado propiedad')->name('certificado_propiedad_pdf');
    Route::get('certificado_unico_propiedad_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoUnicoPropiedad'])->middleware('permission:Certificado propiedad')->name('certificado_unico_propiedad_pdf');
    Route::get('certificado_propiedad_colindancias_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoPropiedadColindancias'])->middleware('permission:Certificado propiedad')->name('certificado_propiedad_colindancias_pdf');
    Route::get('certificado_negativo_pdf/{movimientoRegistral}', [CertificadoPropiedadController::class, 'certificadoNegativo'])->middleware('permission:Certificado propiedad')->name('certificado_negativo_pdf');

    /* Inscripciones */

    /* Consulta */
    Route::get('consulta_inscripcion', ConsultarInscripcion::class)->middleware('permission:Consultas inscripción')->name('consulta_inscripcion');

    /* Propiedad */
    Route::get('propiedad', PropiedadIndex::class)->middleware('permission:Propiedad')->name('propiedad');
    Route::get('subdivisiones', SubdivisionesIndex::class)->middleware('permission:Subdivisiones')->name('propiedad.subdivisiones_index');
    Route::get('subdivision/{propiedad}', Subdivisiones::class)->middleware('permission:Subdivisiones')->name('propiedad.subdivision');
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

    /* Personas morales */
    Route::get('personas_morales', PaseAFolio::class)->middleware('permission:Personas morales')->name('personas_morales');

    /* Consultas */
    Route::get('consultas', Consulta::class)->middleware('permission:Consultas')->name('consultas');

    Route::get('manual', ManualController::class)->name('manual');

});

Route::get('setpassword/{email}', [SetPasswordController::class, 'create'])->name('setpassword');
Route::post('setpassword', [SetPasswordController::class, 'store'])->name('setpassword.store');

