<?php

namespace App\Traits\Inscripciones;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;
use App\Http\Controllers\Varios\VariosController;
use App\Http\Controllers\Gravamen\GravamenController;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;

trait InscripcionesIndex{

    public $modalFinalizar = false;
    public $modalRechazar = false;
    public $modalConcluir = false;
    public $modalReasignar = false;
    public $documento;
    public $observaciones;
    public $motivos;
    public $motivo;
    public $usuarios;

    public MovimientoRegistral $modelo_editar;

    public $actual;

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function estaBloqueado(){

        $movimientos = $this->actual->folioReal->movimientosRegistrales()->whereIn('estado', ['nuevo', 'captura', 'correccion'])->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($this->actual->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: (" . $this->actual->folioReal->folio . '-' . $primerMovimiento->folio . ') debe elaborarce primero.']);

                return true;

            }else{

               return false;

            }

        }else{

            return false;

        }

    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        if(auth()->user()->hasRole('Jefe de departamento inscripciones')){

            $this->actual = $movimientoRegistral;

            if($this->estaBloqueado()){

                return;

            }else{

                $this->ruta($movimientoRegistral);

                return;
            }

        }

        $this->modelo_editar = $movimientoRegistral;

        $aclaracion = $this->modelo_editar->folioReal->aclaracionAdministrativa();

        if($aclaracion){

            if($aclaracion->id == $movimientoRegistral->id){

                $this->ruta($this->modelo_editar);

                return;

            }else{

                $this->dispatch('mostrarMensaje', ['warning', "El folio real tiene aclaración administrativa vigente."]);

                return;

            }

        }

        if($this->modelo_editar->folioReal->avisoPreventivo()){

            $aviso = $this->modelo_editar->folioReal->avisoPreventivo();

            if(!(
                $aviso->movimientoRegistral->tipo_documento == $this->modelo_editar->tipo_documento &&
                $aviso->movimientoRegistral->numero_documento == $this->modelo_editar->numero_documento &&
                $aviso->movimientoRegistral->autoridad_cargo == $this->modelo_editar->autoridad_cargo &&
                $aviso->movimientoRegistral->autoridad_nombre == $this->modelo_editar->autoridad_nombre &&
                $aviso->movimientoRegistral->autoridad_numero == $this->modelo_editar->autoridad_numero &&
                $aviso->movimientoRegistral->fecha_emision == $this->modelo_editar->fecha_emision &&
                $aviso->movimientoRegistral->procedencia == $this->modelo_editar->procedencia)
            ){

                $this->dispatch('mostrarMensaje', ['warning', "El folio real tiene un aviso preventivo vigente."]);

                return;

            }

        }

        if(auth()->user()->hasRole('Jefe de departamento')){

            $this->actual = $this->modelo_editar;

            if($this->estaBloqueado()){

                return;

            }else{

                $this->ruta($this->modelo_editar);

                return;
            }

        }

        $movimientoAsignados = MovimientoRegistral::whereIn('estado', ['nuevo', 'captura', 'correccion'])
                                                        ->where('usuario_Asignado', auth()->id())
                                                        ->withWhereHas('folioReal', function($q){
                                                            $q->where('estado', 'activo');
                                                        })
                                                        ->orderBy('created_at')
                                                        ->get();

        foreach($movimientoAsignados as $movimiento){

            $this->actual = $movimiento;

            if($this->estaBloqueado()){

                /* Esta bloqueado y es el que esta intentando hacer */
                if($this->modelo_editar->id == $this->actual->id){

                    break;

                }else{

                    continue;

                }

            }else{

                /* Si solo hay un movimiento por realizar y no esta bloqueado */
                if($movimientoAsignados->count() == 1 && $this->actual->id == $this->modelo_editar->id){

                    $this->ruta($this->modelo_editar);

                }

                /* Revisar si es el que debe hacer ($this->actual) */
                if($this->modelo_editar->id != $this->actual->id){

                    $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $this->actual->folioReal->folio . '-' . $this->actual->folio . ' primero.']);

                    return;

                }else{

                    $this->ruta($this->modelo_editar);

                    break;

                }

            }

        }

    }

    public function ruta($movimientoRegistral){

        if($movimientoRegistral->inscripcionPropiedad){

            return redirect()->route('propiedad.inscripcion', $movimientoRegistral->inscripcionPropiedad);

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

    }

    public function imprimir(MovimientoRegistral $movimientoRegistral){

        if($movimientoRegistral->getRawOriginal('distrito') != 2){

            if($movimientoRegistral->tipo_servicio == 'ordinario'){

                if(!($this->calcularDiaElaboracion($movimientoRegistral) <= now())){

                    $this->dispatch('mostrarMensaje', ['warning', "El trámite puede finalizarse apartir del " . $this->calcularDiaElaboracion($movimientoRegistral)->format('d-m-Y')]);

                    return;

                }

            }

        }

        try {

            if($movimientoRegistral->inscripcionPropiedad){

                $pdf = (new PropiedadController())->reimprimir($movimientoRegistral->firmaElectronica);

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

                $this->dispatch('imprimir_documento', ['caratula' => $movimientoRegistral->sentencia->id]);

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

        if($modelo->getRawOriginal('distrito') != 2){

            if($modelo->tipo_servicio == 'ordinario'){

                if(!($this->calcularDiaElaboracion($modelo) <= now())){

                    $this->dispatch('mostrarMensaje', ['error', "El trámite puede finalizarse apartir del " . $this->calcularDiaElaboracion($modelo)->format('d-m-Y')]);

                    return;

                }

            }

        }

        $this->reset('documento');

        $this->dispatch('removeFiles');

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

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

        $this->modalReasignar = true;

    }

    public function finalizar(){

        try {

            DB::transaction(function (){

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'finalizado';

                $this->modelo_editar->save();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function concluir(){

        try {

            $this->modelo_editar->actualizado_por = auth()->user()->id;

            $this->modelo_editar->estado = 'concluido';

            $this->modelo_editar->save();

            (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

            $this->dispatch('mostrarMensaje', ['success', "El trámite se concluyó con éxito."]);

            $this->modalConcluir = false;

        } catch (\Throwable $th) {

            Log::error("Error al concluir inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignar(){

        $cantidad = $this->modelo_editar->audits()->where('tags', 'Reasignó usuario')->count();

        if($cantidad >= 2){

            $this->dispatch('mostrarMensaje', ['warning', "Ya se ha reasignado multiples veces."]);

            return;

        }

        try {

            $this->modelo_editar->usuario_asignado = $this->usuarios->where('id', '!=', $this->modelo_editar->usuario_asignado)->random()->id;

            $this->modelo_editar->actualizado_por = auth()->user()->id;

            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reasignó con éxito."]);

            $this->modalReasignar = false;

        } catch (\Throwable $th) {

            Log::error("Error al reasignar movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

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

    public function calcularDiaElaboracion($modelo){

        $diaElaboracion = $modelo->fecha_pago;

        for ($i=0; $i < 3; $i++) {

            $diaElaboracion->addDays(1);

            while($diaElaboracion->isWeekend()){

                $diaElaboracion->addDay();

            }

        }

        return $diaElaboracion;

    }

}
