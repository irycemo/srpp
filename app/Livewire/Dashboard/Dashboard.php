<?php

namespace App\Livewire\Dashboard;

use App\Models\MovimientoRegistral;
use App\Models\Pregunta;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Dashboard extends Component
{

    public $preguntas;
    public $servicios_uruapan;
    public $servicios_resto;

    public function mount(){

        if(Cache::get('preguntas_frecuentes_dashboard')){

            $this->preguntas = Cache::get('preguntas_frecuentes_dashboard');

        }else{

            $this->preguntas = Pregunta::latest()->take(5)->get();

            Cache::put('preguntas_frecuentes_dashboard', $this->preguntas);

        }

        if(Cache::get('movimientos_dashboard_uruapan')){

            $this->servicios_uruapan = Cache::get('movimientos_dashboard_uruapan');

        }else{

            Cache::remember('movimientos_dashboard_uruapan', now()->addHour(), function(){

                $this->servicios_uruapan = MovimientoRegistral::select('id', 'servicio_nombre')
                                                            ->where('distrito', 2)
                                                            ->whereIn('estado', ['elaborado', 'finalizado', 'concluido'])
                                                            ->whereBetween('fecha_elaboracion', [now()->startOfDay(), now()->endOfDay()])
                                                            ->get()
                                                            ->groupBy('servicio_nombre')
                                                            ->map(function($movimiento){
                                                                return count($movimiento);
                                                            });
            });

        }

        if(Cache::get('movimientos_dashboard_resto')){

            $this->servicios_resto = Cache::get('movimientos_dashboard_resto');

        }else{

            Cache::remember('movimientos_dashboard_resto', now()->addHour(), function(){

                $this->servicios_resto = MovimientoRegistral::select('id', 'servicio_nombre')
                                                            ->where('distrito', '!=', 2)
                                                            ->whereIn('estado', ['elaborado', 'finalizado', 'concluido'])
                                                            ->whereBetween('fecha_elaboracion', [now()->startOfDay(), now()->endOfDay()])
                                                            ->get()
                                                            ->groupBy('servicio_nombre')
                                                            ->map(function($movimiento){
                                                                return count($movimiento);
                                                            });
            });

        }

    }

    public function render()
    {
        return view('livewire.dashboard.dashboard')->extends('layouts.admin');
    }

}
