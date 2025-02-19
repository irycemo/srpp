<?php

namespace App\Livewire\Certificaciones\CertificadoNegativo;

use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Personaold;
use App\Models\Propiedadold;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Inscripciones\ColindanciasTrait;
use App\Traits\Certificaciones\CertificadoPropiedadTrait;

class SoloNombre extends Component
{

    use CertificadoPropiedadTrait;
    use ColindanciasTrait;

    protected function rules(){
        return [
            'nombre' => ['required', 'string'],
            'ap_paterno' => ['required', 'string'],
            'ap_materno' => ['required', 'string'],
            'temporalidad' => ['nullable', 'numeric']
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

            }else{

                $this->dispatch('mostrarMensaje', ['success', "No se encontraron resultados con la información ingresada."]);

                $this->flagGenerar = true;

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

            }

        }

    }

    public function generarCertificado(){

        if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($this->certificacion) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->certificacion)->format('d-m-Y')]);

                return;

            }

        }

        try{

            DB::transaction(function (){

                $this->certificacion->movimientoRegistral->estado = 'elaborado';
                $this->certificacion->movimientoRegistral->save();

                $this->certificacion->actualizado_por = auth()->user()->id;
                $this->certificacion->tipo_certificado = 5;
                $this->certificacion->temporalidad = $this->temporalidad;
                $this->certificacion->observaciones_certificado = $this->observaciones;
                $this->certificacion->save();

                $this->procesarPersona($this->nombre, $this->ap_paterno, $this->ap_materno);

            });

            $this->dispatch('imprimir_negativo', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

        } catch (\Throwable $th) {

            Log::error("Error al generar certificado negativo solo nombre por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-negativo.solo-nombre');
    }
}
