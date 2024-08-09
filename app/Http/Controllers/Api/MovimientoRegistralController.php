<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovimientoRegistralRequest;
use App\Exceptions\MovimientoRegistralServiceException;
use App\Http\Requests\MovimientoRegistralCambiarTipoServicioRequest;
use App\Http\Requests\MovimientoRegistralUpdateRequest;
use App\Http\Services\MovimientoRegistralService;


class MovimientoRegistralController extends Controller
{

    public function __construct(public MovimientoRegistralService $movimientoRegistralService){}

    public function store(MovimientoRegistralRequest $request)
    {

        try {

            $data = $this->movimientoRegistralService->store($request);

            return response()->json([
                'result' => 'success',
                'data' => $data
            ], 200);

        } catch (MovimientoRegistralServiceException $th) {

            Log::error('Error al ingresar el trámite: ' . $request->año . '-' . $request->tramite . '-' . $request->usuario . ' desde Sistema Trámites. ' . $th);

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request->año . '-' . $request->tramite  . '-' . $request->usuario .' desde Sistema Trámites. ' . $th);

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }

    }

    public function update(MovimientoRegistralUpdateRequest $request){

        try {

            $this->movimientoRegistralService->update($request);

            return response()->json([
                'result' => 'success',
                'data' => []
            ], 200);

        } catch (MovimientoRegistralServiceException $th) {

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . '-' . $request->usuario . ' desde Sistema Trámites. ' . $th);

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }

    }

    public function cambiarTipoServicio(MovimientoRegistralCambiarTipoServicioRequest $request){

        try {

            $this->movimientoRegistralService->cambiarTipoServicio($request);

            return response()->json([
                'result' => 'success',
                'data' => []
            ], 200);

        } catch (MovimientoRegistralServiceException $th) {

            Log::error('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . '-' . $request->usuario . ' desde Sistema Trámites. ' . $th);

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . '-' . $request->usuario . ' desde Sistema Trámites. ' . $th);

            return response()->json([
                'result' => 'error',
                'data' => 'Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . '-' . $request->usuario . ' en Sistema RPP.',
            ], 500);

        }

    }

}
