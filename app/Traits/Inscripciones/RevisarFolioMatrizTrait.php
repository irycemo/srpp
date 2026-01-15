<?php

namespace App\Traits\Inscripciones;

use App\Models\Predio;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\MovimientoRegistral;


trait RevisarFolioMatrizTrait
{

    public function revisarFolioMatriz(MovimientoRegistral $movimiento)
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
            $nuevoMovimientoRegistral->estado = 'concluido';
            $nuevoMovimientoRegistral->servicio_nombre = 'Genera nuevo folio real';
            $nuevoMovimientoRegistral->folio = $movimiento->folioReal->ultimoFolio();
            $nuevoMovimientoRegistral->save();

            Propiedad::create([
                'servicio' => 'D114',
                'acto_contenido' => 'CREA NUEVO FOLIO',
                'descripcion_acto' => 'ESTE MOVIMIENTO REGISTRAL CREA EL FOLIO REAL: ' . $folioReal->folio . '.',
                'movimiento_registral_id' => $nuevoMovimientoRegistral->id
            ]);

            $movimiento->update([
                'folio_real' => $folioReal->id,
                'folio' => 1,
                'pase_a_folio' => true,
                'movimiento_padre' => $nuevoMovimientoRegistral->id
            ]);

        }

    }

}