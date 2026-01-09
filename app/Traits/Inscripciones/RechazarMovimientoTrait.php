<?php

namespace App\Traits\Inscripciones;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

trait RechazarMovimientoTrait{

    public $motivos_rechazo;
    public $motivo_rechazo;
    public $observaciones;
    public $modal_rechazar = false;

    public function abrirModalRechazar(MovimientoRegistral $modelo){

        $this->reset(['observaciones']);

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modal_rechazar = true;

    }

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $this->rechazarMovimiento($this->modelo_editar);

            });

            $pdf = Pdf::loadView('rechazos.rechazo', [
                'movimientoRegistral' => $this->modelo_editar,
                'motivo' => $this->motivo_rechazo,
                'observaciones' => $this->observaciones
            ])->output();

            $this->reset(['modal_rechazar', 'observaciones']);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

            return response()->streamDownload(
                fn () => print($pdf),
                'rechazo.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al rechazar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function rechazarMovimiento(MovimientoRegistral $movimientoRegistral){

        $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

        (new SistemaTramitesService())->rechazarTramite($movimientoRegistral->año, $movimientoRegistral->tramite, $movimientoRegistral->usuario, $this->motivo_rechazo . ' ' . $observaciones);

        $movimientoRegistral->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

        $movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Rechazó movimiento registral']);

    }

    public function seleccionarMotivo($key){

        $this->motivo_rechazo = $this->motivos_rechazo[$key];

    }

}