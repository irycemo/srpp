<?php

namespace App\Livewire\Gravamenes;

use App\Models\File;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class GravamenIndex extends Component
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

                return redirect()->route('gravamen.inscripcion', $movimientoRegistral->gravamen);

            }

        }else{

            return redirect()->route('gravamen.inscripcion', $movimientoRegistral->gravamen);

        }

    }

    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        $this->dispatch('imprimir_documento', ['gravamen' => $movimientoRegistral->gravamen->id]);

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

            Log::error("Error al finalizar trámite de inscripción de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function render()
    {


        if(auth()->user()->hasRole(['Gravamen', 'Registrador Gravamen'])){

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
                                                    ->whereIn('estado', ['nuevo', 'captura'])
                                                    ->where('usuario_asignado', auth()->id())
                                                    ->whereHas('gravamen', function($q){
                                                        $q->where('servicio', 'DL64');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor gravamen', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('gravamen', 'actualizadoPor', 'folioReal', 'asignadoA')
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
                                                        $q->where('servicio', 'DL64');
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
                                                        $q->where('servicio', 'DL64');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.gravamenes.gravamen-index', compact('movimientos'))->extends('layouts.admin');

    }

}
