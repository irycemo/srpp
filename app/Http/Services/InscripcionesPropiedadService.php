<?php

namespace App\Http\Services;

use App\Models\Propiedad;
use App\Models\MovimientoRegistral;
use App\Exceptions\InscripcionesServiceException;
use App\Http\Services\MovimientoServiceInterface;
use App\Traits\Inscripciones\RecuperarPredioTrait;
use App\Traits\Inscripciones\RevisarFolioMatrizTrait;

class InscripcionesPropiedadService implements MovimientoServiceInterface{

    use RecuperarPredioTrait;
    use RevisarFolioMatrizTrait;

    public function crear(array $request):void
    {

        $propiedad = Propiedad::create($this->requestCrear($request));

        /* Revisar si son para folio matriz */
        if(in_array($propiedad->servicio, ['D114', 'D113', 'D116', 'D115'])){

            $this->revisarFolioMatriz($propiedad->movimientoRegistral);

        }

        if($request['servicio_nombre'] == 'Captura especial de folio real'){

            $usuario = (new AsignacionService())->obtenerUsuarioPaseAFolio($propiedad->movimientoRegistral->getRawOriginal('distrito'));

            $propiedad->movimientoRegistral->update(['usuario_asignado' => $usuario]);

            $propiedad->update([
                'acto_contenido' => 'CAPTURA ESPECIAL DE FOLIO REAL',
                'descripcion_acto' => 'ESTE MOVIMIENTO REGISTRAL CREA EL FOLIO REAL POR CAPTURA ESPECIAL.'
            ]);

        }

    }

    public function obtenerUsuarioAsignado(array $request):int
    {
        return (new AsignacionService())->obtenerUsuarioPropiedad(isset($request['folio_real']), $request['distrito'], $request['estado']);
    }

    public function obtenerSupervisorAsignado(array $request):int
    {
        return (new AsignacionService())->obtenerSupervisorInscripciones($request['distrito']);
    }

    public function corregir(MovimientoRegistral $movimiento):void
    {

        $this->validaciones($movimiento);

        $this->obtenerMovimientoConFirmaElectronica($movimiento);

        $movimiento->update([
            'estado' => 'correccion',
            'actualizado_por' => auth()->id()
        ]);

        foreach ($movimiento->inscripcionPropiedad->actores as $actor) {

            $actor->delete();

        }

        $movimiento->audits()->latest()->first()->update(['tags' => 'Cambio estado a corrección']);

    }

    public function requestCrear(array $request):array
    {

        $array = [];

        $fields = [
            'valor_propiedad',
            'numero_inmuebles',
        ];

        foreach($fields as $field){

            if(array_key_exists($field, $request)){

                $array[$field] = $request[$field];

            }

        }

        return $array +  [
            'servicio' => $request['servicio'],
            'movimiento_registral_id' => $request['movimiento_registral_id'],
        ];

    }

    public function validaciones($movimientoRegistral){

        $movimiento = $movimientoRegistral->folioReal
                                            ->movimientosRegistrales()
                                            ->where('folio', ($movimientoRegistral->folio + 1))
                                            ->whereNotIn('estado', ['nuevo', 'correccion', 'pase_folio', 'no recibido', 'recahzado'])
                                            ->first();

        if($movimiento) throw new InscripcionesServiceException("El folio real tiene movimientos registrales posteriores elaborados.");

        $movimiento = MovimientoRegistral::where('movimiento_padre', $movimientoRegistral->id)->first();

        if($movimiento) throw new InscripcionesServiceException("Este movimiento generó un folio real nuevo.");

    }

}
