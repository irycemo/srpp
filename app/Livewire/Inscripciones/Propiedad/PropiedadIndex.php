<?php

namespace App\Livewire\Inscripciones\Propiedad;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Traits\Inscripciones\InscripcionesIndex;
use App\Exceptions\InscripcionesServiceException;
use App\Http\Services\InscripcionesPropiedadService;

class PropiedadIndex extends Component
{
    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;
    use InscripcionesIndex;

    public function corregir(MovimientoRegistral $movimientoRegistral){

        if(in_array($movimientoRegistral->inscripcionPropiedad->service, ['D149	'])){

            return;

        }

        try {

            DB::transaction(function () use ($movimientoRegistral){

                (new InscripcionesPropiedadService())->corregir($movimientoRegistral);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (InscripcionesServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al enviar a corrección inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereIn('name', ['Propiedad', 'Registrador Propiedad']);
                                        })
                                        ->orderBy('name')
                                        ->get();

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Propiedad', 'Registrador Propiedad', 'Registrador fraccionamientos'])){

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
                                                        $q->whereIn('servicio', ['D158', 'D114', 'D113', 'D115', 'D116', 'D118', 'D149']);
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
                                                        $q->whereIn('servicio', ['D158', 'D114', 'D113', 'D115', 'D116', 'D118', 'D149']);
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
                                                        $q->whereIn('servicio', ['D158', 'D114', 'D113', 'D115', 'D116', 'D118', 'D149']);
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
                                                        $q->whereIn('servicio', ['D158', 'D114', 'D113', 'D115', 'D116', 'D118', 'D149']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Regional'])){

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
                                                    ->when(auth()->user()->ubicacion === 'Regional 1', function($q){
                                                        $q->whereIn('distrito', [3, 9]);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 2', function($q){
                                                        $q->whereIn('distrito', [12, 19]);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 3', function($q){
                                                        $q->whereIn('distrito', [4, 17]);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 4', function($q){
                                                        $q->whereIn('distrito', [2, 18]);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 5', function($q){
                                                        $q->where('distrito', 13);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 6', function($q){
                                                        $q->where('distrito', 15);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 7', function($q){
                                                        $q->whereIn('distrito', [5, 14, 8]);
                                                    })
                                                    ->whereHas('inscripcionPropiedad', function($q){
                                                        $q->whereIn('servicio', ['D158', 'D114', 'D113', 'D115', 'D116', 'D118', 'D149']);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado', 'correccion'])
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.inscripciones.propiedad.propiedad-index', compact('movimientos'))->extends('layouts.admin');
    }
}
