<?php

use App\Livewire\Admin\Roles;
use App\Livewire\Admin\Efirmas;
use App\Livewire\Admin\Permisos;
use App\Livewire\Admin\Personas;
use App\Livewire\Admin\Usuarios;
use App\Livewire\Admin\Auditoria;
use App\Livewire\Admin\Centinela;
use App\Livewire\Admin\Regionales;
use App\Livewire\Admin\Propiedades;
use App\Livewire\Admin\FoliosReales;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\FoliosRealesPM;
use App\Livewire\Admin\MovimientosRegistrales;
use App\Livewire\Admin\VerFolioReal;

Route::group(['middleware' => ['auth', 'esta.activo']], function(){

    Route::get('roles', Roles::class)->middleware('permission:Lista de roles')->name('roles');

    Route::get('permisos', Permisos::class)->middleware('permission:Lista de permisos')->name('permisos');

    Route::get('usuarios', Usuarios::class)->middleware('permission:Lista de usuarios')->name('usuarios');

    Route::get('e_firmas', Efirmas::class)->middleware('permission:Lista de efirmas')->name('e_firmas');

    Route::get('folios_reales', FoliosReales::class)->middleware('permission:Lista de folios reales')->name('folios_reales');

    Route::get('ver_folio_real/{folioReal}', VerFolioReal::class)->middleware('permission:Lista de folios reales')->name('ver_folio_real');

    Route::get('folios_reales_pm', FoliosRealesPM::class)->middleware('permission:Lista de folios reales')->name('folios_reales_pm');

    Route::get('movimientos_registrales', MovimientosRegistrales::class)->middleware('permission:Lista de movimientos registrales')->name('movimientos_registrales');

    Route::get('centinela', Centinela::class)->middleware('permission:Centinela')->name('centinela');

    Route::get('propiedades', Propiedades::class)->middleware('permission:Lista de propiedades')->name('propiedades');

    Route::get('personas', Personas::class)->middleware('permission:Lista de personas')->name('personas');

    Route::get('regionales', Regionales::class)->middleware('permission:Lista de regionales')->name('regionales');

    Route::get('auditoria', Auditoria::class)->middleware('permission:Auditoria')->name('auditoria');

});
