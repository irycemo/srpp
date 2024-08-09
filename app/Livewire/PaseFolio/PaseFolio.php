<?php

namespace App\Livewire\PaseFolio;

use App\Models\File;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class PaseFolio extends Component
{

    use ComponentesTrait;
    use WithPagination;
    use WithFileUploads;

    public $observaciones;
    public $modal = false;
    public $modalFinalizar = false;
    public $documento;

    public MovimientoRegistral $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function rechazar(){

        $this->authorize('update', $this->modelo_editar);

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, $observaciones);

                $this->modelo_editar->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->modelo_editar->save();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

                $this->reset(['modal', 'observaciones']);

            });

        } catch (\Throwable $th) {

            Log::error("Error al rechazar pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->reset(['modal', 'observaciones']);
        }

    }

    public function abrirModalRechazar(MovimientoRegistral $movimientoRegistral){

        $this->modal = true;

        if($this->modelo_editar->isNot($movimientoRegistral))
            $this->modelo_editar = $movimientoRegistral;

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
                    'fileable_id' => $this->modelo_editar->folioReal->id,
                    'fileable_type' => 'App\Models\FolioReal',
                    'descripcion' => 'caratula',
                    'url' => $pdf
                ]);

                $this->modelo_editar->folioReal->update([
                    'estado' => 'activo'
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El folio se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al subir archivo de folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'asignadoA', 'folioReal', 'gravamen')
                                                    ->where('folio', 1)
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura']);
                                                            });
                                                    })
                                                    ->where(function($q){
                                                        $q->where('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('usuario', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }else{

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal', 'gravamen')
                                                    ->where('folio', 1)
                                                    ->where('estado', 'nuevo')
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado']);
                                                            });
                                                    })
                                                    ->where(function($q){
                                                        $q->where('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('usuario', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->where('usuario_asignado', auth()->user()->id)
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);
        }

        return view('livewire.pase-folio.pase-folio', compact('movimientos'))->extends('layouts.admin');
    }
}
