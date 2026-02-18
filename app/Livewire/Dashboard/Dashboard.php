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
    public $gravamen = [];
    public $sentencia = [];
    public $cancelacion = [];
    public $varios = [];
    public $certificado_propiedad = [];
    public $certificado_gravamen = [];
    public $pase_a_folio = [];
    public $reforma = [];

    public function cargarPropiedad($user_id = null){

        MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                            ->when($user_id, function($q) use($user_id){
                                $q->where('usuario_asignado', $user_id);
                            })
                            ->where('created_at', '>', now()->startOfMonth())
                            ->whereHas('inscripcionPropiedad')
                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                $q->where('distrito', 2);
                            })
                            ->groupBy('estado')
                            ->get()
                            ->map(function($movimiento){
                                $this->propiedad[$movimiento->estado] = $movimiento->count;
                            });
    }

    public function cargarGravamen($user_id = null){

        MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                            ->when($user_id, function($q) use($user_id){
                                $q->where('usuario_asignado', $user_id);
                            })
                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                $q->where('distrito', 2);
                            })
                            ->where('created_at', '>', now()->startOfMonth())
                            ->whereHas('gravamen')
                            ->groupBy('estado')
                            ->get()
                            ->map(function($movimiento){
                                $this->gravamen[$movimiento->estado] = $movimiento->count;
                            });
    }

    public function cargarSentencia($user_id = null){

        MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                            ->when($user_id, function($q) use($user_id){
                                $q->where('usuario_asignado', $user_id);
                            })
                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                $q->where('distrito', 2);
                            })
                            ->where('created_at', '>', now()->startOfMonth())
                            ->whereHas('sentencia')
                            ->groupBy('estado')
                            ->get()
                            ->map(function($movimiento){
                                $this->sentencia[$movimiento->estado] = $movimiento->count;
                            });
    }

    public function cargarCancelacion($user_id = null){

        MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                            ->when($user_id, function($q) use($user_id){
                                $q->where('usuario_asignado', $user_id);
                            })
                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                $q->where('distrito', 2);
                            })
                            ->where('created_at', '>', now()->startOfMonth())
                            ->whereHas('cancelacion')
                            ->groupBy('estado')
                            ->get()
                            ->map(function($movimiento){
                                $this->cancelacion[$movimiento->estado] = $movimiento->count;
                            });
    }

    public function cargarVarios($user_id = null){

        MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                            ->when($user_id, function($q) use($user_id){
                                $q->where('usuario_asignado', $user_id);
                            })
                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                $q->where('distrito', 2);
                            })
                            ->where('created_at', '>', now()->startOfMonth())
                            ->whereHas('vario')
                            ->groupBy('estado')
                            ->get()
                            ->map(function($movimiento){
                                $this->varios[$movimiento->estado] = $movimiento->count;
                            });
    }

    public function cargarCertificadoGravamen($user_id = null){

        MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                            ->when($user_id, function($q) use($user_id){
                                $q->where('usuario_asignado', $user_id);
                            })
                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                $q->where('distrito', 2);
                            })
                            ->where('created_at', '>', now()->startOfMonth())
                            ->whereHas('certificacion', function($q){
                                $q->where('servicio', 'DL07');
                            })
                            ->groupBy('estado')
                            ->get()
                            ->map(function($movimiento){
                                $this->certificado_gravamen[$movimiento->estado] = $movimiento->count;
                            });
    }

    public function cargarCertificadoPropiedad($user_id = null){

        MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                            ->when($user_id, function($q) use($user_id){
                                $q->where('usuario_asignado', $user_id);
                            })
                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                $q->where('distrito', 2);
                            })
                            ->where('created_at', '>', now()->startOfMonth())
                            ->whereHas('certificacion', function($q){
                                $q->where('servicio', 'DL10');
                            })
                            ->groupBy('estado')
                            ->get()
                            ->map(function($movimiento){
                                $this->certificado_propiedad[$movimiento->estado] = $movimiento->count;
                            });
    }

    public function cargarPaseAFolio($user_id = null){

        MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                            ->when($user_id, function($q) use($user_id){
                                $q->where('usuario_asignado', $user_id);
                            })
                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                $q->where('distrito', 2);
                            })
                            ->where('created_at', '>', now()->startOfMonth())
                            ->whereIn('estado', ['nuevo', 'correccion', 'no_recibido'])
                            ->where('folio', 1)
                            ->where('pase_a_folio', true)
                            ->whereHas('folioReal', function($q){
                                $q->where('estado', '!=', 'activo');
                            })
                            ->groupBy('estado')
                            ->get()
                            ->map(function($movimiento){
                                $this->certificado_propiedad[$movimiento->estado] = $movimiento->count;
                            });
    }

    public function cargarReforma($user_id = null){

        MovimientoRegistral::select('estado', DB::raw('count(*) as count'))
                            ->when($user_id, function($q) use($user_id){
                                $q->where('usuario_asignado', $user_id);
                            })
                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                $q->where('distrito', 2);
                            })
                            ->where('created_at', '>', now()->startOfMonth())
                            ->whereHas('reformaMoral')
                            ->groupBy('estado')
                            ->get()
                            ->map(function($movimiento){
                                $this->certificado_propiedad[$movimiento->estado] = $movimiento->count;
                            });
    }

    public function mount(){

        $this->preguntas = Pregunta::latest()->take(5)->get();

        if(auth()->user()->hasRole(['Administrador', 'Jefe de departamento jurídico', 'Director', 'Supervisor uruapan'])){

            $this->cargarPropiedad();
            $this->cargarGravamen();
            $this->cargarSentencia();
            $this->cargarCancelacion();
            $this->cargarVarios();
            $this->cargarCertificadoGravamen();
            $this->cargarCertificadoPropiedad();
            $this->cargarPaseAFolio();
            $this->cargarReforma();

        }elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones', 'Supervisor certificaciones'])){

            $this->cargarCertificadoGravamen();
            $this->cargarCertificadoPropiedad();

        }elseif(auth()->user()->hasRole(['Jefe de departamento inscripciones', 'Supervisor inscripciones'])){

            $this->cargarPropiedad();
            $this->cargarGravamen();
            $this->cargarSentencia();
            $this->cargarCancelacion();
            $this->cargarVarios();
            $this->cargarPaseAFolio();
            $this->cargarReforma();

        }elseif(auth()->user()->hasRole(['Registrador Propiedad', 'Propiedad'])){

            $this->cargarPropiedad(auth()->id());

        }elseif(auth()->user()->hasRole(['Registrador Gravamen', 'Gravamen'])){

            $this->cargarGravamen(auth()->id());

        }elseif(auth()->user()->hasRole(['Registrador Sentencias'])){

            $this->cargarSentencia(auth()->id());

        }elseif(auth()->user()->hasRole(['Registrador Cancelación', 'Cancelación'])){

            $this->cargarCancelacion(auth()->id());

        }elseif(auth()->user()->hasRole(['Registrador Varios', 'Varios'])){

            $this->cargarVarios(auth()->id());

        }elseif(auth()->user()->hasRole(['Certificador Propiedad'])){

            $this->cargarCertificadoPropiedad(auth()->id());

        }elseif(auth()->user()->hasRole(['Certificador Gravamen'])){

            $this->cargarCertificadoGravamen(auth()->id());

        }elseif(auth()->user()->hasRole(['Pase a folio'])){

            $this->cargarPaseAFolio(auth()->id());

        }elseif(auth()->user()->hasRole(['Folio real moral'])){

            $this->cargarReforma(auth()->id());

        }

    }

    public function render()
    {
        return view('livewire.dashboard.dashboard')->extends('layouts.admin');
    }
}
