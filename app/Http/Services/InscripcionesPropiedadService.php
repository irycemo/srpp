<?php

namespace App\Http\Services;

use App\Models\Predio;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CertificacionServiceException;

class InscripcionesPropiedadService{

    public function store(array $request){

        try {

            $propiedad = Propiedad::create($this->requestCrear($request));

            if(in_array($propiedad->servicio, ['D114', 'D113', 'D116', 'D115'])){

                $this->revisarFolioMatriz($propiedad->movimientoRegistral);

            }

        } catch (\Throwable $th) {

            Log::error('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

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
            'movimiento_registral_id' => $request['movimiento_registral'],
        ];

    }

    public function revisarFolioMatriz(MovimientoRegistral $movimiento){

        if($movimiento->folioReal->matriz){

            $folioReal = FolioReal::create([
                'estado' => 'captura',
                'folio' => (FolioReal::max('folio') ?? 0) + 1,
                'antecedente' => $movimiento->folioReal->id,
                'distrito_antecedente' => $movimiento->getRawOriginal('distrito'),
                'seccion_antecedente' => $movimiento->seccion,
            ]);

            Predio::create(['folio_real' => $folioReal->id, 'status' => 'nuevo']);

            $nuevoMovimientoRegistral = $movimiento->replicate();
            $nuevoMovimientoRegistral->tomo = null;
            $nuevoMovimientoRegistral->registro = null;
            $nuevoMovimientoRegistral->numero_propiedad = null;
            $nuevoMovimientoRegistral->estado = 'nuevo';
            $nuevoMovimientoRegistral->folio_real = $folioReal->id;
            $nuevoMovimientoRegistral->folio = 1;
            $nuevoMovimientoRegistral->save();

            $nuevoPropiedad = $movimiento->inscripcionPropiedad->replicate();
            $nuevoPropiedad->movimiento_registral_id = $nuevoMovimientoRegistral->id;
            $nuevoPropiedad->save();

            $movimiento->update(['estado' => 'concluido']);

        }

    }

}
