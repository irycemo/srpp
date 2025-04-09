<?php

namespace App\Livewire\PersonaMoral;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Traits\Inscripciones\InscripcionesIndex;
use App\Exceptions\InscripcionesServiceException;

class ReformasIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use InscripcionesIndex;

    public function estaBloqueado(){

        $movimientos = $this->actual->folioRealPersona->movimientosRegistrales()->whereIn('estado', ['nuevo', 'captura', 'correccion'])->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($this->actual->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: (" . $this->actual->folioRealPersona->folio . '-' . $primerMovimiento->folio . ') debe elaborarce primero.']);

                return true;

            }else{

               return false;

            }

        }else{

            return false;

        }

    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        if($movimientoRegistral->folioRealPersona->estado == 'centinela'){

            $this->dispatch('mostrarMensaje', ['warning', "El folio real esta en centinela."]);

            return;

        }

        if(auth()->user()->hasRole('Jefe de departamento inscripciones')){

            $this->actual = $movimientoRegistral;

            if($this->estaBloqueado()){

                return;

            }else{

                $this->ruta($movimientoRegistral);

                return;
            }

        }

        $movimientoAsignados = MovimientoRegistral::withWhereHas('folioRealPersona', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'correccion'])
                                                    ->where('usuario_Asignado', auth()->id())
                                                    ->orderBy('created_at')
                                                    ->get();

        foreach($movimientoAsignados as $movimiento){

            $this->actual = $movimiento;

            if($this->estaBloqueado()){

                /* Esta bloqueado y es el que esta intentando hacer */
                if($this->modelo_editar->id == $this->actual->id){

                    break;

                }else{

                    continue;

                }

            }else{

                /* Si solo hay un movimiento por realizar y no esta bloqueado */
                if($movimientoAsignados->count() == 1 && $this->actual->id == $this->modelo_editar->id){

                    $this->ruta($this->modelo_editar);

                }

                /* Revisar si es el que debe hacer ($this->actual) */
                if($movimientoRegistral->id != $this->actual->id){

                    $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $this->actual->folioRealPersona->folio . '-' . $this->actual->folio . ' primero.']);

                    return;

                }else{

                    $this->ruta($movimientoRegistral);

                    break;

                }

            }

        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->años = Constantes::AÑOS;

        $this->año = now()->format('Y');

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereIn('name', ['Folio real moral']);
                                        })
                                        ->orderBy('name')
                                        ->get();

    }

    public function revisarMovimientosPosteriores(MovimientoRegistral $movimientoRegistral){

        $movimiento = $movimientoRegistral->folioRealPersona
                ->movimientosRegistrales()
                ->where('folio', ($movimientoRegistral->folio + 1))
                ->where('estado', '!=', 'nuevo')
                ->first();

        if($movimiento) throw new InscripcionesServiceException("El folio real tiene movimientos registrales posteriores ya elaborados.");

    }

    public function corregir(MovimientoRegistral $movimientoRegistral){

        try {

            $this->revisarMovimientosPosteriores($movimientoRegistral);

            DB::transaction(function () use ($movimientoRegistral){

                $movimientoRegistral->update([
                    'estado' => 'correccion',
                    'actualizado_por' => auth()->id()
                ]);

                $movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Cambio estado a corrección']);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (InscripcionesServiceException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        }catch (\Throwable $th) {
            Log::error("Error al enviar a corrección gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Folio real moral'])){

            $movimientos = MovimientoRegistral::with('reformaMoral', 'actualizadoPor', 'folioRealPersona')
                                                    ->has('reformaMoral')
                                                    ->WhereHas('folioRealPersona', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'correccion'])
                                                    ->where('usuario_asignado', auth()->user()->id)
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
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

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('reformaMoral', 'actualizadoPor', 'folioRealPersona', 'asignadoA')
                                                    ->has('reformaMoral')
                                                    ->WhereHas('folioRealPersona', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where(function($q){
                                                        $q->whereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            })
                                                            ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('estado', 'LIKE', '%' . $this->search . '%');
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

            $movimientos = MovimientoRegistral::with('reformaMoral', 'actualizadoPor', 'folioRealPersona', 'asignadoA')
                                                    ->has('reformaMoral')
                                                    ->WhereHas('folioRealPersona', function($q){
                                                        $q->where('estado', 'activo');
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
                                                            ->orWhere('estado', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
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

            $movimientos = MovimientoRegistral::with('asignadoA', 'actualizadoPor', 'folioRealPersona')
                                                    ->has('reformaMoral')
                                                    ->WhereHas('folioRealPersona', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                   /*  ->where(function($q){
                                                        $q->whereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            })
                                                            ->orWhereHas('folioRealPersona', function($q){
                                                                $q->where('folio', $this->search);
                                                            })
                                                            ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('estado', 'LIKE', '%' . $this->search . '%');
                                                    }) */
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

        return view('livewire.persona-moral.reformas-index', compact('movimientos'))->extends('layouts.admin');

    }

}
