<?php

namespace App\Traits\Inscripciones;

use App\Models\Vario;
use App\Models\Predio;
use App\Models\Gravamen;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\Sentencia;
use App\Models\Certificacion;
use App\Models\MovimientoRegistral;

trait FirmaElectronicaTrait{


    public function folioRealArray(FolioReal $folioReal):object
    {

        $gravamenes = collect();

        if($folioReal->movimientosRegistrales->count() > 1 && $folioReal->gravamenes->count() >= 1){

            foreach ($folioReal->gravamenes as $gravamen) {

                if($gravamen->movimientoRegistral->folio == 1) continue;

                $gravamen = $this->gravamen($gravamen);

                $gravamen->movimiento_folio = $gravamen->movimientoRegistral->folio;
                $gravamen->tomo = $gravamen->movimientoRegistral->tomo_gravamen;
                $gravamen->registro = $gravamen->movimientoRegistral->registro_gravamen;

                $gravamenes->push($gravamen);

            }

        }

        $object = (object)[];

        $object->id = $folioReal->id;
        $object->estado = $folioReal->estado;
        $object->folio = $folioReal->folio;
        $object->antecedente = $folioReal->antecedente;
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
        $object->fecha_emision = $folioReal->fecha_emision;
        $object->fecha_inscripcion = $folioReal->fecha_inscripcion;
        $object->procedencia = $folioReal->procedencia;
        $object->acto_contenido_antecedente = $folioReal->acto_contenido_antecedente;
        $object->observaciones_antecedente = $folioReal->observaciones_antecedente;
        $object->movimientosRegistrales = $folioReal->movimientosRegistrales->count();
        $object->gravamenes = $gravamenes;

        return $object;

    }

    public function predio(Predio $predio):object
    {

        $colindancias = collect();

        foreach ($predio->colindancias as $colindancia) {

            $item = (object)[];

            $item->viento = $colindancia->viento;
            $item->longitud = $colindancia->longitud;
            $item->descripcion = $colindancia->descripcion;

            $colindancias->push($item);

        }

        $propietarios = collect();

        foreach ($predio->propietarios() as $propietario) {

            $item = (object)[];

            $item->nombre = $propietario->persona->nombre;
            $item->ap_paterno = $propietario->persona->ap_paterno;
            $item->ap_materno = $propietario->persona->ap_materno;
            $item->razon_social = $propietario->persona->razon_social;
            $item->porcentaje_propiedad = $propietario->persona->porcentaje_propiedad;
            $item->procentaje_nuda = $propietario->persona->procentaje_nuda;
            $item->porcentaje_usufructo = $propietario->persona->porcentaje_usufructo;

            $propietarios->push($item);

        }

        $object = (object)[];

        $object->id = $predio->id;
        $object->status = $predio->status;
        $object->curt = $predio->curt;
        $object->superficie_terreno = $predio->superficie_terreno;
        $object->superficie_construccion = $predio->superficie_construccion;
        $object->superficie_judicial = $predio->superficie_judicial;
        $object->superficie_notarial = $predio->superficie_notarial;
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
        $object->observaciones = $predio->observaciones;
        $object->colindancias = $colindancias;
        $object->propietarios = $propietarios;

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

        return $object;

    }

    public function propiedad(Propiedad $propiedad):object
    {

        $object = (object)[];

        $object->id = $propiedad->id;
        $object->servicio = $propiedad->servicio;
        $object->acto_contenido = $propiedad->acto_contenido;
        $object->numero_inmuebles = $propiedad->numero_inmuebles;
        $object->descripcion_acto = $propiedad->descripcion_acto;
        $object->fecha_inscripcion = $propiedad->fecha_inscripcio;

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
        $object->tipo = $gravamen->tipo;
        $object->valor_gravamen = $gravamen->valor_gravamen;
        $object->divisa = $gravamen->divisa;
        $object->fecha_inscripcion = $gravamen->fecha_inscripcion;
        $object->observaciones = $gravamen->observaciones;
        $object->observaciones = $gravamen->observaciones;
        $object->deudores = $deudores;
        $object->acreedores = $acreedores;

        return $object;

    }

    public function sentencia(Sentencia $sentencia):object
    {

        $object = (object)[];

        $object->id = $sentencia->id;
        $object->servicio = $sentencia->servicio;
        $object->acto_contenido = $sentencia->acto_contenido;
        $object->estado = $sentencia->estado;
        $object->descripcion = $sentencia->descripcion;
        $object->fecha_inscripcion = $sentencia->fecha_inscripcion;

        return $object;

    }

    public function vario(Vario $vario):object
    {

        $object = (object)[];

        $object->id = $vario->id;
        $object->servicio = $vario->servicio;
        $object->acto_contenido = $vario->acto_contenido;
        $object->estado = $vario->estado;
        $object->descripcion = $vario->descripcion;
        $object->fecha_inscripcion = $vario->fecha_inscripcion;

        return $object;

    }

}
