<?php

namespace App\Livewire\Consulta;

use Livewire\Component;
use App\Models\Propiedad;
use App\Models\Certificacion;
use App\Traits\Inscripciones\RechazarMovimientoTrait;
use App\Traits\MovimientoRegistral\CambiarAntecedenteTrait;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use App\Models\User;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;

class ConsultasTramite extends Component
{

    use ComponentesTrait;
    use WithPagination;
    use CambiarAntecedenteTrait;
    use RechazarMovimientoTrait;

    public $año;
    public $tramite;
    public $tramite_usuario;
    public $modal2;
    public $modalRechazar;
    public $paginas;
    public $usuarios;
    public $usuario;
    public $años;
    public $motivos;
    public $motivo;

    public $movimientoRegistral;
    public $modelo;
    public $modeloId;
    public $movimientos = [];
    public $modelo_editar;

    public $modal_certificacion = false;
    public $modal_propiedad = false;
    public $numero_paginas;
    public $numero_inmuebles;

    public function abrirModalCertifiacion(MovimientoRegistral $movimientoRegistral){

        $this->movimientoRegistral = $movimientoRegistral;

        $this->modal_certificacion = true;

    }

    public function abrirModalPropiedad(MovimientoRegistral $movimientoRegistral){

        $this->movimientoRegistral = $movimientoRegistral;

        $this->modal_propiedad = true;

    }

    public function actualizarCertificacion(){

        $this->validate(['numero_paginas' => 'required']);

        if(!in_array($this->movimientoRegistral->estado, ['nuevo', 'elaborado'])){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite debe estar en estado nuevo o elaborado."]);

            $this->modal_certificacion = false;

            $this->reset('numero_paginas');

            return;

        }

        if($this->numero_paginas >= $this->movimientoRegistral->certificacion->numero_paginas){

            $this->dispatch('mostrarMensaje', ['warning', "El número de paginas no puede ser mayor o igual al registrado."]);

            $this->modal_certificacion = false;

            $this->reset('numero_paginas');

            return;

        }

        $this->movimientoRegistral->certificacion->update(['numero_paginas' => $this->numero_paginas]);

        $this->dispatch('mostrarMensaje', ['success', "Se actualizó la información con éxito."]);

        $this->modal_certificacion = false;

        $this->reset('numero_paginas');

    }

    public function actualizarPropiedad(){

        $this->validate(['numero_inmuebles' => 'required']);

        if(!in_array($this->movimientoRegistral->estado, ['nuevo'])){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite debe estar en estado nuevo."]);

            $this->modal_propiedad = false;

            $this->reset('numero_inmuebles');

            return;

        }

        if($this->numero_inmuebles >= $this->movimientoRegistral->inscripcionPropiedad->numero_inmuebles){

            $this->dispatch('mostrarMensaje', ['warning', "El número de inmuebles no puede ser mayor o igual al registrado."]);

            $this->modal_propiedad = false;

            $this->reset('numero_inmuebles');

            return;

        }

        $this->movimientoRegistral->inscripcionPropiedad->update(['numero_inmuebles' => $this->numero_inmuebles]);

        $this->dispatch('mostrarMensaje', ['success', "Se actualizó la información con éxito."]);

        $this->modal_propiedad = false;

        $this->reset('numero_inmuebles');

    }

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
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

        $this->movimientoRegistral = MovimientoRegistral::where('año', $this->año)
                                                            ->where('tramite', $this->tramite)
                                                            ->where('usuario', $this->tramite_usuario)
                                                            ->first();

        if(!$this->movimientoRegistral){

            $this->dispatch('mostrarMensaje', ['warning', "No se encontro el trámite."]);

            return;

        }

        if($this->movimientoRegistral->folio_real){

            $this->movimientos = MovimientoRegistral::with('folioReal:id,folio,estado,matriz', 'asignadoA:id,name')
                                                        ->where('folio_real', $this->movimientoRegistral->folio_real)
                                                        ->get();

        }elseif(
            $this->movimientoRegistral->tomo &&
            $this->movimientoRegistral->registro &&
            $this->movimientoRegistral->numero_propiedad &&
            $this->movimientoRegistral->distrito)
        {

            $this->movimientos = MovimientoRegistral::with('folioReal:id,folio,estado,matriz', 'asignadoA:id,name')
                                                        ->where('tomo', $this->movimientoRegistral->tomo)
                                                        ->where('registro', $this->movimientoRegistral->registro)
                                                        ->where('numero_propiedad', $this->movimientoRegistral->numero_propiedad)
                                                        ->where('distrito', $this->movimientoRegistral->getRawOriginal('distrito'))
                                                        ->get();

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

        }elseif($this->movimientoRegistral->certificacion){
            $this->modelo = 'Certificacion';
            $this->modeloId = $this->movimientoRegistral->certificacion->id;

        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->motivos_rechazo = Constantes::RECHAZO_MOTIVOS;

        $this->distritos = Constantes::DISTRITOS;

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
        return view('livewire.consulta.consultas-tramite')->extends('layouts.admin');
    }
}
