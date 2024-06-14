<?php

namespace App\Http\Services;

use App\Models\FolioReal;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Exceptions\AsignacionServiceException;
use App\Exceptions\CertificacionServiceException;
use App\Http\Requests\MovimientoRegistralRequest;
use App\Http\Services\InscripcionesCancelacionService;
use App\Exceptions\MovimientoRegistralServiceException;
use App\Http\Requests\MovimientoRegistralCambiarTipoServicioRequest;

class MovimientoRegistralService{

    public function __construct(
        public AsignacionService $asignacionService,
        public CertificacionesService $certificacionesService,
        public InscripcionesPropiedadService $inscripcionesPropiedadService,
        public InscripcionesGravamenService $inscripcionesGravamenService,
        public InscripcionesCancelacionService $inscripcionesCancelacionService,
        public VariosService $variosService,
        public SentenciasService $sentenciasService
    ){}

    public function store(MovimientoRegistralRequest $request)
    {

        try {

            $request = $request->validated();

            $data = null;

            DB::transaction(function () use($request, &$data){

                $movimiento_registral = MovimientoRegistral::create($this->requestMovimientoCrear($request));

                $this->crearInscripcion($request, $movimiento_registral->id);

                $data = $movimiento_registral;

            });

            return $data;

        } catch (QueryException $th) {

            $errorCode = $th->errorInfo[1];

            if($errorCode == 1062){

                throw new MovimientoRegistralServiceException('El trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' ya se encuentra registrado en Sistema RPP.');

            }else{

                throw new MovimientoRegistralServiceException('El trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . $th->getMessage());

            }

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th->getMessage());

        } catch (CertificacionServiceException $th) {

            throw new MovimientoRegistralServiceException($th->getMessage());

        } catch (AsignacionServiceException $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th->getMessage());

            throw new MovimientoRegistralServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new MovimientoRegistralServiceException('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

    }

    public function crearInscripcion($request, $id){

        if($request['categoria_servicio'] == 'Certificaciones'){

            $this->certificacionesService->store($request + ['movimiento_registral' => $id]);

        }

        if($request['categoria_servicio'] == 'Inscripciones - Propiedad'){


            $this->inscripcionesPropiedadService->store($request + ['movimiento_registral' => $id]);

        }

        if($request['categoria_servicio'] == 'Inscripciones - Gravamenes'){


            $this->inscripcionesGravamenService->store($request + ['movimiento_registral' => $id]);

        }

        if($request['categoria_servicio'] == 'Cancelación - Gravamenes'){


            $this->inscripcionesCancelacionService->store($request + ['movimiento_registral' => $id]);

        }

        if($request['categoria_servicio'] == 'Varios , Arrendamientos, Avisos Preventivos'){


            $this->variosService->store($request + ['movimiento_registral' => $id]);

        }

        if($request['categoria_servicio'] == 'Sentencias'){


            $this->sentenciasService->store($request + ['movimiento_registral' => $id]);

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

            throw new MovimientoRegistralServiceException('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . '-' . $request->usuario . ' en Sistema RPP.');

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

            throw new MovimientoRegistralServiceException('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . '-' . $request->usuario . ' en Sistema RPP.');

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
                'tomo_gravamen',
                'registro_gravamen',
                'seccion',
                'año',
                'tramite',
                'usuario',
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

            $documento_entrada = [
                'tipo_documento' => $request['tipo_documento'] ?? null,
                'autoridad_cargo' => $request['autoridad_cargo'] ?? null,
                'autoridad_nombre' => $request['autoridad_nombre'] ?? null,
                'fecha_emision' => $request['fecha_emision'] ?? null,
                'numero_documento' => $request['numero_documento'] ?? null,
                'procedencia' => $request['procedencia'] ?? null,
            ];

            return $array + [
                'folio' => $this->calcularFolio($request),
                'estado' => 'nuevo',
                'usuario_asignado' => $this->obtenerUsuarioAsignado($documento_entrada, $folioReal, $request['servicio'], $request['distrito'], $request['solicitante'], $request['tipo_servicio'], false),
                'usuario_supervisor' => $this->obtenerSupervisor($request['servicio'], $request['distrito']),
                'solicitante' => $request['nombre_solicitante']
            ];

        } catch (AsignacionServiceException $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th->getMessage());

            throw new MovimientoRegistralServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error('Error al procesar request para generar movimiento registral. ' . $th);

            throw new MovimientoRegistralServiceException('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

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
            'tomo_gravamen',
            'registro_gravamen',
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

    public function obtenerUsuarioAsignado($documento_entrada, $folioReal, $servicio, $distrito, $solicitante, $tipo_servicio, $random):int
    {

        $movimientoRegistral = MovimientoRegistral::where('tipo_documento', $documento_entrada['tipo_documento'])
                                                        ->where('autoridad_cargo', $documento_entrada['autoridad_cargo'])
                                                        ->where('autoridad_nombre', $documento_entrada['autoridad_nombre'])
                                                        ->where('fecha_emision', $documento_entrada['fecha_emision'])
                                                        ->where('numero_documento', $documento_entrada['numero_documento'])
                                                        ->where('procedencia', $documento_entrada['procedencia'])
                                                        ->first();

        if($movimientoRegistral){

            return $movimientoRegistral->usuario_asignado;

        }

        /* Certificaciones: Copias simples, Copias certificadas */
        if($servicio == 'DL13' || $servicio == 'DL14'){

            return $this->asignacionService->obtenerCertificador($distrito, $solicitante, $tipo_servicio, $random);

        }

        /* Certificaciones: Consultas */
        if($servicio == 'DC90' || $servicio == 'DC91' || $servicio == 'DC92' || $servicio == 'DC93'){

            return $this->asignacionService->obtenerUsuarioConsulta($distrito);

        }

        /* Certificaciones: Gravamen */
        if($servicio == 'DL07'){

            return $this->asignacionService->obtenerCertificadorGravamen($distrito, $solicitante, $tipo_servicio, $random);

        }

        $inscripcionesPropiedad = ['D122', 'D114', 'D125', 'D126', 'D124', 'D121', 'D120', 'D119', 'D123', 'D118', 'D116', 'D115', 'D113', 'D731'];

        /* Inscripciones: Propiedad */
        if(in_array($servicio, $inscripcionesPropiedad)){

            return $this->asignacionService->obtenerUsuarioPropiedad($folioReal, $distrito);

        }

        /* Inscripciones: Gravamen */
        if($servicio == 'DL66'){

            return $this->asignacionService->obtenerUsuarioGravamen($folioReal, $distrito);

        }

        /* Inscripciones: Cancelaciones */
        if($servicio == 'D720'){

            return $this->asignacionService->obtenerUsuarioCancelacion($folioReal, $distrito);

        }

        /* Inscripciones: Varios */
        if($servicio == 'DL09'){

            return $this->asignacionService->obtenerUsuarioVarios($folioReal, $distrito);

        }

        /* Inscripciones: Sentencias */
        if($servicio == 'D110'){

            return $this->asignacionService->obtenerUsuarioSentencias($folioReal, $distrito);

        }

    }

    public function obtenerSupervisor($servicio, $distrito):int
    {

        $certificaciones = ['DC90', 'DC91', 'DC92', 'DC93', 'DL13', 'DL14', 'DL07'];

        if(in_array($servicio, $certificaciones)){

            return $this->asignacionService->obtenerSupervisorCertificaciones($distrito);

        }

        $inscripcionesPropiedad = ['D122', 'D114', 'D125', 'D126', 'D124', 'D121', 'D120', 'D119', 'D123', 'D118', 'D116', 'D115', 'D113', 'D731'];

        /* Inscripciones: Propiedad */
        if(in_array($servicio, $inscripcionesPropiedad)){

            return $this->asignacionService->obtenerSupervisorPropiedad($distrito);

        }

        /* Inscripciones: Gravamen */
        if($servicio == 'DL66'){
            return $this->asignacionService->obtenerSupervisorGravamen($distrito);

        }

        /* Inscripciones: Cancelaciones */
        if($servicio == 'D720'){

            return $this->asignacionService->obtenerSupervisorCancelacion($distrito);

        }

        /* Inscripciones: Varios */
        if($servicio == 'DL09'){

            return $this->asignacionService->obtenerSupervisorVarios($distrito);

        }

        /* Inscripciones: Sentencias */
        if($servicio == 'D110'){

            return $this->asignacionService->obtenerSupervisorSentencias($distrito);

        }

    }

    public function calcularFolio($request){

        if(in_array($request['servicio'], ['DL13', 'DL14', 'DC90', 'DC91', 'DC92', 'DC93'])){

            return null;

        }

        if(!isset($request['folio_real'])){

            return 1;

        }else{

            return FolioReal::where('folio', $request['folio_real'])->first()->ultimoFolio() + 1;

        }

    }

}

