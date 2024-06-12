<?php

namespace App\Livewire\Gravamenes;

use Livewire\Component;
use App\Models\Gravamen;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;

class GravamenIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Gravamen $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = Gravamen::make();
    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        $movimientos = $movimientoRegistral->folioReal->movimientosRegistrales()->where('estado', 'nuevo')->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($movimientoRegistral->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: " . $primerMovimiento->año . '-' . $primerMovimiento->tramite . '-' . $primerMovimiento->usuario . ' debe elaborace primero.']);

            }else{

                return redirect()->route('gravamen.inscripcion', $movimientoRegistral->gravamen);

            }

        }else{

            return redirect()->route('gravamen.inscripcion', $movimientoRegistral->gravamen);

        }

    }

    public function render()
    {


        if(auth()->user()->hasRole(['Gravamen'])){

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
                                                    ->where('estado', 'nuevo')
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->whereHas('gravamen', function($q){
                                                        $q->where('servicio', 'DL66');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador'])){

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
                                                        $q->where('servicio', 'DL66');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.gravamenes.gravamen-index', compact('movimientos'))->extends('layouts.admin');

    }

}
