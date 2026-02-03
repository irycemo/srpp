<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovimientoRegistralRequest;
use App\Http\Services\MovimientoRegistralService;
use App\Http\Requests\MovimientoRegistralUpdateRequest;
use App\Http\Requests\MovimientoRegistralCambiarTipoServicioRequest;


class MovimientoRegistralController extends Controller
{

    public function store(MovimientoRegistralRequest $request)
    {

        $validated = $request->validated();

        try {

            $movimientoRegistral = null;

            DB::transaction(function () use($validated, &$movimientoRegistral){

                $movimientoRegistral = (new MovimientoRegistralService($validated['categoria_servicio']))->crear($validated);

            });

            return response()->json([
                'data' => [
                    'id' => $movimientoRegistral?->id,
                    'usuario_asignado' => $movimientoRegistral?->asignadoA->name
                ],
            ], 200);

        } catch (GeneralException $th) {

            Log::error('Error al ingresar el trámite: ' . $validated['año'] . '-' . $validated['tramite'] . '-' . $validated['usuario'] . ' desde Sistema Trámites. ' . $th);

            return response()->json([
                'error' => $th->getMessage(),
            ], 500);

        }catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $validated['año'] . '-' . $validated['tramite'] . '-' . $validated['usuario'] .' desde Sistema Trámites. ' . $th);

            return response()->json([
                'error' => 'Error al actualizar el trámite: ' . $validated['año'] . '-' . $validated['tramite'] . '-' . $validated['usuario'] . ' en Sistema RPP.',
            ], 500);

        }

    }

    public function update(MovimientoRegistralUpdateRequest $request){

        $validated = $request->validated();

        try {

            DB::transaction(function () use($validated){

                (new MovimientoRegistralService($validated['categoria_servicio']))->actualizar($validated);

            });

            return response()->json([
                'data' => []
            ], 200);

        } catch (GeneralException $th) {

            Log::error('Error al actualizar el trámite desde Sistema Trámites. ' . $th);

            return response()->json([
                'error' => $th->getMessage(),
            ], 500);

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite desde Sistema Trámites. ' . $th);

            return response()->json([
                'error' => 'Error al actualizar el trámite en Sistema RPP.',
            ], 500);

        }

    }

    public function cambiarTipoServicio(MovimientoRegistralCambiarTipoServicioRequest $request){

        $validated = $request->validated();

        try {

            DB::transaction(function () use($validated){

                (new MovimientoRegistralService($validated['categoria_servicio']))->cambiarTipoServicio($validated);

            });

            return response()->json([
                'data' => []
            ], 200);

        } catch (GeneralException $th) {

            Log::error('Error al actualizar el trámite: ' . $validated['año'] . '-' . $validated['tramite'] . '-' . $validated['usuario'] . ' desde Sistema Trámites. ' . $th);

            return response()->json([
                'derrorata' => $th->getMessage(),
            ], 500);

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite: ' . $validated['año'] . '-' . $validated['tramite'] . '-' . $validated['usuario'] . ' desde Sistema Trámites. ' . $th);

            return response()->json([
                'error' => 'Error al actualizar el trámite: ' . $validated['año'] . '-' . $validated['tramite'] . '-' . $validated['usuario'] . ' en Sistema RPP.',
            ], 500);

        }

    }

}
