<?php

namespace App\Livewire\Certificaciones\CertificadoNegativo;

use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Personaold;
use App\Models\Propiedadold;
use App\Traits\Inscripciones\ColindanciasTrait;
use App\Traits\Certificaciones\CertificadoPropiedadTrait;

class SoloNombre extends Component
{

    use CertificadoPropiedadTrait;
    use ColindanciasTrait;

    public $certificacion;

    public $predios = [];
    public $prediosOld = [];
    public $temporalidad;

    public $flagGenerar = false;

    protected function rules(){
        return [
            'nombre' => ['required', 'string'],
            'ap_paterno' => ['required', 'string'],
            'ap_materno' => ['required', 'string'],
         ];
    }

    public function buscarPropietarios(){

        $this->validate();

        $this->reset(['predios', 'prediosOld', 'flagGenerar']);

        $persona = Persona::where('tipo', 'FISICA')
                            ->where('nombre', $this->nombre)
                            ->where('ap_paterno', $this->ap_paterno)
                            ->where('ap_materno', $this->ap_materno)
                            ->first();

        if(!$persona){

            $propietariosOld = Personaold::where(function($q){
                            $q->where('nombre2', 'LIKE', '%' . 'nombre' . '%')
                                ->orWhere('nombre1', 'LIKE', '%' . 'nombre' . '%');
                        })
                        ->where('paterno', 'ap_paterno')
                        ->where('materno', 'ap_materno')
                        ->get();

            foreach ($propietariosOld as $propietario) {

                $predio = Propiedadold::where('distrito', $propietario->distrito)
                                        ->where('tomo', $propietario->tomo)
                                        ->where('registro', $propietario->registro)
                                        ->where('noprop', $propietario->noprop)
                                        ->where('status', '!=', 'V')
                                        ->first();

                array_push($this->prediosOld, $predio);

            }

            if(count($this->prediosOld) > 0){

                $this->dispatch('mostrarMensaje', ['warning', "La persona es propietaria de al menos un predio."]);

            }

        }else{

            $propietarios = Actor::where('persona_id', $persona->id)->where('tipo_actor', 'propietario')->get();

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

                    $this->dispatch('mostrarMensaje', ['warning', "La persona es propietaria de al menos un predio."]);

                }

            }else{

                $this->dispatch('mostrarMensaje', ['warning', "No se encontraron resultados con la información ingresada."]);

                $this->flagGenerar = true;

            }

        }

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-negativo.solo-nombre');
    }
}
