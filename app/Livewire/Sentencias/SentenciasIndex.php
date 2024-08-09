<?php

namespace App\Livewire\Sentencias;

use App\Models\File;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class SentenciasIndex extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;

    public MovimientoRegistral $modelo_editar;

    public $modalFinalizar = false;
    public $documento;

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        $movimientoAsignado = MovimientoRegistral::whereIn('estado', ['nuevo', 'captura'])
                                                        ->where('usuario_Asignado', auth()->id())
                                                        ->orderBy('created_at')
                                                        ->first();

        if($movimientoAsignado && $movimientoRegistral->id != $movimientoAsignado->id){

            $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $movimientoAsignado->folioReal->folio . '-' . $movimientoAsignado->folio . ' primero.']);

            return;

        }

        if($movimientoRegistral->folioReal->avisoPreventivo()){

            $this->dispatch('mostrarMensaje', ['warning', "El folio real tiene un aviso preventivo vigente."]);

            return;

        }

        $movimientos = $movimientoRegistral->folioReal->movimientosRegistrales()->whereIn('estado', ['nuevo', 'elaborado'])->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($movimientoRegistral->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: (" . $movimientoRegistral->folioReal->folio . '-' . $primerMovimiento->folio . ') debe elaborarce primero.']);

            }else{

                return redirect()->route('sentencias.inscripcion', $movimientoRegistral->sentencia);

            }

        }else{

            return redirect()->route('sentencias.inscripcion', $movimientoRegistral->sentencia);

        }

    }

    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        $this->dispatch('imprimir_documento', ['sentencia' => $movimientoRegistral->sentencia->id]);

    }

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        $this->reset('documento');

        $this->dispatch('removeFiles');

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function finalizar(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                $pdf = $this->documento->store('/', 'caratulas');

                File::create([
                    'fileable_id' => $this->modelo_editar->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'caratula',
                    'url' => $pdf
                ]);

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'concluido';

                $this->modelo_editar->save();

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Sentencias', 'Registrador Sentencias'])){

            $movimientos = MovimientoRegistral::with('sentencia', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
                                                    ->where('usuario_asignado', auth()->id())
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
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('sentencia', function($q){
                                                        $q->whereIn('servicio', ['D110']);
                                                    })
                                                    ->whereIn('estado', ['nuevo', 'captura'])
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

    }elseif(auth()->user()->hasRole(['Supervisor sentencias', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('sentencia', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
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
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('sentencia', function($q){
                                                        $q->whereIn('servicio', ['D110']);
                                                    })
                                                    ->where('estado', 'elaborado')
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

    }elseif(auth()->user()->hasRole(['Supervisor sentencias', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('sentencia', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
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
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('sentencia', function($q){
                                                        $q->whereIn('servicio', ['D110']);
                                                    })
                                                    ->where('estado', 'elaborado')
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador'])){

            $movimientos = MovimientoRegistral::with('sentencia', 'asignadoA', 'actualizadoPor', 'folioReal:id,folio')
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
                                                    ->whereHas('sentencia', function($q){
                                                        $q->whereIn('servicio', ['D110']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.sentencias.sentencias-index', compact('movimientos'))->extends('layouts.admin');
    }
}
