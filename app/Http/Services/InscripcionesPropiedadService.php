<?php

namespace App\Http\Services;

use App\Models\Predio;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Exceptions\InscripcionesServiceException;
use App\Traits\Inscripciones\RecuperarPropietariosTrait;

class InscripcionesPropiedadService{

    use RecuperarPropietariosTrait;

    public function store(array $request)
    {

        try {

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

        } catch (\Throwable $th) {

            Log::error('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new InscripcionesServiceException('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

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
            $nuevoMovimientoRegistral->estado = 'nuevo';
            $nuevoMovimientoRegistral->folio_real = $folioReal->id;
            $nuevoMovimientoRegistral->folio = 1;
            $nuevoMovimientoRegistral->save();

            $nuevoPropiedad = $movimiento->inscripcionPropiedad->replicate();
            $nuevoPropiedad->movimiento_registral_id = $nuevoMovimientoRegistral->id;
            $nuevoPropiedad->save();

            $movimiento->update(['estado' => 'concluido']);

            $movimiento->inscripcionPropiedad->update([
                'acto_contenido' => 'CREA NUEVO FOLIO',
                'descripcion_acto' => 'ESTE MOVIMIENTO REGISTRAL CREA EL FOLIO REAL: ' . $folioReal->folio . '.'
            ]);

        }

    }

    public function validaciones($movimientoRegistral){

        $movimiento = $movimientoRegistral->folioReal
                                            ->movimientosRegistrales()
                                            ->where('folio', ($movimientoRegistral->folio + 1))
                                            ->where('estado', '!=', 'nuevo')
                                            ->first();

        if($movimiento) throw new InscripcionesServiceException("El folio real tiene movimientos registrales posteriores elaborados.");

        $movimiento = MovimientoRegistral::where('movimiento_padre', $movimientoRegistral->id)->first();

        if($movimiento) throw new InscripcionesServiceException("Este movimiento generó un folio real nuevo.");

    }

    public function corregir(MovimientoRegistral $movimiento){

        $this->validaciones($movimiento);

        $this->obtenerMovimientoConPropietarios($movimiento);

        $movimiento->update([
            'estado' => 'correccion',
            'actualizado_por' => auth()->id()
        ]);

        $movimiento->inscripcionPropiedad->actores()->delete();

        $movimiento->audits()->latest()->first()->update(['tags' => 'Cambio estado a corrección']);

    }

}
