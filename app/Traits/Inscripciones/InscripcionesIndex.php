<?php

namespace App\Traits\Inscripciones;

use App\Models\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

trait InscripcionesIndex{

    public $modalFinalizar = false;
    public $modalRechazar = false;
    public $modalConcluir = false;
    public $documento;
    public $observaciones;
    public $motivos;
    public $motivo;

    public MovimientoRegistral $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        /* $movimientoAsignado = MovimientoRegistral::whereIn('estado', ['nuevo', 'captura'])
                                                        ->where('usuario_Asignado', auth()->id())
                                                        ->whereHas('folioReal', function($q){
                                                            $q->where('estado', 'activo');
                                                        })
                                                        ->orderBy('created_at')
                                                        ->first();

        if($movimientoAsignado->folio && $movimientoRegistral->id != $movimientoAsignado->id){

            $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $movimientoAsignado->folioReal->folio . '-' . $movimientoAsignado->folio . ' primero.']);

            return;

        } */

        if($movimientoRegistral->folioReal->avisoPreventivo()){

            $this->dispatch('mostrarMensaje', ['warning', "El folio real tiene un aviso preventivo vigente."]);

            return;

        }

        $movimientos = $movimientoRegistral->folioReal->movimientosRegistrales()->whereIn('estado', ['nuevo', 'elaborado'])->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($movimientoRegistral->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: (" . $movimientoRegistral->folioReal->folio . '-' . $primerMovimiento->folio . ') debe elaborarce primero.']);

            }else{

                $this->ruta($movimientoRegistral);

            }

        }else{

            $this->ruta($movimientoRegistral);

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

        if($movimientoRegistral->inscripcionPropiedad){

            $this->dispatch('imprimir_documento', ['caratula' => $movimientoRegistral->inscripcionPropiedad->id]);

        }

        if($movimientoRegistral->gravamen){

            $this->dispatch('imprimir_documento', ['caratula' => $movimientoRegistral->gravamen->id]);

        }

        if($movimientoRegistral->vario){

            $this->dispatch('imprimir_documento', ['caratula' => $movimientoRegistral->vario->id]);

        }

        if($movimientoRegistral->cancelacion){

            $this->dispatch('imprimir_documento', ['caratula' => $movimientoRegistral->cancelacion->id]);

        }

        if($movimientoRegistral->sentencia){

            $this->dispatch('imprimir_documento', ['caratula' => $movimientoRegistral->sentencia->id]);

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

    public function finalizar(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                if(env('LOCAL') == "0"){

                    $pdf = $this->documento->store('srpp/caratulas', 's3');

                    File::create([
                        'fileable_id' => $this->modelo_editar->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'caratula',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "1"){

                    $pdf = $this->documento->store('/', 'caratulas');

                    File::create([
                        'fileable_id' => $this->modelo_editar->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'caratula_s3',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "2"){

                    $pdf = $this->documento->store('srpp/caratulas', 's3');

                    File::create([
                        'fileable_id' => $this->modelo_editar->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'caratula_s3',
                        'url' => $pdf
                    ]);

                    $pdf = $this->documento->store('/', 'caratulas');

                    File::create([
                        'fileable_id' => $this->modelo_editar->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'caratula',
                        'url' => $pdf
                    ]);

                }

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

            Log::error("Error al rechazar certificado de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function seleccionarMotivo($key){

        $this->motivo = $this->motivos[$key];

    }

    public function calcularDiaElaboracion($modelo){

        $diaElaboracion = $modelo->fecha_pago;

        for ($i=0; $i < 2; $i++) {

            $diaElaboracion->addDays(1);

            while($diaElaboracion->isWeekend()){

                $diaElaboracion->addDay();

            }

        }

        return $diaElaboracion;

    }

}
