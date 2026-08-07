<?php

namespace App\Livewire\Reportes;

use App\Constantes\Constantes;
use App\Models\MovimientoRegistral;
use Livewire\Component;

class Productividad extends Component
{

    public $fecha1;
    public $fecha2;
    public $distrito = "";
    public $distritos;
    public $servicio;
    public $servicios;
    public $movimientos = [];

    protected function rules(){
        return [
            'fecha1' => 'required|date',
            'fecha2' => 'required|date|after:date1',
         ];
    }

    protected $messages = [
        'fecha1.required' => "La fecha inicial es obligatoria.",
        'fecha2.required' => "La fecha final es obligatoria.",
    ];

    public function updated(){

        $this->movimientos = MovimientoRegistral::select('id', 'servicio_nombre')
                                                ->when($this->distrito && $this->distrito != '', function($q){
                                                    $q->where('distrito', $this->distrito);
                                                })
                                                ->when($this->servicio && $this->servicio != '', function($q){
                                                    $q->where('servicio_nombre', $this->servicio);
                                                })
                                                ->whereIn('estado', ['elaborado', 'finalizado', 'concluido'])
                                                ->whereBetween('fecha_elaboracion', [$this->fecha1 . ' 00:00:00', $this->fecha2 . ' 23:59:59'])
                                                ->get()
                                                ->groupBy('servicio_nombre')
                                                ->map(function($movimiento){
                                                    return count($movimiento);
                                                });

    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

        $this->servicios = Constantes::SERVICIOS;

    }

    public function render()
    {
        return view('livewire.reportes.productividad')->extends('layouts.admin');
    }
}
