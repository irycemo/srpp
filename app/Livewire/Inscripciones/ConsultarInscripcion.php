<?php

namespace App\Livewire\Inscripciones;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
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
    public $motivos;
    public $motivo;

    public $movimientoRegistral;
    public $modelo;
    public $modeloId;

    public function abrirModalRechazar(MovimientoRegistral $modelo){

        $this->reset(['observaciones', 'motivo']);

        if($this->modelo_editar->isNot($modelo))
            $this->movimientoRegistral = $modelo;

        $this->modalRechazar = true;

    }

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function () {

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->movimientoRegistral->año, $this->movimientoRegistral->tramite, $this->movimientoRegistral->usuario, $this->motivo . ' ' . $observaciones);

                $this->movimientoRegistral->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

            $this->modalRechazar = false;

            $pdf = Pdf::loadView('rechazos.rechazo', [
                'movimientoRegistral' => $this->movimientoRegistral,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones
            ])->output();

            return response()->streamDownload(
                fn () => print($pdf),
                'rechazo.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al rechazar inscripcion por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignar(){

        $this->validate([
            'usuario' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $this->movimientoRegistral->update(['usuario_asignado' => $this->usuario]);

                $this->dispatch('mostrarMensaje', ['success', "El trámite se reasigno con éxito."]);

                $this->modal2 = false;

            });

        } catch (\Throwable $th) {
            Log::error("Error al reasignar inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

        if($this->movimientoRegistral->inscripcionPropiedad){

            $this->modelo = 'Propiedad';
            $this->modeloId = $this->movimientoRegistral->inscripcionPropiedad->id;

        }elseif($this->movimientoRegistral->fideicomiso){
            $this->modelo = 'Fideicomiso';
            $this->modeloId = $this->movimientoRegistral->fideicomiso->id;


        }elseif($this->movimientoRegistral->cancelacion){
            $this->modelo = 'Cancelacion';
            $this->modeloId = $this->movimientoRegistral->cancelacion->id;


        }elseif($this->movimientoRegistral->gravamen){
            $this->modelo = 'Gravamen';
            $this->modeloId = $this->movimientoRegistral->gravamen->id;


        }elseif($this->movimientoRegistral->sentencia){
            $this->modelo = 'Sentencia';
            $this->modeloId = $this->movimientoRegistral->sentencia->id;

        }elseif($this->movimientoRegistral->vario){
            $this->modelo = 'Vario';
            $this->modeloId = $this->movimientoRegistral->vario->id;

        }elseif($this->movimientoRegistral->reformaMoral){
            $this->modelo = 'Reforma';
            $this->modeloId = $this->movimientoRegistral->reformaMoral->id;

        }

    }

    public function seleccionarMotivo($key){

        $this->motivo = $this->motivos[$key];

    }

    public function mount(){

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

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
