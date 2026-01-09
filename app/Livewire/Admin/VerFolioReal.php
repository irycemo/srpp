<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\FolioReal;
use App\Models\MovimientoRegistral;

class VerFolioReal extends Component
{

    public FolioReal $folioReal;
    public $movimiento_registral;

    public function verMovimientoRegistral(MovimientoRegistral $movimiento_registral){

        $this->movimiento_registral = $movimiento_registral;

    }

    public function reaordenarMovimientos($id_1, $id_2){

        $movimiento_registral_1 = MovimientoRegistral::find($id_1);

        $folio_1 = $movimiento_registral_1->folio;

        $movimiento_registral_2 = MovimientoRegistral::find($id_2);

        $folio_2 = $movimiento_registral_2->folio;

        $movimiento_registral_1->update(['folio' => $folio_2, 'actualizado_por' => auth()->id()]);

        $movimiento_registral_1->audits()->latest()->first()->update(['tags' => 'Cambió el orden de folio']);

        $movimiento_registral_2->update(['folio' => $folio_1, 'actualizado_por' => auth()->id()]);

        $movimiento_registral_2->audits()->latest()->first()->update(['tags' => 'Cambió el orden de folio']);

        $this->folioReal->refresh();

    }

    public function agregarAclaracionAdministrativa(){

        try {

            $movimiento_registral = MovimientoRegistral::create([
                'folio_real' => $this->folioReal->id,

            ]);

        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    public function render()
    {
        return view('livewire.admin.ver-folio-real')->extends('layouts.admin');
    }
}
