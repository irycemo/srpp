<?php

namespace App\Livewire\PaseFolio;

use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Colindancia;
use App\Models\Propietario;
use App\Constantes\Constantes;
use App\Models\Escritura;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

class Elaboracion extends Component
{

    public $modal = false;
    public $crear = false;
    public $editar = false;

    /* Documento entrada */
    public $tipo_documento;
    public $autoridad_cargo;
    public $autoridad_nombre;
    public $numero_documento;
    public $fecha_emision;
    public $fecha_inscripcion;
    public $procedencia;

    /* Descripción del predio */
    public $localidad;
    public $oficina;
    public $tipo;
    public $registro;
    public $region;
    public $municipio;
    public $zona;
    public $sector;
    public $manzana;
    public $predio;
    public $edificio;
    public $departamento;
    public $curt;
    public $superficie_terreno;
    public $superficie_construccion;
    public $superficie_judicial;
    public $superficie_notarial;
    public $area_comun_terreno;
    public $area_comun_construccion;
    public $valor_terreno_comun;
    public $valor_construccion_comun;
    public $valor_total_terreno;
    public $valor_total_construccion;
    public $valor_catastral;
    public $monto_transaccion;
    public $divisa;
    public $observaciones;
    public $descripcion;

    /* Ubicación predio */
    public $tipo_vialidad;
    public $tipo_asentamiento;
    public $nombre_vialidad;
    public $nombre_asentamiento;
    public $numero_exterior;
    public $numero_exterior_2;
    public $numero_adicional;
    public $numero_adicional_2;
    public $numero_interior;
    public $lote;
    public $manzana_ubicacion;
    public $codigo_postal;
    public $lote_fraccionador;
    public $manzana_fraccionador;
    public $etapa_fraccionador;
    public $nombre_edificio;
    public $clave_edificio;
    public $departamento_edificio;
    public $estado_ubicacion;
    public $municipio_ubicacion;
    public $ciudad;
    public $localidad_ubicacion;
    public $poblado;
    public $ejido;
    public $parcela;
    public $solar;

    /* Escritura */
    public $escritura_numero;
    public $escritura_fecha_inscripcion;
    public $escritura_fecha_escritura;
    public $escritura_numero_hojas;
    public $escritura_numero_paginas;
    public $escritura_notaria;
    public $escritura_nombre_notario;
    public $escritura_estado_notario;
    public $escritura_observaciones;

    /* Propietarios */
    public $propietarios = [];
    public $tipo_propietario;
    public $porcentaje;
    public $tipo_persona;
    public $nombre;
    public $ap_paterno;
    public $ap_materno;
    public $curp;
    public $rfc;
    public $razon_social;
    public $fecha_nacimiento;
    public $nacionalidad;
    public $estado_civil;
    public $calle;
    public $numero_exterior_propietario;
    public $numero_interior_propietario;
    public $colonia;
    public $cp;
    public $entidad;
    public $municipio_propietario;

    public MovimientoRegistral $movimientoRegistral;
    public Predio $propiedad;
    public Escritura $escritura;

    public $propietario;
    public $tipos_asentamientos;
    public $tipos_vialidades;
    public $tipos_propietarios;
    public $medidas = [];
    public $vientos;
    public $distritos;
    public $estados;

    public function resetear(){

        $this->reset([
            'tipo_propietario',
            'porcentaje',
            'tipo_persona',
            'nombre',
            'ap_paterno',
            'ap_materno',
            'curp',
            'rfc',
            'razon_social',
            'fecha_nacimiento',
            'nacionalidad',
            'estado_civil',
            'calle',
            'numero_exterior_propietario',
            'numero_interior_propietario',
            'colonia',
            'cp',
            'entidad',
            'municipio_propietario',
            'modal'
        ]);
    }

    public function generarFolioReal(){

        DB::transaction(function () {

            $folioReal = FolioReal::create([
                'estado' => 'formacion',
                'folio' => (FolioReal::max('folio') ?? 0) + 1,
                'tomo_antecedente' => $this->movimientoRegistral->tomo,
                'tomo_antecedente_bis' => $this->movimientoRegistral->tomo_bis,
                'registro_antecedente' => $this->movimientoRegistral->registro,
                'registro_antecedente_bis' => $this->movimientoRegistral->registro_bis,
                'numero_propiedad_antecedente' => $this->movimientoRegistral->numero_propiedad,
                'distrito_antecedente' => $this->movimientoRegistral->distrito,
                'seccion_antecedente' => $this->movimientoRegistral->seccion,
            ]);

            $this->movimientoRegistral->update(['folio_real' => $folioReal->id]);

            if ($this->tipo_documento == 'escritura'){

                $this->escritura = Escritura::create([
                    'numero' => $this->escritura_numero,
                    'fecha_inscripcion' => $this->escritura_fecha_inscripcion,
                    'fecha_escritura' => $this->escritura_fecha_escritura,
                    'numero_hojas' => $this->escritura_numero_hojas,
                    'numero_paginas' => $this->escritura_numero_paginas,
                    'notaria' => $this->escritura_notaria,
                    'nombre_notario' => $this->escritura_nombre_notario,
                    'estado_notario' => $this->escritura_estado_notario,
                    'comentario' => $this->escritura_observaciones,
                ]);

                $this->propiedad = Predio::create([
                    'escritura_id' => $this->escritura->id,
                    'folio_real' => $folioReal->id,
                    'status' => 'nuevo'
                ]);

            }

            $this->propiedad = Predio::create([
                'folio_real' => $folioReal->id,
                'status' => 'nuevo'
            ]);

        });

    }

    public function guardarDocumentoEntrada(){

        $this->validate([
            'tipo_documento' => 'nullable',
            'autoridad_cargo' => 'nullable',
            'autoridad_nombre' => 'nullable',
            'numero_documento' => 'nullable',
            'fecha_emision' => 'nullable',
            'fecha_inscripcion' => 'nullable',
            'procedencia' => 'nullable',
            'escritura_fecha_inscripcion' => 'nullable',
            'escritura_fecha_escritura' => 'nullable',
            'escritura_numero_hojas' => 'nullable',
            'escritura_numero_paginas' => 'nullable',
            'escritura_notaria' => 'nullable',
            'escritura_nombre_notario' => 'nullable',
            'escritura_estado_notario' => 'nullable',
            'escritura_observaciones' => 'nullable',
        ]);

        if(!$this->movimientoRegistral->folio_real){

            $this->generarFolioReal();

            $this->movimientoRegistral->refresh();

        }

        try {

            DB::transaction(function () {

                $this->movimientoRegistral->folioReal->update([
                    'tipo_documento' => $this->tipo_documento,
                    'autoridad_cargo' => $this->autoridad_cargo,
                    'autoridad_nombre' => $this->autoridad_nombre,
                    'numero_documento' => $this->numero_documento,
                    'fecha_emision' => $this->fecha_emision,
                    'fecha_inscripcion' => $this->fecha_inscripcion,
                    'procedencia' => $this->procedencia,
                ]);

                if($this->tipo_documento == 'escritura'){

                    $this->escritura->update([
                        'numero' => $this->escritura_numero,
                        'fecha_inscripcion' => $this->escritura_fecha_inscripcion,
                        'fecha_escritura' => $this->escritura_fecha_escritura,
                        'numero_hojas' => $this->escritura_numero_hojas,
                        'numero_paginas' => $this->escritura_numero_paginas,
                        'notaria' => $this->escritura_notaria,
                        'nombre_notario' => $this->escritura_nombre_notario,
                        'estado_notario' => $this->escritura_estado_notario,
                        'comentario' => $this->escritura_observaciones,
                    ]);

                }

                $this->dispatch('mostrarMensaje', ['success', "El documento de entrada se guardó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function guardarDescripcionPredio(){

        $this->validate([
            'localidad' => 'required',
            'oficina' => 'required',
            'tipo' => 'required',
            'registro' => 'required',
            'region' => 'required',
            'municipio' => 'required',
            'zona' => 'required',
            'sector' => 'required',
            'manzana' => 'required',
            'predio' => 'required',
            'edificio' => 'required',
            'departamento' => 'required',
            'curt' => 'required',
            'superficie_terreno' => 'required',
            'superficie_construccion' => 'required',
            'superficie_judicial' => 'required',
            'superficie_notarial' => 'required',
            'area_comun_terreno' => 'required',
            'area_comun_construccion' => 'required',
            'valor_terreno_comun' => 'required',
            'valor_construccion_comun' => 'required',
            'valor_total_terreno' => 'required',
            'valor_total_construccion' => 'required',
            'valor_catastral' => 'required',
            'monto_transaccion' => 'required',
            'divisa' => 'required',
            'observaciones' => 'required',
            'medidas.*' => 'required',
            'medidas.*.viento' => 'required|string',
            'medidas.*.longitud' => [
                                        'required',
                                        'numeric',
                                        'min:0',
                                    ],
            'medidas.*.descripcion' => 'required|string',
            'predio' => 'required'
        ]);

        if(!$this->movimientoRegistral->folio_real){

            $this->generarFolioReal();

        }

        try {

            DB::transaction(function () {

                $this->movimientoRegistral->inscripcionPropiedad->update([
                    'cc_estado' => 16,
                    'cc_region_catastral' => $this->region,
                    'cc_municipio' => $this->municipio,
                    'cc_zona_catastral' => $this->zona,
                    'cc_sector' => $this->sector,
                    'cc_manzana' => $this->manzana,
                    'cc_predio' => $this->predio,
                    'cc_edificio' => $this->edificio,
                    'cc_departamento' => $this->departamento,
                    'cp_localidad' => $this->localidad,
                    'cp_oficina' => $this->oficina,
                    'cp_tipo_predio' => $this->tipo,
                    'cp_registro' => $this->registro,

                    'superficie_terreno' => $this->superficie_terreno,
                    'superficie_construccion' => $this->superficie_construccion,
                    'superficie_judicial' => $this->superficie_judicial,
                    'superficie_notarial' => $this->superficie_notarial,
                    'area_comun_terreno' => $this->area_comun_terreno,
                    'area_comun_construccion' => $this->area_comun_construccion,
                    'valor_terreno_comun' => $this->valor_terreno_comun,
                    'valor_construccion_comun' => $this->valor_construccion_comun,
                    'valor_total_terreno' => $this->valor_total_terreno,
                    'valor_total_construccion' => $this->valor_total_construccion,
                    'valor_catastral' => $this->valor_catastral,
                    'monto_transaccion' => $this->monto_transaccion,
                    'divisa' => $this->divisa,
                    'descripcion' => $this->observaciones
                ]);

                $this->propiedad->update([
                    'cc_estado' => 16,
                    'cc_region_catastral' => $this->region,
                    'cc_municipio' => $this->municipio,
                    'cc_zona_catastral' => $this->zona,
                    'cc_sector' => $this->sector,
                    'cc_manzana' => $this->manzana,
                    'cc_predio' => $this->predio,
                    'cc_edificio' => $this->edificio,
                    'cc_departamento' => $this->departamento,
                    'cp_localidad' => $this->localidad,
                    'cp_oficina' => $this->oficina,
                    'cp_tipo_predio' => $this->tipo,
                    'cp_registro' => $this->registro,
                    'curt' => $this->curt,
                    'superficie_terreno' => $this->superficie_terreno,
                    'superficie_construccion' => $this->superficie_construccion,
                    'superficie_judicial' => $this->superficie_judicial,
                    'superficie_notarial' => $this->superficie_notarial,
                    'area_comun_terreno' => $this->area_comun_terreno,
                    'area_comun_construccion' => $this->area_comun_construccion,
                    'valor_terreno_comun' => $this->valor_terreno_comun,
                    'valor_construccion_comun' => $this->valor_construccion_comun,
                    'valor_total_terreno' => $this->valor_total_terreno,
                    'valor_total_construccion' => $this->valor_total_construccion,
                    'valor_catastral' => $this->valor_catastral,
                    'divisa' => $this->divisa,
                    'descripcion' =>$this->observaciones
                ]);

                foreach ($this->medidas as $key =>$medida) {

                    if($medida['id'] == null){

                        $aux = $this->propiedad->colindancias()->create([
                            'viento' => $medida['viento'],
                            'longitud' => $medida['longitud'],
                            'descripcion' => $medida['descripcion'],
                        ]);

                        $this->medidas[$key]['id'] = $aux->id;

                    }else{

                        Colindancia::find($medida['id'])->update([
                            'viento' => $medida['viento'],
                            'longitud' => $medida['longitud'],
                            'descripcion' => $medida['descripcion'],
                        ]);

                    }

                }

                $this->dispatch('mostrarMensaje', ['success', "La descripción del predio se guardó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar descripción del predio en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function guardarUbicacionPredio(){

        $this->validate([
            'tipo_vialidad' => 'required',
            'tipo_asentamiento' => 'required',
            'nombre_vialidad' => 'required',
            'nombre_asentamiento' => 'required',
            'numero_exterior' => 'required',
            'numero_exterior_2' => 'required',
            'numero_adicional' => 'required',
            'numero_adicional_2' => 'required',
            'numero_interior' => 'required',
            'lote' => 'required',
            'manzana_ubicacion' => 'required',
            'codigo_postal' => 'required',
            'lote_fraccionador' => 'required',
            'manzana_fraccionador' => 'required',
            'etapa_fraccionador' => 'required',
            'nombre_edificio' => 'required',
            'clave_edificio' => 'required',
            'departamento_edificio' => 'required',
            'municipio_ubicacion' => 'required',
            'ciudad' => 'required',
            'localidad_ubicacion' => 'required',
            'poblado' => 'required',
            'ejido' => 'required',
            'parcela' => 'required',
            'solar' => 'required',
        ]);

        if(!$this->movimientoRegistral->folio_real){

            $this->generarFolioReal();

        }

        try {

            DB::transaction(function () {

                $this->movimientoRegistral->inscripcionPropiedad->update([
                    'tipo_vialidad' => $this->tipo_vialidad,
                    'tipo_asentamiento' => $this->tipo_asentamiento,
                    'nombre_vialidad' => $this->nombre_vialidad,
                    'nombre_asentamiento' => $this->nombre_asentamiento,
                    'numero_exterior' => $this->numero_exterior,
                    'numero_exterior_2' => $this->numero_exterior_2,
                    'numero_adicional' => $this->numero_adicional,
                    'numero_adicional_2' => $this->numero_adicional_2,
                    'numero_interior' => $this->numero_interior,
                    'lote' => $this->lote,
                    'manzana' => $this->manzana_ubicacion,
                    'codigo_postal' => $this->codigo_postal,
                    'lote_fraccionador' => $this->lote_fraccionador,
                    'manzana_fraccionador' => $this->manzana_fraccionador,
                    'etapa_fraccionador' => $this->etapa_fraccionador,
                    'nombre_edificio' => $this->nombre_edificio,
                    'clave_edificio' => $this->clave_edificio,
                    'departamento_edificio' => $this->departamento_edificio,
                    'municipio' => $this->municipio_ubicacion,
                    'ciudad' => $this->ciudad,
                    'localidad' => $this->localidad_ubicacion,
                    'poblado' => $this->poblado,
                    'ejido' => $this->ejido,
                    'parcela' => $this->parcela,
                    'solar' => $this->solar,
                    'observaciones' => $this->observaciones
                ]);

                $this->propiedad->update([
                    'tipo_vialidad' => $this->tipo_vialidad,
                    'tipo_asentamiento' => $this->tipo_asentamiento,
                    'nombre_vialidad' => $this->nombre_vialidad,
                    'nombre_asentamiento' => $this->nombre_asentamiento,
                    'numero_exterior' => $this->numero_exterior,
                    'numero_exterior_2' => $this->numero_exterior_2,
                    'numero_adicional' => $this->numero_adicional,
                    'numero_adicional_2' => $this->numero_adicional_2,
                    'numero_interior' => $this->numero_interior,
                    'lote' => $this->lote,
                    'manzana' => $this->manzana_ubicacion,
                    'codigo_postal' => $this->codigo_postal,
                    'lote_fraccionador' => $this->lote_fraccionador,
                    'manzana_fraccionador' => $this->manzana_fraccionador,
                    'etapa_fraccionador' => $this->etapa_fraccionador,
                    'nombre_edificio' => $this->nombre_edificio,
                    'clave_edificio' => $this->clave_edificio,
                    'departamento_edificio' => $this->departamento_edificio,
                    'municipio' => $this->municipio_ubicacion,
                    'ciudad' => $this->ciudad,
                    'localidad' => $this->localidad_ubicacion,
                    'poblado' => $this->poblado,
                    'ejido' => $this->ejido,
                    'parcela' => $this->parcela,
                    'solar' => $this->solar,
                    'descripcion' => $this->observaciones
                ]);

                $this->dispatch('mostrarMensaje', ['success', "La ubicación del predio se guardó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar ubicación del predio en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function agregarPropietario(){

        if(!$this->movimientoRegistral->inscripcionPropiedad->cp_oficina){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los datos del predio."]);

            return;

        }

        $this->modal = true;
        $this->crear = true;

    }

    public function guardarPropietario(){

        $this->validate([
            'tipo_propietario' => 'required',
            'porcentaje' => 'required',
            'tipo_persona' => 'required',
            'nombre' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'curp' => 'required',
            'rfc' => 'required',
            'razon_social' => 'required',
            'fecha_nacimiento' => 'required',
            'nacionalidad' => 'required',
            'estado_civil' => 'required',
            'calle' => 'required',
            'numero_exterior_propietario' => 'required',
            'numero_interior_propietario' => 'required',
            'colonia' => 'required',
            'cp' => 'required',
            'entidad' => 'required',
            'municipio_propietario' => 'required',
        ]);

        if($this->revisarProcentajes() + $this->porcentaje > 100){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede exceder el 100%."]);

            return;

        }

        try {

            DB::transaction(function () {

                $persona = Persona::Create([
                    'tipo' => $this->tipo_persona,
                    'nombre' => $this->nombre,
                    'ap_paterno' => $this->ap_paterno,
                    'ap_materno' => $this->ap_materno,
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'razon_social' => $this->razon_social,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'nacionalidad' => $this->nacionalidad,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior_propietario,
                    'numero_interior' => $this->numero_interior_propietario,
                    'colonia' => $this->colonia,
                    'cp' => $this->cp,
                    'entidad' => $this->entidad,
                    'municipio' => $this->municipio_propietario,
                    'creado_por' => auth()->id()
                ]);

                $this->propiedad->propietarios()->create([
                    'persona_id' => $persona->id,
                    'tipo' => $this->tipo_propietario,
                    'porcentaje' => $this->porcentaje,
                    'creado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El propietario se guardó con éxito."]);

                $this->resetear();

                $this->propiedad->refresh();

                $this->propiedad->load('propietarios.persona');
            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar propietario en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function editarPropietario(Propietario $propietario){

        $this->propietario = $propietario;

        $this->tipo_propietario = $propietario->tipo;
        $this->porcentaje = $propietario->porcentaje;
        $this->tipo_persona = $propietario->persona->tipo;
        $this->nombre = $propietario->persona->nombre;
        $this->ap_paterno = $propietario->persona->ap_paterno;
        $this->ap_materno = $propietario->persona->ap_materno;
        $this->curp = $propietario->persona->curp;
        $this->rfc = $propietario->persona->rfc;
        $this->razon_social = $propietario->persona->razon_social;
        $this->fecha_nacimiento = $propietario->persona->fecha_nacimiento;
        $this->nacionalidad = $propietario->persona->nacionalidad;
        $this->estado_civil = $propietario->persona->estado_civil;
        $this->calle = $propietario->persona->calle;
        $this->numero_exterior_propietario = $propietario->persona->numero_exterior;
        $this->numero_interior_propietario = $propietario->persona->numero_interior;
        $this->colonia = $propietario->persona->colonia;
        $this->cp = $propietario->persona->cp;
        $this->entidad = $propietario->persona->entidad;
        $this->municipio_propietario = $propietario->persona->municipio;

        $this->modal = true;

        $this->editar = true;

    }

    public function actualizarPropietario(){

        $this->validate([
            'tipo_propietario' => 'required',
            'porcentaje' => 'required',
            'tipo_persona' => 'required',
            'nombre' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'curp' => 'required',
            'rfc' => 'required',
            'razon_social' => 'required',
            'fecha_nacimiento' => 'required',
            'nacionalidad' => 'required',
            'estado_civil' => 'required',
            'calle' => 'required',
            'numero_exterior_propietario' => 'required',
            'numero_interior_propietario' => 'required',
            'colonia' => 'required',
            'cp' => 'required',
            'entidad' => 'required',
            'municipio_propietario' => 'required',
        ]);

        if($this->revisarProcentajes() + $this->porcentaje > 100){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede exceder el 100%."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->propietario->persona->update([
                    'tipo' => $this->tipo_persona,
                    'nombre' => $this->nombre,
                    'ap_paterno' => $this->ap_paterno,
                    'ap_materno' => $this->ap_materno,
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'razon_social' => $this->razon_social,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'nacionalidad' => $this->nacionalidad,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior_propietario,
                    'numero_interior' => $this->numero_interior_propietario,
                    'colonia' => $this->colonia,
                    'cp' => $this->cp,
                    'entidad' => $this->entidad,
                    'municipio' => $this->municipio_propietario,
                    'creado_por' => auth()->id()
                ]);

                $this->propietario->update([
                    'tipo' => $this->tipo_propietario,
                    'porcentaje' => $this->porcentaje,
                    'actualizado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El propietario se actualizó con éxito."]);

                $this->resetear();

                $this->propiedad->refresh();

                $this->propiedad->load('propietarios.persona');
            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar propietario en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarPropietario(Propietario $propietario){

        try {

            $propietario->delete();

            $this->dispatch('mostrarMensaje', ['success', "El propietario se eliminó con éxito."]);

            $this->resetear();

            $this->propiedad->refresh();

            $this->propiedad->load('propietarios.persona');

        } catch (\Throwable $th) {

            Log::error("Error al borrar propietario en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function revisarProcentajes(){

        $porcentaje = 0;

        foreach($this->propiedad->propietarios as $propietario){

            $porcentaje = $porcentaje + $propietario->porcentaje;

        }

        return $porcentaje;

    }

    public function agregarColindancia(){

        $this->medidas[] = ['viento' => null, 'longitud' => null, 'descripcion' => null, 'id' => null];

    }

    public function borrarColindancia($index){

        try {

            $this->propiedad->colindancias()->where('id', $this->medidas[$index]['id'])->delete();

        } catch (\Throwable $th) {
            Log::error("Error al borrar colindancia en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
        }

        unset($this->medidas[$index]);

        $this->medidas = array_values($this->medidas);

    }

    public function mount(){

        $this->tipos_vialidades = Constantes::TIPO_VIALIDADES;

        $this->tipos_asentamientos = Constantes::TIPO_ASENTAMIENTO;

        $this->tipos_propietarios = Constantes::TIPO_PROPIETARIO;

        $this->vientos = Constantes::VIENTOS;

        $this->distritos = Constantes::DISTRITOS;

        $this->estados = Constantes::ESTADOS;

        if($this->movimientoRegistral->folioReal){

            /* Documento entrada */
            $this->tipo_documento = $this->movimientoRegistral->folioReal->tipo_documento;
            $this->autoridad_cargo = $this->movimientoRegistral->folioReal->autoridad_cargo;
            $this->autoridad_nombre = $this->movimientoRegistral->folioReal->autoridad_nombre;
            $this->numero_documento = $this->movimientoRegistral->folioReal->numero_documento;
            $this->fecha_emision = $this->movimientoRegistral->folioReal->fecha_emision;
            $this->fecha_inscripcion = $this->movimientoRegistral->folioReal->fecha_inscripcion;
            $this->procedencia = $this->movimientoRegistral->folioReal->procedencia;

            $this->propiedad = Predio::with('propietarios.persona', 'colindancias')->where('folio_real', $this->movimientoRegistral->folio_real)->first();

            if($this->propiedad){

                $this->curt = $this->propiedad->curt;

                $this->localidad = $this->movimientoRegistral->inscripcionPropiedad->cp_localidad;
                $this->oficina = $this->movimientoRegistral->inscripcionPropiedad->cp_oficina;
                $this->tipo = $this->movimientoRegistral->inscripcionPropiedad->cp_tipo_predio;
                $this->registro = $this->movimientoRegistral->inscripcionPropiedad->cp_registro;
                $this->region = $this->movimientoRegistral->inscripcionPropiedad->cc_region_catastral;
                $this->municipio = $this->movimientoRegistral->inscripcionPropiedad->cc_municipio;
                $this->zona = $this->movimientoRegistral->inscripcionPropiedad->cc_zona_catastral;
                $this->sector = $this->movimientoRegistral->inscripcionPropiedad->cc_sector;
                $this->manzana = $this->movimientoRegistral->inscripcionPropiedad->cc_manzana;
                $this->predio = $this->movimientoRegistral->inscripcionPropiedad->cc_predio;
                $this->edificio = $this->movimientoRegistral->inscripcionPropiedad->cc_edificio;
                $this->departamento = $this->movimientoRegistral->inscripcionPropiedad->cc_departamento;
                $this->superficie_terreno = $this->movimientoRegistral->inscripcionPropiedad->superficie_terreno;
                $this->superficie_construccion = $this->movimientoRegistral->inscripcionPropiedad->superficie_construccion;
                $this->superficie_judicial = $this->movimientoRegistral->inscripcionPropiedad->superficie_judicial;
                $this->superficie_notarial = $this->movimientoRegistral->inscripcionPropiedad->superficie_notarial;
                $this->area_comun_terreno = $this->movimientoRegistral->inscripcionPropiedad->area_comun_terreno;
                $this->area_comun_construccion = $this->movimientoRegistral->inscripcionPropiedad->area_comun_construccion;
                $this->valor_terreno_comun = $this->movimientoRegistral->inscripcionPropiedad->valor_terreno_comun;
                $this->valor_construccion_comun = $this->movimientoRegistral->inscripcionPropiedad->valor_construccion_comun;
                $this->valor_total_terreno = $this->movimientoRegistral->inscripcionPropiedad->valor_total_terreno;
                $this->valor_total_construccion = $this->movimientoRegistral->inscripcionPropiedad->valor_total_construccion;
                $this->valor_catastral = $this->movimientoRegistral->inscripcionPropiedad->valor_catastral;
                $this->monto_transaccion = $this->movimientoRegistral->inscripcionPropiedad->monto_transaccion;
                $this->divisa = $this->movimientoRegistral->inscripcionPropiedad->divisa;

                $this->tipo_vialidad = $this->movimientoRegistral->inscripcionPropiedad->tipo_vialidad;
                $this->tipo_asentamiento = $this->movimientoRegistral->inscripcionPropiedad->tipo_asentamiento;
                $this->nombre_vialidad = $this->movimientoRegistral->inscripcionPropiedad->nombre_vialidad;
                $this->nombre_asentamiento = $this->movimientoRegistral->inscripcionPropiedad->nombre_asentamiento;
                $this->numero_exterior = $this->movimientoRegistral->inscripcionPropiedad->numero_exterior;
                $this->numero_exterior_2 = $this->movimientoRegistral->inscripcionPropiedad->numero_exterior_2;
                $this->numero_adicional = $this->movimientoRegistral->inscripcionPropiedad->numero_adicional;
                $this->numero_adicional_2 = $this->movimientoRegistral->inscripcionPropiedad->numero_adicional_2;
                $this->numero_interior = $this->movimientoRegistral->inscripcionPropiedad->numero_interior;
                $this->lote = $this->movimientoRegistral->inscripcionPropiedad->lote;
                $this->manzana_ubicacion = $this->movimientoRegistral->inscripcionPropiedad->manzana;
                $this->codigo_postal = $this->movimientoRegistral->inscripcionPropiedad->codigo_postal;
                $this->lote_fraccionador = $this->movimientoRegistral->inscripcionPropiedad->lote_fraccionador;
                $this->manzana_fraccionador = $this->movimientoRegistral->inscripcionPropiedad->manzana_fraccionador;
                $this->etapa_fraccionador = $this->movimientoRegistral->inscripcionPropiedad->etapa_fraccionador;
                $this->nombre_edificio = $this->movimientoRegistral->inscripcionPropiedad->nombre_edificio;
                $this->clave_edificio = $this->movimientoRegistral->inscripcionPropiedad->clave_edificio;
                $this->departamento_edificio = $this->movimientoRegistral->inscripcionPropiedad->departamento_edificio;
                $this->municipio_ubicacion = $this->movimientoRegistral->inscripcionPropiedad->municipio;
                $this->ciudad = $this->movimientoRegistral->inscripcionPropiedad->ciudad;
                $this->localidad_ubicacion = $this->movimientoRegistral->inscripcionPropiedad->localidad;
                $this->poblado = $this->movimientoRegistral->inscripcionPropiedad->poblado;
                $this->ejido = $this->movimientoRegistral->inscripcionPropiedad->ejido;
                $this->parcela = $this->movimientoRegistral->inscripcionPropiedad->parcela;
                $this->solar = $this->movimientoRegistral->inscripcionPropiedad->solar;
                $this->observaciones = $this->movimientoRegistral->inscripcionPropiedad->observaciones;
                $this->descripcion = $this->movimientoRegistral->inscripcionPropiedad->descripcion;

                if($this->propiedad->escritura){

                    $this->escritura_numero = $this->propiedad->escritura->numero;
                    $this->escritura_fecha_inscripcion = $this->propiedad->escritura->fecha_inscripcion;
                    $this->escritura_fecha_escritura = $this->propiedad->escritura->fecha_escritura;
                    $this->escritura_numero_hojas = $this->propiedad->escritura->numero_hojas;
                    $this->escritura_numero_paginas = $this->propiedad->escritura->numero_paginas;
                    $this->escritura_notaria = $this->propiedad->escritura->notaria;
                    $this->escritura_nombre_notario = $this->propiedad->escritura->nombre_notario;
                    $this->escritura_estado_notario = $this->propiedad->escritura->estado_notario;
                    $this->escritura_observaciones = $this->propiedad->escritura->comentario;

                }

                foreach ($this->propiedad->colindancias as $colindancia) {

                    $this->medidas[] = [
                        'id' => $colindancia->id,
                        'viento' => $colindancia->viento,
                        'longitud' => $colindancia->longitud,
                        'descripcion' => $colindancia->descripcion,
                    ];

                }

            }

        }

    }

    public function render()
    {

        $this->authorize('view', $this->movimientoRegistral);

        return view('livewire.pase-folio.elaboracion')->extends('layouts.admin');
    }
}
