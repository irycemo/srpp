<?php

namespace App\Livewire\Certificaciones\CertificadoPropiedad;

use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Personaold;
use App\Models\Propiedadold;
use App\Traits\CalcularDiaElaboracionTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Certificaciones\CertificadoPropiedadTrait;

class CertificadoUnico extends Component
{

    use CertificadoPropiedadTrait;
    use CalcularDiaElaboracionTrait;

    protected function rules(){
        return [
            'nombre' => ['required', 'string'],
            'ap_paterno' => ['required', 'string'],
            'ap_materno' => ['required', 'string'],
         ];
    }

    public function buscarPropietario(){

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

            }

            $personas = Personaold::where(function($q) use ($propietario){
                                        $q->where('nombre2', $propietario['nombre'])
                                            ->orWhere('nombre1', $propietario['nombre']);
                                    })
                                    ->where('paterno', $propietario['ap_paterno'])
                                    ->where('materno', $propietario['ap_materno'])
                                    ->where('distrito', $this->certificacion->movimientoRegistral->getRawOriginal('distrito'))
                                    ->get();

            if(!$personas->count()){

                $nombre = $propietario['nombre'] . ' ' . $propietario['ap_paterno'] . ' ' . $propietario['ap_materno'];

                $propiedades = Propiedadold::where('propietarios', 'like', '%' . $nombre . '%')
                                            ->where('status', '!=', 'V')
                                            ->where('distrito', $this->certificacion->movimientoRegistral->getRawOriginal('distrito'))
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
                                        ->whereHas('folioReal', function($q){
                                            $q->where('estado', 'activo')
                                                ->where('distrito', $this->certificacion->movimientoRegistral->getRawOriginal('distrito'));
                                        })
                                        ->first();

                    if($predio) array_push($this->predios, $predio);

                }

                if(count($this->predios) > 1){

                    $this->dispatch('mostrarMensaje', ['warning', "Se encontraron propiedades."]);

                    $this->flagGenerar = false;

                    return;

                }

            }

        }

        if(count($this->propiedadOldIds) > 0){

            $this->prediosOld = Propiedadold::whereKey($this->propiedadOldIds)->where('status', '!=', 'V')->get();

            if(count($this->prediosOld) > 1){

                $this->dispatch('mostrarMensaje', ['warning', "Se encontraron propiedades."]);

                $this->flagGenerar = false;

                return;

            }

        }

        $this->dispatch('mostrarMensaje', ['success', "No se encontraron resultados con la información ingresada."]);

        $this->flagGenerar = true;

    }

    public function generarCertificado(){

        $this->validate();

        if(!auth()->user()->hasRole(['Jefe de departamento certificaciones']) && $this->certificacion->movimientoRegistral->distrito != '02 Uruapan'){

            if($this->calcularDiaElaboracion($this->certificacion->movimientoRegistral)) return;

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

                if($this->certificacion->movimientoRegistral->fecha_entrega > now()){

                    $this->certificacion->audits()->latest()->first()->update(['tags' => 'Generó certificado anticipadamente.']);

                }

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
