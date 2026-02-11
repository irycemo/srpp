<?php

namespace App\Traits\Inscripciones;

use App\Exceptions\GeneralException;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\FolioReal;
use App\Models\FirmaElectronica;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Str;

trait RecuperarPredioTrait{

    public function obtenerMovimientoConFirmaElectronica(MovimientoRegistral $movimientoRegistral){

        if($movimientoRegistral->folio == 1){

            if(! $movimientoRegistral->folioReal->firmaElectronica) throw new GeneralException('No hay una firma electronica activa para recuperar la información.');

            $propietarios = $this->recuperarPropietarios($movimientoRegistral->folioReal->firmaElectronica);

            $this->restaurarPropietarios($movimientoRegistral->folioReal, $propietarios);

            $this->restaurarPredio($movimientoRegistral->folioReal->predio, $movimientoRegistral->folioReal->firmaElectronica);

        }else{

            $folio = $movimientoRegistral->folio - 1;

            $movimiento = $movimientoRegistral->folioReal->movimientosRegistrales()->where('folio', $folio)->first();


            if($movimiento?->firmaElectronica){

                $propietarios = $this->recuperarPropietarios($movimiento->firmaElectronica);

            }else{

                $propietarios = null;
            }

            while($propietarios == null){

                $folio = $folio - 1;

                if($folio < 1){

                    if(! $movimientoRegistral->folioReal->firmaElectronica) throw new GeneralException('No hay una firma electronica activa para recuperar la información.');

                    $propietarios = $this->recuperarPropietarios($movimientoRegistral->folioReal->firmaElectronica);

                    $this->restaurarPropietarios($movimientoRegistral->folioReal, $propietarios);

                    return;

                }

                $movimiento = $movimientoRegistral->folioReal->movimientosRegistrales()->where('folio', $folio)->first();

                if($movimiento->firmaElectronica){

                    $propietarios = $this->recuperarPropietarios($movimiento->firmaElectronica);

                }else{

                    $propietarios = null;

                }

            }

            $this->restaurarPropietarios($movimientoRegistral->folioReal, $propietarios);

            $this->restaurarPredio($movimientoRegistral->folioReal->predio, $movimiento->firmaElectronica);

        }

    }

    public function recuperarPropietarios(FirmaElectronica $firmaElectronica){

        $objeto = json_decode($firmaElectronica->cadena_original);

        return $objeto->predio->propietarios;

    }

    public function restaurarPropietarios(FolioReal $folioReal, $propietarios){

        foreach($folioReal->predio->propietarios() as $propietario) {

            $propietario->delete();

        }

        foreach($propietarios as $propietario) {

            $persona = Persona::firstOrCreate(
                [
                    'nombre' => $propietario->nombre,
                    'ap_paterno' => $propietario->ap_paterno,
                    'ap_materno' => $propietario->ap_materno,
                    'razon_social' => $propietario->razon_social,
                ],
                [
                    'nombre' => $propietario->nombre,
                    'ap_paterno' => $propietario->ap_paterno,
                    'ap_materno' => $propietario->ap_materno,
                    'razon_social' => $propietario->razon_social,
                ]
            );

            $folioReal->predio->actores()->create([
                'tipo_actor' => 'propietario',
                'persona_id' => $persona->id,
                'porcentaje_propiedad' => $propietario->porcentaje_propiedad,
                'porcentaje_nuda' => $propietario->porcentaje_nuda,
                'porcentaje_usufructo' => $propietario->porcentaje_usufructo,
            ]);

        }

    }

    public function restaurarPredio(Predio $predio, FirmaElectronica $firmaElectronica){

        $objeto = json_decode($firmaElectronica->cadena_original);

        if($objeto->predio->unidad_area == 'Hectareas'){

            $superficie_terreno = Str::replace('-' , '', $objeto->predio->superficie_terreno);
            $superficie_judicial = Str::replace('-' , '', $objeto->predio->superficie_judicial);
            $superficie_notarial = Str::replace('-' , '', $objeto->predio->superficie_notarial);

        }else{

            $superficie_terreno = null;
            $superficie_judicial = null;
            $superficie_notarial = null;

        }

        $predio->update([
            'superficie_terreno' => $superficie_terreno,
            'superficie_construccion' => $objeto->predio->superficie_construccion,
            'superficie_judicial' => $superficie_judicial,
            'superficie_notarial' => $superficie_notarial,
            'area_comun_terreno' => $objeto->predio->area_comun_terreno,
            'area_comun_construccion' => $objeto->predio->area_comun_construccion,
            'valor_terreno_comun' => $objeto->predio->valor_terreno_comun,
            'valor_construccion_comun' => $objeto->predio->valor_construccion_comun,
            'valor_total_terreno' => $objeto->predio->valor_total_terreno,
            'valor_total_construccion' => $objeto->predio->valor_total_construccion,
            'valor_catastral' => $objeto->predio->valor_catastral,
            'monto_transaccion' => $objeto->predio->monto_transaccion,
            'divisa' => $objeto->predio->divisa,
            'unidad_area' => $objeto->predio->unidad_area,
            'tipo_vialidad' => $objeto->predio->tipo_vialidad,
            'tipo_asentamiento' => $objeto->predio->tipo_asentamiento,
            'nombre_vialidad' => $objeto->predio->nombre_vialidad,
            'nombre_asentamiento' => $objeto->predio->nombre_asentamiento,
            'numero_exterior' => $objeto->predio->numero_exterior,
            'numero_exterior_2' => $objeto->predio->numero_exterior_2,
            'numero_adicional' => $objeto->predio->numero_adicional,
            'numero_adicional_2' => $objeto->predio->numero_adicional_2,
            'numero_interior' => $objeto->predio->numero_interior,
            'lote' => $objeto->predio->lote,
            'manzana' => $objeto->predio->manzana,
            'codigo_postal' => $objeto->predio->codigo_postal,
            'lote_fraccionador' => $objeto->predio->lote_fraccionador,
            'manzana_fraccionador' => $objeto->predio->manzana_fraccionador,
            'etapa_fraccionador' => $objeto->predio->etapa_fraccionador,
            'nombre_edificio' => $objeto->predio->nombre_edificio,
            'clave_edificio' => $objeto->predio->clave_edificio,
            'departamento_edificio' => $objeto->predio->departamento_edificio,
            'entre_vialidades' => $objeto->predio->entre_vialidades,
            'nombre_predio' => $objeto->predio->nombre_predio,
            'estado' => $objeto->predio->estado,
            'municipio' => $objeto->predio->municipio,
            'ciudad' => $objeto->predio->ciudad,
            'localidad' => $objeto->predio->localidad,
            'poblado' => $objeto->predio->poblado,
            'ejido' => $objeto->predio->ejido,
            'parcela' => $objeto->predio->parcela,
            'solar' => $objeto->predio->solar,
            'zona_ubicacion' => $objeto->predio->zona_ubicacion,
            'uso_suelo' => $objeto->predio->uso_suelo,
            'xutm' => $objeto->predio->xutm,
            'yutm' => $objeto->predio->yutm,
            'zutm' => $objeto->predio->zutm,
            'lon' => $objeto->predio->lon,
            'lat' => $objeto->predio->lat,
            'cc_estado' => $objeto->predio->cc_estado,
            'cc_region_catastral' => $objeto->predio->cc_region_catastral,
            'cc_municipio' => $objeto->predio->cc_municipio,
            'cc_zona_catastral' => $objeto->predio->cc_zona_catastral,
            'cc_sector' => $objeto->predio->cc_sector,
            'cc_manzana' => $objeto->predio->cc_manzana,
            'cc_predio' => $objeto->predio->cc_predio,
            'cc_edificio' => $objeto->predio->cc_edificio,
            'cc_departamento' => $objeto->predio->cc_departamento,
            'cp_localidad' => $objeto->predio->cp_localidad,
            'cp_oficina' => $objeto->predio->cp_oficina,
            'cp_tipo_predio' => $objeto->predio->cp_tipo_predio,
            'cp_registro' => $objeto->predio->cp_registro,
            'descripcion' => $objeto->predio->descripcion,
            'observaciones' => $objeto->predio->observaciones,
            'partes_iguales' => 0,
        ]);

        $predio->colindancias()->delete();

        foreach($objeto->predio->colindancias as $colindancia){

            $predio->colindancias()->create([
                'viento' => $colindancia->viento,
                'longitud' => $colindancia->longitud,
                'descripcion' => $colindancia->descripcion,
            ]);

        }

    }

}
