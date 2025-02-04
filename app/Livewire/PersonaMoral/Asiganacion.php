<?php

namespace App\Livewire\PersonaMoral;

use App\Models\Actor;
use Livewire\Component;
use App\Models\Escritura;
use App\Models\ReformaMoral;
use App\Constantes\Constantes;
use App\Http\Controllers\FolioPersonaMoralController\FolioPersonaMoralController;
use App\Models\FolioRealPersona;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\AsignacionService;
use Exception;

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
    public $tomo;
    public $registro;

    public $objeto;

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

    public $modalContraseña = false;
    public $contraseña;

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
            'escritura_fecha_escritura' => 'required|date|before:today',
            'escritura_fecha_inscripcion' => 'required|date|before:today',
            'tipo' => 'required',
            'observaciones_escritura' => 'nullable',
            'domicilio' => 'required',
            'objeto' => 'required'
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

                        if($this->tomo && $this->registro){

                            $folio = FolioRealPersona::where('tomo_antecedente', $this->tomo)->where('registro_antecedente', $this->registro)->first();

                            if($folio && ($folio->id != $this->movimientoRegistral->folio_real_persona))
                                throw new Exception('Ya existe un folio de persona moral con el tomo y registro ingresados.');

                        }

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
                            'tomo_antecedente' => $this->tomo,
                            'registro_antecedente' => $this->registro,
                            'actualizado_por' => auth()->id()
                        ]);

                        $this->movimientoRegistral->folioRealPersona->objetos()->whereIn('estado', ['captura', 'activo'])->first()?->update(['objeto' => $this->objeto, 'estado' => 'captura']);

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

            $this->movimientoRegistral->refresh();

            $this->refreshActores();

            $this->dispatch('mostrarMensaje', ['success', "La información se guardo con éxito."]);

            $this->dispatch('cargarModelo', [get_class($this->movimientoRegistral->folioRealPersona), $this->movimientoRegistral->folio_real_persona]);

        } catch (Exception $ex) {

            $this->movimientoRegistral = MovimientoRegistral::make();

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {

            $this->movimientoRegistral = MovimientoRegistral::make();

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
            'descripcion' => 'ESTE MOVIMIENTO REGISTRAL DA ORIGEN AL FOLIO REAL DE PERSONA MORAL'
        ]);

    }

    public function crearFolioRealPersonaMoral(){

        if($this->tomo && $this->registro && FolioRealPersona::where('tomo_antecedente', $this->tomo)->where('registro_antecedente', $this->registro)->first())
            throw new Exception('Ya existe un folio de persona moral con el tomo y registro ingresados.');

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
            'tomo_antecedente' => $this->tomo,
            'registro_antecedente' => $this->registro,
            'creado_por' => auth()->id()
        ]);

        $this->folioRealPersonaMoral->objetos()->create([
            'fecha_alta' => now()->toDateString(),
            'estado' => 'captura',
            'objeto' => $this->objeto
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

    public function finalizar(){

        if(!$this->movimientoRegistral?->folioRealPersona->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe guardar el documento de entrada."]);

            return;

        }

        if(!$this->movimientoRegistral?->folioRealPersona){

            $this->dispatch('mostrarMensaje', ['error', "Debe guardar la información para finalizar."]);

            return;

        }

        if(!$this->movimientoRegistral?->folioRealPersona->actores()->count()){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar al menos un participante."]);

            return;
        }

        $this->modalContraseña = true;

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        $this->guardar();

        try {

            DB::transaction(function () {

                $this->movimientoRegistral->folioRealPersona->escritura->update([
                    'actualizado_por' => auth()->id()
                ]);

                $this->movimientoRegistral->folioRealPersona->update([
                    'asignado_por' => auth()->user()->name,
                    'estado' => 'elaborado',
                    'fecha_inscripcion' => now()->toDateString(),
                    'actualizado_por' => auth()->id()
                ]);

                $this->movimientoRegistral->folioRealPersona->objetos()->whereIn('estado', ['captura', 'activo'])->first()?->update(['estado' => 'activo']);

                (new FolioPersonaMoralController())->caratula($this->movimientoRegistral->reformaMoral);

            });

            return redirect()->route('pase_folio_personas_morales');

        } catch (\Throwable $th) {
            Log::error("Error al inscribir folio real de persona moral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->actores = Constantes::ACTORES_FOLIO_REAL_PERSONA_MORAL;

        $this->distritos = Constantes::DISTRITOS;

        if(isset($this->movimientoRegistral)){

            $this->tomo = $this->movimientoRegistral->tomo;
            $this->registro = $this->movimientoRegistral->registro;

            if($this->movimientoRegistral->folioRealPersona){

                $this->denominacion = $this->movimientoRegistral->folioRealPersona->denominacion;
                $this->fecha_constitucion = $this->movimientoRegistral->folioRealPersona->fecha_constitucion;
                $this->distrito = $this->movimientoRegistral->folioRealPersona->getRawOriginal('distrito');
                $this->duracion = $this->movimientoRegistral->folioRealPersona->duracion;
                $this->capital = $this->movimientoRegistral->folioRealPersona->capital;
                $this->tipo = $this->movimientoRegistral->folioRealPersona->tipo;
                $this->domicilio = $this->movimientoRegistral->folioRealPersona->domicilio;
                $this->tomo = $this->movimientoRegistral->folioRealPersona->tomo_antecedente;
                $this->registro = $this->movimientoRegistral->folioRealPersona->registro_antecedente;
                $this->observaciones = $this->movimientoRegistral->folioRealPersona->observaciones;

                $this->numero_escritura = $this->movimientoRegistral->folioRealPersona->escritura->numero;
                $this->escritura_fecha_inscripcion = $this->movimientoRegistral->folioRealPersona->escritura->fecha_inscripcion;
                $this->escritura_fecha_escritura = $this->movimientoRegistral->folioRealPersona->escritura->fecha_escritura;
                $this->numero_hojas = $this->movimientoRegistral->folioRealPersona->escritura->numero_hojas;
                $this->numero_paginas = $this->movimientoRegistral->folioRealPersona->escritura->numero_paginas;
                $this->notaria = $this->movimientoRegistral->folioRealPersona->escritura->notaria;
                $this->nombre_notario = $this->movimientoRegistral->folioRealPersona->escritura->nombre_notario;
                $this->observaciones_escritura = $this->movimientoRegistral->folioRealPersona->escritura->comentario;

                $this->objeto = $this->movimientoRegistral->folioRealPersona->objetos()->whereIn('estado', ['captura', 'activo'])->first()?->objeto;

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
