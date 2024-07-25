<?php

namespace App\Livewire\Varios;

use App\Models\Vario;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class VariosIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Vario $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = Vario::make();
    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        $movimientos = $movimientoRegistral->folioReal->movimientosRegistrales()->whereIn('estado', ['nuevo', 'elaborado'])->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($movimientoRegistral->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: (" . $movimientoRegistral->folioReal->folio . '-' . $primerMovimiento->folio . ') debe elaborace primero.']);

            }else{

                return redirect()->route('varios.inscripcion', $movimientoRegistral->vario);

            }

        }else{

            return redirect()->route('varios.inscripcion', $movimientoRegistral->vario);

        }

    }

    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        $this->dispatch('imprimir_documento', ['vario' => $movimientoRegistral->vario->id]);

    }

    public function finalizar(MovimientoRegistral $modelo){

        try {

            DB::transaction(function () use ($modelo){

                $modelo->actualizado_por = auth()->user()->id;

                $modelo->estado = 'concluido';

                $modelo->save();

                (new SistemaTramitesService())->finaliarTramite($modelo->año, $modelo->tramite, $modelo->usuario, 'concluido');

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Varios', 'Registrador Varios'])){

            $movimientos = MovimientoRegistral::with('vario', 'actualizadoPor', 'folioReal')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->whereHas('vario', function($q){
                                                        $q->where('servicio', 'DL09');
                                                    })
                                                    ->where('estado', 'nuevo')
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor varios', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('vario', 'actualizadoPor', 'folioReal')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereHas('vario', function($q){
                                                        $q->where('servicio', 'DL09');
                                                    })
                                                    ->where('estado', 'elaborado')
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador'])){

            $movimientos = MovimientoRegistral::with('vario', 'asignadoA', 'actualizadoPor', 'folioReal')
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
                                                    ->whereHas('vario', function($q){
                                                        $q->where('servicio', 'DL09');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.varios.varios-index', compact('movimientos'))->extends('layouts.admin');
    }
}
