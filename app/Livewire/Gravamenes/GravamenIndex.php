<?php

namespace App\Livewire\Gravamenes;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;
use App\Traits\Inscripciones\InscripcionesIndex;

class GravamenIndex extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;
    use InscripcionesIndex;

    public function mount(){

        $this->crearModeloVacio();

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereIn('name', ['Gravamen', 'Registrador Gravamen']);
                                        })
                                        ->orderBy('name')
                                        ->get();

    }

    public function render()
    {


        if(auth()->user()->hasRole(['Gravamen', 'Registrador Gravamen'])){

            $movimientos = MovimientoRegistral::with('gravamen', 'actualizadoPor', 'folioReal')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado'])
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->whereHas('gravamen', function($q){
                                                        $q->whereIn('servicio', ['DL07', 'DM68', 'D155', 'D150']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor gravamen', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('gravamen', 'actualizadoPor', 'folioReal', 'asignadoA')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado'])
                                                    ->whereHas('gravamen', function($q){
                                                        $q->whereIn('servicio', ['DL07', 'DM68', 'D155', 'D150']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento inscripciones'])){

            $movimientos = MovimientoRegistral::with('gravamen', 'asignadoA', 'actualizadoPor', 'folioReal')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->where('estado', 'activo');
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
                                                            ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('gravamen', function($q){
                                                        $q->whereIn('servicio', ['DL07', 'DM68', 'D155', 'D150']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador'])){

            $movimientos = MovimientoRegistral::with('gravamen', 'asignadoA', 'actualizadoPor', 'folioReal')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->where('estado', 'activo');
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
                                                            ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->whereHas('gravamen', function($q){
                                                        $q->whereIn('servicio', ['DL07', 'DM68', 'D155', 'D150']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.gravamenes.gravamen-index', compact('movimientos'))->extends('layouts.admin');

    }

}
