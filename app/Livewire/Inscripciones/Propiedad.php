<?php

namespace App\Livewire\Inscripciones;

use App\Models\MovimientoRegistral;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Propiedad as ModelPropiedad;
use App\Traits\ComponentesTrait;

class Propiedad extends Component
{
    use WithPagination;
    use ComponentesTrait;

    public ModelPropiedad $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = ModelPropiedad::make();
    }

    public function render()
    {

        $movimientos = MovimientoRegistral::with('inscripcionPropiedad', 'asignadoA', 'actualizadoPor')
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
                                                ->whereHas('inscripcionPropiedad', function($q){
                                                    $q->whereIn('servicio', ['D158', 'DC91', 'D122', 'D114', 'D125']);
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        return view('livewire.inscripciones.propiedad', compact('movimientos'))->extends('layouts.admin');
    }
}
