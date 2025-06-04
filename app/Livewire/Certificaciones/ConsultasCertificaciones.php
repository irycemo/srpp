<?php

namespace App\Livewire\Certificaciones;

use App\Constantes\Constantes;
use App\Models\User;
use Livewire\Component;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;
use Livewire\WithPagination;

class ConsultasCertificaciones extends Component
{

    use ComponentesTrait;
    use WithPagination;

    public $certificacion;
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

    public function save(){

        $this->validate(['paginas' => 'required']);

        if(in_array($this->certificacion->estado, ['nuevo', 'elaborado'])){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite debe estar en estado nuevo o elaborado."]);

            $this->modal = false;

            $this->reset('paginas');

            return;

        }

        if($this->paginas >= $this->certificacion->certificacion->numero_paginas){

            $this->dispatch('mostrarMensaje', ['warning', "El número de paginas no puede ser mayor o igual al registrado."]);

            $this->modal = false;

            $this->reset('paginas');

            return;

        }

        $this->certificacion->certificacion->update(['numero_paginas' => $this->paginas]);

        $this->dispatch('mostrarMensaje', ['success', "Se actualizó la información con éxito."]);

        $this->modal = false;

        $this->reset('paginas');

    }

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

        $this->certificacion = MovimientoRegistral::where('año', $this->año)->where('tramite', $this->tramite)->where('usuario', $this->tramite_usuario)->first();

        if(!$this->certificacion){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el trámite."]);

            return;

        }

        if(!$this->certificacion->certificacion){

            $this->certificacion = null;

            $this->dispatch('mostrarMensaje', ['error', "El trámite no es una certificación."]);

        }

    }

    public function reactivarTramtie(MovimientoRegistral $movimientoRegistral){

        try {

            if($movimientoRegistral->certificacion->folio_carpeta_copias){

                $movimientoRegistral->update([
                    'estado' => 'elaborado',
                    'fecha_entrega' => $this->calcularFechaEntrega($movimientoRegistral->tipo_servicio)
                ]);

            }else{

                $movimientoRegistral->update([
                    'estado' => 'nuevo',
                    'fecha_entrega' => $this->calcularFechaEntrega($movimientoRegistral->tipo_servicio)
                ]);

            }

            $this->certificacion = $movimientoRegistral;

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reactivó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al reactivar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function calcularFechaEntrega($tipo_servicio):string
    {

        if($tipo_servicio == 'ordinario'){

            $actual = now();

            for ($i=0; $i < 5; $i++) {

                $actual->addDays(1);

                while($actual->isWeekend()){

                    $actual->addDay();

                }

            }

            return $actual->toDateString();

        }elseif($tipo_servicio == 'urgente'){

            $actual = now()->addDays(1);

            while($actual->isWeekend()){

                $actual->addDay();

            }

            return $actual->toDateString();

        }else{

            return now()->toDateString();

        }

    }

    public function mount(){

        $this->años = Constantes::AÑOS;

        $this->año = now()->format('Y');

        $this->usuarios = User::whereHas('roles', function($q){
                                        $q->whereIn('name', [
                                            'Certificador',
                                            'Certificador Juridico',
                                            'Certificador Oficialia',
                                            'Certificador Gravamen',
                                            'Certificador Propiedad'
                                        ]);
                                    })
                                    ->orderBy('name')
                                    ->get();

    }

    public function render()
    {
        return view('livewire.certificaciones.consultas-certificaciones')->extends('layouts.admin');
    }

}
