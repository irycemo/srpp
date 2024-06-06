<?php

namespace App\Livewire\Varios;

use App\Models\Vario;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;

class VariosIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Vario $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = Vario::make();
    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        return redirect()->route('varios.inscripcion', $movimientoRegistral->vario);

        $movimientos = $movimientoRegistral->folioReal->movimientosRegistrales()->where('estado', 'nuevo')->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($movimientoRegistral->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: " . $primerMovimiento->año . '-' . $primerMovimiento->tramite . '-' . $primerMovimiento->usuario . ' debe elaborace primero.']);

            }else{

                return redirect()->route('varios.inscripcion', $movimientoRegistral->vario);

            }

        }else{

            return redirect()->route('varios.inscripcion', $movimientoRegistral->vario);

        }

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Varios'])){

            $movimientos = MovimientoRegistral::with('vario', 'actualizadoPor', 'folioReal')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->whereHas('vario', function($q){
                                                        $q->where('servicio', 'DL09');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador'])){

            $movimientos = MovimientoRegistral::with('vario', 'asignadoA', 'actualizadoPor', 'folioReal')
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
                                                    ->whereHas('vario', function($q){
                                                        $q->where('servicio', 'DL09');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.varios.varios-index', compact('movimientos'))->extends('layouts.admin');
    }
}
