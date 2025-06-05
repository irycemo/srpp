<?php

namespace App\Livewire\Certificaciones\CertificadoPropiedad;

use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Personaold;
use App\Models\Propiedadold;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Certificaciones\CertificadoPropiedadTrait;

class CertificadoUnico extends Component
{

    use CertificadoPropiedadTrait;

    protected function rules(){
        return [
            'nombre' => ['required', 'string'],
            'ap_paterno' => ['required', 'string'],
            'ap_materno' => ['required', 'string'],
         ];
    }

    public function buscarPropietario(){

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

            if(count($this->prediosOld) == 0){

                $this->dispatch('mostrarMensaje', ['warning', "No se encontraron resultados con la información ingresada."]);

            }elseif(count($this->prediosOld) == 1){

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

                if(count($this->predios) == 0){

                    $this->dispatch('mostrarMensaje', ['warning', "No se encontraron resultados con la información ingresada."]);

                }elseif(count($this->predios) == 1){

                    $this->flagGenerar = true;

                }

            }else{

                $this->dispatch('mostrarMensaje', ['warning', "No se encontraron resultados con la información ingresada."]);

            }

        }

    }

    public function generarCertificado(){

        $this->validate();

        if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($this->certificacion) <= now()) && !auth()->user()->hasRole(['Jefe de departamento certificaciones'])){

                $this->dispatch('mostrarMensaje', ['warning', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->certificacion)->format('d-m-Y')]);

                return;

            }

        }

        try{

            DB::transaction(function (){

                $folioReal = FolioReal::find($this->predios[0]->folio_real);

                $this->certificacion->movimientoRegistral->folio_real = $folioReal->id;
                $this->certificacion->movimientoRegistral->folio = $folioReal->ultimoFolio() + 1;
                $this->certificacion->movimientoRegistral->estado = 'elaborado';
                $this->certificacion->movimientoRegistral->save();

                $this->certificacion->actualizado_por = auth()->user()->id;
                $this->certificacion->tipo_certificado = 3;
                $this->certificacion->temporalidad = $this->temporalidad;
                $this->certificacion->observaciones_certificado = $this->observaciones;
                $this->certificacion->save();

                $this->procesarPersona($this->nombre, $this->ap_paterno, $this->ap_materno);

            });

            $this->dispatch('imprimir_unico_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

        } catch (\Throwable $th) {

            Log::error("Error al generar certificado de propiedad único por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-propiedad.certificado-unico');
    }
}
