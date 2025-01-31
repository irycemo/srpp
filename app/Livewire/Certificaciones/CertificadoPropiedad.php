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

    public $nombre;
    public $ap_paterno;
    public $ap_materno;

    public $predios = [];
    public $prediosOld = [];

    public $flagNegativo = false;
    public $flagUnico = false;

    public $observaciones;

    public $medidas = [];
    public $vientos;

    protected function rules(){
        return [
            'nombre' => ['required', 'string'],
            'ap_paterno' => ['required', 'string'],
            'ap_materno' => ['required', 'string'],
         ];
    }

    protected $validationAttributes = [
        'ap_paterno' => 'apellido paterno',
        'ap_materno' => 'apellido materno',
    ];

    public function updated($property, $value){

        if(in_array($property, ['radio', 'propiedad_radio', 'negativo_radio'])){

            $this->reset(['nombre', 'ap_paterno', 'ap_materno','predios', 'prediosOld', 'flagNegativo', 'observaciones']);

        }

        if(in_array($property, ['nombre', 'ap_paterno', 'ap_materno'])){

            $this->reset(['predios', 'prediosOld', 'flagNegativo', 'observaciones']);

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

    public function buscarPropietarioUnico(){

        $this->validate();

        $this->reset(['predios', 'prediosOld']);

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

                $predio = PropiedadOld::where('distrito', $propietario->distrito)
                                        ->where('tomo', $propietario->tomo)
                                        ->where('registro', $propietario->registro)
                                        ->where('noprop', $propietario->noprop)
                                        ->where('status', '!=', 'V')
                                        ->first();

                array_push($this->prediosOld, $predio);

            }

            if(count($this->prediosOld) == 0){

                $this->flagNegativo = true;

                $this->dispatch('mostrarMensaje', ['warning', "No se encontraron resultados con la información ingresada."]);

            }

            if(count($this->prediosOld) == 1){

                $this->flagUnico = true;

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

                    $this->flagNegativo = true;

                    $this->dispatch('mostrarMensaje', ['warning', "No se encontraron resultados con la información ingresada."]);

                }

                if(count($this->predios) == 1){

                    $this->flagUnico = true;

                    $this->dispatch('mostrarMensaje', ['warning', "Se encontró al menos una propiedad."]);

                }

            }else{

                $this->flagNegativo = true;

                $this->dispatch('mostrarMensaje', ['warning', "No se encontraron resultados con la información ingresada."]);

            }

        }

        if($this->flagUnico &&  $this->certificacion->movimientoRegistral->folioReal){

            $this->buscarPropietarioEnFolio();

        }

    }

    public function generarCertificadoPropiedadUnico(){

        $this->validate();

        if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($this->certificacion) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->certificacion)->format('d-m-Y')]);

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

    public function generarCertificadoColindancias(){

        if($this->certificacion->movimientoRegistral->getRawOriginal('distrito') != 2){

            if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

                if(!($this->calcularDiaElaboracion($this->certificacion) <= now())){

                    $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->certificacion)->format('d-m-Y')]);

                    return;

                }

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
                $this->certificacion->tipo_certificado = 4;
                $this->certificacion->observaciones_certificado = $this->observaciones;
                $this->certificacion->save();

                $this->procesarPersona($this->nombre, $this->ap_paterno, $this->ap_materno);

            });

            $this->dispatch('imprimir_propiedad_colindancias', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

        } catch (\Throwable $th) {

            Log::error("Error al generar certificado con colindancias por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function generarCertificadoPropiedad(){

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
                $this->certificacion->tipo_certificado = 2;
                $this->certificacion->observaciones_certificado = $this->observaciones;
                $this->certificacion->save();

                $this->procesarPersona($this->nombre, $this->ap_paterno, $this->ap_materno);

            });

            $this->dispatch('imprimir_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

        } catch (\Throwable $th) {

            Log::error("Error al generar certificado de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

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

    public function rechazar(){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia'])){

            if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

                if(!($this->calcularDiaElaboracion($this->certificacion) <= now())){

                    $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->certificacion)->format('d-m-Y')]);

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

        if($this->certificacion->movimientoRegistral->folioReal->predio){

            foreach ($this->certificacion->movimientoRegistral->folioReal->predio->colindancias as $colindancia) {

                $this->medidas[] = [
                    'id' => $colindancia->id,
                    'viento' => $colindancia->viento,
                    'longitud' => $colindancia->longitud,
                    'descripcion' => $colindancia->descripcion,
                ];

            }

        }

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-propiedad')->extends('layouts.admin');
    }

}
