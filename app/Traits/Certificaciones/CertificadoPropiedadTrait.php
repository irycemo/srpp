<?php

namespace App\Traits\Certificaciones;

use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\Personaold;
use App\Models\Propiedadold;
use App\Models\CertificadoPersona;

trait CertificadoPropiedadTrait{

    public $certificacion;

    public $nombre;
    public $ap_paterno;
    public $ap_materno;

    public $observaciones;

    public $modalRechazar = false;
    public $flagGenerar = false;

    public $temporalidad;

    public $predios = [];
    public $prediosOld = [];
    public $propietarios = [];

    public $personasIds = [];
    public $propiedadOldIds = [];

    public function procesarPersona($nombre, $ap_paterno, $ap_materno){

        $this->certificacion->personas()->delete();

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

    public function procesarPersonas($personas){

        $this->certificacion->personas()->delete();

        foreach ($personas as $persona) {

            $persona = Persona::firstOrCreate(
                [
                    'tipo' => 'FISICA',
                    'nombre' => $persona['nombre'],
                    'ap_paterno' => $persona['ap_paterno'],
                    'ap_materno' => $persona['ap_materno'],
                ],
                [
                    'tipo' => 'FISICA',
                    'nombre' => $persona['nombre'],
                    'ap_paterno' => $persona['ap_paterno'],
                    'ap_materno' => $persona['ap_materno']
                ]
            );

            CertificadoPersona::create(['certificacion_id' => $this->certificacion->id, 'persona_id' => $persona->id]);

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

    public function buscarPropietarios(){

        $this->reset(['flagGenerar','personasIds', 'prediosOld', 'predios', 'propiedadOldIds']);

        $this->validate();

        foreach ($this->propietarios as $propietario) {

            $persona = Persona::where('tipo', 'FISICA')
                            ->where('nombre', $propietario['nombre'])
                            ->where('ap_paterno', $propietario['ap_paterno'])
                            ->where('ap_materno', $propietario['ap_materno'])
                            ->first();

            if($persona){

                array_push($this->personasIds, $persona->id);

            }else{

                $persona = Personaold::where(function($q) use ($propietario){
                                            $q->where('nombre2', $propietario['nombre'])
                                                ->orWhere('nombre1', $propietario['nombre']);
                                        })
                                        ->where('paterno', $propietario['ap_paterno'])
                                        ->where('materno', $propietario['ap_materno'])
                                        ->first();

                if($persona) array_push($this->propiedadOldIds, $persona->idPropiedad);

            }

        }

        if(count($this->personasIds) > 0){

            $propietarios = Actor::whereIn('persona_id', $this->personasIds)->where('tipo_actor', 'propietario')->get();

            if($propietarios->count()){

                foreach ($propietarios as $propietario) {

                    $predio = Predio::wherekey($propietario->actorable_id)
                                        ->whereHas('folioReal', function($q){
                                            $q->where('estado', 'activo');
                                        })
                                        ->first();

                    if($predio) array_push($this->predios, $predio);

                }

                if(count($this->predios) > 0){

                    $this->dispatch('mostrarMensaje', ['warning', "Se encontraron propietarios."]);

                    $this->flagGenerar = false;

                }else{

                    $this->dispatch('mostrarMensaje', ['success', "No se encontraron resultados con la información ingresada."]);

                    $this->flagGenerar = true;

                }

            }else{

                $this->dispatch('mostrarMensaje', ['success', "No se encontraron resultados con la información ingresada."]);

                $this->flagGenerar = true;

            }

        }

        if(count($this->propiedadOldIds) > 0){

            $this->prediosOld = Propiedadold::whereKey($this->propiedadOldIds)->where('status', '!=', 'V')->get();

            if(count($this->prediosOld) > 0){

                $this->dispatch('mostrarMensaje', ['warning', "Se encontraron propietarios."]);

                $this->flagGenerar = false;

            }else{

                $this->dispatch('mostrarMensaje', ['success', "No se encontraron resultados con la información ingresada."]);

                $this->flagGenerar = true;

            }

        }

    }

}
