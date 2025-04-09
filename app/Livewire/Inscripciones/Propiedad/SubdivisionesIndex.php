<?php

namespace App\Livewire\Inscripciones\Propiedad;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;
use App\Traits\Inscripciones\InscripcionesIndex;

class SubdivisionesIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use InscripcionesIndex;

    public function mount(){

        $this->crearModeloVacio();

        $this->años = Constantes::AÑOS;

        $this->año = now()->format('Y');

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Registrador fraccionamientos');
                                        })
                                        ->orderBy('name')
                                        ->get();

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Propiedad', 'Registrador fraccionamientos'])){

            $movimientos = MovimientoRegistral::with('inscripcionPropiedad', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('inscripcionPropiedad', function($q){
                                                        $q->where('servicio', 'D127');
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'correccion'])
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('inscripcionPropiedad', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('inscripcionPropiedad', function($q){
                                                        $q->where('servicio', 'D127');
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado', 'correccion'])
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento inscripciones'])){

            $movimientos = MovimientoRegistral::with('inscripcionPropiedad', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('inscripcionPropiedad', function($q){
                                                        $q->where('servicio', 'D127');
                                                    })
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico'])){

            $movimientos = MovimientoRegistral::with('inscripcionPropiedad', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela', 'bloqueado']);
                                                    })
                                                    ->whereHas('inscripcionPropiedad', function($q){
                                                        $q->where('servicio', 'D127');
                                                    })
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.inscripciones.propiedad.subdivisiones-index', compact('movimientos'))->extends('layouts.admin');
    }
}
