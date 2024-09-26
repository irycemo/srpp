<?php

namespace App\Traits\Inscripciones;

use App\Models\Predio;
use App\Models\FolioReal;

trait FirmaElectronicaTrait{


    public function folioRealArray(FolioReal $folioReal):array
    {

        return [
            'id' => $folioReal->id,
            'estado' => $folioReal->estado,
            'folio' => $folioReal->folio,
            'antecedente' => $folioReal->antecedente,
            'tomo_antecedente' => $folioReal->tomo_antecedente,
            'tomo_antecedente_bis' => $folioReal->tomo_antecedente_bis,
            'registro_antecedente' => $folioReal->registro_antecedente,
            'registro_antecedente_bis' => $folioReal->registro_antecedente_bis,
            'numero_propiedad_antecedente' => $folioReal->numero_propiedad_antecedente,
            'distrito_antecedente' => $folioReal->distrito_antecedente,
            'seccion_antecedente' => $folioReal->seccion_antecedente,
            'tipo_documento' => $folioReal->tipo_documento,
            'numero_documento' => $folioReal->numero_documento,
            'autoridad_cargo' => $folioReal->autoridad_cargo,
            'autoridad_nombre' => $folioReal->autoridad_nombre,
            'autoridad_numero' => $folioReal->autoridad_numero,
            'fecha_emision' => $folioReal->fecha_emision,
            'fecha_inscripcion' => $folioReal->fecha_inscripcion,
            'procedencia' => $folioReal->procedencia,
            'acto_contenido_antecedente' => $folioReal->acto_contenido_antecedente,
            'observaciones_antecedente' => $folioReal->observaciones_antecedente,
        ];

    }

    public function predio(Predio $predio):array
    {

        $colindancias = [];

        foreach ($predio->colindancias as $colindancia) {

            $colindancias[] = [
                'viento' => $colindancia->viento,
                'longitud' => $colindancia->longitud,
                'descripcion' => $colindancia->descripcion,
            ];

        }

        $propietarios = [];

        foreach ($predio->propietarios() as $propietario) {

            $propietarios[] = [
                'nombre' => $propietario->persona->nombre,
                'ap_paterno' => $propietario->persona->ap_paterno,
                'ap_materno' => $propietario->persona->ap_materno,
                'razon_social' => $propietario->persona->razon_social,
                'porcentaje_propiedad' => $propietario->persona->porcentaje_propiedad,
                'procentaje_nuda' => $propietario->persona->procentaje_nuda,
                'porcentaje_usufructo' => $propietario->persona->porcentaje_usufructo,
            ];

        }

        return [
            'id' => $predio->id,
            'status' => $predio->status,
            'curt' => $predio->curt,
            'superficie_terreno' => $predio->superficie_terreno,
            'superficie_construccion' => $predio->superficie_construccion,
            'superficie_judicial' => $predio->superficie_judicial,
            'superficie_notarial' => $predio->superficie_notarial,
            'area_comun_terreno' => $predio->area_comun_terreno,
            'area_comun_construccion' => $predio->area_comun_construccion,
            'valor_terreno_comun' => $predio->valor_terreno_comun,
            'valor_construccion_comun' => $predio->valor_construccion_comun,
            'valor_total_terreno' => $predio->valor_total_terreno,
            'valor_total_construccion' => $predio->valor_total_construccion,
            'valor_catastral' => $predio->valor_catastral,
            'monto_transaccion' => $predio->monto_transaccion,
            'divisa' => $predio->divisa,
            'unidad_area' => $predio->unidad_area,
            'tipo_vialidad' => $predio->tipo_vialidad,
            'tipo_asentamiento' => $predio->tipo_asentamiento,
            'nombre_vialidad' => $predio->nombre_vialidad,
            'nombre_asentamiento' => $predio->nombre_asentamiento,
            'numero_exterior' => $predio->numero_exterior,
            'numero_exterior_2' => $predio->numero_exterior_2,
            'numero_adicional' => $predio->numero_adicional,
            'numero_adicional_2' => $predio->numero_adicional_2,
            'numero_interior' => $predio->numero_interior,
            'lote' => $predio->lote,
            'manzana' => $predio->manzana,
            'codigo_postal' => $predio->codigo_postal,
            'lote_fraccionador' => $predio->lote_fraccionador,
            'manzana_fraccionador' => $predio->manzana_fraccionador,
            'etapa_fraccionador' => $predio->etapa_fraccionador,
            'nombre_edificio' => $predio->nombre_edificio,
            'clave_edificio' => $predio->clave_edificio,
            'departamento_edificio' => $predio->departamento_edificio,
            'entre_vialidades' => $predio->entre_vialidades,
            'nombre_predio' => $predio->nombre_predio,
            'estado' => $predio->estado,
            'municipio' => $predio->municipio,
            'ciudad' => $predio->ciudad,
            'localidad' => $predio->localidad,
            'poblado' => $predio->poblado,
            'ejido' => $predio->ejido,
            'parcela' => $predio->parcela,
            'solar' => $predio->solar,
            'uso_suelo' => $predio->uso_suelo,
            'xutm' => $predio->xutm,
            'yutm' => $predio->yutm,
            'zutm' => $predio->zutm,
            'lon' => $predio->lon,
            'lat' => $predio->lat,
            'cc_estado' => $predio->cc_estado,
            'cc_region_catastral' => $predio->cc_region_catastral,
            'cc_municipio' => $predio->cc_municipio,
            'cc_zona_catastral' => $predio->cc_zona_catastral,
            'cc_sector' => $predio->cc_sector,
            'cc_manzana' => $predio->cc_manzana,
            'cc_predio' => $predio->cc_predio,
            'cc_edificio' => $predio->cc_edificio,
            'cc_departamento' => $predio->cc_departamento,
            'cp_localidad' => $predio->cp_localidad,
            'cp_oficina' => $predio->cp_oficina,
            'cp_tipo_predio' => $predio->cp_tipo_predio,
            'cp_registro' => $predio->cp_registro,
            'descripcion' => $predio->descripcion,
            'observaciones' => $predio->observaciones,
            'observaciones' => $predio->observaciones,
            'colindancias' => $colindancias,
            'propietarios' => $propietarios
        ];

    }

}
