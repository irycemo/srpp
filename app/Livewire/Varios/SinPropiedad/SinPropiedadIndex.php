<?php

namespace App\Livewire\Varios\SinPropiedad;

use App\Constantes\Constantes;
use App\Models\MovimientoRegistral;
use App\Models\User;
use App\Traits\ComponentesTrait;
use App\Traits\Inscripciones\AutorizarImpresionTrait;
use App\Traits\Inscripciones\EnviarMovimientoCorreccion;
use App\Traits\Inscripciones\FinalizarInscripcionTrait;
use App\Traits\Inscripciones\InscripcionesIndex;
use App\Traits\Inscripciones\ReasignarmeMovimientoTrait;
use App\Traits\Inscripciones\ReasignarUsuarioTrait;
use App\Traits\Inscripciones\RechazarMovimientoTrait;
use App\Traits\Inscripciones\RecibirDocumentoTrait;
use App\Traits\Inscripciones\RecuperarPredioTrait;
use App\Traits\RevisarMovimientosPosterioresTrait;
use Livewire\Component;
use Livewire\WithPagination;

class SinPropiedadIndex extends Component
{

    use WithPagination;
    use InscripcionesIndex;
    use ComponentesTrait;
    use RevisarMovimientosPosterioresTrait;
    use RechazarMovimientoTrait;
    use RecuperarPredioTrait;
    use EnviarMovimientoCorreccion;
    use RecibirDocumentoTrait;
    use FinalizarInscripcionTrait;
    use ReasignarUsuarioTrait;
    use ReasignarmeMovimientoTrait;
    use AutorizarImpresionTrait;

    public function mount(){

        $this->crearModeloVacio();

        $this->años = Constantes::AÑOS;

        $this->motivos_rechazo = Constantes::RECHAZO_MOTIVOS;

        $this->usuarios_regionales = Constantes::USUARIOS_REGIONALES;

        if(auth()->user()->hasRole(['Regional'])){

            $regional = auth()->user()->ubicacion[-1];

            $this->usuarios_regionales_fliped = array_keys($this->usuarios_regionales, $regional);

        }

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereIn('name', ['Varios', 'Registrador Varios']);
                                        })
                                        ->orderBy('name')
                                        ->get();

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Varios', 'Registrador Varios', 'Aclaraciones administrativas', 'Avisos preventivos'])){

            $movimientos = MovimientoRegistral::select('id', 'folio', 'año', 'tramite', 'servicio_nombre', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('vario:id,movimiento_registral_id', 'actualizadoPor:id,name', 'asignadoA:id,name')
                                                    ->whereNull('folio_real')
                                                    ->whereHas('vario')
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->where('servicio_nombre', 'Inscripciones varios sin afectación de propiedad')
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'correccion', 'no recibido', 'autorizado'])
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor uruapan', 'Operaciones'])){

            $movimientos = MovimientoRegistral::select('id', 'folio', 'año', 'tramite', 'servicio_nombre', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('vario:id,movimiento_registral_id', 'actualizadoPor:id,name', 'asignadoA:id,name')
                                                    ->whereNull('folio_real')
                                                    ->whereHas('vario')
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('servicio_nombre', 'Inscripciones varios sin afectación de propiedad')
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado', 'correccion', 'no recibido', 'autorizado'])
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento inscripciones'])){

            $movimientos = MovimientoRegistral::select('id', 'folio', 'año', 'tramite', 'servicio_nombre', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('vario:id,movimiento_registral_id', 'actualizadoPor:id,name', 'asignadoA:id,name')
                                                    ->whereNull('folio_real')
                                                    ->whereHas('vario')
                                                    ->where('servicio_nombre', 'Inscripciones varios sin afectación de propiedad')
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico'])){

            $movimientos = MovimientoRegistral::select('id', 'folio', 'año', 'tramite', 'servicio_nombre', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('vario:id,movimiento_registral_id', 'actualizadoPor:id,name', 'asignadoA:id,name')
                                                    ->whereNull('folio_real')
                                                    ->whereHas('vario')
                                                    ->where('servicio_nombre', 'Inscripciones varios sin afectación de propiedad')
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Regional'])){

            $movimientos = MovimientoRegistral::select('id', 'folio', 'año', 'tramite', 'servicio_nombre', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega')
                                                    ->with('vario:id,movimiento_registral_id', 'actualizadoPor:id,name', 'asignadoA:id,name')
                                                    ->whereNull('folio_real')
                                                    ->whereHas('vario')
                                                    ->where('servicio_nombre', 'Inscripciones varios sin afectación de propiedad')
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
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado', 'correccion', 'rechazado', 'autorizado'])
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.varios.sin-propiedad.sin-propiedad-index', compact('movimientos'))->extends('layouts.admin');

    }

}
