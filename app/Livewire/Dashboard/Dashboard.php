<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Pregunta;
use App\Models\Propiedad;

class Dashboard extends Component
{

    public $preguntas;
    public $propiedad = [];

    public function cargarPropiedad(){

        $this->propiedad['no recibido'] = Propiedad::whereHas('movimientoRegistral', function($q){
                                    $q->where('estado', 'no recibido')->where('created_at', '>', now()->startOfMonth());
                                })->count();

        $this->propiedad['nuevo'] = Propiedad::whereHas('movimientoRegistral', function($q){
                                        $q->where('estado', 'nuevo')->where('created_at', '>', now()->startOfMonth());
                                    })->count();

        $this->propiedad['captura'] = Propiedad::whereHas('movimientoRegistral', function($q){
                                        $q->where('estado', 'captura')->where('created_at', '>', now()->startOfMonth());
                                    })->count();

        $this->propiedad['correccion'] = Propiedad::whereHas('movimientoRegistral', function($q){
                                        $q->where('estado', 'correccion')->where('created_at', '>', now()->startOfMonth());
                                    })->count();

        $this->propiedad['elaborado'] = Propiedad::whereHas('movimientoRegistral', function($q){
                                        $q->where('estado', 'elaborado')->where('created_at', '>', now()->startOfMonth());
                                    })->count();

        $this->propiedad['finalizado'] = Propiedad::whereHas('movimientoRegistral', function($q){
                                        $q->where('estado', 'finalizado')->where('created_at', '>', now()->startOfMonth());
                                    })->count();

        $this->propiedad['concluido'] = Propiedad::whereHas('movimientoRegistral', function($q){
                                        $q->where('estado', 'concluido')->where('created_at', '>', now()->startOfMonth());
                                    })->count();

        $this->propiedad['rechazado'] = Propiedad::whereHas('movimientoRegistral', function($q){
                                        $q->where('estado', 'rechazado')->where('created_at', '>', now()->startOfMonth());
                                    })->count();

    }

    public function mount(){

        $this->preguntas = Pregunta::latest()->take(5)->get();

        if(auth()->user()->hasRole(['Administrador', 'Jefe de departamento jurídico', 'Director'])){

            $this->cargarPropiedad();

        }elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones', 'Supervisor certificaciones'])){

        }elseif(auth()->user()->hasRole(['Jefe de departamento inscripciones', 'Supervisor inscripciones'])){

        }elseif(auth()->user()->hasRole(['Registrador Propiedad', 'Propiedad'])){

        }elseif(auth()->user()->hasRole(['Registrador Gravamen', 'Gravamen'])){

        }elseif(auth()->user()->hasRole(['Registrador Sentencias'])){

        }elseif(auth()->user()->hasRole(['Registrador Cancelación', 'Cancelación'])){

        }elseif(auth()->user()->hasRole(['Registrador Varios', 'Varios'])){

        }elseif(auth()->user()->hasRole(['Certificador Propiedad'])){

        }elseif(auth()->user()->hasRole(['Certificador Gravamen'])){

        }elseif(auth()->user()->hasRole(['Certificador Gravamen'])){

        }elseif(auth()->user()->hasRole(['Pase a folio'])){

        }elseif(auth()->user()->hasRole(['Folio real moral'])){

        }

    }
    public function render()
    {
        return view('livewire.dashboard.dashboard')->extends('layouts.admin');
    }
}
