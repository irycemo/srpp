<?php

namespace App\Livewire\Varios;

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
use App\Traits\Inscripciones\RecuperarPropietariosTrait;

class VariosIndex extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;
    use InscripcionesIndex;
    use RecuperarPropietariosTrait;

    public function corregir(MovimientoRegistral $movimientoRegistral){

        try {

            $this->revisarMovimientosPosteriores($movimientoRegistral);

            if(in_array($movimientoRegistral->vario->acto_contenido, ['DONACIÓN / VENTA DE USUFRUCTO', 'CONSOLIDACIÓN DEL USUFRUCTO', 'ACLARACIÓN ADMINISTRATIVA', 'ESCRITURA ACLARATORIA'])){

                $this->obtenerMovimientoConPropietarios($movimientoRegistral);

            }elseif($movimientoRegistral->vario->acto_contenido == 'PRIMER AVISO PREVENTIVO'){

                $this->revertirPrimerAvisoPreventivo($movimientoRegistral);

            }

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

        } catch (\Throwable $th) {
            Log::error("Error al enviar a corrección varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function revertirPrimerAvisoPreventivo(MovimientoRegistral $movimientoRegistral){

        $movimientoCertificadoGravamen = MovimientoRegistral::where('movimiento_padre', $movimientoRegistral->id)->first();

        $this->revisarMovimientosPosteriores($movimientoCertificadoGravamen);

        $movimientoCertificadoGravamen->certificacion->delete();

        $movimientoCertificadoGravamen->firmasElectronicas?->each->delete();

        foreach($movimientoCertificadoGravamen->archivos as $archivo){

            if($archivo->descripcion == 'caratula'){

                unlink('caratulas/' . $archivo->url);

            }elseif($archivo->descripcion == 'documento_entrada'){

                unlink('documento_entrada/' . $archivo->url);

            }

            $archivo->delete();

        }

        $movimientoCertificadoGravamen->delete();

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

        if(auth()->user()->hasRole(['Varios', 'Registrador Varios', 'Aclaraciones administrativas', 'Avisos preventivos'])){

            $movimientos = MovimientoRegistral::with('vario', 'actualizadoPor', 'folioReal')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->whereHas('vario', function($q){
                                                        $q->whereIn('servicio', ['DN83', 'D128', 'D112', 'D110', 'D157', 'DL19', 'DL16']);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'correccion'])
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('vario', 'actualizadoPor', 'folioReal', 'asignadoA')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['activo', 'centinela']);
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('vario', function($q){
                                                        $q->whereIn('servicio', ['DN83', 'D128', 'D112', 'D110', 'D157', 'DL19', 'DL16']);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado', 'correccion'])
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento inscripciones'])){

            $movimientos = MovimientoRegistral::with('vario', 'asignadoA', 'actualizadoPor', 'folioReal')
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
                                                            ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('vario', function($q){
                                                        $q->whereIn('servicio', ['DN83', 'D128', 'D112', 'D110', 'D157', 'DL19', 'DL16']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico'])){

            $movimientos = MovimientoRegistral::with('vario', 'asignadoA', 'actualizadoPor', 'folioReal')
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
                                                            ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->whereHas('vario', function($q){
                                                        $q->whereIn('servicio', ['DN83', 'D128', 'D112', 'D110', 'D157', 'DL19', 'DL16']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Regional'])){

            $movimientos = MovimientoRegistral::with('vario', 'actualizadoPor', 'folioReal', 'asignadoA')
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
                                                    ->whereHas('vario', function($q){
                                                        $q->whereIn('servicio', ['DN83', 'D128', 'D112', 'D110', 'D157', 'DL19', 'DL16']);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'finalizado', 'correccion'])
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.varios.varios-index', compact('movimientos'))->extends('layouts.admin');
    }
}
