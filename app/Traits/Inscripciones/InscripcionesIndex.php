<?php

namespace App\Traits\Inscripciones;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\CalcularDiaElaboracionTrait;
use App\Http\Services\SistemaTramitesService;
use App\Http\Controllers\Varios\VariosController;
use App\Traits\RevisarMovimientosPosterioresTrait;
use App\Http\Controllers\Reformas\ReformaController;
use App\Http\Controllers\Gravamen\GravamenController;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use App\Http\Controllers\Subdivisiones\SubdivisionesController;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;
use App\Http\Controllers\InscripcionesPropiedad\FideicomisoController;

trait InscripcionesIndex{

    use CalcularDiaElaboracionTrait;
    use RevisarMovimientosPosterioresTrait;

    public $modalFinalizar = false;
    public $modalRechazar = false;
    public $modalConcluir = false;
    public $modalReasignarUsuario = false;
    public $modalRecibirDocumentacion = false;
    public $documento;
    public $observaciones;
    public $motivos;
    public $motivo;
    public $usuarios;
    public $usuarios_regionales;
    public $usuarios_regionales_fliped;
    public $contraseña;

    public $años;
    public $filters = [
        'año' => '',
        'tramite' => '',
        'usuario' => '',
        'folio_real' => '',
        'folio' => '',
        'estado' => ''
    ];

    public MovimientoRegistral $modelo_editar;

    public $actual;

    protected function rules()
    {

        return ['modelo_editar.usuario_asignado' => 'required'];

    }

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function updatedFilters() { $this->resetPage(); }

    public function estaBloqueado(){

        $movimientos = $this->actual->folioReal->movimientosRegistrales()->whereIn('estado', ['nuevo', 'captura', 'correccion'])->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($this->actual->folioReal->avisoPreventivo()){

                $aviso = $this->actual->folioReal->avisoPreventivo();

                if($this->actual->inscripcionPropiedad &&
                    $aviso->movimientoRegistral->tipo_documento == $this->actual->tipo_documento &&
                    $aviso->movimientoRegistral->numero_documento == $this->actual->numero_documento &&
                    $aviso->movimientoRegistral->autoridad_cargo == $this->actual->autoridad_cargo &&
                    $aviso->movimientoRegistral->autoridad_nombre == $this->actual->autoridad_nombre &&
                    $aviso->movimientoRegistral->autoridad_numero == $this->actual->autoridad_numero &&
                    $aviso->movimientoRegistral->fecha_emision == $this->actual->fecha_emision &&
                    $aviso->movimientoRegistral->procedencia == $this->actual->procedencia
                ){

                    $this->ruta($this->actual);

                    return;

                }

                if($this->actual->vario?->acto_contenido == 'SEGUNDO AVISO PREVENTIVO'){

                    $this->ruta($this->modelo_editar);

                    return false;

                }

                if(in_array($this->actual->vario?->acto_contenido, ['CANCELACIÓN DE SEGUNDO AVISO PREVENTIVO', 'CANCELACIÓN DE PRIMER AVISO PREVENTIVO'])){

                    $this->ruta($this->modelo_editar);

                    return false;

                }

                $this->dispatch('mostrarMensaje', ['warning', "El folio real tiene un aviso preventivo vigente."]);

                return true;

            }elseif($this->actual->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: (" . $this->actual->folioReal->folio . '-' . $primerMovimiento->folio . ') debe elaborarse primero.']);

                return true;

            }else{

               return false;

            }

        }else{

            return false;

        }

    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        $this->modelo_editar = $movimientoRegistral;

        if($this->modelo_editar->folioReal->estado == 'centinela'){

            $this->dispatch('mostrarMensaje', ['warning', "El folio real esta en centinela."]);

            return;

        }

        if(auth()->user()->hasRole('Jefe de departamento inscripciones')){

            $this->actual = $this->modelo_editar;

            if($this->estaBloqueado()){

                return;

            }else{

                $this->ruta($this->modelo_editar);

                return;
            }

        }

        $aclaracion = $this->modelo_editar->folioReal->aclaracionAdministrativa();

        if($aclaracion){

            if($aclaracion->id == $this->modelo_editar->id){

                $this->ruta($this->modelo_editar);

                return;

            }else{

                $this->dispatch('mostrarMensaje', ['warning', "El folio real tiene aclaración administrativa vigente."]);

                return;

            }

        }

        $this->actual = $this->modelo_editar;

        if($this->estaBloqueado()){

            return;

        }else{

            $this->ruta($this->modelo_editar);

        }

        /* $movimientoAsignados = MovimientoRegistral::with('vario')
                                                        ->whereIn('estado', ['nuevo', 'captura', 'correccion'])
                                                        ->where('usuario_Asignado', auth()->id())
                                                        ->withWhereHas('folioReal', function($q){
                                                            $q->where('estado', 'activo');
                                                        })
                                                        ->orderBy('created_at')
                                                        ->get();

        foreach($movimientoAsignados as $movimiento){

            $this->actual = $movimiento;

            if($this->estaBloqueado()){

                //Esta bloqueado y es el que esta intentando hacer
                if($this->modelo_editar->id == $this->actual->id){

                    break;

                }else{

                    continue;

                }

            }else{

                //Si solo hay un movimiento por realizar y no esta bloqueado
                if($movimientoAsignados->count() == 1 && $this->actual->id == $this->modelo_editar->id){

                    $this->ruta($this->modelo_editar);

                }

                //Revisar si es el que debe hacer ($this->actual)
                if($this->modelo_editar->id != $this->actual->id){

                    $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $this->actual->folioReal->folio . '-' . $this->actual->folio . ' primero.']);

                    return;

                }else{

                    $this->ruta($this->modelo_editar);

                    break;

                }

            }

        } */

    }

    public function ruta($movimientoRegistral){

        if($movimientoRegistral->inscripcionPropiedad){

            if(in_array($movimientoRegistral->inscripcionPropiedad->servicio, ['D121', 'D120', 'D123', 'D122', 'D119', 'D124'])){

                return redirect()->route('propiedad.fraccionamiento', $movimientoRegistral->inscripcionPropiedad);

            }elseif(in_array($movimientoRegistral->inscripcionPropiedad->servicio, ['D127'])){

                return redirect()->route('propiedad.subdivision', $movimientoRegistral->inscripcionPropiedad);

            }else{

                return redirect()->route('propiedad.inscripcion', $movimientoRegistral->inscripcionPropiedad);

            }

        }

        if($movimientoRegistral->fideicomiso){

            return redirect()->route('propiedad.fideicomiso', $movimientoRegistral->fideicomiso);

        }

        if($movimientoRegistral->gravamen){

            return redirect()->route('gravamen.inscripcion', $movimientoRegistral->gravamen);

        }

        if($movimientoRegistral->vario){

            return redirect()->route('varios.inscripcion', $movimientoRegistral->vario);

        }

        if($movimientoRegistral->cancelacion){

            return redirect()->route('cancelacion.inscripcion', $movimientoRegistral->cancelacion);

        }

        if($movimientoRegistral->sentencia){

            return redirect()->route('sentencias.inscripcion', $movimientoRegistral->sentencia);

        }

        if($movimientoRegistral->reformaMoral){

            return redirect()->route('reformas.inscripcion', $movimientoRegistral->reformaMoral);

        }

    }

    public function imprimir(MovimientoRegistral $movimientoRegistral){

        if($movimientoRegistral->getRawOriginal('distrito') != 2 && !auth()->user()->hasRole(['Jefe de departamento inscripciones'])){

            if($this->calcularDiaElaboracion($movimientoRegistral)) return;

        }

        try {

            if($movimientoRegistral->inscripcionPropiedad){

                if(in_array($movimientoRegistral->inscripcionPropiedad->servicio, ['D127', 'D121', 'D120', 'D123', 'D122', 'D119', 'D124', 'D125', 'D126'])){

                    $pdf = (new SubdivisionesController())->reimprimir($movimientoRegistral->firmaElectronica);

                }else{

                    $pdf = (new PropiedadController())->reimprimir($movimientoRegistral->firmaElectronica);

                }

            }

            if($movimientoRegistral->fideicomiso){

                $pdf = (new FideicomisoController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->gravamen){

                $pdf = (new GravamenController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->vario){

                $pdf = (new VariosController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->cancelacion){

                $pdf = (new CancelacionController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->sentencia){

                $pdf = (new SentenciasController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->reformaMoral){

                $pdf = (new ReformaController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {
            Log::error("Error al reimiprimir caratula de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        if($modelo->getRawOriginal('distrito') != 2 && !auth()->user()->hasRole(['Jefe de departamento inscripciones'])){

            if($this->calcularDiaElaboracion($modelo)) return;

        }

        $this->reset('documento');

        $this->dispatch('removeFiles');

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function abrirModalRecibirDocumentacion(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalRecibirDocumentacion = true;

    }

    public function abrirModalRechazar(MovimientoRegistral $modelo){

        $this->reset(['observaciones', 'motivo']);

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalRechazar = true;

    }

    public function abrirModalConcluir(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalConcluir = true;

    }

    public function abrirModalReasignar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignarUsuario = true;

        if($this->modelo_editar->inscripcionPropiedad){

            $this->cargarUsuarios(['Propiedad', 'Registrador Propiedad']);

        }

        if($this->modelo_editar->gravamen){

            $this->cargarUsuarios(['Gravamen', 'Registrador Gravamen']);

        }

        if($this->modelo_editar->vario){

            $this->cargarUsuarios(['Varios', 'Registrador Varios', 'Aclaraciones administrativas', 'Avisos preventivos']);

        }

        if($this->modelo_editar->cancelacion){

            $this->cargarUsuarios(['Cancelación', 'Registrador cancelación']);

        }

        if($this->modelo_editar->sentencia){

            $this->cargarUsuarios(['Sentencias', 'Registrador sentencias']);

        }

        if($this->modelo_editar->reformaMoral){

            $this->cargarUsuarios(['Folio real moral']);

        }

    }

    public function recibirDocumentacion(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                if(auth()->user()->hasRole('Jefe de departamento inscripciones')){

                    $this->modelo_editar->usuario_asignado = auth()->id();

                }

                $this->modelo_editar->estado = 'nuevo';

                $this->modelo_editar->actualizado_por = auth()->id();

                $this->modelo_editar->save();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Recibió documentación']);

            });

            $this->modalRecibirDocumentacion = false;

            $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al recibir documentación de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function cargarUsuarios($roles){

        $this->usuarios = User::whereHas('roles', function($q) use($roles){
                                    $q->whereIn('name', $roles);
                                })
                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->orderBy('name')
                                ->get();

    }

    public function finalizar(){

        try {

            DB::transaction(function (){

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'finalizado';

                $this->modelo_editar->save();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Finalizó inscripción']);

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

            $this->modalFinalizar = false;

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function concluir(){

        try {

            DB::transaction(function () {

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'concluido';

                $this->modelo_editar->save();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Concluyó inscripción']);

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se concluyó con éxito."]);

            $this->modalConcluir = false;

        } catch (\Throwable $th) {

            Log::error("Error al concluir inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignarUsuario(){

        try {

            DB::transaction(function () {

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->save();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            Log::error("Error al reasignar movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignarUsuarioAleatoriamente(){

        $cantidad = $this->modelo_editar->audits()->where('tags', 'Reasignó usuario')->count();

        if($cantidad >= 2){

            $this->dispatch('mostrarMensaje', ['warning', "Ya se ha reasignado multiples veces."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->modelo_editar->usuario_asignado = $this->usuarios->random()->id;
                $this->modelo_editar->actualizado_por = auth()->id();
                $this->modelo_editar->save();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            });

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function () {

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, $this->motivo . ' ' . $observaciones);

                $this->modelo_editar->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Rechazó inscripción']);

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

            $this->modalRechazar = false;

            $pdf = Pdf::loadView('rechazos.rechazo', [
                'movimientoRegistral' => $this->modelo_editar,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones
            ])->output();

            return response()->streamDownload(
                fn () => print($pdf),
                'rechazo.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al rechazar inscripcion por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function seleccionarMotivo($key){

        $this->motivo = $this->motivos[$key];

    }

}