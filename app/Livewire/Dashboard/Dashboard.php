<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Pregunta;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;

class Dashboard extends Component
{

    public $preguntas;
    public $propiedad = [];

    public function cargarPropiedad($user_id = null){

        $this->propiedad = MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                                            ->when($user_id, function($q) use($user_id){
                                                $q->where('usuario_asignado', $user_id);
                                            })
                                            ->where('created_at', '>', now()->startOfMonth())
                                            ->whereHas('inscripcionPropiedad')
                                            ->groupBy('estado')
                                            ->get()
                                            ->map(function($movimiento){
                                                return [$movimiento->estado => $movimiento->count];
                                            })->toArray();

        $this->propiedad = array_values($this->propiedad);

        dd($this->propiedad);

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
