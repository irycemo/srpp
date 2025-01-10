<?php

namespace App\Livewire\PersonaMoral;

use App\Constantes\Constantes;
use App\Http\Services\AsignacionService;
use App\Models\MovimientoRegistral;
use Livewire\Component;

class Asiganacion extends Component
{

    public MovimientoRegistral $movimientoRegistral;

    public $denominacion;
    public $fecha_constitucion;
    public $capital;
    public $duracion;
    public $notaria;
    public $nombre_notario;
    public $numero_escritura;
    public $numero_hojas;
    public $descripcion;
    public $observaciones;
    public $distritos;
    public $distrito;

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
            'descripcion' => 'required',
            'distrito' => 'required',
            'observaciones' => 'nullable',
         ];
    }

    protected $validationAttributes  = [
        'denominacion' => 'denominación',
        'duracion' => 'duración',
        'descripcion' => 'descripción',
        'numero_escritura' => 'número de escritura',
        'fecha_constitucion' => 'fecha de constitución',
        'nombre_notario' => 'nombre del notario',
        'numero_hojas' => 'número de hojas',
    ];

    public function guardar(){

        $this->validate();

        if(!$this->movimientoRegistral){



        }else{



        }

    }

    public function crearMovimientoRegistral(){

        $this->movimientoRegistral = MovimientoRegistral::create([
            'estado' => 'nuevo',
            'folio' => 1,
            'seccion' => 'Folio real de persona moral',
            'distrito' => $this->distrito,
            'usuario_asignado' => auth()->id(),
            'usuario_supervisor' => (new AsignacionService())->obtenerSupervisorInscripciones($this->distrito)
        ]);

    }

    public function crearFolioRealPersonaMoral(){



    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.persona-moral.asiganacion')->extends('layouts.admin');
    }
}
