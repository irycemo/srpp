<?php

namespace App\Http\Services;

use App\Models\User;
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
use App\Http\Requests\MovimientoRegistralUpdateRequest;
use App\Http\Requests\MovimientoRegistralCambiarTipoServicioRequest;
use App\Models\FolioRealPersona;

class MovimientoRegistralService{

    public function __construct(
        public AsignacionService $asignacionService,
        public CertificacionesService $certificacionesService,
        public InscripcionesPropiedadService $inscripcionesPropiedadService,
        public InscripcionesGravamenService $inscripcionesGravamenService,
        public InscripcionesCancelacionService $inscripcionesCancelacionService,
        public VariosService $variosService,
        public SentenciasService $sentenciasService,
        public ReformaMoralService $reformaMoralService,
        public FideicomisoService $fideicomisoService
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

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            if($errorCode == 1062){

                throw new MovimientoRegistralServiceException('El trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' ya se encuentra registrado en Sistema RPP.');

            }else{

                throw new MovimientoRegistralServiceException('El trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . $th->getMessage());

            }

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

        } catch (CertificacionServiceException $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new MovimientoRegistralServiceException($th->getMessage());

        } catch (AsignacionServiceException $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new MovimientoRegistralServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new MovimientoRegistralServiceException('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

    }

    public function crearInscripcion($request, $id){

        if($request['categoria_servicio'] == 'Certificaciones'){

            $this->certificacionesService->store($request + ['movimiento_registral' => $id]);

            return;

        }

        if(in_array($request['categoria_servicio'], ['Inscripciones - Propiedad', 'Subdivisiones'])){

            if($request['servicio'] == 'D149' && $request['servicio_nombre'] == 'Inscripción de fideicomiso'){

                $this->fideicomisoService->store($request + ['movimiento_registral' => $id]);

                return;

            }else{

                $this->inscripcionesPropiedadService->store($request + ['movimiento_registral' => $id]);

                return;

            }

        }

        if($request['categoria_servicio'] == 'Inscripciones - Gravamenes'){


            $this->inscripcionesGravamenService->store($request + ['movimiento_registral' => $id]);

            return;

        }

        if($request['categoria_servicio'] == 'Cancelación - Gravamenes'){


            $this->inscripcionesCancelacionService->store($request + ['movimiento_registral' => $id]);

            return;

        }

        if($request['categoria_servicio'] == 'Varios, Arrendamientos, Avisos Preventivos'){


            $this->variosService->store($request + ['movimiento_registral' => $id]);

            return;

        }

        if($request['categoria_servicio'] == 'Sentencias'){


            $this->sentenciasService->store($request + ['movimiento_registral' => $id]);

            return;

        }

        if($request['categoria_servicio'] == 'Folio real de persona moral'){


            $this->reformaMoralService->store($request + ['movimiento_registral' => $id]);

            return;

        }

    }

    public function update(MovimientoRegistralUpdateRequest $request):void
    {

        try {

            $data = $request->validated();

            $data['estado'] = 'nuevo';

            DB::transaction(function () use($data){

                $movimiento_registral = MovimientoRegistral::findOrFail($data['movimiento_registral']);

                if(isset($data['folio_real'])){

                    if($data['folio_real'] != $movimiento_registral->folioReal?->folio){

                        $this->buscarNuevoFolioReal($data, $movimiento_registral);

                    }else{

                        $data['folio_real'] = $movimiento_registral->folio_real;

                        $this->actualizarMovimientoRegistral($data, $movimiento_registral);

                    }

                }else{

                    if(
                        isset($data['tomo']) &&
                        isset($data['registro']) &&
                        isset($data['numero_propiedad'])
                    ){

                        if(
                            $movimiento_registral->tomo != $data['tomo'] ||
                            $movimiento_registral->registro != $data['registro'] ||
                            $movimiento_registral->numero_propiedad != $data['numero_propiedad']
                        ){

                            $array = $this->revisarEncolamientoSinFolioInmobiliario($data, $movimiento_registral->id);

                            $this->actualizarMovimientoRegistral($data + $array, $movimiento_registral);

                            if($movimiento_registral->folioReal) $this->reacomodarFolios($movimiento_registral->folioReal);

                        }else{

                            $this->actualizarMovimientoRegistral($data, $movimiento_registral);

                        }

                    }else{

                        $this->actualizarMovimientoRegistral($data, $movimiento_registral);

                    }

                }

                if($movimiento_registral->certificacion && in_array($movimiento_registral->certificacion->servicio, ['DL14', 'DL13', 'DC93'])){

                    $this->recalcularFechaEntrega($movimiento_registral);

                }

                if($movimiento_registral->cancelacion && $movimiento_registral->folio_real){

                    $movimientoACancelar = $movimiento_registral->folioReal->movimientosRegistrales()->where('folio', $data['asiento_registral'])->first();

                    if($movimientoACancelar->id != $movimiento_registral->cancelacion->gravamen){

                        $movimiento_registral->cancelacion->update(['gravamen' => $movimientoACancelar->id]);

                    }

                }

            });

        } catch (MovimientoRegistralServiceException $th) {

            Log::error('Error al actualizar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th->getMessage());

            throw new MovimientoRegistralServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new MovimientoRegistralServiceException('Error al actualizar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' en Sistema RPP.');

        }

    }

    public function cambiarTipoServicio(MovimientoRegistralCambiarTipoServicioRequest $request):void
    {

        try {

            $data = $request->validated();

            $movimiento_registral = MovimientoRegistral::findOrFail($data['movimiento_registral']);

            $movimiento_registral->update([
                'estado' => 'nuevo',
                'tipo_servicio' => $data['tipo_servicio'],
                'monto' => $movimiento_registral->monto + (float)$data['monto'],
            ]);

            $this->recalcularFechaEntrega($movimiento_registral);

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new MovimientoRegistralServiceException('Error al actualizar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' en Sistema RPP.');

        }

    }

    public function requestMovimientoCrear($request):Array
    {

        try {

            $array = [];

            $fields = [
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
                'usuario_tramites_linea_id',
                'servicio_nombre',
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
                'procedencia'
            ];

            foreach($fields as $field){

                if(array_key_exists($field, $request)){

                    $array[$field] = $request[$field];

                }

            }

            if(isset($request['folio_real'])){

                $folioReal = FolioReal::where('folio', $request['folio_real'])->first();

                $array['folio_real'] = $folioReal->id;
                $array['folio'] = $this->calcularFolio($request);

                if($folioReal->estado == 'activo'){

                    if($request['categoria_servicio'] == 'Certificaciones'){

                        $array['estado'] = 'nuevo';

                    }else{

                        $array['estado'] = 'no recibido';

                    }

                }else{

                    $array['estado'] = 'precalificacion';
                }

            }else{

                if(isset($request['tomo']) && isset($request['registro']) && isset($request['numero_propiedad'])){

                    $folioReal = FolioReal::where('tomo_antecedente', $request['tomo'])
                                            ->where('registro_antecedente', $request['registro'])
                                            ->where('distrito_antecedente', $request['distrito'])
                                            ->where('numero_propiedad_antecedente', $request['numero_propiedad'])
                                            ->first();

                    if($folioReal){

                        $array['folio_real'] = $folioReal->id;
                        $array['folio'] = $this->calcularFolio($request);

                        if($folioReal->estado == 'activo'){

                            if($request['categoria_servicio'] == 'Certificaciones'){

                                $array['estado'] = 'nuevo';

                            }else{

                                $array['estado'] = 'no recibido';

                            }

                        }else{

                            $array['estado'] = 'precalificacion';
                        }

                    }else{

                        $array = array_merge($array, $this->revisarEncolamientoSinFolioInmobiliario($request));

                    }

                }else{

                    $array['folio_real'] = null;
                    $array['folio'] = 1;

                    if($request['categoria_servicio'] == 'Certificaciones'){

                        $array['estado'] = 'nuevo';

                    }else{

                        $array['estado'] = 'no recibido';

                    }

                }

            }

            if(isset($request['folio_real_persona_moral'])){

                $folioRealPersonaMoral = FolioRealPersona::where('folio', $request['folio_real_persona_moral'])->first();

                $array['folio_real_persona'] = $folioRealPersonaMoral->id;
                $array['folio'] = $this->calcularFolioPersonaMoral($request);
                $array['estado'] = 'no recibido';

            }

            $documento_entrada = [
                'tipo_documento' => $request['tipo_documento'] ?? null,
                'autoridad_cargo' => $request['autoridad_cargo'] ?? null,
                'autoridad_nombre' => $request['autoridad_nombre'] ?? null,
                'fecha_emision' => $request['fecha_emision'] ?? null,
                'numero_documento' => $request['numero_documento'] ?? null,
                'procedencia' => $request['procedencia'] ?? null,
            ];

            return $array + [
                'usuario_asignado' => $this->obtenerUsuarioAsignado($documento_entrada, isset($request['folio_real']), $request['servicio'], $request['distrito'], $request['solicitante'], $request['tipo_servicio'],$request['categoria_servicio'], $array['estado'], false),
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

    public function obtenerUsuarioAsignado($documento_entrada, $folioReal, $servicio, $distrito, $solicitante, $tipo_servicio, $categoria_servicio, $estado, $random):int
    {

        /* Certificaciones: Copias simples, Copias certificadas */
        if($servicio == 'DL13' || $servicio == 'DL14'){

            if($folioReal){

                return $this->asignacionService->obtenerCertificador($distrito, $solicitante, $tipo_servicio, $random);

            }else{

                return $this->asignacionService->obtenerCopiador($distrito, $solicitante, $tipo_servicio, $random);

            }

        }

        /* Certificaciones: Consultas */
        if($servicio == 'DC90' || $servicio == 'DC91' || $servicio == 'DC92' || $servicio == 'DC93'){

            return $this->asignacionService->obtenerUsuarioConsulta($distrito);

        }

        /* Certificaciones: Gravamen */
        if($servicio == 'DL07'){

            return $this->asignacionService->obtenerCertificadorGravamen($distrito, $solicitante, $tipo_servicio, $random, $folioReal);

        }

        /* Certificaciones: Propiedad */
        if($servicio == 'DL10' || $servicio == 'DL11'){

            return $this->asignacionService->obtenerCertificadorPropiedad($distrito, $solicitante, $tipo_servicio, $random, $folioReal);

        }

        if(isset($documento_entrada['tipo_documento'])
            && isset($documento_entrada['autoridad_cargo'])
            && isset($documento_entrada['autoridad_nombre'])
            && isset($documento_entrada['fecha_emision'])
            && isset($documento_entrada['numero_documento']))
        {

           $movimientos =  MovimientoRegistral::with('gravamen', 'vario', 'sentencia', 'cancelacion', 'inscripcionPropiedad', 'asignadoA')
                                        ->when(isset($documento_entrada['tipo_documento']), function($q) use($documento_entrada){
                                            $q->where('tipo_documento', $documento_entrada['tipo_documento']);
                                        })
                                        ->when(isset($documento_entrada['autoridad_cargo']), function($q) use($documento_entrada){
                                            $q->where('autoridad_cargo', $documento_entrada['autoridad_cargo']);
                                        })
                                        ->when(isset($documento_entrada['autoridad_nombre']), function($q) use($documento_entrada){
                                            $q->where('autoridad_nombre', $documento_entrada['autoridad_nombre']);
                                        })
                                        ->when(isset($documento_entrada['fecha_emision']), function($q) use($documento_entrada){
                                            $q->where('fecha_emision', $documento_entrada['fecha_emision']);
                                        })
                                        ->when(isset($documento_entrada['numero_documento']), function($q) use($documento_entrada){
                                            $q->where('numero_documento', $documento_entrada['numero_documento']);
                                        })
                                        ->when(isset($documento_entrada['procedencia']), function($q) use($documento_entrada){
                                            $q->where('procedencia', $documento_entrada['procedencia']);
                                        })
                                        ->get();

            foreach ($movimientos as $movimiento) {

                if($categoria_servicio == 'Inscripciones - Gravamenes' && $movimiento->inscripcionPropiedad){

                    if($movimiento->asignadoA->hasAllRoles(['Registrador Propiedad', 'Registrador Gravamen'])){

                        return $movimiento->usuario_asignado;

                    }

                }elseif($categoria_servicio == 'Inscripciones - Propiedad' && $movimiento->gravamen){

                    if($movimiento->asignadoA->hasAllRoles(['Registrador Propiedad', 'Registrador Gravamen'])){

                        return $movimiento->usuario_asignado;

                    }

                }elseif($categoria_servicio == 'Inscripciones - Gravamenes' && $movimiento->gravamen){

                    if($movimiento->asignadoA->hasRole('Pase a folio')){

                        return $this->asignacionService->obtenerUsuarioGravamen($folioReal, $distrito, $estado);

                    }else{

                        return $movimiento->usuario_asignado;

                    }

                }elseif($categoria_servicio == 'Varios, Arrendamientos, Avisos Preventivos' && $movimiento->vario){


                    if($movimiento->asignadoA->hasRole('Pase a folio')){

                        return $this->asignacionService->obtenerUsuarioVarios($folioReal, $distrito, $estado);

                    }else{

                        return $movimiento->usuario_asignado;

                    }

                }elseif($categoria_servicio == 'Cancelación - Gravamenes' && $movimiento->cancelacion){

                    if($movimiento->asignadoA->hasRole('Pase a folio')){

                        return $this->asignacionService->obtenerUsuarioCancelacion($folioReal, $distrito, $estado);

                    }else{

                        return $movimiento->usuario_asignado;

                    }

                }elseif($categoria_servicio == 'Sentencias' && $movimiento->sentencia){

                    if($movimiento->asignadoA->hasRole('Pase a folio')){

                        return $this->asignacionService->obtenerUsuarioSentencias($folioReal, $distrito, $estado);

                    }else{

                        return $movimiento->usuario_asignado;

                    }

                }elseif($categoria_servicio == 'Inscripciones - Propiedad' && $movimiento->inscripcionPropiedad){

                    if($movimiento->asignadoA->hasRole('Pase a folio')){

                        return $this->asignacionService->obtenerUsuarioPropiedad($folioReal, $distrito, $estado);

                    }else{

                        return $movimiento->usuario_asignado;

                    }

                }

            }

        }

        $inscripcionesPropiedad = ['D114', 'D118', 'D116', 'D115', 'D113', 'D157', 'D149'];

        /* Inscripciones: Propiedad */
        if(in_array($servicio, $inscripcionesPropiedad) && $categoria_servicio == 'Inscripciones - Propiedad'){

            return $this->asignacionService->obtenerUsuarioPropiedad($folioReal, $distrito, $estado);

        }

        /* Inscripciones: Gravamen */
        if(in_array($servicio, ['D127', 'D153', 'D150', 'D155', 'DM68', 'D154']) && $categoria_servicio == 'Inscripciones - Gravamenes'){

            return $this->asignacionService->obtenerUsuarioGravamen($folioReal, $distrito, $estado);

        }

        /* Inscripciones: Cancelaciones */
        if($servicio == 'D156' && $categoria_servicio == 'Cancelación - Gravamenes'){

            return $this->asignacionService->obtenerUsuarioCancelacion($folioReal, $distrito, $estado);

        }

        /* Inscripciones: Varios */
        if(in_array($servicio, ['D128', 'D112', 'D110', 'D157', 'DL19', 'DL16', 'DN83']) && $categoria_servicio == 'Varios, Arrendamientos, Avisos Preventivos'){

            return $this->asignacionService->obtenerUsuarioVarios($folioReal, $distrito, $estado);

        }

        /* Inscripciones: Sentencias */
        if($servicio == 'D157' && $categoria_servicio == 'Sentencias'){

            return $this->asignacionService->obtenerUsuarioSentencias($folioReal, $distrito, $estado);

        }

        /* Inscripciones: Folio real de persona moral */
        if($categoria_servicio == 'Folio real de persona moral'){

            return $this->asignacionService->obtenerUsuarioFolioRealMoral($distrito);

        }

        /* Inscripciones: Subdivisiones */
        if($categoria_servicio == 'Subdivisiones'){

            return $this->asignacionService->obtenerUsuarioSubdivisiones($distrito);

        }

    }

    public function obtenerSupervisor($servicio, $distrito):int
    {

        if($distrito == 2){

            $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor uruapan');
                                })
                                ->first();

            if($supervisor) return $supervisor->id;

        }

        $certificaciones = ['DC90', 'DC91', 'DC92', 'DC93', 'DL13', 'DL14', 'DL07', 'DL10', 'DL11'];

        if(in_array($servicio, $certificaciones)){

            return $this->asignacionService->obtenerSupervisorCertificaciones($distrito);

        }else{

            return $this->asignacionService->obtenerSupervisorInscripciones($distrito);

        }

    }

    public function calcularFolio($request){

        /* Certificaciones */
        if(in_array($request['servicio'], ['DL13', 'DL14', 'DC90', 'DC91', 'DC92', 'DC93'])){

            return null;

        }

        /* Inscripción de folio real de persona moral */
        if(!isset($request['tomo']) && !isset($request['registro']) && !isset($request['numero_propiedad']) && $request['servicio'] == 'D110'){

            return null;

        }

        /* Certificados de propiedad o negativos, sin antecedente */
        if($request['servicio'] == 'DL10'){

            if(!isset($request['folio_real'])){

                if(isset($request['tomo']) && isset($request['registro']) && isset($request['numero_propiedad'])){

                    return 1;

                }else{

                    return null;
                }

            }

        }

        /* Inscripciones */
        if(!isset($request['folio_real'])){

            return 1;

        }else{

            return FolioReal::where('folio', $request['folio_real'])->first()->ultimoFolio() + 1;

        }

    }

    public function calcularFolioPersonaMoral($request){

        return FolioRealPersona::where('folio', $request['folio_real_persona_moral'])->first()->ultimoFolio() + 1;

    }

    public function recalcularFechaEntrega($movimientoRegistral){

        $fecha = null;

        if($movimientoRegistral->tipo_servicio == 'ordinario'){

            $actual = now();

            for ($i=0; $i < 2; $i++) {

                $actual->addDays(1);

                while($actual->isWeekend()){

                    $actual->addDay();

                }

            }

            $fecha = $actual->toDateString();

        }elseif($movimientoRegistral->tipo_servicio == 'urgente'){

            $actual = now()->addDays(1);

            while($actual->isWeekend()){

                $actual->addDay();

            }

            $fecha = $actual->toDateString();

        }else{

            $fecha = now()->toDateString();

        }

        $movimientoRegistral->update(['fecha_entrega' => $fecha]);

    }

    public function buscarNuevoFolioReal($data, $movimiento_registral){

        $folioReal = FolioReal::where('folio', $data['folio_real'])->first();

        if(!$folioReal) throw new MovimientoRegistralServiceException('El folio real no existe.');

        if($folioReal->estado != 'activo') throw new MovimientoRegistralServiceException('El folio real no esta activo.');

        $data['folio_real'] = $folioReal->id;
        $data['estado'] = 'nuevo';
        $data['tomo'] = null;
        $data['registro'] = null;
        $data['numero_propiedad'] = null;

        if(! $movimiento_registral->folioReal?->movimientosRegistrales()->where('folio', 1)->first()){

            $data['folio'] = 1;

        }else{

            $data['folio'] = $folioReal->ultimoFolio() + 1;

        }

        $this->actualizarMovimientoRegistral($data, $movimiento_registral);

    }

    public function actualizarMovimientoRegistral($data, MovimientoRegistral $movimiento_registral){

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
            'numero_documento',
            'tipo_documento',
            'autoridad_cargo',
            'autoridad_nombre',
            'autoridad_numero',
            'fecha_emision',
            'procedencia',
            'numero_propiedad',
            'solicitante',
            'tomo',
            'tomo_bis',
            'registro',
            'registro_bis',
            'folio_real',
            'folio',
            'estado'
        ];

        foreach($fields as $field){

            if(array_key_exists($field, $data)){

                $array[$field] = $data[$field];

            }

        }

        $array['solicitante'] = $data['nombre_solicitante'];

        $movimiento_registral->update($array);

    }

    public function revisarEncolamientoSinFolioInmobiliario($data){

        $movimientos_registrales = MovimientoRegistral::where('tomo', $data['tomo'])
                                                    ->where('registro', $data['registro'])
                                                    ->where('numero_propiedad', $data['numero_propiedad'])
                                                    ->where('distrito', $data['distrito'])
                                                    ->whereNull('folio_real')
                                                    ->get();

        if($movimientos_registrales->count()){

            if(! $movimientos_registrales->where('folio', 1)->first()){

                return [
                    'folio' => 1,
                    'estado' => 'nuevo',
                    'folio_real' => null
                ];

            }else{

                $max_folio = $movimientos_registrales->max('folio');

                return [
                    'folio' => $max_folio + 1,
                    'estado' => 'precalificacion',
                    'folio_real' => null
                ];

            }

        }else{

            if($data['categoria_servicio'] == 'Certificaciones'){

                return [
                    'folio' => 1,
                    'estado' => 'nuevo',
                    'folio_real' => null
                ];

            }else{

                return [
                    'folio' => 1,
                    'estado' => 'no recibido',
                    'folio_real' => null
                ];

            }

        }


    }

    public function reacomodarFolios(FolioReal $folioReal){

        $total_movimientos = $folioReal->movimientosRegistrales->count();

        for ($i=1; $i <= $total_movimientos; $i++) {

            if(! $folioReal->movimientosRegistrales()->where('folio', $i)){

                $movimiento = MovimientoRegistral::where('folio_real', $folioReal->id)
                                                ->where('estado', 'nuevo')
                                                ->orderBy('folio')
                                                ->first();

                if(! $movimiento){

                    $movimiento = MovimientoRegistral::where('folio_real', $folioReal->id)
                                                ->whereIn('estado', ['carga_parcial', 'pase_folio'])
                                                ->orderBy('folio')
                                                ->first();

                }

                if(! $movimiento) break;

                $movimiento->update(['folio' => $i]);

            }

        }

    }

}

