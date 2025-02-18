<?php

namespace App\Livewire\Certificaciones;

use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Personaold;
use App\Models\Colindancia;
use App\Models\Propiedadold;
use App\Models\Certificacion;
use App\Constantes\Constantes;
use App\Models\CertificadoPersona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class CertificadoPropiedad extends Component
{

    public Certificacion $certificacion;

    public $modalRechazar = false;

    public $radio;
    public $propiedad_radio;
    public $negativo_radio;

    public $flagNegativo = false;

    public $vientos;

    protected $validationAttributes = [
        'ap_paterno' => 'apellido paterno',
        'ap_materno' => 'apellido materno',
    ];

    public function generarCertificadoNegativo(){

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
                $this->certificacion->observaciones_certificado = $this->observaciones;
                $this->certificacion->save();

                $this->procesarPersona($this->nombre, $this->ap_paterno, $this->ap_materno);

            });

            $this->dispatch('imprimir_negativo', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

        } catch (\Throwable $th) {

            Log::error("Error al generar certificado con colindancias por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function generarCertificadoNegativoPropiedad(){

        if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($this->certificacion) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->certificacion)->format('d-m-Y')]);

                return;

            }

        }

        try{

            DB::transaction(function (){

                foreach ($this->medidas as $key =>$medida) {

                    if($medida['id'] == null){

                        $aux = $this->certificacion->movimientoRegistral->folioReal->predio->colindancias()->create([
                            'viento' => $medida['viento'],
                            'longitud' => $medida['longitud'],
                            'descripcion' => $medida['descripcion'],
                        ]);

                        $this->medidas[$key]['id'] = $aux->id;

                    }else{

                        Colindancia::find($medida['id'])->update([
                            'viento' => $medida['viento'],
                            'longitud' => $medida['longitud'],
                            'descripcion' => $medida['descripcion'],
                        ]);

                    }

                }

                $this->certificacion->movimientoRegistral->estado = 'elaborado';
                $this->certificacion->movimientoRegistral->save();

                $this->certificacion->actualizado_por = auth()->user()->id;
                $this->certificacion->tipo_certificado = 1;
                $this->certificacion->observaciones_certificado = $this->observaciones;
                $this->certificacion->save();

                $this->procesarPersona($this->nombre, $this->ap_paterno, $this->ap_materno);

            });

            $this->dispatch('imprimir_negativo_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

        } catch (\Throwable $th) {

            Log::error("Error al generar certificado con colindancias por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function buscarPropietarioEnFolio(){

        $this->validate();

        $predio = $this->certificacion->movimientoRegistral->folioReal->predio;

        $existe = $predio->propietarios()->filter(function ($user){

                                            return
                                                strtolower($user->persona->nombre) == strtolower($this->nombre) &&
                                                strtolower($user->persona->ap_paterno) == strtolower($this->ap_paterno) &&
                                                strtolower($user->persona->ap_materno) == strtolower($this->ap_materno);

                                        })->first();

        if(!$existe) {

            $this->dispatch('mostrarMensaje', ['success', $this->nombre  . ' ' . $this->ap_paterno  . ' ' . $this->ap_materno . ' no es propietario.']);

            $this->flagNegativo = true;

            $this->flagUnico = false;

        }else{

            $this->dispatch('mostrarMensaje', ['warning', $this->nombre  . ' ' . $this->ap_paterno  . ' ' . $this->ap_materno . ' es propietario.']);

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

        if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario' && $this->modelo_editar->movimientoRegistral->distrito != '02 Uruapan'){

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

                foreach ($this->propietarios as $propietario) {

                    $this->procesarPersona($propietario['nombre'], $propietario['ap_paterno'], $propietario['ap_materno']);

                }

            });

            if($tipo == 1){

                $this->dispatch('imprimir_negativo_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

            }elseif($tipo == 2){

                $this->dispatch('imprimir_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

            }if($tipo == 3){

                $this->dispatch('imprimir_unico_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

            }if($tipo == 4){



            }if($tipo == 5){

                $this->dispatch('imprimir_negativo', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

            }

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function agregarColindancia(){

        $this->medidas[] = ['viento' => null, 'longitud' => null, 'descripcion' => null, 'id' => null];

    }

    public function borrarColindancia($index){

        try {

            Colindancia::where('id', $this->medidas[$index]['id'])->delete();

        } catch (\Throwable $th) {
            Log::error("Error al borrar colindancia en inscripcion de propipedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
        }

        unset($this->medidas[$index]);

        $this->medidas = array_values($this->medidas);

    }

    public function mount(){

        $this->vientos = Constantes::VIENTOS;

        /* if($this->certificacion->movimientoRegistral->folioReal->predio){

            foreach ($this->certificacion->movimientoRegistral->folioReal->predio->colindancias as $colindancia) {

                $this->medidas[] = [
                    'id' => $colindancia->id,
                    'viento' => $colindancia->viento,
                    'longitud' => $colindancia->longitud,
                    'descripcion' => $colindancia->descripcion,
                ];

            }

        } */

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-propiedad')->extends('layouts.admin');
    }

}
