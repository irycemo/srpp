<?php

namespace App\Livewire\Inscripciones\Propiedad;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;
use App\Traits\Inscripciones\InscripcionesIndex;

class FraccionamientosIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use InscripcionesIndex;

    public function mount(){

        $this->crearModeloVacio();

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
                                                    ->where(function($q){
                                                        $q->whereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            })
                                                            ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('folio', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('inscripcionPropiedad', function($q){
                                                        $q->whereIn('servicio', ['D121', 'D120', 'D123', 'D122', 'D119', 'D124', 'D125', 'D126']);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'correccion'])
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('inscripcionPropiedad', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->where(function($q){
                                                        $q->whereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            })
                                                            ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('folio', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('inscripcionPropiedad', function($q){
                                                        $q->whereIn('servicio', ['D121', 'D120', 'D123', 'D122', 'D119', 'D124', 'D125', 'D126']);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado', 'correccion'])
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento inscripciones'])){

            $movimientos = MovimientoRegistral::with('inscripcionPropiedad', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->where(function($q){
                                                        $q->whereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            })
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->where('folio', $this->search);
                                                            })
                                                            ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('estado', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('folio', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('inscripcionPropiedad', function($q){
                                                        $q->whereIn('servicio', ['D121', 'D120', 'D123', 'D122', 'D119', 'D124', 'D125', 'D126']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico'])){

            $movimientos = MovimientoRegistral::with('inscripcionPropiedad', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela', 'bloqueado']);
                                                    })
                                                    ->where(function($q){
                                                        $q->whereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            })
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->where('folio', $this->search);
                                                            })
                                                            ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('estado', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('folio', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                    })
                                                    ->whereHas('inscripcionPropiedad', function($q){
                                                        $q->whereIn('servicio', ['D121', 'D120', 'D123', 'D122', 'D119', 'D124', 'D125', 'D126']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.inscripciones.propiedad.fraccionamientos-index', compact('movimientos'))->extends('layouts.admin');
    }

}
