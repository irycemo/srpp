<?php

namespace App\Http\Services;

use App\Models\Fideicomiso;
use App\Models\MovimientoRegistral;
use App\Exceptions\InscripcionesServiceException;
use App\Http\Services\MovimientoServiceInterface;
use App\Traits\Inscripciones\RecuperarPredioTrait;

class FideicomisoService implements MovimientoServiceInterface{

    use RecuperarPredioTrait;

    public function crear(array $request):void
    {

        Fideicomiso::create([
            'estado' => 'nuevo',
            'movimiento_registral_id' => $request['movimiento_registral_id'],
        ]);

    }

    public function obtenerUsuarioAsignado(array $request):int | null
    {
        return null;
    }

    public function obtenerSupervisorAsignado(array $request):int
    {
        return 0;
    }

    public function corregir(MovimientoRegistral $movimiento):void
    {

        $this->validaciones($movimiento);

        $this->obtenerMovimientoConFirmaElectronica($movimiento);

        $movimiento->update([
            'estado' => 'correccion',
            'actualizado_por' => auth()->id()
        ]);

        $movimiento->fideicomiso->update(['estado' => 'nuevo']);

        foreach ($movimiento->fideicomiso->actores as $actor) {

            $actor->delete();

        }

        $movimiento->audits()->latest()->first()->update(['tags' => 'Cambio estado a corrección']);

    }

    public function validaciones($movimientoRegistral){

        $movimiento = $movimientoRegistral->folioReal
                                            ->movimientosRegistrales()
                                            ->where('folio', ($movimientoRegistral->folio + 1))
                                            ->whereNotIn('estado', ['nuevo', 'correccion', 'pase_folio', 'no recibido'])
                                            ->first();

        if($movimiento) throw new InscripcionesServiceException("El folio real tiene movimientos registrales posteriores elaborados.");

        $movimiento = MovimientoRegistral::where('movimiento_padre', $movimientoRegistral->id)->first();

        if($movimiento) throw new InscripcionesServiceException("Este movimiento generó un folio real nuevo.");

    }

}
