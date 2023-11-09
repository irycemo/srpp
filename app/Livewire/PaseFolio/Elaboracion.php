<?php

namespace App\Livewire\PaseFolio;

use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioReal;
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

    public $tipos_propietarios;

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

                    $this->propiedad->escritura->update([
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

                $this->dispatch('cargarPropiedad', id: $this->propiedad->id);

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

    public function mount(){

        $this->tipos_propietarios = Constantes::TIPO_PROPIETARIO;

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

            }

        }

    }

    public function render()
    {

        $this->authorize('view', $this->movimientoRegistral);

        return view('livewire.pase-folio.elaboracion')->extends('layouts.admin');
    }
}
