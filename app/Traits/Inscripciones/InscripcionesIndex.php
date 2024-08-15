<?php

namespace App\Traits\Inscripciones;

use App\Models\File;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

trait InscripcionesIndex{

    public $modalFinalizar = false;
    public $documento;

    public MovimientoRegistral $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        $movimientoAsignado = MovimientoRegistral::whereIn('estado', ['nuevo', 'captura'])
                                                        ->where('usuario_Asignado', auth()->id())
                                                        ->whereHas('folioReal', function($q){
                                                            $q->where('estado', 'activo');
                                                        })
                                                        ->orderBy('created_at')
                                                        ->first();

        if($movimientoAsignado->folio && $movimientoRegistral->id != $movimientoAsignado->id){

            $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $movimientoAsignado->folioReal->folio . '-' . $movimientoAsignado->folio . ' primero.']);

            return;

        }

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

        if($movimientoRegistral->propiedad){

            return redirect()->route('propiedad.inscripcion', $movimientoRegistral->inscripcionPropiedad);

        }

        if($movimientoRegistral->gravamen){

            return redirect()->route('gravamen.inscripcion', $movimientoRegistral->gravamen);

        }

        if($movimientoRegistral->vario){

            return redirect()->route('vario.inscripcion', $movimientoRegistral->vario);

        }

        if($movimientoRegistral->cancelacion){

            return redirect()->route('cancelacion.inscripcion', $movimientoRegistral->cancelacion);

        }

        if($movimientoRegistral->sentencia){

            return redirect()->route('sentencia.inscripcion', $movimientoRegistral->sentencia);

        }

    }


    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        $this->dispatch('imprimir_documento', ['caratula' => $movimientoRegistral->inscripcionPropiedad->id]);

    }

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        $this->reset('documento');

        $this->dispatch('removeFiles');

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function finalizar(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                $pdf = $this->documento->store('/', 'caratulas');

                File::create([
                    'fileable_id' => $this->modelo_editar->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'caratula',
                    'url' => $pdf
                ]);

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'concluido';

                $this->modelo_editar->save();

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

}
