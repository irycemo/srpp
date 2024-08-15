<?php

namespace App\Livewire\Inscripciones;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class ConsultarInscripcion extends Component
{

    use ComponentesTrait;
    use WithPagination;

    public $año;
    public $tramite;
    public $tramite_usuario;
    public $modal2;
    public $modalRechazar;
    public $paginas;
    public $observaciones;
    public $usuarios;
    public $usuario;
    public $años;

    public $movimientoRegistral;

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->certificacion->año, $this->certificacion->tramite, $this->certificacion->usuario, $observaciones);

                $this->certificacion->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->certificacion->actualizado_por = auth()->user()->id;

                $this->certificacion->certificacion->observaciones = $this->certificacion->certificacion->observaciones . $observaciones;

                $this->certificacion->certificacion->save();

                $this->certificacion->save();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

                $this->modalRechazar = false;

            });

        } catch (\Throwable $th) {
            Log::error("Error al rechazar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function reasignar(){

        $this->validate([
            'usuario' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $this->certificacion->update(['usuario_asignado' => $this->usuario]);

                $this->dispatch('mostrarMensaje', ['success', "El trámite se reasigno con éxito."]);

                $this->modal2 = false;

            });

        } catch (\Throwable $th) {
            Log::error("Error al reasignar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function consultar(){

        $this->validate([
            'tramite' => 'required',
            'año' => 'required',
            'tramite_usuario' => 'required'
        ]);

        $this->movimientoRegistral = MovimientoRegistral::where('año', $this->año)->where('tramite', $this->tramite)->where('usuario', $this->tramite_usuario)->first();

        if(!$this->movimientoRegistral){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el trámite."]);

            return;

        }

        if($this->movimientoRegistral->certificacion){

            $this->dispatch('mostrarMensaje', ['error', "El trámite no es una inscripción."]);

        }

    }

    public function reactivarTramtie(MovimientoRegistral $movimientoRegistral){

        try {

            if($movimientoRegistral->certificacion->folio_carpeta_copias){

                $movimientoRegistral->update(['estado' => 'elaborado']);

            }else{

                $movimientoRegistral->update(['estado' => 'nuevo']);

            }

            $this->certificacion = $movimientoRegistral;

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reactivó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al reactivar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->años = Constantes::AÑOS;

        $this->año = now()->format('Y');

        $this->usuarios = User::whereHas('roles', function($q){
                                        $q->whereIn('name', [
                                            'Registrador Propiedad',
                                            'Propiedad',
                                            'Registrador Gravamen',
                                            'Gravamen',
                                            'Registrador Sentencias',
                                            'Sentencias',
                                            'Registrador Cancelación',
                                            'Cancelación',
                                            'Registrador Varios',
                                            'Varios'
                                        ]);
                                    })
                                    ->orderBy('name')
                                    ->get();

    }

    public function render()
    {
        return view('livewire.inscripciones.consultar-inscripcion')->extends('layouts.admin');
    }
}
