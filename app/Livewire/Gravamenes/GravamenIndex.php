<?php

namespace App\Livewire\Gravamenes;

use Livewire\Component;
use App\Models\Gravamen;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class GravamenIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Gravamen $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = Gravamen::make();
    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        $movimientos = $movimientoRegistral->folioReal->movimientosRegistrales()->whereIn('estado', ['nuevo', 'elaborado'])->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($movimientoRegistral->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: (" . $movimientoRegistral->folioReal->folio . '-' . $primerMovimiento->folio . ') debe elaborace primero.']);

            }else{

                return redirect()->route('gravamen.inscripcion', $movimientoRegistral->gravamen);

            }

        }else{

            return redirect()->route('gravamen.inscripcion', $movimientoRegistral->gravamen);

        }

    }

    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        $this->dispatch('imprimir_documento', ['gravamen' => $movimientoRegistral->gravamen->id]);

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

            Log::error("Error al finalizar trámite de inscripción de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function render()
    {


        if(auth()->user()->hasRole(['Gravamen'])){

            $movimientos = MovimientoRegistral::with('gravamen', 'actualizadoPor', 'folioReal')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('estado', 'nuevo')
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->whereHas('gravamen', function($q){
                                                        $q->where('servicio', 'DL66');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor gravamen', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('gravamen', 'actualizadoPor', 'folioReal')
                                                    ->whereHas('folioReal', function($q){
                                                        $q->where('estado', 'activo');
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('estado', 'elaborado')
                                                    ->whereHas('gravamen', function($q){
                                                        $q->where('servicio', 'DL66');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador'])){

            $movimientos = MovimientoRegistral::with('gravamen', 'asignadoA', 'actualizadoPor', 'folioReal')
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
                                                    ->whereHas('gravamen', function($q){
                                                        $q->where('servicio', 'DL66');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.gravamenes.gravamen-index', compact('movimientos'))->extends('layouts.admin');

    }

}
