<?php

namespace App\Traits\Inscripciones;

use App\Models\Predio;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\MovimientoRegistral;


trait RevisarFolioMatrizTrait
{

    public function revisarFolioMatriz(MovimientoRegistral $movimiento):int|null
    {

        if($movimiento->folioReal?->matriz){

            $folioReal = FolioReal::create([
                'estado' => 'captura',
                'folio' => (FolioReal::max('folio') ?? 0) + 1,
                'antecedente' => $movimiento->folioReal->id,
                'distrito_antecedente' => $movimiento->getRawOriginal('distrito'),
                'seccion_antecedente' => $movimiento->seccion,
                'autoridad_cargo' => $movimiento->autoridad_cargo,
                'autoridad_nombre' => $movimiento->autoridad_nombre,
                'autoridad_numero' => $movimiento->autoridad_numero,
                'numero_documento' => $movimiento->numero_documento,
                'fecha_emision' => $movimiento->fecha_emision,
                'fecha_inscripcion' => $movimiento->fecha_inscripcion,
                'procedencia' => $movimiento->procedencia,
                'tipo_documento' => $movimiento->tipo_documento,
            ]);

            Predio::create(['folio_real' => $folioReal->id, 'status' => 'nuevo']);

            $nuevoMovimientoRegistral = $movimiento->replicate();
            $nuevoMovimientoRegistral->tomo = null;
            $nuevoMovimientoRegistral->registro = null;
            $nuevoMovimientoRegistral->numero_propiedad = null;
            $nuevoMovimientoRegistral->estado = 'nuevo';
            $nuevoMovimientoRegistral->servicio_nombre = $movimiento->servicio_nombre;
            $nuevoMovimientoRegistral->folio_real = $folioReal->id;
            $nuevoMovimientoRegistral->folio = 1;
            $nuevoMovimientoRegistral->pase_a_folio = true;
            $nuevoMovimientoRegistral->movimiento_padre = $movimiento->id;
            $nuevoMovimientoRegistral->save();

            Propiedad::create([
                'servicio' => 'D114',
                'acto_contenido' => 'CREA NUEVO FOLIO',
                'descripcion_acto' => 'ESTE MOVIMIENTO REGISTRAL CREA EL FOLIO REAL: ' . $folioReal->folio . '.',
                'movimiento_registral_id' => $movimiento->id
            ]);

            $movimiento->update([
                'servicio_nombre' => 'Genera nuevo folio real',
                'pase_a_folio' => false,
                'estado' => 'concluido',
            ]);

            return $nuevoMovimientoRegistral->id;

        }

        return null;

    }

}