<?php

namespace App\Livewire\Inscripciones\Propiedad;

use Exception;
use App\Models\File;
use App\Models\User;
use Livewire\Component;
use App\Models\Colindancia;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;
use App\Traits\Inscripciones\Propiedad\PropiedadTrait;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;

class InscripcionGeneral extends Component
{

    use PropiedadTrait;
    use WithFileUploads;

    public $transmitentes = [];

    public $nuevoFolio;

    protected function rules(){
        return [
            'inscripcion.cp_localidad' => 'required',
            'inscripcion.cp_oficina' => 'required',
            'inscripcion.cp_tipo_predio' => 'required',
            'inscripcion.cp_registro' => 'required',
            /* 'inscripcion.cc_region_catastral' => 'required',
            'inscripcion.cc_municipio' => 'required',
            'inscripcion.cc_zona_catastral' => 'required',
            'inscripcion.cc_sector' => 'required',
            'inscripcion.cc_manzana' => 'required',
            'inscripcion.cc_predio' => 'required',
            'inscripcion.cc_edificio' => 'required',
            'inscripcion.cc_departamento' => 'required', */
            'inscripcion.acto_contenido' => 'required',
            'inscripcion.descripcion_acto' => 'nullable',
            'inscripcion.superficie_terreno' => 'nullable',
            'inscripcion.unidad_area' => 'required',
            'inscripcion.superficie_construccion' => 'nullable',
            'inscripcion.monto_transaccion' => 'required',
            'inscripcion.observaciones' => 'nullable',
            'inscripcion.divisa' => 'nullable',
            'inscripcion.superficie_judicial' => 'nullable',
            'inscripcion.superficie_notarial' => 'nullable',
            'inscripcion.area_comun_terreno' => 'nullable',
            'inscripcion.area_comun_construccion' => 'nullable',
            'inscripcion.valor_terreno_comun' => 'nullable',
            'inscripcion.valor_construccion_comun' => 'nullable',
            'inscripcion.valor_total_terreno' => 'nullable',
            'inscripcion.valor_total_construccion' => 'nullable',
            'inscripcion.valor_catastral' => 'nullable',
            'inscripcion.codigo_postal' => 'nullable',
            'inscripcion.nombre_asentamiento' => 'nullable',
            'inscripcion.municipio' => 'nullable',
            'inscripcion.ciudad' => 'nullable',
            'inscripcion.tipo_asentamiento' => 'nullable',
            'inscripcion.localidad' => 'nullable',
            'inscripcion.tipo_vialidad' => 'nullable',
            'inscripcion.nombre_vialidad' => 'nullable',
            'inscripcion.numero_exterior' => 'nullable',
            'inscripcion.numero_interior' => 'nullable',
            'inscripcion.nombre_edificio' => 'nullable',
            'inscripcion.departamento_edificio' => 'nullable',
            'inscripcion.departamento_edificio' => 'nullable',
            'inscripcion.descripcion' => 'nullable',
            'inscripcion.lote' => 'nullable',
            'inscripcion.manzana' => 'nullable',
            'inscripcion.ejido' => 'nullable',
            'inscripcion.parcela' => 'nullable',
            'inscripcion.solar' => 'nullable',
            'inscripcion.poblado' => 'nullable',
            'inscripcion.numero_exterior_2' => 'nullable',
            'inscripcion.numero_adicional' => 'nullable',
            'inscripcion.numero_adicional_2' => 'nullable',
            'inscripcion.lote_fraccionador' => 'nullable',
            'inscripcion.manzana_fraccionador' => 'nullable',
            'inscripcion.etapa_fraccionador' => 'nullable',
            'inscripcion.clave_edificio' => 'nullable',
         ];
    }

    public function updatedTransmitentes($value, $index){

        $i = explode('.', $index);

        if($this->transmitentes[$i[0]][$i[1]] == ''){

            $this->transmitentes[$i[0]][$i[1]] = 0;

        }

    }

    public function validaciones(){

        if($this->inscripcion->propietarios()->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe tener almenos un propietario."]);

            return true;

        }

        if($this->inscripcion->transmitentes()->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe tener almenos un transmitente."]);

            return true;

        }

        /* if($this->revisarProcentajes()) return true; */

        if($this->inscripcion->movimientoRegistral->estado != 'correccion'){

            if($this->revisarProcentajesFinal()) return true;

        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                if($this->inscripcion->movimientoRegistral->estado != 'correccion')
                    $this->inscripcion->movimientoRegistral->estado = 'captura';

                $this->inscripcion->movimientoRegistral->actualizado_por = auth()->id();
                $this->inscripcion->movimientoRegistral->save();

                $this->inscripcion->save();

                $this->predio->save();

                foreach ($this->medidas as $key =>$medida) {

                    if($medida['id'] == null){

                        $aux = $this->predio->colindancias()->create([
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

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function revisarProcentajes($id = null){

        $pp_transmitentes = 0;

        $pp_adquirientes = 0;

        $pn_transmitentes = 0;

        $pn_adquirientes = 0;

        $pu_transmitentes = 0;

        $pu_adquirientes = 0;

        foreach($this->inscripcion->transmitentes() as $transmitente){

            $pn_transmitentes = $pn_transmitentes + $transmitente->porcentaje_nuda;

            $pu_transmitentes = $pu_transmitentes + $transmitente->porcentaje_usufructo;

            $pp_transmitentes = $pp_transmitentes + $transmitente->porcentaje_propiedad;

        }

        foreach($this->inscripcion->propietarios() as $propietario){

            if($id == $propietario->id)
                continue;

            $pn_adquirientes = $pn_adquirientes + $propietario->porcentaje_nuda;

            $pu_adquirientes = $pu_adquirientes + $propietario->porcentaje_usufructo;

            $pp_adquirientes = $pp_adquirientes + $propietario->porcentaje_propiedad;

        }

        if($pp_transmitentes == 0){

            if(($this->porcentaje_propiedad + $pp_adquirientes) > $pp_transmitentes){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de propiedad no puede exceder el " . $pp_transmitentes . '%.']);

                return true;

            }

            if(($this->porcentaje_nuda + $pn_adquirientes) > $pn_transmitentes){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de nuda no puede exceder el " . $pn_transmitentes . '%.']);

                return true;

            }

            if(($this->porcentaje_usufructo + $pu_adquirientes) > $pu_transmitentes){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de usufructo no puede exceder el " . $pu_transmitentes . '%.']);

                return true;

            }

        }else{

            if(($this->porcentaje_propiedad + $pp_adquirientes) > $pp_transmitentes){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de propiedad no puede exceder el " . $pp_transmitentes . '%.']);

                return true;

            }

            if(($this->porcentaje_nuda + $pn_adquirientes + $pp_adquirientes) > $pp_transmitentes){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de nuda no puede exceder el " . $pn_transmitentes . '%.']);

                return true;

            }

            if(($this->porcentaje_usufructo + $pu_adquirientes + $pp_adquirientes) > $pp_transmitentes){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de usufructo no puede exceder el " . $pu_transmitentes . '%.']);

                return true;

            }

        }

    }

    public function revisarProcentajesFinal(){

        $pn_adquirientes = 0;

        $pn_transmitentes = 0;

        $pu_adquirientes = 0;

        $pu_transmitentes = 0;

        $pp_adquirientes = 0;

        $pp_transmitentes = 0;

        $pu = 0;

        $pp = 0;

        $pn = 0;

        foreach($this->inscripcion->propietarios() as $adquiriente){

            $pn_adquirientes = $pn_adquirientes + $adquiriente['porcentaje_nuda'];

            $pu_adquirientes = $pu_adquirientes + $adquiriente['porcentaje_usufructo'];

            $pp_adquirientes = $pp_adquirientes + $adquiriente['porcentaje_propiedad'];

        }

        foreach($this->inscripcion->transmitentes() as $transmitente){

            $pn_transmitentes = $pn_transmitentes + $transmitente['porcentaje_nuda'];

            $pu_transmitentes = $pu_transmitentes + $transmitente['porcentaje_usufructo'];

            $pp_transmitentes = $pp_transmitentes + $transmitente['porcentaje_propiedad'];

        }

        foreach($this->transmitentes as $transmitente){

            $pn = $pn + $transmitente['porcentaje_nuda'];

            $pu = $pu + $transmitente['porcentaje_usufructo'];

            $pp = $pp + $transmitente['porcentaje_propiedad'];

        }

        $suma = $pp_adquirientes + $pp;

        if($suma == 0){

            $suma = $pn_adquirientes + $pn;

            if(round($suma, 2) > round($pn_transmitentes + $pp_transmitentes,2)){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de propiedad debe ser " . $pp_transmitentes . '%.']);

                return true;

            }

            $suma = $pu_adquirientes + $pu;

            if(round($suma, 2) != round($pu_transmitentes + $pp_transmitentes, 2)){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de usufructo debe ser " . $pu_transmitentes + $pp_transmitentes . '%.']);

                return true;

            }

        }else{

            if(round($suma,2) != round($pp_transmitentes,2)){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de propiedad debe ser " . $pp_transmitentes . '%.']);

                return true;

            }

            $suma = $pn_adquirientes + $pn;

            if(round($suma, 2) != round($pn_transmitentes,2)){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de nuda debe ser " . $pn_transmitentes . '%.']);

                return true;

            }

            $suma = $pu_adquirientes + $pu;

            if(round($suma, 2) != round($pu_transmitentes, 2)){

                $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de usufructo debe ser " . $pu_transmitentes . '%.']);

                return true;

            }

        }

        /* if(round($suma,2) < round(($pp_transmitentes - 0.01), 2)){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de propiedad debe ser " . $pp_transmitentes . '%.']);

            return true;

        }

        $suma = $pn_adquirientes + $pn;

        if(round($suma, 2) < round(($pn_transmitentes -0.01), 2)){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de nuda debe ser " . $pn_transmitentes . '%.']);

            return true;

        }

        $suma = $pu_adquirientes + $pu;

        if(round($suma, 2) < round(($pu_transmitentes -0.01), 2)){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de usufructo debe ser " . $pu_transmitentes . '%.']);

            return true;

        } */

    }

    public function revisarPorcentajesCorreccion(){

        $pn = 0;

        $pu = 0;

        $pp = 0;

        foreach($this->predio->propietarios() as $propietario){

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        if($pp == 0){

            if($pn < 99.9999){

                throw new Exception("El porcentaje de nuda propiedad no es el 100%.");

            }

            if($pu < 99.9999){

                throw new Exception("El porcentaje de usufructo no es el 100%.");

            }

        }else{


            if(($pn + $pp) < 99.9999){

                throw new Exception("El porcentaje de nuda propiedad no es el 100%.");

            }

            if(($pu + $pp) < 99.9999){

                throw new Exception("El porcentaje de usufructo no es el 100%.");

            }

        }

    }

    public function procesarPropietarios(){

        foreach($this->transmitentes as $propietario){

            if($propietario['porcentaje_propiedad'] == 0 && $propietario['porcentaje_nuda'] == 0 && $propietario['porcentaje_usufructo'] == 0){

                $actor = $this->predio->actores()->whereHas('persona', function($q) use($propietario){
                                                                                $q->where('nombre', $propietario['nombre'])
                                                                                    ->where('ap_paterno', $propietario['ap_paterno'])
                                                                                    ->where('ap_materno', $propietario['ap_materno'])
                                                                                    ->where('razon_social', $propietario['razon_social']);
                                                                                })
                                                                                ->first();

                $actor->delete();

            }else{

                 $aux = $this->predio->actores()->whereHas('persona', function($q) use($propietario){
                                                                                    $q->where('nombre', $propietario['nombre'])
                                                                                        ->where('ap_paterno', $propietario['ap_paterno'])
                                                                                        ->where('ap_materno', $propietario['ap_materno'])
                                                                                        ->where('razon_social', $propietario['razon_social']);
                                                                                    })
                                                                                    ->first();

                $aux->update([
                    'porcentaje_propiedad' => $propietario['porcentaje_propiedad'],
                    'porcentaje_nuda' => $propietario['porcentaje_nuda'],
                    'porcentaje_usufructo' => $propietario['porcentaje_usufructo'],
                ]);

            }

            $transmitente = $this->inscripcion->transmitentes()->find($propietario['id']);

            if($transmitente){

                $transmitente->update([
                    'porcentaje_propiedad' => $transmitente->porcentaje_propiedad - $propietario['porcentaje_propiedad'],
                    'porcentaje_nuda' => $transmitente->porcentaje_nuda - $propietario['porcentaje_nuda'],
                    'porcentaje_usufructo' => $transmitente->porcentaje_usufructo - $propietario['porcentaje_usufructo'],
                ]);

            }

        }

        foreach($this->inscripcion->propietarios() as $adquiriente){

            $actor = $this->predio->actores()->where('persona_id', $adquiriente->persona_id)->first();

            if($actor){

                $actor->update([
                    'porcentaje_propiedad' => $actor->porcentaje_propiedad + $adquiriente->porcentaje_propiedad,
                    'porcentaje_nuda' => $actor->porcentaje_nuda + $adquiriente->porcentaje_nuda,
                    'porcentaje_usufructo' => $actor->porcentaje_usufructo + $adquiriente->porcentaje_usufructo,
                ]);

            }else{

                $this->predio->actores()->create([
                    'persona_id' => $adquiriente->persona->id,
                    'tipo_actor' => 'propietario',
                    'porcentaje_propiedad' => $adquiriente->porcentaje_propiedad,
                    'porcentaje_nuda' => $adquiriente->porcentaje_nuda,
                    'porcentaje_usufructo' => $adquiriente->porcentaje_usufructo,
                    'creado_por' => auth()->id()
                ]);

            }

        }

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->predio->save();

                if($this->inscripcion->movimientoRegistral->estado != 'correccion'){

                    $this->procesarPropietarios();

                }else{

                    $this->revisarProcentajes();

                }

                $this->inscripcion->fecha_inscripcion = now()->toDateString();
                $this->inscripcion->actualizado_por = auth()->id();
                $this->inscripcion->save();

                $this->predio->cp_localidad = $this->inscripcion->cp_localidad;
                $this->predio->cp_oficina = $this->inscripcion->cp_oficina;
                $this->predio->cp_tipo_predio = $this->inscripcion->cp_tipo_predio;
                $this->predio->cp_registro = $this->inscripcion->cp_registro;
                $this->predio->superficie_terreno = $this->inscripcion->superficie_terreno;
                $this->predio->unidad_area = $this->inscripcion->unidad_area;
                $this->predio->superficie_construccion = $this->inscripcion->superficie_construccion;
                $this->predio->monto_transaccion = $this->inscripcion->monto_transaccion;
                $this->predio->observaciones = $this->inscripcion->observaciones;
                $this->predio->superficie_judicial = $this->inscripcion->superficie_judicial;
                $this->predio->superficie_notarial = $this->inscripcion->superficie_notarial;
                $this->predio->area_comun_terreno = $this->inscripcion->area_comun_terreno;
                $this->predio->area_comun_construccion = $this->inscripcion->area_comun_construccion;
                $this->predio->valor_terreno_comun = $this->inscripcion->valor_terreno_comun;
                $this->predio->valor_construccion_comun = $this->inscripcion->valor_construccion_comun;
                $this->predio->valor_total_terreno = $this->inscripcion->valor_total_terreno;
                $this->predio->valor_total_construccion = $this->inscripcion->valor_total_construccion;
                $this->predio->valor_catastral = $this->inscripcion->valor_catastral;
                $this->predio->codigo_postal = $this->inscripcion->codigo_postal;
                $this->predio->nombre_asentamiento = $this->inscripcion->nombre_asentamiento;
                $this->predio->municipio = $this->inscripcion->municipio;
                $this->predio->ciudad = $this->inscripcion->ciudad;
                $this->predio->tipo_asentamiento = $this->inscripcion->tipo_asentamiento;
                $this->predio->localidad = $this->inscripcion->localidad;
                $this->predio->tipo_vialidad = $this->inscripcion->tipo_vialidad;
                $this->predio->nombre_vialidad = $this->inscripcion->nombre_vialidad;
                $this->predio->numero_exterior = $this->inscripcion->numero_exterior;
                $this->predio->numero_interior = $this->inscripcion->numero_interior;
                $this->predio->nombre_edificio = $this->inscripcion->nombre_edificio;
                $this->predio->departamento_edificio = $this->inscripcion->departamento_edificio;
                $this->predio->departamento_edificio = $this->inscripcion->departamento_edificio;
                $this->predio->descripcion = $this->inscripcion->descripcion;
                $this->predio->lote = $this->inscripcion->lote;
                $this->predio->manzana = $this->inscripcion->manzana;
                $this->predio->ejido = $this->inscripcion->ejido;
                $this->predio->parcela = $this->inscripcion->parcela;
                $this->predio->solar = $this->inscripcion->solar;
                $this->predio->poblado = $this->inscripcion->poblado;
                $this->predio->numero_exterior_2 = $this->inscripcion->numero_exterior_2;
                $this->predio->numero_adicional = $this->inscripcion->numero_adicional;
                $this->predio->numero_adicional_2 = $this->inscripcion->numero_adicional_2;
                $this->predio->lote_fraccionador = $this->inscripcion->lote_fraccionador;
                $this->predio->manzana_fraccionador = $this->inscripcion->manzana_fraccionador;
                $this->predio->etapa_fraccionador = $this->inscripcion->etapa_fraccionador;
                $this->predio->clave_edificio = $this->inscripcion->clave_edificio;
                $this->predio->actualizado_por = auth()->id();
                $this->predio->save();

                foreach ($this->medidas as $key =>$medida) {

                    if($medida['id'] == null){

                        $aux = $this->predio->colindancias()->create([
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

                if($this->nuevoFolio) $this->generarNuevoFolioReal();

                $this->inscripcion->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->inscripcion->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de propiedad']);

                (new PropiedadController())->caratula($this->inscripcion);

            });

            return redirect()->route('propiedad');

        } catch (Exception $ex) {

            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $ex);
            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if(!$this->inscripcion->movimientoRegistral->documentoEntrada()){

            try {

                $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                    ->accept('application/json')
                                    ->asForm()
                                    ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                        'año' => $this->inscripcion->movimientoRegistral->año,
                                                                                        'tramite' => $this->inscripcion->movimientoRegistral->tramite,
                                                                                        'usuario' => $this->inscripcion->movimientoRegistral->usuario,
                                                                                        'estado' => 'nuevo'
                                                                                    ]);

                $data = collect(json_decode($response, true));

                if($response->status() == 200){

                    $contents = file_get_contents($data['url']);

                    $filename = basename($data['url']);

                    Storage::disk('documento_entrada')->put($filename, $contents);

                    File::create([
                        'fileable_id' => $this->inscripcion->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $filename
                    ]);

                }

            } catch (ConnectionException $th) {

                Log::error("Error al cargar archivo en cancelación: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

                $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

            }

        }

        $this->predio = $this->inscripcion->movimientoRegistral->folioReal->predio;

        if(!$this->inscripcion->acto_contenido){

            foreach($this->inscripcion->getAttributes() as $attribute => $value){

                if(!$value && isset($this->predio->{ $attribute })){

                    $this->inscripcion->{$attribute} = $this->predio->{ $attribute};

                }

            }

        }

        foreach ($this->predio->colindancias as $colindancia) {

            $this->medidas[] = [
                'id' => $colindancia->id,
                'viento' => $colindancia->viento,
                'longitud' => $colindancia->longitud,
                'descripcion' => $colindancia->descripcion,
            ];

        }

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first();

        if(!$director) abort(500, message:"Es necesario registrar al director.");

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento inscripciones');
        })->first();

        if(!$jefe_departamento) abort(500, message:"Es necesario registrar al jefe de Departamento de Registro de Inscripciones.");

        foreach ($this->inscripcion->transmitentes() as $transmitente) {

            $this->transmitentes[] = [
                'id' => $transmitente['id'],
                'nombre' => $transmitente->persona->nombre,
                'ap_paterno' => $transmitente->persona->ap_paterno,
                'ap_materno' => $transmitente->persona->ap_materno,
                'razon_social' => $transmitente->persona->razon_social,
                'porcentaje_propiedad' => $transmitente->porcentaje_propiedad,
                'porcentaje_nuda' => $transmitente->porcentaje_nuda,
                'porcentaje_usufructo' => $transmitente->porcentaje_usufructo,
            ];
        }

        $this->areas = Constantes::UNIDADES;

        $this->divisas = Constantes::DIVISAS;

        $this->vientos = Constantes::VIENTOS;

        $this->tipos_vialidades = Constantes::TIPO_VIALIDADES;

        $this->tipos_asentamientos = Constantes::TIPO_ASENTAMIENTO;

    }

    public function render()
    {

        $this->authorize('view', $this->inscripcion->movimientoRegistral);

        if($this->inscripcion->movimientoRegistral->folioReal->estado != 'activo') abort(401, 'El folio real no esta activo');

        return view('livewire.inscripciones.propiedad.inscripcion-general');
    }
}
