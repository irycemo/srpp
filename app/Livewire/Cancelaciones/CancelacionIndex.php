<?php

namespace App\Livewire\Cancelaciones;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;
use App\Traits\Inscripciones\InscripcionesIndex;
use App\Traits\Inscripciones\EnviarMovimientoCorreccion;
use App\Traits\Inscripciones\RechazarMovimientoTrait;
use App\Traits\RevisarMovimientosPosterioresTrait;

class CancelacionIndex extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;
    use InscripcionesIndex;
    use RevisarMovimientosPosterioresTrait;
    use RechazarMovimientoTrait;
    use EnviarMovimientoCorreccion;

    public function mount(){

        $this->crearModeloVacio();

        $this->años = Constantes::AÑOS;

        $this->motivos_rechazo = Constantes::RECHAZO_MOTIVOS;

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereIn('name', ['Cancelación', 'Registrador cancelación']);
                                        })
                                        ->orderBy('name')
                                        ->get();

        $this->usuarios_regionales = Constantes::USUARIOS_REGIONALES;

        if(auth()->user()->hasRole(['Regional'])){

            $regional = auth()->user()->ubicacion[-1];

            $this->usuarios_regionales_fliped = array_keys($this->usuarios_regionales, $regional);

        }

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Cancelación', 'Registrador Cancelación'])){

            $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('cancelacion:id,movimiento_registral_id', 'asignadoA:id,name', 'actualizadoPor:id,name', 'folioReal:id,folio')
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
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'correccion', 'no recibido'])
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

            $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('cancelacion:id,movimiento_registral_id', 'asignadoA:id,name', 'actualizadoPor:id,name', 'folioReal:id,folio')
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
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado', 'correccion', 'no recibido'])
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

            $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('cancelacion:id,movimiento_registral_id', 'asignadoA:id,name', 'actualizadoPor:id,name', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    /* ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    }) */
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

            $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('cancelacion:id,movimiento_registral_id', 'asignadoA:id,name', 'actualizadoPor:id,name', 'folioReal:id,folio')
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

            $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('cancelacion:id,movimiento_registral_id', 'asignadoA:id,name', 'actualizadoPor:id,name', 'folioReal:id,folio')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 1', function($q){
                                                        $q->whereIn('distrito', [3, 9])
                                                            ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 2', function($q){
                                                        $q->whereIn('distrito', [12, 19])
                                                            ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 3', function($q){
                                                        $q->whereIn('distrito', [4, 17])
                                                            ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 4', function($q){
                                                        $q->whereIn('distrito', [2, 18])
                                                            ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 5', function($q){
                                                        $q->where('distrito', 13)
                                                            ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 6', function($q){
                                                        $q->where('distrito', 15)
                                                            ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
                                                    })
                                                    ->when(auth()->user()->ubicacion === 'Regional 7', function($q){
                                                        $q->whereIn('distrito', [5, 14, 8])
                                                            ->orWhereIn('usuario', $this->usuarios_regionales_fliped);
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
