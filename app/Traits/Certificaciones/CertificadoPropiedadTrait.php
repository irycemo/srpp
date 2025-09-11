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
    public $modalObservaciones = false;
    public $flagGenerar = false;

    public $temporalidad;

    public $predios = [];
    public $prediosOld = [];
    public $propietarios = [];

    public $personasIds = [];
    public $propiedadOldIds = [];

    public $motivo;
    public $predio_seleccionado;
    public $prediosEliminados = [];

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

    public function buscarPropietarios(){

        $this->reset(['flagGenerar','personasIds', 'prediosOld', 'predios', 'propiedadOldIds']);

        $this->validate();

        $buscar_con_distrito = true;

        if($this->certificacion->movimientoRegistral->servicio_nombre == 'Certificado negativo de vivienda bienestar'){

            $buscar_con_distrito = false;

        }

        foreach ($this->propietarios as $propietario) {

            $persona = Persona::where('tipo', 'FISICA')
                            ->where('nombre', $propietario['nombre'])
                            ->where('ap_paterno', $propietario['ap_paterno'])
                            ->where('ap_materno', $propietario['ap_materno'])
                            ->first();

            if($persona){

                array_push($this->personasIds, $persona->id);

            }

            $personas = Personaold::where(function($q) use ($propietario){
                                        $q->where('nombre2', $propietario['nombre'])
                                            ->orWhere('nombre1', $propietario['nombre']);
                                    })
                                    ->where('paterno', $propietario['ap_paterno'])
                                    ->where('materno', $propietario['ap_materno'])
                                    ->when($buscar_con_distrito, function($q){
                                        $q->where('distrito', $this->certificacion->movimientoRegistral->getRawOriginal('distrito'));
                                    })
                                    ->get();

            if(!$personas->count()){

                $nombre = $propietario['nombre'] . ' ' . $propietario['ap_paterno'] . ' ' . $propietario['ap_materno'];

                $propiedades = Propiedadold::where('propietarios', 'like', '%' . $nombre . '%')
                                            ->where('status', '!=', 'V')
                                            ->when($buscar_con_distrito, function($q){
                                                $q->where('distrito', $this->certificacion->movimientoRegistral->getRawOriginal('distrito'));
                                            })
                                            ->get();

                if($propiedades){

                    foreach ($propiedades as $propiedad) {

                        array_push($this->propiedadOldIds, $propiedad->id);

                    }

                }

            }

            if($personas->count()){

                foreach ($personas as $persona) {

                    array_push($this->propiedadOldIds, $persona->idPropiedad);

                }

            }

        }

        if(count($this->personasIds) > 0){

            $propietarios = Actor::whereIn('persona_id', $this->personasIds)->where('tipo_actor', 'propietario')->where('actorable_type', 'App\Models\Predio')->get();

            if($propietarios->count()){

                foreach ($propietarios as $propietario) {

                    $predio = Predio::wherekey($propietario->actorable_id)
                                        ->whereHas('folioReal', function($q) use($buscar_con_distrito){
                                            $q->where('estado', 'activo')
                                                ->when($buscar_con_distrito, function($q){
                                                    $q->where('distrito_antecedente', $this->certificacion->movimientoRegistral->getRawOriginal('distrito'));
                                                });
                                        })
                                        ->first();

                    if($predio) array_push($this->predios, $predio);

                }

                if(count($this->predios) > 0){

                    $this->dispatch('mostrarMensaje', ['warning', "Se encontraron propiedades."]);

                    $this->flagGenerar = false;

                    return;

                }

            }

        }

        if(count($this->propiedadOldIds) > 0){

            $this->prediosOld = Propiedadold::whereKey($this->propiedadOldIds)->where('status', '!=', 'V')->get();

            if(count($this->prediosOld) > 0){

                $this->dispatch('mostrarMensaje', ['warning', "Se encontraron propiedades."]);

                $this->flagGenerar = false;

                return;

            }

        }

        $this->dispatch('mostrarMensaje', ['success', "No se encontraron resultados con la información ingresada."]);

        $this->flagGenerar = true;

    }

    public function abrirModalObservaciones($id){

        $this->predio_seleccionado = $id;

        $this->modalObservaciones = true;

    }

    public function quitarPropiedad(){

        if(isset($this->predios[$this->predio_seleccionado])){

            $predio = $this->predios[$this->predio_seleccionado];

            $this->prediosEliminados [] = [
                'folio_real' => $predio->folioReal->folio,

            ];

            unset($this->predios[$this->predio_seleccionado]);

        }else{

            $predio = $this->prediosOld[$this->predio_seleccionado];

            $this->prediosEliminados [] = [
                'tomo' => $predio->tomo,
                'registro' => $predio->registro,
                'noprop' => $predio->noprop,
                'distrito' => $predio->distrito,

            ];

            unset($this->prediosOld[$this->predio_seleccionado]);

        }

        if(count($this->predios) == 0 && count($this->prediosOld) == 0){

            $this->flagGenerar = true;

        }

        $this->reset(['motivo', 'modalObservaciones']);

    }

    public function procesarPrediosEliminados(){

        $texto = 'De acuerdo a la búsqueda, se encontrarón homonimias en los siguientes registros, sin embargo, no corresponde a la persona solicitante.';

        foreach ($this->prediosEliminados as $predio) {

            if(isset($predio['folio_real'])){

                $texto = $texto . '<p>El verificador eliminó la propiedad con Folio Real: '. $predio['folio_real'] . ' con motivo: ' . '</p>';

            }else{

                $texto = $texto . '<p>El verificador eliminó la propiedad con Tomo: '. $predio['tomo'] . ' registro: ' . $predio['registro'] . ' distrito: ' . $predio['distrito'] . ' número de propiedad: ' .  $predio['noprop'] . ' con motivo: ' . '</p>';

            }

        }

        return $texto;

    }

}
