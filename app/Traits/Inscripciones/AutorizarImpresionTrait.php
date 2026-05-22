<?php

namespace App\Traits\Inscripciones;

use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait AutorizarImpresionTrait{

    public function autorizarImpresionAnticipada(MovimientoRegistral $movimientoRegistral){

        try {

            DB::transaction(function () use($movimientoRegistral){

                $movimientoRegistral->update([
                    'estado' => 'autorizado',
                    'actualizado_por' => auth()->id()
                ]);

                $movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Autorizó impresión anticipada']);

            });

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);

            Log::error("Error al autorizar impresión anticipada movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

}
