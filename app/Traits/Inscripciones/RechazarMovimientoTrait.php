<?php

namespace App\Traits\Inscripciones;

use App\Models\MovimientoRegistral;
use App\Http\Services\SistemaTramitesService;

trait RechazarMovimientoTrait{

    public $motivos_rechazo;
    public $motivo_rechazo;
    public $observaciones;
    public $modal_rechazar = false;

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