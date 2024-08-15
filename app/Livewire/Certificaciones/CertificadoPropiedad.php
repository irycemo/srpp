<?php

namespace App\Livewire\Certificaciones;

use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Personaold;
use App\Models\Propietario;
use App\Models\Propiedadold;
use App\Models\Certificacion;
use Illuminate\Validation\Rule;
use App\Models\CertificadoPersona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class CertificadoPropiedad extends Component
{

    public Certificacion $certificacion;

    public $movimientoRegistral;

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
    public $flagPropietario = false;

    public $propietarios = [];

    public $observaciones;

    protected function rules(){
        return [
            'propietarios.*' => ['nullable', Rule::requiredIf($this->certificacion->numero_paginas > 1)],
            'propietarios.*.nombre' => ['nullable', 'string', Rule::requiredIf($this->certificacion->numero_paginas > 1)],
            'propietarios.*.ap_paterno' => ['nullable', 'string', Rule::requiredIf($this->certificacion->numero_paginas > 1)],
            'propietarios.*.ap_materno' => ['nullable', 'string', Rule::requiredIf($this->certificacion->numero_paginas > 1)],
         ];
    }

    protected $validationAttributes  = [
        'propietarios.*.nombre' => 'nombre',
        'propietarios.*.ap_paterno' => 'apellido paterno',
        'propietarios.*.ap_materno' => 'apellido materno',
    ];

    public function updated($property, $value){

        if(in_array($property, ['nombre', 'ap_paterno', 'ap_materno'])){

            $this->reset(['propietario', 'propietarioOld', 'predio', 'predioOld', 'flagPropietario', 'razon_social']);

        }

        if($property == 'razon_social'){

            $this->reset(['propietario', 'propietarioOld', 'predio', 'predioOld', 'flagPropietario', 'nombre', 'ap_paterno', 'ap_materno']);

        }

    }

    public function abrirModalRechazar(){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia'])){

            if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

                if(!($this->calcularDiaElaboracion($this->certificacion) <= now())){

                    $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->certificacion)->format('d-m-Y')]);

                    return;

                }

            }

        }

        $this->modalRechazar = true;

    }

    public function buscarPropietario(){

        $this->reset(['propietario', 'propietarioOld', 'predio', 'predioOld', 'flagPropietario']);

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
            $this->propietario = Actor::where('persona_id', $persona->id)->where('tipo_actor', 'propietario')->first();

        if(!$this->propietario){

            $this->propietarioOld = Personaold::where(function($q){
                            $q->where('nombre2', 'LIKE', '%' . $this->nombre . '%')
                                ->orWhere('nombre1', 'LIKE', '%' . $this->nombre . '%');
                        })
                        ->where('paterno', $this->ap_paterno)
                        ->where('materno', $this->ap_materno)
                        ->first();

            if($this->propietarioOld)
                $this->predioOld = PropiedadOld::where('distrito', $this->propietarioOld->distrito)
                                                ->where('tomo', $this->propietarioOld->tomo)
                                                ->where('registro', $this->propietarioOld->registro)
                                                ->where('noprop', $this->propietarioOld->noprop)
                                                ->where('status', '!=', 'V')
                                                ->first();

            if(!$this->predioOld){

                $this->reset(['propietario', 'propietarioOld', 'predio', 'predioOld', 'flagPropietario']);

                $this->flagPropietario = true;

                $this->dispatch('mostrarMensaje', ['success', "Sin resultados"]);

                return;

            }

        }else{

            $this->predio = Predio::find($this->propietario->actorable_id);

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

    public function generarCertificado($tipo){

        $this->validate();

        if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($this->certificacion) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->certificacion)->format('d-m-Y')]);

                return;

            }

        }

        try{

            DB::transaction(function () use ($tipo){

                $this->certificacion->movimientoRegistral->estado = 'elaborado';

                $this->certificacion->movimientoRegistral->save();

                if(auth()->user()->hasRole(['Supervisor certificaciones', 'Supervisor uruapan'])){

                    $this->certificacion->reimpreso_en = now();

                }

                $this->certificacion->actualizado_por = auth()->user()->id;
                $this->certificacion->tipo_certificado = $tipo;
                $this->certificacion->save();

                if($tipo == 2){

                    foreach ($this->propietarios as $propietario) {

                        $this->procesarPersona($propietario['nombre'], $propietario['ap_paterno'], $propietario['ap_materno']);

                    }

                }

                if($tipo == 5){

                    $this->procesarPersona($this->nombre, $this->ap_paterno, $this->ap_materno);

                }

            });

            if($tipo == 1){

                $this->dispatch('imprimir_negativo_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

            }elseif($tipo == 2){

                $this->dispatch('imprimir_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

            }if($tipo == 3){

                $this->dispatch('imprimir_unico_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

            }if($tipo == 4){

                $this->dispatch('imprimir_propiedad_colindancias', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

            }if($tipo == 5){

                $this->dispatch('imprimir_negativo', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

            }

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizarSupervisor(Certificacion $modelo){

        if($modelo->movimientoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($modelo) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($modelo)->format('d-m-Y')]);

                return;

            }

        }

        try {

            DB::transaction(function () use ($modelo){

                $this->certificacion->finalizado_en = now();

                $this->certificacion->firma = now();

                $this->certificacion->actualizado_por = auth()->user()->id;

                $this->certificacion->movimientoRegistral->estado = 'concluido';

                $this->certificacion->movimientoRegistral->save();

                $this->certificacion->save();

                (new SistemaTramitesService())->finaliarTramite($this->certificacion->movimientoRegistral->año, $this->certificacion->movimientoRegistral->tramite, $this->certificacion->movimientoRegistral->usuario, 'concluido');

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

            if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

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

                (new SistemaTramitesService())->rechazarTramite($this->certificacion->movimientoRegistral->año, $this->certificacion->movimientoRegistral->tramite, $this->certificacion->movimientoRegistral->usuario, $observaciones);

                $this->certificacion->movimientoRegistral->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->certificacion->actualizado_por = auth()->user()->id;

                $this->certificacion->observaciones = $this->certificacion->observaciones . $observaciones;

                $this->certificacion->save();

            });

            return redirect()->route('certificados_propiedad');

        } catch (\Throwable $th) {

            Log::error("Error al rechazar certificado de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function procesarPersona($nombre, $ap_paterno, $ap_materno){

        $persona = Persona::firstOrCreate(
            [
                'tipo' => 'FISICA',
                'nombre' => $nombre,
                'ap_paterno' => $ap_paterno,
                'ap_materno' => $ap_materno
            ],
            [
                'tipo' => 'FISICA',
                'nombre' => $nombre,
                'ap_paterno' => $ap_paterno,
                'ap_materno' => $ap_materno
            ]
        );

        CertificadoPersona::create(['certificacion_id' => $this->certificacion->id, 'persona_id' => $persona->id]);

    }

    public function mount(){

        for ($i=0; $i < $this->certificacion->numero_paginas; $i++) {

            $this->propietarios [] = ['nombre' => null, 'ap_paterno' => null, 'ap_materno' => null];

        }

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-propiedad')->extends('layouts.admin');
    }

}
