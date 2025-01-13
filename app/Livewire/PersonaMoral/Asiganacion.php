<?php

namespace App\Livewire\PersonaMoral;

use App\Models\Actor;
use Livewire\Component;
use App\Models\Escritura;
use App\Models\ReformaMoral;
use App\Constantes\Constantes;
use App\Models\FolioRealPersona;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\AsignacionService;

class Asiganacion extends Component
{

    public MovimientoRegistral $movimientoRegistral;

    public FolioRealPersona $folioRealPersonaMoral;
    public $denominacion;
    public $fecha_constitucion;
    public $capital;
    public $duracion;
    public $observaciones;
    public $distritos;
    public $distrito;
    public $tipo;
    public $domicilio;

    public Escritura $escritura;
    public $escritura_fecha_inscripcion;
    public $escritura_fecha_escritura;
    public $notaria;
    public $nombre_notario;
    public $numero_escritura;
    public $numero_hojas;
    public $numero_paginas;
    public $observaciones_escritura;

    public $actores;

    protected $listeners = ['refresh' => 'refreshActores'];

    protected function rules(){
        return [
            'denominacion' => 'required',
            'fecha_constitucion' => 'required|date',
            'capital' => 'required|numeric|min:0',
            'duracion' => 'required|numeric|min:0',
            'notaria' => 'required|numeric|min:1',
            'nombre_notario' => 'required',
            'numero_escritura' => 'required|numeric|min:1',
            'numero_hojas' => 'required|numeric|min:1',
            'distrito' => 'required',
            'numero_paginas' => 'required|numeric|min:1',
            'observaciones' => 'nullable',
            'escritura_fecha_inscripcion' => 'required|date|before:today',
            'escritura_fecha_escritura' => 'required|date|before:today',
            'tipo' => 'required',
            'observaciones_escritura' => 'nullable',
            'domicilio' => 'required'
         ];
    }

    protected $validationAttributes  = [
        'denominacion' => 'denominación',
        'duracion' => 'duración',
        'numero_escritura' => 'número de escritura',
        'fecha_constitucion' => 'fecha de constitución',
        'nombre_notario' => 'nombre del notario',
        'numero_hojas' => 'número de hojas',
        'numero_paginas' => 'número de páginas',
        'escritura_fecha_inscripcion' => 'fecha de inscripción',
        'escritura_fecha_escritura' => 'fecha de la escritura'
    ];

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                if(!$this->movimientoRegistral->getKey()){

                    $this->crearMovimientoRegistral();

                    $this->crearFolioRealPersonaMoral();

                    $this->movimientoRegistral->update([
                        'folio_real_persona' => $this->folioRealPersonaMoral->id,
                    ]);

                    $this->crearEscritura();

                    $this->folioRealPersonaMoral->update([
                        'escritura_id' => $this->escritura->id,
                    ]);

                }else{

                    if(!$this->movimientoRegistral->folio_real_persona){

                        $this->crearFolioRealPersonaMoral();

                        $this->movimientoRegistral->update([
                            'folio_real_persona' => $this->folioRealPersonaMoral->id,
                            'actualizado_por' => auth()->id()
                        ]);

                        $this->crearEscritura();

                        $this->folioRealPersonaMoral->update([
                            'escritura_id' => $this->escritura->id,
                            'actualizado_por' => auth()->id()
                        ]);

                    }else{

                        $this->movimientoRegistral->folioRealPersona->update([
                            'denominacion' => $this->denominacion,
                            'estado' => 'captura',
                            'fecha_constitucion' => $this->fecha_constitucion,
                            'distrito' => $this->distrito,
                            'duracion' => $this->duracion,
                            'capital' => $this->capital,
                            'tipo' => $this->tipo,
                            'domicilio' => $this->domicilio,
                            'observaciones' => $this->observaciones,
                            'actualizado_por' => auth()->id()
                        ]);

                        $this->movimientoRegistral->folioRealPersona->escritura->update([
                            'numero' => $this->numero_escritura,
                            'fecha_inscripcion' => $this->escritura_fecha_inscripcion,
                            'fecha_escritura' => $this->escritura_fecha_escritura,
                            'numero_hojas' => $this->numero_hojas,
                            'numero_paginas' => $this->numero_paginas,
                            'notaria' => $this->notaria,
                            'nombre_notario' => $this->nombre_notario,
                            'estado_notario' => 'Michoacán',
                            'comentario' => $this->observaciones_escritura,
                            'actualizado_por' => auth()->id()
                        ]);

                    }

                }

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardo con éxito."]);

            $this->dispatch('cargarModelo', [get_class($this->movimientoRegistral->folioRealPersona), $this->movimientoRegistral->folio_real_persona]);

        } catch (\Throwable $th) {

            Log::error("Error al guardar folio de persona moral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function crearMovimientoRegistral(){

        $this->movimientoRegistral = MovimientoRegistral::create([
            'estado' => 'nuevo',
            'folio' => 1,
            'seccion' => 'Folio real de persona moral',
            'distrito' => $this->distrito,
            'fecha_entrega' => now()->toDateString(),
            'tipo_servicio' => 'extra_urgente',
            'usuario_asignado' => auth()->id(),
            'usuario_supervisor' => (new AsignacionService())->obtenerSupervisorInscripciones($this->distrito)
        ]);

        ReformaMoral::create([
            'movimiento_registral_id' => $this->movimientoRegistral->id,
            'acto_contenido' => 'INSCRIPCIÓN DE FOLIO REAL DE PERSONA MORAL',
            'descripcion' => 'ESTE MOVMIENTO REGISTRAL DA ORIGEN AL FOLIO REAL DE PERSONA MORAL'
        ]);

    }

    public function crearFolioRealPersonaMoral(){

        $this->folioRealPersonaMoral = FolioRealPersona::create([
            'folio' => (FolioRealPersona::max('folio') ?? 0) + 1,
            'denominacion' => $this->denominacion,
            'estado' => 'captura',
            'fecha_constitucion' => $this->fecha_constitucion,
            'fecha_inscripcion' => now()->toDateString(),
            'distrito' => $this->distrito,
            'duracion' => $this->duracion,
            'capital' => $this->capital,
            'tipo' => $this->tipo,
            'domicilio' => $this->domicilio,
            'observaciones' => $this->observaciones,
            'creado_por' => auth()->id()
        ]);

    }

    public function crearEscritura(){

        $this->escritura = Escritura::create([
            'numero' => $this->numero_escritura,
            'fecha_inscripcion' => $this->escritura_fecha_inscripcion,
            'fecha_escritura' => $this->escritura_fecha_escritura,
            'numero_hojas' => $this->numero_hojas,
            'numero_paginas' => $this->numero_paginas,
            'notaria' => $this->notaria,
            'nombre_notario' => $this->nombre_notario,
            'estado_notario' => 'Michoacán',
            'comentario' => $this->observaciones_escritura,
            'creado_por' => auth()->id()
        ]);

    }

    public function refreshActores(){

        $this->movimientoRegistral->folioRealPersona->load('actores.persona');

    }

    public function eliminarActor(Actor $actor){

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "El socio se eliminó con éxito."]);

            $this->refreshActores();

        } catch (\Throwable $th) {

            Log::error("Error al eliminar socio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        $this->actores = Constantes::ACTORES_FOLIO_REAL_PERSONA_MORAL;

        $this->distritos = Constantes::DISTRITOS;

        if(isset($this->movimientoRegistral)){

            if($this->movimientoRegistral->folioRealPersona){

                $this->denominacion = $this->movimientoRegistral->folioRealPersona->denominacion;
                $this->fecha_constitucion = $this->movimientoRegistral->folioRealPersona->fecha_constitucion;
                $this->distrito = $this->movimientoRegistral->folioRealPersona->distrito;
                $this->duracion = $this->movimientoRegistral->folioRealPersona->duracion;
                $this->capital = $this->movimientoRegistral->folioRealPersona->capital;
                $this->tipo = $this->movimientoRegistral->folioRealPersona->tipo;
                $this->domicilio = $this->movimientoRegistral->folioRealPersona->domicilio;
                $this->observaciones = $this->movimientoRegistral->folioRealPersona->observaciones;

                $this->numero_escritura = $this->movimientoRegistral->folioRealPersona->escritura->numero;
                $this->escritura_fecha_inscripcion = $this->movimientoRegistral->folioRealPersona->escritura->fecha_inscripcion;
                $this->escritura_fecha_escritura = $this->movimientoRegistral->folioRealPersona->escritura->fecha_escritura;
                $this->numero_hojas = $this->movimientoRegistral->folioRealPersona->escritura->numero_hojas;
                $this->numero_paginas = $this->movimientoRegistral->folioRealPersona->escritura->numero_paginas;
                $this->notaria = $this->movimientoRegistral->folioRealPersona->escritura->notaria;
                $this->nombre_notario = $this->movimientoRegistral->folioRealPersona->escritura->nombre_notario;
                $this->observaciones_escritura = $this->movimientoRegistral->folioRealPersona->escritura->comentario;

                $this->movimientoRegistral->folioRealPersona->load('actores.persona');

            }

        }else{

            $this->movimientoRegistral = MovimientoRegistral::make();

        }

    }

    public function render()
    {
        return view('livewire.persona-moral.asiganacion')->extends('layouts.admin');
    }

}
