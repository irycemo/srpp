<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\FolioReal;
use App\Models\FolioRealPersona;
use App\Models\MovimientoRegistral;
use App\Exceptions\GeneralException;
use App\Http\Services\MovimientoServiceInterface;
use App\Traits\MovimientoRegistral\MovimientoRegistralHelpersTrait;

class MovimientoRegistralService{

    use MovimientoRegistralHelpersTrait;

    public MovimientoServiceInterface $movimiento_service_interface;

    public function __construct(public string $categoria)
    {

        $this->movimiento_service_interface = match($categoria){
                                                    'Certificaciones' => new CertificacionesService,
                                                    'Inscripciones - Propiedad', 'Subdivisiones' => new InscripcionesPropiedadService,
                                                    'Inscripciones - Gravamenes' => new InscripcionesGravamenService,
                                                    'Cancelación - Gravamenes' => new InscripcionesCancelacionService,
                                                    'Varios, Arrendamientos, Avisos Preventivos' => new VariosService,
                                                    'Sentencias' => new SentenciasService,
                                                    'Folio real de persona moral' => new ReformaMoralService,
                                                    'Folio simplificado' => new FolioSimplificadoService
                                                };

    }

    public function crear(array $request):MovimientoRegistral
    {

        $movimiento_registral = MovimientoRegistral::create($this->requestMovimientoCrear($request));

        $this->movimiento_service_interface->crear($request + ['movimiento_registral_id' => $movimiento_registral->id]);

        return $movimiento_registral;

    }

    public function actualizar(array $request):void
    {

        $request['estado'] = 'nuevo';

        $movimiento_registral = MovimientoRegistral::find($request['movimiento_registral']);

        if(!$movimiento_registral) throw new GeneralException('No se encontro el movimiento registral a actualizar.');

        if($this->requestTieneFolioReal($request)){

            /* Cambio el folio real */
            if($request['folio_real'] != $movimiento_registral->folioReal?->folio){

                if($movimiento_registral->folioReal) $this->reacomodarFolios($movimiento_registral->folioReal);

                $this->reacomodarFoliosPrecalificacion($movimiento_registral);

                $this->buscarNuevoFolioReal($request, $movimiento_registral);

            /* No cambio el folio real */
            }else{

                $request['folio_real'] = $movimiento_registral->folio_real;

                $this->actualizarMovimientoRegistral($request, $movimiento_registral);

            }

        /* Request no trae folio real */
        }else{

            /* Trae antecedente */
            if($this->requestTieneAntecedente($request)){

                /* Cambio el antecedente */
                if($this->antecedenteCambio($request, $movimiento_registral)){

                    $array = $this->revisarEncolamientoSinFolioInmobiliario($request, $movimiento_registral->id);

                    if($movimiento_registral->folioReal) $this->reacomodarFolios($movimiento_registral->folioReal);

                    $this->reacomodarFoliosPrecalificacion($movimiento_registral);

                    $this->actualizarMovimientoRegistral($request + $array, $movimiento_registral);

                /* No cambio el antecedente */
                }else{

                    $this->actualizarMovimientoRegistral($request, $movimiento_registral);

                }

            /* No trae antecedente */
            }else{

                $this->actualizarMovimientoRegistral($request, $movimiento_registral);

            }

        }

        /* Certificaciones */
        if($movimiento_registral->certificacion && in_array($movimiento_registral->certificacion->servicio, ['DL14', 'DL13', 'DC93'])){

            $this->recalcularFechaEntrega($movimiento_registral);

        }

        /* Actualizar relacion de cancelacion con su gravamen */
        if($movimiento_registral->cancelacion && $movimiento_registral->folio_real){

            $movimiento_registral->refresh();

            $movimientoACancelar = $movimiento_registral->folioReal->movimientosRegistrales()->where('folio', $request['asiento_registral'])->first();

            if($movimientoACancelar->id != $movimiento_registral->cancelacion->gravamen){

                $movimiento_registral->cancelacion->update(['gravamen' => $movimientoACancelar->id]);

            }

        }

    }

    public function cambiarTipoServicio(array $request):void
    {

        $movimiento_registral = MovimientoRegistral::findOrFail($request['movimiento_registral']);

        $movimiento_registral->update([
            'estado' => 'nuevo',
            'tipo_servicio' => $request['tipo_servicio'],
            'monto' => $movimiento_registral->monto + (float)$request['monto'],
        ]);

        $this->recalcularFechaEntrega($movimiento_registral);

    }

    public function requestMovimientoCrear(array $request):array
    {

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

        /* Request trae folio real */
        if($this->requestTieneFolioReal($request)){

            $folioReal = FolioReal::where('folio', $request['folio_real'])->first();

            $array['folio_real'] = $folioReal->id;
            $array['folio'] = $this->calcularFolio($request);

            /* El folio esta activo */
            if($folioReal->estado == 'activo'){

                /* Certificación */
                if($request['categoria_servicio'] == 'Certificaciones'){

                    $array['estado'] = 'nuevo';

                /* Inscripción */
                }else{

                    $array['estado'] = 'no recibido';

                }

            /* El folio no esta activo */
            }else{

                $array['estado'] = 'precalificacion';

            }

        /* Request no trae folio real */
        }else{

            /* Request trae antecedente */
            if($this->requestTieneAntecedente($request)){

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

            /* Request no trea antecedente */
            }else{

                $array['folio_real'] = null;
                $array['folio'] = 1;
                $array['pase_a_folio'] = $request['categoria_servicio'] == 'Folio simplificado' ? false : true;

                if($request['categoria_servicio'] == 'Certificaciones'){

                    $array['estado'] = 'nuevo';

                }else{

                    $array['estado'] = 'no recibido';

                }

            }

        }

        if($this->requestTieneFolioRealPersonaMoral($request)){

            $folioRealPersonaMoral = FolioRealPersona::where('folio', $request['folio_real_persona_moral'])->first();

            $array['folio_real_persona'] = $folioRealPersonaMoral->id;
            $array['folio'] = $this->calcularFolioPersonaMoral($request);
            $array['estado'] = 'no recibido';

        }

        return $array + [
            'usuario_asignado' => $this->obtenerUsuarioAsignado($request + ['estado' => $array['estado']]),
            'usuario_supervisor' => $this->obtenerSupervisor($request),
            'solicitante' => $request['nombre_solicitante']
        ];

    }

    public function obtenerUsuarioAsignado($request):int | null
    {

        if($request['categoria_servicio'] == 'Certificaciones'){

            return $this->movimiento_service_interface->obtenerUsuarioAsignado($request);

        }else{

            /* Revisar documento de entrada para asignar al mismo usuario */
            if($this->requestTieneDocumentoEntrada($request))
            {

                $movimientos =  MovimientoRegistral::with('gravamen', 'vario', 'sentencia', 'cancelacion', 'inscripcionPropiedad', 'asignadoA')
                                            ->when(isset($request['tipo_documento']), function($q) use($request){
                                                $q->where('tipo_documento', $request['tipo_documento']);
                                            })
                                            ->when(isset($request['autoridad_cargo']), function($q) use($request){
                                                $q->where('autoridad_cargo', $request['autoridad_cargo']);
                                            })
                                            ->when(isset($request['autoridad_nombre']), function($q) use($request){
                                                $q->where('autoridad_nombre', $request['autoridad_nombre']);
                                            })
                                            ->when(isset($request['fecha_emision']), function($q) use($request){
                                                $q->where('fecha_emision', $request['fecha_emision']);
                                            })
                                            ->when(isset($request['numero_documento']), function($q) use($request){
                                                $q->where('numero_documento', $request['numero_documento']);
                                            })
                                            ->when(isset($request['procedencia']), function($q) use($request){
                                                $q->where('procedencia', $request['procedencia']);
                                            })
                                            ->get();

                foreach ($movimientos as $movimiento) {

                    if($movimiento->inscripcionPropiedad && $movimiento->asignadoA->hasAllRoles(['Registrador Propiedad', 'Registrador Gravamen'])){

                        return $movimiento->usuario_asignado;

                    }elseif($movimiento->gravamen && $movimiento->asignadoA->hasAllRoles(['Registrador Propiedad', 'Registrador Gravamen'])){

                        return $movimiento->usuario_asignado;

                    }elseif($movimiento->gravamen){

                        if($movimiento->asignadoA->hasRole('Pase a folio')){

                            return $this->movimiento_service_interface->obtenerUsuarioAsignado($request);

                        }else{

                            return $movimiento->usuario_asignado;

                        }

                    }elseif($movimiento->vario){


                        if($movimiento->asignadoA->hasRole('Pase a folio')){

                            return $this->movimiento_service_interface->obtenerUsuarioAsignado($request);

                        }else{

                            return $movimiento->usuario_asignado;

                        }

                    }elseif($movimiento->cancelacion){

                        if($movimiento->asignadoA->hasRole('Pase a folio')){

                            return $this->movimiento_service_interface->obtenerUsuarioAsignado($request);

                        }else{

                            return $movimiento->usuario_asignado;

                        }

                    }elseif($movimiento->sentencia){

                        if($movimiento->asignadoA->hasRole('Pase a folio')){

                            return $this->movimiento_service_interface->obtenerUsuarioAsignado($request);

                        }else{

                            return $movimiento->usuario_asignado;

                        }

                    }elseif($movimiento->inscripcionPropiedad){

                        if($movimiento->asignadoA->hasRole('Pase a folio')){

                            return $this->movimiento_service_interface->obtenerUsuarioAsignado($request);

                        }else{

                            return $movimiento->usuario_asignado;

                        }

                    }

                }

            }

            return $this->movimiento_service_interface->obtenerUsuarioAsignado($request);

        }

        return null;

    }

    public function obtenerSupervisor(array $request):int
    {

        if($request['distrito'] == 2){

            $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor uruapan');
                                })
                                ->first();

            if($supervisor) return $supervisor->id;

        }

        return $this->movimiento_service_interface->obtenerSupervisorAsignado($request);

    }

    public function calcularFolio(array $request):int | null
    {

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

    public function calcularFolioPersonaMoral(array $request):int
    {

        return FolioRealPersona::where('folio', $request['folio_real_persona_moral'])->first()->ultimoFolio() + 1;

    }

    public function recalcularFechaEntrega(MovimientoRegistral $movimientoRegistral):void
    {

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

    public function buscarNuevoFolioReal(array $request, MovimientoRegistral $movimiento_registral):void
    {

        $folioReal = FolioReal::where('folio', $request['folio_real'])->first();

        if(!$folioReal) throw new GeneralException('El folio real no existe.');

        /* if($folioReal->estado != 'activo') throw new MovimientoRegistralServiceException('El folio real no esta activo.'); */

        $request['folio_real'] = $folioReal->id;
        $request['estado'] = 'nuevo';
        $request['tomo'] = null;
        $request['registro'] = null;
        $request['numero_propiedad'] = null;

        if(! $folioReal->movimientosRegistrales()->where('folio', 1)->first()){

            $request['folio'] = 1;
            $request['pase_a_folio'] = true;

        }else{

            $request['folio'] = $folioReal->ultimoFolio() + 1;

        }

        $this->actualizarMovimientoRegistral($request, $movimiento_registral);

    }

    public function actualizarMovimientoRegistral(array $request, MovimientoRegistral $movimiento_registral):void
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

            if(array_key_exists($field, $request)){

                $array[$field] = $request[$field];

            }

        }

        $array['solicitante'] = $request['nombre_solicitante'];

        $movimiento_registral->update($array);

    }

    public function revisarEncolamientoSinFolioInmobiliario(array $request):array
    {

        $movimientos_registrales = MovimientoRegistral::where('tomo', $request['tomo'])
                                                    ->where('registro', $request['registro'])
                                                    ->where('numero_propiedad', $request['numero_propiedad'])
                                                    ->where('distrito', $request['distrito'])
                                                    ->whereNull('folio_real')
                                                    ->get();

        if($movimientos_registrales->count()){

            if(! $movimientos_registrales->where('folio', 1)->first()){

                return [
                    'folio' => 1,
                    'estado' => 'nuevo',
                    'folio_real' => null,
                    'pase_a_folio' => true
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

            if($request['categoria_servicio'] == 'Certificaciones'){

                if(in_array($request['servicio'], ['DL13', 'DL14', 'DC90', 'DC91', 'DC92', 'DC93'])){

                    return [
                        'folio' => 1,
                        'estado' => 'nuevo',
                        'folio_real' => null
                    ];

                }elseif($this->requestTieneAntecedente($request) && in_array($request['servicio'], ['DL07', 'DL10'])){

                    return [
                        'folio' => 1,
                        'estado' => 'nuevo',
                        'folio_real' => null,
                        'pase_a_folio' => true
                    ];

                }elseif(! $this->requestTieneAntecedente($request) && $request['servicio'] == 'DL10'){

                    return [
                        'folio' => 1,
                        'estado' => 'nuevo',
                        'folio_real' => null,
                    ];
                }

            }if($request['categoria_servicio'] == 'Folio simplificado'){

                return [
                    'folio' => 1,
                    'estado' => 'no recibido',
                    'folio_real' => null,
                    'pase_a_folio' => false
                ];

            }else{

                return [
                    'folio' => 1,
                    'estado' => 'no recibido',
                    'folio_real' => null,
                    'pase_a_folio' => true
                ];

            }

        }


    }

    public function reacomodarFolios(FolioReal $folioReal):void
    {

        $total_movimientos = $folioReal->movimientosRegistrales->count();

        for ($i=1; $i <= $total_movimientos; $i++) {

            if(! $folioReal->movimientosRegistrales()->where('folio', $i)){

                $movimiento = MovimientoRegistral::where('folio_real', $folioReal->id)
                                                ->where('estado', 'nuevo')
                                                ->orderBy('folio')
                                                ->first();

                if(! $movimiento){

                    $movimiento = MovimientoRegistral::where('folio_real', $folioReal->id)
                                                ->whereIn('estado', ['carga_parcial', 'pase_folio', 'no recibido'])
                                                ->orderBy('folio')
                                                ->first();

                }

                if(! $movimiento) break;

                if($i == 1){

                    $movimiento->update(['folio' => $i, 'pase_a_folio' => true]);

                }else{

                    $movimiento->update(['folio' => $i]);

                }

            }

        }

    }

    public function reacomodarFoliosPrecalificacion(MovimientoRegistral $movimiento_registral):void
    {

        $movimientos = MovimientoRegistral::where('tomo', $movimiento_registral->tomo)
                                                ->where('registro', $movimiento_registral->registro)
                                                ->where('numero_propiedad', $movimiento_registral->numero_propiedad)
                                                ->where('distrito', $movimiento_registral->getRawOriginal('distrito'))
                                                ->where('folio', '>', $movimiento_registral->folio)
                                                ->get();

        foreach ($movimientos as $movimiento) {

            $movimiento->decrement('folio');

        }

    }

}

