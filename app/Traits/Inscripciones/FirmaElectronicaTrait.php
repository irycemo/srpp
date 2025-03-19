<?php

namespace App\Traits\Inscripciones;

use Carbon\Carbon;
use App\Models\Vario;
use App\Models\Predio;
use App\Traits\QrTrait;
use App\Models\Gravamen;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\Sentencia;
use App\Models\Cancelacion;
use App\Models\Fideicomiso;
use App\Models\ReformaMoral;
use App\Models\Certificacion;
use App\Models\FirmaElectronica;
use App\Models\FolioRealPersona;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Storage;

trait FirmaElectronicaTrait{

    use QrTrait;

    public function folioReal(FolioReal $folioReal):object
    {

        $folioReal->load(
            'cancelaciones.movimientoRegistral',
            'cancelaciones.gravamenCancelado',
            'gravamenes.movimientoRegistral',
            'gravamenes.deudores',
            'gravamenes.acreedores'
        );

        $gravamenes = collect();

        foreach ($folioReal->gravamenes as $gravamen) {

            if($gravamen->movimientoRegistral->estado != 'pase_folio') continue;

            $item = $this->gravamen($gravamen);

            $gravamenes->push($item);

        }

        $cancelaciones = collect();

        foreach ($folioReal->cancelaciones as $cancelacion) {

            if($cancelacion->movimientoRegistral->estado != 'pase_folio') continue;

            $item = $this->cancelacion($cancelacion);

            $cancelaciones->push($item);

        }

        $sentencias = collect();

        foreach ($folioReal->sentencias as $sentencia) {

            $sentencia->load('movimientoRegistral');

            if($sentencia->movimientoRegistral->estado != 'pase_folio') continue;

            $item = $this->sentencia($sentencia);

            $sentencias->push($item);

        }

        $varios = collect();

        foreach ($folioReal->varios as $vario) {

            $vario->load('movimientoRegistral');

            if($vario->movimientoRegistral->estado != 'pase_folio') continue;

            $item = $this->vario($vario);

            $varios->push($item);

        }

        $antecedentes = collect();

        $folioReal->load('antecedentes.folioRealAntecedente');

        foreach ($folioReal->antecedentes as $antecedente) {

            $item = (object)[];

            $item->folio_real = $antecedente->folioRealAntecedente?->folio;
            $item->tomo_antecedente = $antecedente->tomo_antecedente;
            $item->registro_antecedente = $antecedente->registro_antecedente;
            $item->numero_propiedad_antecedente = $antecedente->numero_propiedad_antecedente;
            $item->distrito_antecedente = $antecedente->distrito_antecedente;
            $item->seccion_antecedente = $antecedente->seccion_antecedente;

            $antecedentes->push($item);

        }

        $escritura = (object)[];

        if($folioReal->predio->escritura){

            $escritura->numero = $folioReal->predio->escritura->numero;
            $escritura->fecha_inscripcion = Carbon::parse($folioReal->predio->escritura->fecha_inscripcion)->format('d/m/Y');
            $escritura->fecha_escritura = Carbon::parse($folioReal->predio->escritura->fecha_escritura)->format('d/m/Y');
            $escritura->numero_hojas = $folioReal->predio->escritura->numero_hojas;
            $escritura->numero_paginas = $folioReal->predio->escritura->numero_paginas;
            $escritura->notaria = $folioReal->predio->escritura->notaria;
            $escritura->nombre_notario = $folioReal->predio->escritura->nombre_notario;
            $escritura->estado_notario = $folioReal->predio->escritura->estado_notario;
            $escritura->comentario = $folioReal->predio->escritura->comentario;
            $escritura->acto_contenido_antecedente = $folioReal->predio->escritura->acto_contenido_antecedente;
            $escritura->comentario = $folioReal->predio->escritura->comentario;

        }

        $object = (object)[];

        if($folioReal->folioRealAntecedente){

            $object->antecedente = $folioReal->folioRealAntecedente->folio;

        }

        $object->id = $folioReal->id;
        $object->matriz = $folioReal->matriz;
        $object->estado = $folioReal->estado;
        $object->folio = $folioReal->folio;
        $object->tomo_antecedente = $folioReal->tomo_antecedente;
        $object->tomo_antecedente_bis = $folioReal->tomo_antecedente_bis;
        $object->registro_antecedente = $folioReal->registro_antecedente;
        $object->registro_antecedente_bis = $folioReal->registro_antecedente_bis;
        $object->numero_propiedad_antecedente = $folioReal->numero_propiedad_antecedente;
        $object->distrito_antecedente = $folioReal->distrito_antecedente;
        $object->seccion_antecedente = $folioReal->seccion_antecedente;
        $object->tipo_documento = $folioReal->tipo_documento;
        $object->numero_documento = $folioReal->numero_documento;
        $object->autoridad_cargo = $folioReal->autoridad_cargo;
        $object->autoridad_nombre = $folioReal->autoridad_nombre;
        $object->autoridad_numero = $folioReal->autoridad_numero;
        $object->fecha_emision = Carbon::parse($folioReal->fecha_emision)->format('d/m/Y');
        $object->fecha_inscripcion = Carbon::parse($folioReal->fecha_inscripcion)->format('d/m/Y');
        $object->procedencia = $folioReal->procedencia;
        $object->acto_contenido_antecedente = $folioReal->acto_contenido_antecedente;
        $object->observaciones_antecedente = $folioReal->observaciones_antecedente;
        $object->movimientosRegistrales = $folioReal->movimientosRegistrales->count();
        $object->gravamenes = $gravamenes;
        $object->antecedentes = $antecedentes;
        $object->escritura = $escritura;
        $object->cancelaciones = $cancelaciones;
        $object->sentencias = $sentencias;
        $object->varios = $varios;
        $object->asignado_por = $folioReal->asignado_por;

        return $object;

    }

    public function predio(Predio $predio):object
    {

        $colindancias = collect();

        $predio->load('colindancias');

        foreach ($predio->colindancias as $colindancia) {

            $item = (object)[];

            $item->viento = $colindancia->viento;
            $item->longitud = $colindancia->longitud_formateada;
            $item->descripcion = $colindancia->descripcion;

            $colindancias->push($item);

        }

        $propietarios = collect();

        foreach ($predio->propietarios() as $propietario) {

            $item = (object)[];

            $item->nombre = $propietario->persona->nombre;
            $item->ap_paterno = $propietario->persona->ap_paterno;
            $item->ap_materno = $propietario->persona->ap_materno;
            $item->multiple_nombre = $propietario->persona->multiple_nombre;
            $item->razon_social = $propietario->persona->razon_social;
            $item->porcentaje_propiedad = $propietario->porcentaje_propiedad_formateada;
            $item->porcentaje_nuda = $propietario->porcentaje_nuda_formateada;
            $item->porcentaje_usufructo = $propietario->porcentaje_usufructo_formateada;

            $propietarios->push($item);

        }

        $object = (object)[];

        $object->id = $predio->id;
        $object->status = $predio->status;
        $object->curt = $predio->curt;
        $object->superficie_construccion = $predio->superficie_construccion_formateada;
        $object->area_comun_terreno = $predio->area_comun_terreno;
        $object->area_comun_construccion = $predio->area_comun_construccion;
        $object->valor_terreno_comun = $predio->valor_terreno_comun;
        $object->valor_construccion_comun = $predio->valor_construccion_comun;
        $object->valor_total_terreno = $predio->valor_total_terreno;
        $object->valor_total_construccion = $predio->valor_total_construccion;
        $object->valor_catastral = $predio->valor_catastral;
        $object->monto_transaccion = $predio->monto_transaccion;
        $object->divisa = $predio->divisa;
        $object->unidad_area = $predio->unidad_area;
        $object->tipo_vialidad = $predio->tipo_vialidad;
        $object->tipo_asentamiento = $predio->tipo_asentamiento;
        $object->nombre_vialidad = $predio->nombre_vialidad;
        $object->nombre_asentamiento = $predio->nombre_asentamiento;
        $object->numero_exterior = $predio->numero_exterior;
        $object->numero_exterior_2 = $predio->numero_exterior_2;
        $object->numero_adicional = $predio->numero_adicional;
        $object->numero_adicional_2 = $predio->numero_adicional_2;
        $object->numero_interior = $predio->numero_interior;
        $object->lote = $predio->lote;
        $object->manzana = $predio->manzana;
        $object->codigo_postal = $predio->codigo_postal;
        $object->lote_fraccionador = $predio->lote_fraccionador;
        $object->manzana_fraccionador = $predio->manzana_fraccionador;
        $object->etapa_fraccionador = $predio->etapa_fraccionador;
        $object->nombre_edificio = $predio->nombre_edificio;
        $object->clave_edificio = $predio->clave_edificio;
        $object->departamento_edificio = $predio->departamento_edificio;
        $object->entre_vialidades = $predio->entre_vialidades;
        $object->nombre_predio = $predio->nombre_predio;
        $object->estado = $predio->estado;
        $object->municipio = $predio->municipio;
        $object->ciudad = $predio->ciudad;
        $object->localidad = $predio->localidad;
        $object->poblado = $predio->poblado;
        $object->ejido = $predio->ejido;
        $object->zona_ubicacion = $predio->zona_ubicacion;
        $object->parcela = $predio->parcela;
        $object->solar = $predio->solar;
        $object->uso_suelo = $predio->uso_suelo;
        $object->xutm = $predio->xutm;
        $object->yutm = $predio->yutm;
        $object->zutm = $predio->zutm;
        $object->lon = $predio->lon;
        $object->lat = $predio->lat;
        $object->cc_estado = $predio->cc_estado;
        $object->cc_region_catastral = $predio->cc_region_catastral;
        $object->cc_municipio = $predio->cc_municipio;
        $object->cc_zona_catastral = $predio->cc_zona_catastral;
        $object->cc_sector = $predio->cc_sector;
        $object->cc_manzana = $predio->cc_manzana;
        $object->cc_predio = $predio->cc_predio;
        $object->cc_edificio = $predio->cc_edificio;
        $object->cc_departamento = $predio->cc_departamento;
        $object->cp_localidad = $predio->cp_localidad;
        $object->cp_oficina = $predio->cp_oficina;
        $object->cp_tipo_predio = $predio->cp_tipo_predio;
        $object->cp_registro = $predio->cp_registro;
        $object->descripcion = $predio->descripcion;
        $object->observaciones = $predio->observaciones;
        $object->colindancias = $colindancias;
        $object->propietarios = $propietarios;
        $object->superficie_terreno = $predio->superficie_terreno_formateada;
        $object->superficie_judicial = $predio->superficie_judicial_formateada;
        $object->superficie_notarial = $predio->superficie_notarial_formateada;

        return $object;

    }

    public function movimientoRegistral(MovimientoRegistral $movimientoRegistral):object
    {

        $certificacion = null;

        if($movimientoRegistral->certificacion){

            $certificacion = $this->certificacion($movimientoRegistral->certificacion);

        }

        $propiedad = null;

        if($movimientoRegistral->inscripcionPropiedad){

            $propiedad = $this->propiedad($movimientoRegistral->inscripcionPropiedad);

        }

        $gravamen = null;

        if($movimientoRegistral->gravamen){

            $gravamen = $this->gravamen($movimientoRegistral->gravamen);

        }

        $sentencia = null;

        if($movimientoRegistral->sentencia){

            $sentencia = $this->sentencia($movimientoRegistral->sentencia);

        }

        $vario = null;

        if($movimientoRegistral->vario){

            $vario = $this->vario($movimientoRegistral->vario);

        }

        $object = (object)[];

        $object->id = $movimientoRegistral->id;
        $object->estado = $movimientoRegistral->estado;
        $object->folio_real = $movimientoRegistral->folioReal->folio;
        $object->folio = $movimientoRegistral->folio;
        $object->año = $movimientoRegistral->año;
        $object->tramite = $movimientoRegistral->tramite;
        $object->usuario = $movimientoRegistral->usuario;
        $object->monto = $movimientoRegistral->monto;
        $object->tipo_servicio = $movimientoRegistral->tipo_servicio;
        $object->solicitante = $movimientoRegistral->solicitante;
        $object->tomo_gravamen = $movimientoRegistral->tomo_gravamen;
        $object->registro_gravamen = $movimientoRegistral->registro_gravamen;
        $object->registro_gravamen = $movimientoRegistral->registro_gravamen;
        $object->certificacion = $certificacion;
        $object->propiedad = $propiedad;
        $object->gravamen = $gravamen;
        $object->sentencia = $sentencia;
        $object->vario = $vario;

        return $object;

    }

    public function certificacion(Certificacion $certificacion):object
    {

        $object = (object)[];

        $object->id = $certificacion->id;
        $object->servicio = $certificacion->servicio;
        $object->numero_paginas = $certificacion->numero_paginas;
        $object->folio_carpeta_copias = $certificacion->folio_carpeta_copias;
        $object->observaciones = $certificacion->observaciones;
        $object->observaciones_certificado = $certificacion->observaciones_certificado;
        $object->movimiento_folio = $certificacion->movimientoRegistral->folio;
        $object->numero_documento = $certificacion->movimientoRegistral->numero_documento;
        $object->autoridad_cargo = $certificacion->movimientoRegistral->autoridad_cargo;
        $object->autoridad_nombre = $certificacion->movimientoRegistral->autoridad_nombre;
        $object->autoridad_numero = $certificacion->movimientoRegistral->autoridad_numero;
        $object->tipo_documento = $certificacion->movimientoRegistral->tipo_documento;
        $object->fecha_emision = Carbon::parse($certificacion->movimientoRegistral->fecha_emision)->format('d/m/Y');
        $object->fecha_inscripcion = Carbon::parse($certificacion->movimientoRegistral->fecha_inscripcion)->format('d/m/Y');
        $object->fecha_prelacion = Carbon::parse($certificacion->movimientoRegistral->fecha_prelacion)->format('d/m/Y');
        $object->procedencia = $certificacion->movimientoRegistral->procedencia;

        return $object;

    }

    public function propiedad(Propiedad $propiedad):object
    {

        $propietarios = collect();

        foreach ($propiedad->propietarios() as $propietario) {

            $item = (object)[];

            $item->nombre = $propietario->persona->nombre;
            $item->ap_paterno = $propietario->persona->ap_paterno;
            $item->ap_materno = $propietario->persona->ap_materno;
            $item->razon_social = $propietario->persona->razon_social;
            $item->multiple_nombre = $propietario->persona->multiple_nombre;
            $item->porcentaje_propiedad = $propietario->porcentaje_propiedad_formateada;
            $item->porcentaje_nuda = $propietario->porcentaje_nuda_formateada;
            $item->porcentaje_usufructo = $propietario->porcentaje_usufructo_formateada;

            $propietario->load('representadoPor.persona');

            $item->representado_por = null;

            foreach($propietario->representadoPor as $representante){

                $item->representado_por = $item->representado_por . ' / ' .
                                            $representante->persona->nombre . ' ' .
                                            $representante->persona->ap_paterno . ' ' .
                                            $representante->persona->ap_materno . ' ' .
                                            $representante->persona->multiple_nombre . ' ' .
                                            $representante->persona->razon_social ;

            }

            $propietarios->push($item);

        }

        $transmitentes = collect();

        foreach ($propiedad->transmitentes() as $transmitente) {

            $item = (object)[];

            $item->nombre = $transmitente->persona->nombre;
            $item->ap_paterno = $transmitente->persona->ap_paterno;
            $item->ap_materno = $transmitente->persona->ap_materno;
            $item->razon_social = $transmitente->persona->razon_social;
            $item->multiple_nombre = $transmitente->persona->multiple_nombre;
            $item->porcentaje_propiedad = $transmitente->porcentaje_propiedad_formateada;
            $item->porcentaje_nuda = $transmitente->porcentaje_nuda_formateada;
            $item->porcentaje_usufructo = $transmitente->porcentaje_usufructo_formateada;

            $item->representado_por = null;

            foreach($propietario->representadoPor as $representante){

                $item->representado_por = $item->representado_por . ' / ' .
                                            $representante->persona->nombre . ' ' .
                                            $representante->persona->ap_paterno . ' ' .
                                            $representante->persona->ap_materno . ' ' .
                                            $representante->persona->multiple_nombre . ' ' .
                                            $representante->persona->razon_social ;

            }

            $transmitentes->push($item);

        }

        $object = (object)[];

        $object->id = $propiedad->id;
        $object->servicio = $propiedad->servicio;
        $object->acto_contenido = $propiedad->acto_contenido;
        $object->numero_inmuebles = $propiedad->numero_inmuebles;
        $object->descripcion_acto = $propiedad->descripcion_acto;
        $object->fecha_inscripcion = Carbon::parse($propiedad->fecha_inscripcion)->format('d/m/Y');
        $object->propietarios = $propietarios;
        $object->transmitentes = $transmitentes;
        $object->movimiento_folio = $propiedad->movimientoRegistral->folio;
        $object->numero_documento = $propiedad->movimientoRegistral->numero_documento;
        $object->autoridad_cargo = $propiedad->movimientoRegistral->autoridad_cargo;
        $object->autoridad_nombre = $propiedad->movimientoRegistral->autoridad_nombre;
        $object->tipo_documento = $propiedad->movimientoRegistral->tipo_documento;
        $object->autoridad_numero = $propiedad->movimientoRegistral->autoridad_numero;
        $object->fecha_emision = Carbon::parse($propiedad->movimientoRegistral->fecha_emision)->format('d/m/Y');
        $object->fecha_inscripcion = Carbon::parse($propiedad->movimientoRegistral->fecha_inscripcion)->format('d/m/Y');
        $object->fecha_prelacion = Carbon::parse($propiedad->movimientoRegistral->fecha_prelacion)->format('d/m/Y');
        $object->procedencia = $propiedad->movimientoRegistral->procedencia;

        return $object;

    }

    public function gravamen(Gravamen $gravamen):object
    {

        $deudores = collect();

        foreach ($gravamen->deudores as $deudor) {

            $item = (object)[];

            $item->tipo_deudor = $deudor->tipo_deudor;
            $item->nombre = $deudor->persona->nombre;
            $item->ap_paterno = $deudor->persona->ap_paterno;
            $item->ap_materno = $deudor->persona->ap_materno;
            $item->razon_social = $deudor->persona->razon_social;

            $deudores->push($item);

        }

        $acreedores = collect();

        foreach ($gravamen->acreedores as $acreedor) {

            $item = (object)[];

            $item->nombre = $acreedor->persona->nombre;
            $item->ap_paterno = $acreedor->persona->ap_paterno;
            $item->ap_materno = $acreedor->persona->ap_materno;
            $item->razon_social = $acreedor->persona->razon_social;

            $acreedores->push($item);

        }

        $object = (object)[];

        $object->id = $gravamen->id;
        $object->servicio = $gravamen->servicio;
        $object->acto_contenido = $gravamen->acto_contenido;
        $object->estado = $gravamen->estado;
        $object->expediente = $gravamen->expediente;
        $object->tipo = $gravamen->tipo;
        $object->valor_gravamen = $gravamen->valor_gravamen;
        $object->divisa = $gravamen->divisa;
        $object->fecha_inscripcion = Carbon::parse($gravamen->fecha_inscripcion)->format('d/m/Y');
        $object->fecha_prelacion = Carbon::parse($gravamen->movimientoRegistral->fecha_prelacion)->format('d/m/Y');
        $object->observaciones = $gravamen->observaciones;
        $object->deudores = $deudores;
        $object->acreedores = $acreedores;
        $object->movimiento_folio = $gravamen->movimientoRegistral->folio;
        $object->tomo = $gravamen->movimientoRegistral->tomo_gravamen;
        $object->registro = $gravamen->movimientoRegistral->registro_gravamen;
        $object->distrito = $gravamen->movimientoRegistral->distrito;
        $object->numero_documento = $gravamen->movimientoRegistral->numero_documento;
        $object->autoridad_cargo = $gravamen->movimientoRegistral->autoridad_cargo;
        $object->tipo_documento = $gravamen->movimientoRegistral->tipo_documento;
        $object->autoridad_nombre = $gravamen->movimientoRegistral->autoridad_nombre;
        $object->autoridad_numero = $gravamen->movimientoRegistral->autoridad_numero;
        $object->fecha_emision = Carbon::parse($gravamen->movimientoRegistral->fecha_emision)->format('d/m/Y');
        $object->procedencia = $gravamen->movimientoRegistral->procedencia;

        return $object;

    }

    public function sentencia(Sentencia $sentencia):object
    {

        $object = (object)[];

        $movimientoCancelado = null;

        if($sentencia->acto_contenido == 'CANCELACIÓN DE INSCRIPCIÓN'){

            $movimientoCancelado = MovimientoRegistral::where('movimiento_padre', $sentencia->movimientoRegistral->id)->first();

        }

        $object->id = $sentencia->id;
        $object->servicio = $sentencia->servicio;
        $object->acto_contenido = $sentencia->acto_contenido;
        $object->estado = $sentencia->estado;
        $object->descripcion = $sentencia->descripcion;
        $object->fecha_inscripcion = Carbon::parse($sentencia->fecha_inscripcion)->format('d/m/Y');
        $object->fecha_prelacion = Carbon::parse($sentencia->movimientoRegistral->fecha_prelacion)->format('d/m/Y');
        $object->hojas = $sentencia->hojas;
        $object->tipo = $sentencia->tipo;
        $object->expediente = $sentencia->expediente;
        $object->tomo = $sentencia->tomo;
        $object->registro = $sentencia->registro;
        $object->movimientoCancelado = $movimientoCancelado;
        $object->movimiento_folio = $sentencia->movimientoRegistral->folio;
        $object->numero_documento = $sentencia->movimientoRegistral->numero_documento;
        $object->autoridad_cargo = $sentencia->movimientoRegistral->autoridad_cargo;
        $object->autoridad_nombre = $sentencia->movimientoRegistral->autoridad_nombre;
        $object->tipo_documento = $sentencia->movimientoRegistral->tipo_documento;
        $object->autoridad_numero = $sentencia->movimientoRegistral->autoridad_numero;
        $object->fecha_emision = Carbon::parse($sentencia->movimientoRegistral->fecha_emision)->format('d/m/Y');
        $object->procedencia = $sentencia->movimientoRegistral->procedencia;

        return $object;

    }

    public function vario(Vario $vario):object
    {

        $folioPersonaMoral = (object)[];

        $vario->load('movimientoRegistral');

        $object = (object)[];

        $object->id = $vario->id;
        $object->servicio = $vario->servicio;
        $object->acto_contenido = $vario->acto_contenido;
        $object->estado = $vario->estado;
        $object->descripcion = $vario->descripcion;
        $object->tomo = $vario->movimientoRegistral->tomo;
        $object->registro = $vario->movimientoRegistral->registro;
        $object->movimiento_folio = $vario->movimientoRegistral->folio;
        $object->folioPersonaMoral = $folioPersonaMoral;
        $object->tipo_documento = $vario->movimientoRegistral->tipo_documento;
        $object->numero_documento = $vario->movimientoRegistral->numero_documento;
        $object->autoridad_cargo = $vario->movimientoRegistral->autoridad_cargo;
        $object->autoridad_nombre = $vario->movimientoRegistral->autoridad_nombre;
        $object->autoridad_numero = $vario->movimientoRegistral->autoridad_numero;
        $object->fecha_emision = Carbon::parse($vario->movimientoRegistral->fecha_emision)->format('d/m/Y');
        $object->fecha_inscripcion = Carbon::parse($vario->fecha_inscripcion)->format('d/m/Y');
        $object->fecha_prelacion = Carbon::parse($vario->movimientoRegistral->fecha_prelacion)->format('d/m/Y');
        $object->procedencia = $vario->movimientoRegistral->procedencia;

        return $object;

    }

    public function cancelacion(Cancelacion $cancelacion):object
    {

        $cancelacion->load('gravamenCancelado.gravamen');

        $object = (object)[];

        $object->id = $cancelacion->id;
        $object->servicio = $cancelacion->servicio;
        $object->acto_contenido = $cancelacion->acto_contenido;
        $object->tipo = $cancelacion->tipo;
        $object->estado = $cancelacion->estado;
        $object->observaciones = $cancelacion->observaciones;
        $object->fecha_inscripcion = Carbon::parse($cancelacion->fecha_inscripcion)->format('d/m/Y');
        $object->movimiento_folio = $cancelacion->movimientoRegistral->folio;
        $object->numero_documento = $cancelacion->movimientoRegistral->numero_documento;
        $object->tipo_documento = $cancelacion->movimientoRegistral->tipo_documento;
        $object->autoridad_cargo = $cancelacion->movimientoRegistral->autoridad_cargo;
        $object->autoridad_nombre = $cancelacion->movimientoRegistral->autoridad_nombre;
        $object->autoridad_numero = $cancelacion->movimientoRegistral->autoridad_numero;
        $object->fecha_emision = Carbon::parse($cancelacion->movimientoRegistral->fecha_emision)->format('d/m/Y');
        $object->fecha_prelacion = Carbon::parse($cancelacion->movimientoRegistral->fecha_prelacion)->format('d/m/Y');
        $object->procedencia = $cancelacion->movimientoRegistral->procedencia;
        $object->gravamen = $this->gravamen($cancelacion->gravamenCancelado->gravamen);

        return $object;

    }

    public function subdivision(Propiedad $subdivision):object
    {

        $folios_generados = FolioReal::where('antecedente', $subdivision->movimientoRegistral->folio_real)->pluck('folio');

        $object = (object)[];

        $object->id = $subdivision->id;
        $object->servicio = $subdivision->servicio;
        $object->acto_contenido = $subdivision->acto_contenido;
        $object->descripcion_acto = $subdivision->descripcion_acto;
        $object->observaciones = $subdivision->observaciones;
        $object->fecha_inscripcion = Carbon::parse($subdivision->fecha_inscripcion)->format('d/m/Y');
        $object->movimiento_folio = $subdivision->movimientoRegistral->folio;
        $object->numero_documento = $subdivision->movimientoRegistral->numero_documento;
        $object->tipo_documento = $subdivision->movimientoRegistral->tipo_documento;
        $object->autoridad_cargo = $subdivision->movimientoRegistral->autoridad_cargo;
        $object->autoridad_nombre = $subdivision->movimientoRegistral->autoridad_nombre;
        $object->autoridad_numero = $subdivision->movimientoRegistral->autoridad_numero;
        $object->fecha_emision = Carbon::parse($subdivision->movimientoRegistral->fecha_emision)->format('d/m/Y');
        $object->fecha_prelacion = Carbon::parse($subdivision->movimientoRegistral->fecha_prelacion)->format('d/m/Y');
        $object->procedencia = $subdivision->movimientoRegistral->procedencia;
        $object->folios_generados = $folios_generados;

        return $object;

    }

    public function folioRealPersonaMoral(FolioRealPersona $folioReal){

        $folioReal->load('actores.persona', 'objetos', 'reformas');

        $escritura = (object)[];

        if($folioReal->escritura){

            $escritura->numero = $folioReal->escritura->numero;
            $escritura->fecha_inscripcion = Carbon::parse($folioReal->escritura->fecha_inscripcion)->format('d/m/Y');
            $escritura->fecha_escritura = Carbon::parse($folioReal->escritura->fecha_escritura)->format('d/m/Y');
            $escritura->numero_hojas = $folioReal->escritura->numero_hojas;
            $escritura->numero_paginas = $folioReal->escritura->numero_paginas;
            $escritura->notaria = $folioReal->escritura->notaria;
            $escritura->nombre_notario = $folioReal->escritura->nombre_notario;
            $escritura->estado_notario = $folioReal->escritura->estado_notario;
            $escritura->comentario = $folioReal->escritura->comentario;
            $escritura->acto_contenido_antecedente = $folioReal->escritura->acto_contenido_antecedente;
            $escritura->comentario = $folioReal->escritura->comentario;

        }

        $participantes = collect();

        foreach ($folioReal->actores as $actor) {

            $item = (object)[];

            $item->nombre = $actor->persona->nombre;
            $item->ap_paterno = $actor->persona->ap_paterno;
            $item->ap_materno = $actor->persona->ap_materno;
            $item->multiple_nombre = $actor->persona->multiple_nombre;
            $item->razon_social = $actor->persona->razon_social;
            $item->tipo_socio = $actor->tipo_socio;

            $participantes->push($item);

        }

        $reformas = collect();

        foreach ($folioReal->reformas as $reforma) {

            if($reforma->acto_contenido != 'ACTA DE ASAMBLEA') continue;

            $reforma->load('movimientoRegistral');

            $item = (object)[];

            $item->fecha_inscripcion = Carbon::parse($reforma->fecha_inscripcion)->format('d/m/Y');
            $item->fecha_constitucion = Carbon::parse($reforma->fecha_protocolizacion)->format('d/m/Y');
            $item->acto_contenido = $reforma->acto_contenido;
            $item->movimiento_folio = $reforma->movimientoRegistral->folio;
            $item->tomo = $reforma->movimientoRegistral->tomo;
            $item->registro = $reforma->movimientoRegistral->registro;
            $item->numero_documento = $reforma->movimientoRegistral->numero_documento;
            $item->autoridad_numero = $reforma->movimientoRegistral->autoridad_numero;
            $item->descripcion = $reforma->descripcion;

            $reformas->push($item);

        }

        $object = (object)[];

        $object->id = $folioReal->id;
        $object->folio = $folioReal->folio;
        $object->denominacion = $folioReal->denominacion;
        $object->domicilio = $folioReal->domicilio;
        $object->fecha_inscripcion = Carbon::parse($folioReal->fecha_inscripcion)->format('d/m/Y');
        $object->fecha_constitucion = Carbon::parse($folioReal->fecha_constitucion)->format('d/m/Y');
        $object->distrito = $folioReal->distrito;
        $object->duracion = $folioReal->duracion;
        $object->capital = $folioReal->capital;
        $object->tomo = $folioReal->tomo_antecedente;
        $object->registro = $folioReal->registro_antecedente;
        $object->tipo = $folioReal->tipo;

        $object->observaciones = $folioReal->observaciones;
        $object->asignado_por = $folioReal->asignado_por;
        $object->escritura = $escritura;
        $object->objeto = $folioReal->objetos()->where('estado', 'activo')->first()->objeto;
        $object->participantes = $participantes;
        $object->reformas = $reformas;

        return $object;

    }

    public function reforma(ReformaMoral $reforma){

        $object = (object)[];

        $object->fecha_inscripcion = Carbon::parse($reforma->fecha_inscripcion)->format('d/m/Y');
        $object->fecha_constitucion = Carbon::parse($reforma->fecha_protocolizacion)->format('d/m/Y');
        $object->acto_contenido = $reforma->acto_contenido;
        $object->descripcion = $reforma->descripcion;
        $object->folioReal = $this->folioRealPersonaMoral($reforma->movimientoRegistral->folioRealPersona);
        $object->fecha_prelacion = Carbon::parse($reforma->movimientoRegistral->fecha_prelacion)->format('d/m/Y');

        return $object;

    }

    public function fideicomiso(Fideicomiso $fideicomiso){

        $fideicomiso->load('actores.persona');

        $actores = collect();

        foreach ($fideicomiso->actores as $actor) {

            $item = (object)[];

            $item->tipo = $actor->tipo_actor;
            $item->nombre = $actor->persona->nombre;
            $item->ap_paterno = $actor->persona->ap_paterno;
            $item->ap_materno = $actor->persona->ap_materno;
            $item->razon_social = $actor->persona->razon_social;

            $actores->push($item);

        }

        $object = (object)[];

        $object->id = $fideicomiso->id;
        $object->tipo = $fideicomiso->tipo;
        $object->objeto = $fideicomiso->objeto;
        $object->fecha_vencimiento = Carbon::parse($fideicomiso->fecha_vencimiento)->format('d/m/Y');
        $object->fecha_inscripcion = Carbon::parse($fideicomiso->fecha_inscripcion)->format('d/m/Y');
        $object->numero_documento = $fideicomiso->movimientoRegistral->numero_documento;
        $object->autoridad_cargo = $fideicomiso->movimientoRegistral->autoridad_cargo;
        $object->tipo_documento = $fideicomiso->movimientoRegistral->tipo_documento;
        $object->autoridad_nombre = $fideicomiso->movimientoRegistral->autoridad_nombre;
        $object->autoridad_numero = $fideicomiso->movimientoRegistral->autoridad_numero;
        $object->fecha_emision = Carbon::parse($fideicomiso->movimientoRegistral->fecha_emision)->format('d/m/Y');
        $object->procedencia = $fideicomiso->movimientoRegistral->procedencia;
        $object->fecha_prelacion = Carbon::parse($fideicomiso->movimientoRegistral->fecha_prelacion)->format('d/m/Y');
        $object->actores = $actores;

        return $object;

    }

    public function resetCaratula($id){

        $movimiento = MovimientoRegistral::with('archivos')->find($id);

        $firmas = FirmaElectronica::where('movimiento_registral_id', $id)->get();

        foreach ($firmas as $firma) {

            $firma->update(['estado' => 'cancelado']);

        }

        if($movimiento->archivos()->where('descripcion', 'caratula')->count()){

            foreach($movimiento->archivos as $archivo){

                if($archivo->descripcion == 'caratula'){

                    Storage::disk('caratulas')->delete($archivo->url);

                }

            }

            $movimiento->archivos()->where('descripcion', 'caratula')->first()->delete();

        }

    }

}
