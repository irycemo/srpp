<?php

namespace App\Livewire\PersonaMoral;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;
use App\Traits\Inscripciones\InscripcionesIndex;

class PaseAFolio extends Component
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
                                            $q->whereIn('name', ['Folio real moral']);
                                        })
                                        ->orderBy('name')
                                        ->get();

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Folio real moral'])){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioRealPersona')
                                                    ->has('reformaMoral')
                                                    ->where('folio', 1)
                                                    ->whereIn('estado', ['nuevo', 'correccion'])
                                                    ->where('usuario_asignado', auth()->user()->id)
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real_persona')
                                                            ->orWhereHas('folioRealPersona', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado']);
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioRealPersona', 'asignadoA')
                                                    ->has('reformaMoral')
                                                    ->where('folio', 1)
                                                    ->where('usuario_supervisor', auth()->user()->id)
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real_persona')
                                                            ->orWhereHas('folioRealPersona', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado']);
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico', 'Jefe de departamento inscripciones', 'Director'])){

            $movimientos = MovimientoRegistral::with('asignadoA', 'actualizadoPor', 'folioRealPersona')
                                                ->has('reformaMoral')
                                                ->where('folio', 1)
                                                ->where(function($q){
                                                    $q->whereNull('folio_real_persona')
                                                        ->orWhereHas('folioRealPersona', function($q){
                                                            $q->whereIn('estado', ['nuevo', 'captura', 'elaborado']);
                                                        });
                                                })
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }

        return view('livewire.persona-moral.pase-a-folio', compact('movimientos'))->extends('layouts.admin');

    }
}
