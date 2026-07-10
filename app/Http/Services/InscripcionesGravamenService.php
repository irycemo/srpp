<?php

namespace App\Http\Services;

use App\Models\Gravamen;
use App\Models\MovimientoRegistral;
use App\Http\Services\MovimientoServiceInterface;
use App\Traits\Inscripciones\RevisarFolioMatrizTrait;

class InscripcionesGravamenService implements MovimientoServiceInterface{

    use RevisarFolioMatrizTrait;

    public function crear(array $request):void
    {

        $gravamen = Gravamen::create([
            'estado' => 'nuevo',
            'servicio' => $request['servicio'],
            'movimiento_registral_id' => $request['movimiento_registral_id'],
        ]);

        /* Revisar si son para folio matriz */
        $id = $this->revisarFolioMatriz($gravamen->movimientoRegistral);

        if($id){

            $gravamen->update(['movimiento_registral_id' => $id]);

            $gravamen->refresh();

        }

        /* Reestructura de credito */
        if($request['servicio'] == 'D153'){

            $movimiento_gravamen_a_reestructurar = MovimientoRegistral::find($request['asiento_registral']);

            $gravamen_a_reestructurar = $movimiento_gravamen_a_reestructurar->gravamen;

            $gravamen_a_reestructurar->update(['asociado_a' => $gravamen->id, 'estado' => 'reestructurado']);

        }

    }

    public function obtenerUsuarioAsignado(array $request):int | null
    {
        return (new AsignacionService())->obtenerUsuarioGravamen(isset($request['folio_real']), $request['distrito'], $request['estado']);
    }

    public function obtenerSupervisorAsignado(array $request):int
    {
        return (new AsignacionService())->obtenerSupervisorInscripciones($request['distrito']);
    }

    public function corregir(MovimientoRegistral $movimientoRegistral):void
    {}

    public function regresarMovimientoId(MovimientoRegistral $movimiento):MovimientoRegistral
    {

        $movimiento_hijo = MovimientoRegistral::where('movimiento_padre', $movimiento->id)->first();

        if($movimiento_hijo){

            return $movimiento_hijo;

        }else{

            return $movimiento;

        }

    }

}
