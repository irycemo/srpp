<?php

namespace App\Livewire\Cancelaciones;

use Livewire\Component;
use App\Models\Gravamen;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Traits\Inscripciones\InscripcionesIndex;
use App\Exceptions\InscripcionesServiceException;

class CancelacionIndex extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;
    use InscripcionesIndex;

    public function corregir(MovimientoRegistral $movimientoRegistral){

        if($this->modelo_editar->isNot($movimientoRegistral))
            $this->modelo_editar = $movimientoRegistral;

        try {

            $this->revisarMovimientosPosteriores($movimientoRegistral);

            DB::transaction(function () use ($movimientoRegistral){

                $this->reactivarGravamen($movimientoRegistral->cancelacion->gravamenCancelado->gravamen);

                $movimientoRegistral->update([
                    'estado' => 'correccion',
                    'actualizado_por' => auth()->id()
                ]);

                $movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Cambio estado a corrección']);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (InscripcionesServiceException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al enviar a corrección cancelación por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reactivarGravamen(Gravamen $gravamen){

        $observaciones = str_replace('Cancelado parcialmente mediante movimiento registral: ' . $this->modelo_editar->folioReal->folio . '-' . $this->modelo_editar->folio, '', $gravamen->observaciones);

        $observaciones = str_replace('Cancelado mediante movimiento registral: ' . $this->modelo_editar->folioReal->folio . '-' . $this->modelo_editar->folio, '', $observaciones);

        if($gravamen->movimientoRegistral->estado == 'pase_folio'){

            $monto = $gravamen->valor_gravamen;

        }else{

            $monto = json_decode($gravamen->movimientoRegistral->firmaElectronica->cadena_original)->gravamen->valor_gravamen;

        }

        $gravamen->update([
            'observaciones' => $observaciones,
            'valor_gravamen' => $monto,
            'estado' => 'activo'
        ]);

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->años = Constantes::AÑOS;

        $this->filters['año'] = now()->format('Y');

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Cancelación', 'Registrador Cancelación'])){

            $movimientos = MovimientoRegistral::with('cancelacion', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
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
                                                    ->whereHas('cancelacion', function($q){
                                                        $q->whereIn('servicio', ['D156']);
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

        }if(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('cancelacion', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('cancelacion', function($q){
                                                        $q->whereIn('servicio', ['D156']);
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

            $movimientos = MovimientoRegistral::with('cancelacion', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('cancelacion', function($q){
                                                        $q->whereIn('servicio', ['D156']);
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

            $movimientos = MovimientoRegistral::with('cancelacion', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela', 'bloqueado']);
                                                    })
                                                    ->whereHas('cancelacion', function($q){
                                                        $q->whereIn('servicio', ['D156']);
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

        }elseif(auth()->user()->hasRole(['Regional'])){

            $movimientos = MovimientoRegistral::with('cancelacion', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
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
                                                    ->whereHas('cancelacion', function($q){
                                                        $q->whereIn('servicio', ['D156']);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado'])
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

        return view('livewire.cancelaciones.cancelacion-index', compact('movimientos'))->extends('layouts.admin');

    }
}
