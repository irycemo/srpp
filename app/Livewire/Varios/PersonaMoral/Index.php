<?php

namespace App\Livewire\Varios\PersonaMoral;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;
use App\Traits\Inscripciones\InscripcionesIndex;

class Index extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;
    use InscripcionesIndex;

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        $this->ruta($movimientoRegistral);

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereIn('name', ['Varios', 'Registrador Varios']);
                                        })
                                        ->orderBy('name')
                                        ->get();

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Varios', 'Registrador Varios'])){

            $movimientos = MovimientoRegistral::with('vario', 'actualizadoPor', 'folioRealPersona')
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->whereHas('vario', function($q){
                                                                $q->where('servicio', 'D110');
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'correccion'])
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor varios', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('vario', 'actualizadoPor', 'folioRealPersona', 'asignadoA')
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->whereHas('vario', function($q){
                                                                $q->where('servicio', 'D110');
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'correccion', 'finalizado'])
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento'])){

            $movimientos = MovimientoRegistral::with('vario', 'asignadoA', 'actualizadoPor', 'folioRealPersona')
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->whereHas('vario', function($q){
                                                                $q->where('servicio', 'D110');
                                                            });
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
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador'])){

            $movimientos = MovimientoRegistral::with('vario', 'asignadoA', 'actualizadoPor', 'folioRealPersona')
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->whereHas('vario', function($q){
                                                                $q->where('servicio', 'D110');
                                                            });
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
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.varios.persona-moral.index', compact('movimientos'))->extends('layouts.admin');

    }
}
