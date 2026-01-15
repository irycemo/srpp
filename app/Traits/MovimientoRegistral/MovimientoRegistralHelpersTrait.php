<?php

namespace App\Traits\MovimientoRegistral;

use App\Models\MovimientoRegistral;

trait MovimientoRegistralHelpersTrait{

    public function requestTieneFolioReal(array $request):bool
    {
        return isset($request['folio_real']);
    }

    public function requestTieneAntecedente(array $request):bool
    {
        return isset($request['tomo']) && isset($request['registro']) && isset($request['numero_propiedad']);
    }

    public function antecedenteCambio(array $array, MovimientoRegistral $movimientoRegistral){

        return $movimientoRegistral->tomo != $array['tomo'] ||
                $movimientoRegistral->registro != $array['registro'] ||
                $movimientoRegistral->numero_propiedad != $array['numero_propiedad'];

    }

    public function requestTieneFolioRealPersonaMoral(array $request):bool
    {
        return isset($request['folio_real_persona_moral']);
    }

    public function requestTieneDocumentoEntrada(array $request):bool
    {

        return isset($request['tipo_documento'])
                && isset($request['autoridad_cargo'])
                && isset($request['autoridad_nombre'])
                && isset($request['fecha_emision'])
                && isset($request['numero_documento']);

    }

}
