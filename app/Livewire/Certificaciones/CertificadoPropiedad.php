<?php

namespace App\Livewire\Certificaciones;

use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Personaold;
use App\Models\Propietario;
use App\Models\Propiedadold;
use App\Models\Certificacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class CertificadoPropiedad extends Component
{

    public Certificacion $certificacion;

    public $moviminetoRegistral;

    public $modalRechazar = false;

    public $radio;
    public $nombre;
    public $ap_paterno;
    public $ap_materno;
    public $razon_social;

    public $propietario;
    public $propietarioOld;
    public $predio;
    public $predioOld;

    public function abrirModalRechazar(Certificacion $modelo){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia'])){

            if($modelo->movimientoRegistral->tipo_servicio == 'ordinario'){

                if(!($this->calcularDiaElaboracion($modelo) <= now())){

                    $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($modelo)->format('d-m-Y')]);

                    return;

                }

            }

        }

        $this->resetearTodo();
            $this->modalRechazar = true;
            $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function buscarPropietario(){

        $this->validate([
            'nombre' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
        ]);

        $persona = Persona::where('tipo', 'FISICA')
                            ->where('nombre', $this->nombre)
                            ->where('ap_paterno', $this->ap_paterno)
                            ->where('ap_materno', $this->ap_materno)
                            ->first();

        if($persona)
            $this->propietario = Propietario::where('persona_id', $persona->id)->first();

        if(!$this->propietario){

            $this->propietarioOld = Personaold::where(function($q){
                            $q->where('nombre2', 'LIKE', '%' . $this->nombre . '%')
                                ->orWhere('nombre1', 'LIKE', '%' . $this->nombre . '%');
                        })
                        ->where('paterno', $this->ap_paterno)
                        ->where('materno', $this->ap_materno)
                        ->first();

            if(!$this->propietarioOld){

                $this->dispatch('mostrarMensaje', ['error', "Sin resultados"]);

                return;

            }else{

                $this->predioOld = Propiedadold::find($this->propietarioOld->idPropiedad);

            }

        }else{

            $this->predio = Predio::find($this->propietario->predio_id);

        }

    }

    public function buscarPropietarioMoral(){

        $this->validate([
            'razon_social' => 'required',
        ]);

        $persona = Persona::where('tipo', 'MORAL')
                            ->where('razon_social', $this->nombre)
                            ->first();

        $this->propietario = Propietario::where('persona_id', $persona->id)->first();

        if(!$this->propietario){

            $this->propietarioOld = Personaold::where(function($q){
                            $q->where('nombre2', 'LIKE', '%' . $this->nombre . '%')
                                ->orWhere('nombre1', 'LIKE', '%' . $this->nombre . '%');
                        })
                        ->orWhere('paterno', 'LIKE', '%' . $this->ap_paterno . '%')
                        ->orWhere('materno', 'LIKE', '%' . $this->ap_materno . '%')
                        ->first();

            if(!$this->propietarioOld){

                $this->dispatch('mostrarMensaje', ['error', "Sin resultados"]);

                return;

            }else{

                $this->predioOld = Propiedadold::find($this->propietarioOld->idPropiedad);

            }

        }else{

            $this->predio = Predio::find($this->propietario->predio_id);

        }

    }

    public function calcularDiaElaboracion($modelo){

        $diaElaboracion = $modelo->movimientoRegistral->fecha_pago;

        for ($i=0; $i < 2; $i++) {

            $diaElaboracion->addDays(1);

            while($diaElaboracion->isWeekend()){

                $diaElaboracion->addDay();

            }

        }

        return $diaElaboracion;

    }

    public function generarCertificado(){

        $this->modal = false;

        if($this->moviminetoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($this->modelo_editar) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->modelo_editar)->format('d-m-Y')]);

                return;

            }

        }

        try{

            DB::transaction(function (){

                $this->moviminetoRegistral->estado = 'elaborado';

                $this->moviminetoRegistral->save();

                if(auth()->user()->hasRole(['Supervisor certificaciones', 'Supervisor uruapan'])){

                    $this->modelo_editar->reimpreso_en = now();

                }

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->save();

                $this->dispatch('imprimir_documento', ['gravamen' => $this->moviminetoRegistral->id]);

                $this->modal = false;

                $this->reset('predio');

            });



        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizarSupervisor(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if($modelo->movimientoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($modelo) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($modelo)->format('d-m-Y')]);

                return;

            }

        }

        try {

            DB::transaction(function () use ($modelo){

                $this->modelo_editar->finalizado_en = now();

                $this->modelo_editar->firma = now();

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->movimientoRegistral->estado = 'concluido';

                $this->modelo_editar->movimientoRegistral->save();

                $this->modelo_editar->save();

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, 'concluido');

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function rechazar(){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia'])){

            if($this->modelo_editar->movimientoRegistral->tipo_servicio == 'ordinario'){

                if(!($this->calcularDiaElaboracion($this->modelo_editar) <= now())){

                    $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->modelo_editar)->format('d-m-Y')]);

                    return;

                }

            }

        }

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, $observaciones);

                $this->modelo_editar->movimientoRegistral->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->observaciones = $this->modelo_editar->observaciones . $observaciones;

                $this->modelo_editar->save();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

                $this->resetearTodo();

            });

        } catch (\Throwable $th) {

            Log::error("Error al rechazar certificado de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-propiedad')->extends('layouts.admin');
    }

}
