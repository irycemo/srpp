<?php

namespace App\Actions\MovimientoRegistral;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\SistemaTramitesService;
use App\Models\MovimientoRegistral;
use App\Exceptions\GeneralException;
use Illuminate\Support\Collection;


class EliminarMovimientoRegistralAction
{

    public function __construct(private SistemaTramitesService $sistemaTramitesService)
    {}

    public function handle(MovimientoRegistral $movimiento_registral):void
    {

        if(! $movimiento_registral->folio_real){

            $this->procesarMovimientosPrecalificacion($movimiento_registral);

            return;

        }

        $movimientos = $this->obtenerArbolMovimientos($movimiento_registral);

        $ids_a_eliminar = $movimientos->pluck('id');

        foreach ($movimientos as $item) {

            $this->validarMovimientoRegistral($item, $ids_a_eliminar);

        }

        $archivosAEliminar = collect();

        DB::transaction(function () use (
            $movimientos,
            &$archivosAEliminar
        ) {

            foreach ($movimientos->reverse() as $item) {

                $archivosAEliminar = $archivosAEliminar->merge($this->borrarMovimientoRegistral($item));

            }

        });

        $this->eliminarArchivosMovimientoRegistral($archivosAEliminar);

    }

    private function obtenerArbolMovimientos(MovimientoRegistral $movimiento): Collection
    {

        $movimientos = collect([$movimiento]);

        $hijos = MovimientoRegistral::with('folioReal:id,folio')
                                        ->where('movimiento_padre', $movimiento->id)
                                        ->get();

        foreach ($hijos as $hijo) {

            $movimientos = $movimientos->merge(
                $this->obtenerArbolMovimientos($hijo)
            );

        }

        return $movimientos;

    }

    private function validarMovimientoRegistral(MovimientoRegistral $movimiento, Collection $ids_a_eliminar): void
    {

        if (!in_array($movimiento->estado, [
            'nuevo',
            'correccion',
            'pase_folio',
            'no recibido',
            'recahzado',
            'precalificacion',
        ])) {

            throw new GeneralException(
                "El folio real: {$movimiento->folioReal->folio} " .
                "tiene movimientos registrales elaborados no es posible borrarlo."
            );
        }

        $hayMovimientosPosteriores = MovimientoRegistral::where('folio_real', $movimiento->folio_real)
                                                            ->where('folio', '>', $movimiento->folio)
                                                            ->whereIn('estado', [
                                                                'elaborado',
                                                                'finalizado',
                                                                'concluido',
                                                            ])
                                                            ->whereNotIn('id', $ids_a_eliminar)
                                                            ->exists();

        if ($hayMovimientosPosteriores) {

            throw new GeneralException(
                'El folio real del movimiento registral: ' .
                $movimiento->folioReal->folio . '-' .
                $movimiento->folio .
                ' tiene movimientos posteriores elaborados'
            );

        }

    }

    private function borrarMovimientoRegistral(MovimientoRegistral $movimiento): Collection
    {

        $movimiento->load(
            'firmasElectronicas',
            'certificacion',
            'inscripcionPropiedad.actores',
            'cancelacion',
            'gravamen.actores',
            'sentencia',
            'vario.actores',
            'reformaMoral.actores',
            'archivos'
        );

        $archivos = $movimiento->archivos->map(function ($archivo) {
                                                return [
                                                    'id' => $archivo->id,
                                                    'url' => $archivo->url,
                                                    'descripcion' => $archivo->descripcion,
                                                ];

                                            });

        $movimiento->firmasElectronicas?->each->delete();

        $movimiento->certificacion?->delete();

        $movimiento->inscripcionPropiedad?->actores?->each->delete();

        $movimiento->inscripcionPropiedad?->delete();

        $movimiento->cancelacion?->delete();

        $movimiento->gravamen?->actores?->each->delete();

        $movimiento->gravamen?->delete();

        $movimiento->sentencia?->actores?->each->delete();

        $movimiento->sentencia?->delete();

        $movimiento->vario?->actores?->each->delete();

        $movimiento->vario?->delete();

        $movimiento->reformaMoral?->actores?->each->delete();

        $movimiento->reformaMoral?->delete();

        $movimiento->archivos?->each->delete();

        $this->sistemaTramitesService->desvincularMovimientoRegistral($movimiento->id);

        $movimiento->delete();

        return $archivos;

    }

    private function eliminarArchivosMovimientoRegistral(Collection $archivos): void
    {

        foreach ($archivos as $archivo) {

            if (app()->isProduction()) {

                $prefijo = config('services.ses.ruta_documento_entrada') . '/' . $archivo['url'];

                $rutas = $archivos->map(
                                        fn ($archivo) =>
                                            $prefijo . '/' . $archivo['url']
                                    )
                                    ->values()
                                    ->all();

                Storage::disk('s3')->delete($rutas);

                return;

            }

            foreach ($archivos as $archivo) {

                if ($archivo['descripcion'] === 'caratula') {

                    $ruta = 'caratulas/' . $archivo['url'];

                } elseif (
                    $archivo['descripcion'] === 'documento_entrada'
                ) {

                    $ruta = 'documento_entrada/' . $archivo['url'];

                } else {

                    continue;
                }

                if (file_exists($ruta)) {
                    unlink($ruta);
                }
            }

        }
    }

    private function procesarMovimientosPrecalificacion(MovimientoRegistral $movimiento_registral):void
    {

        $movimientos_precalificacion = MovimientoRegistral::where('tomo', $movimiento_registral->tomo)
                                                            ->where('registro', $movimiento_registral->registro)
                                                            ->where('distrito', $movimiento_registral->getRawOriginal('distrito'))
                                                            ->where('folio', '>', $movimiento_registral->folio)
                                                            ->orderBy('folio')
                                                            ->get();

        DB::transaction(function () use($movimientos_precalificacion, $movimiento_registral){

        $count = $movimiento_registral->folio;

            if($movimientos_precalificacion->count()){

                foreach ($movimientos_precalificacion as $movimiento) {

                    if($movimiento->folio - 1 === 1){

                        $movimiento->update(['folio' => $movimiento->folio - 1, 'estado' => 'no recibido']);

                    }

                    $movimiento->update(['folio' => $count]);

                    $count ++;

                }

            }

            $this->borrarMovimientoRegistral($movimiento_registral);

        });

    }

}
