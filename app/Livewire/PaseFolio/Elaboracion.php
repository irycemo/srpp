<?php

namespace App\Livewire\PaseFolio;

use App\Models\Predio;
use Livewire\Component;
use App\Models\FolioReal;
use App\Constantes\Constantes;
use App\Models\Escritura;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

class Elaboracion extends Component
{

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

    public MovimientoRegistral $movimientoRegistral;
    public Predio $propiedad;
    public Escritura $escritura;

    public $distritos;
    public $estados;

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
            'escritura_numero' => 'nullable',
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

                if($this->tipo_documento == 'escritura' && !$this->propiedad->escritura_id){

                    $this->escritura = Escritura::Create([
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

                    $this->propiedad->update(['escritura_id' => $this->escritura->id]);

                }else{

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

                $this->dispatch('cargarPropiedad', id: $this->propiedad->id);

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

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

            $this->propiedad = Predio::where('folio_real', $this->movimientoRegistral->folio_real)->first();

            if($this->propiedad){

                if($this->propiedad->escritura){

                    $this->escritura = $this->propiedad->escritura;

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
