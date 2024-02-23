<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Exceptions\AsignacionServiceException;
use App\Exceptions\CertificacionServiceException;
use App\Http\Requests\MovimientoRegistralRequest;
use App\Exceptions\MovimientoRegistralServiceException;
use App\Http\Requests\MovimientoRegistralCambiarTipoServicioRequest;
use App\Models\FolioReal;

class MovimientoRegistralService{

    public function __construct(
        public AsignacionService $asignacionService,
        public CertificacionesService $certificacionesService,
        public InscripcionesPropiedadService $inscripcionesPropiedadService
    ){}

    public function store(MovimientoRegistralRequest $request)
    {

        try {

            $request = $request->validated();

            $data = null;

            DB::transaction(function () use($request, &$data){

                $movimiento_registral = MovimientoRegistral::create($this->requestMovimientoCrear($request));

                if($request['categoria_servicio'] == 'Certificaciones'){

                    $this->certificacionesService->store($request + ['movimiento_registral' => $movimiento_registral->id]);

                }

                if($request['categoria_servicio'] == 'Inscripciones - Propiedad'){


                    $this->inscripcionesPropiedadService->store($request + ['movimiento_registral' => $movimiento_registral->id]);

                }

                $data = $movimiento_registral;

            });

            return $data;

        } catch (QueryException $th) {

            $errorCode = $th->errorInfo[1];

            if($errorCode == 1062){

                throw new MovimientoRegistralServiceException('El trámite: ' . $request['año'] . '-' . $request['tramite'] . ' ya se encuentra registrado en Sistema RPP.');

            }else{

                throw new MovimientoRegistralServiceException('El trámite: ' . $request['año'] . '-' . $request['tramite'] . $th->getMessage());

            }

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites. ' . $th->getMessage());

        } catch (CertificacionServiceException $th) {

            throw new MovimientoRegistralServiceException($th->getMessage());

        } catch (AsignacionServiceException $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites. ' . $th->getMessage());

            throw new MovimientoRegistralServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites. ' . $th);

            throw new MovimientoRegistralServiceException('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites.');

        }

    }

    public function update(MovimientoRegistralRequest $request):void
    {

        try {

            $data = $request->validated();

            DB::transaction(function () use($data){

                $movimiento_registral = MovimientoRegistral::findOrFail($data['movimiento_registral']);

                $movimiento_registral->update($this->requestMovimientoActualizar($data));

            });

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . ' desde Sistema Trámites. ' . $th);

            throw new MovimientoRegistralServiceException('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . ' en Sistema RPP.');

        }

    }

    public function cambiarTipoServicio(MovimientoRegistralCambiarTipoServicioRequest $request):void
    {

        try {

            $data = $request->validated();

            $movimiento_registral = MovimientoRegistral::findOrFail($data['movimiento_registral']);

            $movimiento_registral->update(['tipo_servicio' => $data['tipo_servicio'], 'monto' => $movimiento_registral->monto + (float)$data['monto']]);

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . ' desde Sistema Trámites. ' . $th);

            throw new MovimientoRegistralServiceException('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . ' en Sistema RPP.');

        }

    }

    public function requestMovimientoCrear($request):Array
    {

        try {

            $array = [];

            $fields = [
                'folio_real',
                'numero_propiedad',
                'tomo',
                'tomo_bis',
                'registro',
                'registro_bis',
                'distrito',
                'seccion',
                'año',
                'tramite',
                'tipo_servicio',
                'monto',
                'fecha_prelacion',
                'fecha_pago',
                'fecha_entrega',
                'numero_oficio',
                'tipo_documento',
                'numero_documento',
                'autoridad_cargo',
                'autoridad_nombre',
                'autoridad_numero',
                'fecha_emision',
                'procedencia',
            ];

            foreach($fields as $field){

                if(array_key_exists($field, $request)){

                    $array[$field] = $request[$field];

                }

            }

            isset($request['folio_real']) ? $folioReal = $request['folio_real'] : $folioReal = null;

            return $array + [
                'folio' => $this->calcularFolio($request),
                'estado' => 'nuevo',
                'usuario_asignado' => $this->obtenerUsuarioAsignado($folioReal, $request['servicio'], $request['distrito'], $request['solicitante'], $request['tipo_servicio'], false),
                'usuario_supervisor' => $this->obtenerSupervisor($request['servicio'], $request['distrito']),
                'solicitante' => $request['nombre_solicitante']
            ];

        } catch (AsignacionServiceException $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites. ' . $th->getMessage());

            throw new MovimientoRegistralServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error('Error al procesar request para generar movimiento registral. ' . $th);

            throw new MovimientoRegistralServiceException('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites.');

        }

    }

    public function requestMovimientoActualizar($request):array
    {

        $array = [];

        $fields = [
            'tomo',
            'tomo_bis',
            'registro',
            'registro_bis',
            'distrito',
            'seccion',
            'numero_oficio',
            'tipo_documento',
            'autoridad_cargo',
            'autoridad_nombre',
            'autoridad_numero',
            'fecha_emision',
            'procedencia',
        ];

        foreach($fields as $field){

            if(array_key_exists($field, $request)){

                $array[$field] = $request[$field];

            }

        }

        return $array + ['estado' => 'nuevo'];

    }

    public function obtenerUsuarioAsignado($folioReal, $servicio, $distrito, $solicitante, $tipo_servicio, $random):int
    {

        /* Certificaciones: Copias simples, Copias certificadas */
        if($servicio == 'DL13' || $servicio == 'DL14'){

            return $this->asignacionService->obtenerCertificador($distrito, $solicitante, $tipo_servicio, $random);

        }

        /* Certificaciones: Consultas */
        if($servicio == 'DC90' || $servicio == 'DC91' || $servicio == 'DC92' || $servicio == 'DC93'){

            return $this->asignacionService->obtenerUsuarioConsulta($distrito);

        }

        $inscripcionesPropiedad = ['D122', 'D114', 'D125', 'D126', 'D124', 'D121', 'D120', 'D119', 'D123', 'D118', 'D116', 'D115', 'D113'];

        /* Inscripciones: Propiedad */
        if(in_array($servicio, $inscripcionesPropiedad)){

            return $this->asignacionService->obtenerUsuarioPropiedad($folioReal, $distrito);

        }

    }

    public function obtenerSupervisor($servicio, $distrito):int
    {

        $certificaciones = ['DC90', 'DC91', 'DC92', 'DC93', 'DL13', 'DL14'];

        if(in_array($servicio, $certificaciones)){

            return $this->asignacionService->obtenerSupervisorCertificaciones($distrito);

        }

        $inscripcionesPropiedad = ['D122', 'D114', 'D125', 'D126', 'D124', 'D121', 'D120', 'D119', 'D123', 'D118', 'D116', 'D115', 'D113'];

        if(in_array($servicio, $inscripcionesPropiedad)){

            return $this->asignacionService->obtenerSupervisorPropiedad($distrito);

        }



    }

    public function calcularFolio($request){

        if($request['categoria_servicio'] == 'Inscripciones - Propiedad'){

            if(!isset($request['folio_real'])){

                return 1;

            }else{

                return FolioReal::find($request['folio_real'])->ultimoFolio() + 1;

            }

        }else{

            return null;

        }

    }

}
