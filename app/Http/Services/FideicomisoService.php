<?php

namespace App\Http\Services;

use App\Models\Fideicomiso;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Exceptions\InscripcionesServiceException;
use App\Traits\Inscripciones\RecuperarPredioTrait;

class FideicomisoService{

    use RecuperarPredioTrait;

    public function store(array $request)
    {

        try {

            Fideicomiso::create([
                'estado' => 'nuevo',
                'movimiento_registral_id' => $request['movimiento_registral'],
            ]);

        } catch (\Throwable $th) {

            Log::error('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new InscripcionesServiceException('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

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

    public function corregir(MovimientoRegistral $movimiento){

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

}
