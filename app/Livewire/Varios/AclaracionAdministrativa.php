<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Colindancia;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\Inscripciones\Varios\VariosTrait;
use Livewire\WithFileUploads;

class AclaracionAdministrativa extends Component
{

    use WithFileUploads;
    use VariosTrait;

    public $modalPropietario = false;
    public $crear = false;
    public $editar = false;

    public $areas;
    public $divisas;
    public $vientos;
    public $tipos_vialidades;
    public $tipos_asentamientos;

    public $medidas = [];

    public $predio;

    public $actor;

    public $tipo_propietario;
    public $porcentaje_propiedad = 0.00;
    public $porcentaje_nuda = 0.00;
    public $porcentaje_usufructo = 0.00;
    public $tipo_persona;
    public $nombre;
    public $ap_paterno;
    public $ap_materno;
    public $curp;
    public $rfc;
    public $razon_social;
    public $fecha_nacimiento;
    public $nacionalidad;
    public $estado_civil;
    public $calle;
    public $numero_exterior_propietario;
    public $numero_interior_propietario;
    public $colonia;
    public $cp;
    public $entidad;
    public $ciudad;
    public $municipio_propietario;

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
            'predio.superficie_terreno' => 'required',
            'predio.unidad_area' => 'required',
            'predio.superficie_construccion' => 'required',
            'predio.monto_transaccion' => 'required',
            'predio.observaciones' => 'nullable',
            'predio.curt' => 'nullable',
            'predio.superficie_judicial' => 'nullable',
            'predio.superficie_notarial' => 'nullable',
            'predio.area_comun_terreno' => 'nullable',
            'predio.area_comun_construccion' => 'nullable',
            'predio.valor_terreno_comun' => 'nullable',
            'predio.valor_construccion_comun' => 'nullable',
            'predio.valor_total_terreno' => 'nullable',
            'predio.valor_total_construccion' => 'nullable',
            'predio.valor_catastral' => 'nullable',
            'predio.divisa' => 'required',
            'predio.codigo_postal' => 'nullable',
            'predio.nombre_asentamiento' => 'nullable',
            'predio.municipio' => 'nullable',
            'predio.ciudad' => 'nullable',
            'predio.tipo_asentamiento' => 'nullable',
            'predio.localidad' => 'nullable',
            'predio.tipo_vialidad' => 'nullable',
            'predio.nombre_vialidad' => 'nullable',
            'predio.numero_exterior' => 'nullable',
            'predio.numero_interior' => 'nullable',
            'predio.nombre_edificio' => 'nullable',
            'predio.departamento_edificio' => 'nullable',
            'predio.departamento_edificio' => 'nullable',
            'predio.descripcion' => 'nullable',
            'predio.lote' => 'nullable',
            'predio.manzana' => 'nullable',
            'predio.ejido' => 'nullable',
            'predio.parcela' => 'nullable',
            'predio.solar' => 'nullable',
            'predio.poblado' => 'nullable',
            'predio.numero_exterior_2' => 'nullable',
            'predio.numero_adicional' => 'nullable',
            'predio.numero_adicional_2' => 'nullable',
            'predio.lote_fraccionador' => 'nullable',
            'predio.manzana_fraccionador' => 'nullable',
            'predio.etapa_fraccionador' => 'nullable',
            'predio.clave_edificio' => 'nullable',
         ];
    }

    public function updated($property, $value){

        if(in_array($property, ['porcentaje_nuda', 'porcentaje_usufructo', 'porcentaje_propiedad']) && $value == ''){

            $this->$property = 0;

        }

        if(in_array($property, ['porcentaje_nuda', 'porcentaje_usufructo'])){

            $this->reset('porcentaje_propiedad');

        }elseif($property == 'porcentaje_propiedad'){

            $this->reset(['porcentaje_nuda', 'porcentaje_usufructo']);

        }

    }

    public function resetear(){

        $this->reset([
            'tipo_propietario',
            'porcentaje_propiedad',
            'porcentaje_nuda',
            'porcentaje_usufructo',
            'tipo_persona',
            'nombre',
            'ap_paterno',
            'ap_materno',
            'curp',
            'rfc',
            'razon_social',
            'fecha_nacimiento',
            'nacionalidad',
            'estado_civil',
            'calle',
            'numero_exterior_propietario',
            'numero_interior_propietario',
            'colonia',
            'cp',
            'entidad',
            'municipio_propietario',
            'modalPropietario'
        ]);
    }

    public function cargarPredioInicial(){

        if(!$this->vario->predio_id){

            $this->predio = Predio::create([
                'status' => 'varios',
                'cp_localidad' => $this->vario->movimientoRegistral->folioReal->predio->cp_localidad,
                'cp_oficina' => $this->vario->movimientoRegistral->folioReal->predio->cp_oficina,
                'cp_tipo_predio' => $this->vario->movimientoRegistral->folioReal->predio->cp_tipo_predio,
                'cp_registro' => $this->vario->movimientoRegistral->folioReal->predio->cp_registro,
                'superficie_terreno' => $this->vario->movimientoRegistral->folioReal->predio->superficie_terreno,
                'unidad_area' => $this->vario->movimientoRegistral->folioReal->predio->unidad_area,
                'superficie_construccion' => $this->vario->movimientoRegistral->folioReal->predio->superficie_construccion,
                'monto_transaccion' => $this->vario->movimientoRegistral->folioReal->predio->monto_transaccion,
                'divisa' => $this->vario->movimientoRegistral->folioReal->predio->divisa,
                'observaciones' => $this->vario->movimientoRegistral->folioReal->predio->observaciones,
                'superficie_judicial' => $this->vario->movimientoRegistral->folioReal->predio->superficie_judicial,
                'superficie_notarial' => $this->vario->movimientoRegistral->folioReal->predio->superficie_notarial,
                'area_comun_terreno' => $this->vario->movimientoRegistral->folioReal->predio->area_comun_terreno,
                'area_comun_construccion' => $this->vario->movimientoRegistral->folioReal->predio->area_comun_construccion,
                'valor_terreno_comun' => $this->vario->movimientoRegistral->folioReal->predio->valor_terreno_comun,
                'valor_construccion_comun' => $this->vario->movimientoRegistral->folioReal->predio->valor_construccion_comun,
                'valor_total_terreno' => $this->vario->movimientoRegistral->folioReal->predio->valor_total_terreno,
                'valor_total_construccion' => $this->vario->movimientoRegistral->folioReal->predio->valor_total_construccion,
                'valor_catastral' => $this->vario->movimientoRegistral->folioReal->predio->valor_catastral,
                'codigo_postal' => $this->vario->movimientoRegistral->folioReal->predio->codigo_postal,
                'nombre_asentamiento' => $this->vario->movimientoRegistral->folioReal->predio->nombre_asentamiento,
                'municipio' => $this->vario->movimientoRegistral->folioReal->predio->municipio,
                'ciudad' => $this->vario->movimientoRegistral->folioReal->predio->ciudad,
                'tipo_asentamiento' => $this->vario->movimientoRegistral->folioReal->predio->tipo_asentamiento,
                'localidad' => $this->vario->movimientoRegistral->folioReal->predio->localidad,
                'tipo_vialidad' => $this->vario->movimientoRegistral->folioReal->predio->tipo_vialidad,
                'nombre_vialidad' => $this->vario->movimientoRegistral->folioReal->predio->nombre_vialidad,
                'numero_exterior' => $this->vario->movimientoRegistral->folioReal->predio->numero_exterior,
                'numero_interior' => $this->vario->movimientoRegistral->folioReal->predio->numero_interior,
                'nombre_edificio' => $this->vario->movimientoRegistral->folioReal->predio->nombre_edificio,
                'departamento_edificio' => $this->vario->movimientoRegistral->folioReal->predio->departamento_edificio,
                'departamento_edificio' => $this->vario->movimientoRegistral->folioReal->predio->departamento_edificio,
                'descripcion' => $this->vario->movimientoRegistral->folioReal->predio->descripcion,
                'lote' => $this->vario->movimientoRegistral->folioReal->predio->lote,
                'manzana' => $this->vario->movimientoRegistral->folioReal->predio->manzana,
                'ejido' => $this->vario->movimientoRegistral->folioReal->predio->ejido,
                'parcela' => $this->vario->movimientoRegistral->folioReal->predio->parcela,
                'solar' => $this->vario->movimientoRegistral->folioReal->predio->solar,
                'poblado' => $this->vario->movimientoRegistral->folioReal->predio->poblado,
                'numero_exterior_2' => $this->vario->movimientoRegistral->folioReal->predio->numero_exterior_2,
                'numero_adicional' => $this->vario->movimientoRegistral->folioReal->predio->numero_adicional,
                'numero_adicional_2' => $this->vario->movimientoRegistral->folioReal->predio->numero_adicional_2,
                'lote_fraccionador' => $this->vario->movimientoRegistral->folioReal->predio->lote_fraccionador,
                'manzana_fraccionador' => $this->vario->movimientoRegistral->folioReal->predio->manzana_fraccionador,
                'etapa_fraccionador' => $this->vario->movimientoRegistral->folioReal->predio->etapa_fraccionador,
                'clave_edificio' => $this->vario->movimientoRegistral->folioReal->predio->clave_edificio,
            ]);

            $this->vario->update(['predio_id' => $this->predio->id]);

            foreach ($this->vario->movimientoRegistral->folioReal->predio->colindancias as $colindancia) {

                $this->predio->colindancias()->create([
                    'viento' => $colindancia->viento,
                    'longitud' => $colindancia->longitud,
                    'descripcion' => $colindancia->descripcion,
                ]);

            }

            foreach ($this->vario->movimientoRegistral->folioReal->predio->propietarios() as $actor) {

                $this->predio->actores()->create([
                    'persona_id' => $actor->persona_id,
                    'tipo_actor' => $actor->tipo_actor,
                    'porcentaje_propiedad' => $actor->porcentaje_propiedad,
                    'porcentaje_nuda' => $actor->porcentaje_nuda,
                    'porcentaje_usufructo' => $actor->porcentaje_usufructo,
                ]);

            }

        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                $this->vario->movimientoRegistral->update(['estado' => 'captura']);

                $this->vario->save();

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
            Log::error("Error al guardar aclaración administrativa por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function agregarColindancia(){

        $this->medidas[] = ['viento' => null, 'longitud' => null, 'descripcion' => null, 'id' => null];

    }

    public function borrarColindancia($index){

        $this->authorize('update',  $this->vario->movimientoRegistral);

        try {

            $this->predio->colindancias()->where('id', $this->medidas[$index]['id'])->delete();

        } catch (\Throwable $th) {
            Log::error("Error al borrar colindancia en sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
        }

        unset($this->medidas[$index]);

        $this->medidas = array_values($this->medidas);

    }

    public function agregarPropietario(){

        $this->modalPropietario = true;
        $this->crear = true;

    }

    public function guardarPropietario(){

        $this->authorize('update', $this->vario->movimientoRegistral);

        $this->validate([
            'porcentaje_propiedad' => 'nullable|numeric|min:0|max:100',
            'porcentaje_nuda' => 'nullable|numeric|min:0|max:100',
            'porcentaje_usufructo' => 'nullable|numeric|min:0|max:100',
            'tipo_persona' => 'required',
            'nombre' => [
                Rule::requiredIf($this->tipo_persona === 'FISICA')
            ],
            'ap_paterno' => Rule::requiredIf($this->tipo_persona === 'FISICA'),
            'ap_materno' => Rule::requiredIf($this->tipo_persona === 'FISICA'),
            'curp' => [
                'nullable',
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => [Rule::requiredIf($this->tipo_persona === 'MORAL')],
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable',
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior_propietario' => 'nullable',
            'numero_interior_propietario' => 'nullable',
            'colonia' => 'nullable',
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio_propietario' => 'nullable',
        ]);

        if($this->porcentaje_propiedad === 0 && $this->porcentaje_nuda === 0 && $this->porcentaje_usufructo === 0){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede ser 0."]);

            return;

        }

        if($this->revisarProcentajes()){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede exceder el 100%."]);

            return;

        }

        $persona = Persona::query()
                    ->where(function($q){
                        $q->when($this->nombre, fn($q) => $q->where('nombre', $this->nombre))
                            ->when($this->ap_paterno, fn($q) => $q->where('ap_paterno', $this->ap_paterno))
                            ->when($this->ap_materno, fn($q) => $q->where('ap_materno', $this->ap_materno));
                    })
                    ->when($this->razon_social, fn($q) => $q->orWhere('razon_social', $this->razon_social))
                    ->when($this->rfc, fn($q) => $q->orWhere('rfc', $this->rfc))
                    ->when($this->curp, fn($q) => $q->orWhere('curp', $this->curp))
                    ->first();

        if($persona){

            foreach ($this->predio->propietarios() as $propietario) {

                if($persona->id == $propietario->persona_id){

                    $this->dispatch('mostrarMensaje', ['error', "La persona ya es un propietario."]);

                    return;

                }

            }

        }

        try {

            DB::transaction(function () use ($persona){

                if($persona != null){

                    $persona->update([
                        'estado_civil' => $this->estado_civil,
                        'calle' => $this->calle,
                        'numero_exterior' => $this->numero_exterior_propietario,
                        'numero_interior' => $this->numero_interior_propietario,
                        'colonia' => $this->colonia,
                        'cp' => $this->cp,
                        'entidad' => $this->entidad,
                        'municipio' => $this->municipio_propietario,
                        'actualizado_por' => auth()->id()
                    ]);

                }else{

                    $persona = Persona::create([
                        'tipo' => $this->tipo_persona,
                        'nombre' => $this->nombre,
                        'ap_paterno' => $this->ap_paterno,
                        'ap_materno' => $this->ap_materno,
                        'curp' => $this->curp,
                        'rfc' => $this->rfc,
                        'razon_social' => $this->razon_social,
                        'fecha_nacimiento' => $this->fecha_nacimiento,
                        'nacionalidad' => $this->nacionalidad,
                        'estado_civil' => $this->estado_civil,
                        'calle' => $this->calle,
                        'numero_exterior' => $this->numero_exterior_propietario,
                        'numero_interior' => $this->numero_interior_propietario,
                        'colonia' => $this->colonia,
                        'cp' => $this->cp,
                        'entidad' => $this->entidad,
                        'municipio' => $this->municipio_propietario,
                        'creado_por' => auth()->id()
                    ]);

                }

                $this->predio->actores()->create([
                    'persona_id' => $persona->id,
                    'tipo_actor' => 'propietario',
                    'porcentaje_propiedad' => $this->porcentaje_propiedad,
                    'porcentaje_nuda' => $this->porcentaje_nuda,
                    'porcentaje_usufructo' => $this->porcentaje_usufructo,
                    'creado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El propietario se guardó con éxito."]);

                $this->resetear();

                $this->predio->refresh();

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar propietario en aclaración administrativa por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function revisarProcentajes($id = null){

        $pp = 0;

        $pn = 0;

        $pu = 0;

        foreach($this->predio->propietarios() as $propietario){

            if($id == $propietario->id)
                continue;

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        $pp = $pp + (float)$this->porcentaje_propiedad;

        $pn = $pn + (float)$this->porcentaje_nuda + $pp;

        $pu = $pu + (float)$this->porcentaje_usufructo + $pp;

        if($pn > 100 || $pu > 100)
            return true;
        else
            return false;

    }

    public function revisarProcentajesFinal(){

        $pn = 0;

        $pu = 0;

        $pp = 0;

        foreach($this->predio->propietarios() as $propietario){

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        if($pp == 0){

            if($pn <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de nuda propiedad no es el 100%."]);

                return true;

            }

            if($pu <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de usufructo no es el 100%."]);

                return true;

            }

        }else{


            if(($pn + $pp) <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de nuda propiedad no es el 100%."]);

                return true;

            }

            if(($pu + $pp) <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de usufructo no es el 100%."]);

                return true;

            }

        }

    }

    public function editarActor(Actor $actor){

        $this->resetear();

        $this->actor = $actor;

        $this->tipo_propietario = $actor->tipo_actor;
        $this->porcentaje_propiedad = $actor->porcentaje_propiedad;
        $this->porcentaje_nuda = $actor->porcentaje_nuda;
        $this->porcentaje_usufructo = $actor->porcentaje_usufructo;
        $this->tipo_persona = $actor->persona->tipo;
        $this->nombre = $actor->persona->nombre;
        $this->ap_paterno = $actor->persona->ap_paterno;
        $this->ap_materno = $actor->persona->ap_materno;
        $this->curp = $actor->persona->curp;
        $this->rfc = $actor->persona->rfc;
        $this->razon_social = $actor->persona->razon_social;
        $this->fecha_nacimiento = $actor->persona->fecha_nacimiento;
        $this->nacionalidad = $actor->persona->nacionalidad;
        $this->estado_civil = $actor->persona->estado_civil;
        $this->calle = $actor->persona->calle;
        $this->numero_exterior_propietario = $actor->persona->numero_exterior;
        $this->numero_interior_propietario = $actor->persona->numero_interior;
        $this->colonia = $actor->persona->colonia;
        $this->cp = $actor->persona->cp;
        $this->entidad = $actor->persona->entidad;
        $this->municipio_propietario = $actor->persona->municipio;

        $this->modalPropietario = true;

        $this->crear = false;

        $this->editar = true;

    }

    public function actualizarActor(){

        $this->validate([
            'porcentaje_propiedad' => 'nullable|numeric|min:0|max:100',
            'porcentaje_nuda' => 'nullable|numeric|min:0|max:100',
            'porcentaje_usufructo' => 'nullable|numeric|min:0|max:100',
            'tipo_persona' => 'required',
            'nombre' => [
                Rule::requiredIf($this->tipo_persona === 'FISICA')
            ],
            'ap_paterno' => Rule::requiredIf($this->tipo_persona === 'FISICA'),
            'ap_materno' => Rule::requiredIf($this->tipo_persona === 'FISICA'),
            'curp' => [
                'nullable',
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => [Rule::requiredIf($this->tipo_persona === 'MORAL')],
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable',
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior_propietario' => 'nullable',
            'numero_interior_propietario' => 'nullable',
            'colonia' => 'nullable',
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio_propietario' => 'nullable',
        ]);

        if($this->porcentaje_propiedad == 0 && $this->porcentaje_nuda == 0 && $this->porcentaje_usufructo == 0){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede ser 0."]);

            return;

        }

        if($this->revisarProcentajes($this->actor->id)){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede exceder el 100%."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->actor->persona->update([
                    'tipo' => $this->tipo_persona,
                    'nombre' => $this->nombre,
                    'ap_paterno' => $this->ap_paterno,
                    'ap_materno' => $this->ap_materno,
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'razon_social' => $this->razon_social,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'nacionalidad' => $this->nacionalidad,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior_propietario,
                    'numero_interior' => $this->numero_interior_propietario,
                    'colonia' => $this->colonia,
                    'cp' => $this->cp,
                    'entidad' => $this->entidad,
                    'municipio' => $this->municipio_propietario,
                    'creado_por' => auth()->id()
                ]);

                $this->actor->update([
                    'tipo_propietario' => $this->tipo_propietario,
                    'porcentaje_propiedad' => $this->porcentaje_propiedad,
                    'porcentaje_nuda' => $this->porcentaje_nuda,
                    'porcentaje_usufructo' => $this->porcentaje_usufructo,
                    'actualizado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito."]);

                $this->resetear();

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar actor en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarActor(Actor $actor){

        $this->authorize('update', $this->vario->movimientoRegistral);

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->resetear();

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en aclaración administrativa por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function propcesarPredio(){

        $this->vario->movimientoRegistral->folioReal->predio->cp_localidad = $this->predio->cp_localidad;
        $this->vario->movimientoRegistral->folioReal->predio->cp_oficina = $this->predio->cp_oficina;
        $this->vario->movimientoRegistral->folioReal->predio->cp_tipo_predio = $this->predio->cp_tipo_predio;
        $this->vario->movimientoRegistral->folioReal->predio->cp_registro = $this->predio->cp_registro;
        $this->vario->movimientoRegistral->folioReal->predio->superficie_terreno = $this->predio->superficie_terreno;
        $this->vario->movimientoRegistral->folioReal->predio->unidad_area = $this->predio->unidad_area;
        $this->vario->movimientoRegistral->folioReal->predio->superficie_construccion = $this->predio->superficie_construccion;
        $this->vario->movimientoRegistral->folioReal->predio->monto_transaccion = $this->predio->monto_transaccion;
        $this->vario->movimientoRegistral->folioReal->predio->observaciones = $this->predio->observaciones;
        $this->vario->movimientoRegistral->folioReal->predio->superficie_judicial = $this->predio->superficie_judicial;
        $this->vario->movimientoRegistral->folioReal->predio->superficie_notarial = $this->predio->superficie_notarial;
        $this->vario->movimientoRegistral->folioReal->predio->area_comun_terreno = $this->predio->area_comun_terreno;
        $this->vario->movimientoRegistral->folioReal->predio->area_comun_construccion = $this->predio->area_comun_construccion;
        $this->vario->movimientoRegistral->folioReal->predio->valor_terreno_comun = $this->predio->valor_terreno_comun;
        $this->vario->movimientoRegistral->folioReal->predio->valor_construccion_comun = $this->predio->valor_construccion_comun;
        $this->vario->movimientoRegistral->folioReal->predio->valor_total_terreno = $this->predio->valor_total_terreno;
        $this->vario->movimientoRegistral->folioReal->predio->valor_total_construccion = $this->predio->valor_total_construccion;
        $this->vario->movimientoRegistral->folioReal->predio->valor_catastral = $this->predio->valor_catastral;
        $this->vario->movimientoRegistral->folioReal->predio->codigo_postal = $this->predio->codigo_postal;
        $this->vario->movimientoRegistral->folioReal->predio->nombre_asentamiento = $this->predio->nombre_asentamiento;
        $this->vario->movimientoRegistral->folioReal->predio->municipio = $this->predio->municipio;
        $this->vario->movimientoRegistral->folioReal->predio->ciudad = $this->predio->ciudad;
        $this->vario->movimientoRegistral->folioReal->predio->tipo_asentamiento = $this->predio->tipo_asentamiento;
        $this->vario->movimientoRegistral->folioReal->predio->localidad = $this->predio->localidad;
        $this->vario->movimientoRegistral->folioReal->predio->tipo_vialidad = $this->predio->tipo_vialidad;
        $this->vario->movimientoRegistral->folioReal->predio->nombre_vialidad = $this->predio->nombre_vialidad;
        $this->vario->movimientoRegistral->folioReal->predio->numero_exterior = $this->predio->numero_exterior;
        $this->vario->movimientoRegistral->folioReal->predio->numero_interior = $this->predio->numero_interior;
        $this->vario->movimientoRegistral->folioReal->predio->nombre_edificio = $this->predio->nombre_edificio;
        $this->vario->movimientoRegistral->folioReal->predio->departamento_edificio = $this->predio->departamento_edificio;
        $this->vario->movimientoRegistral->folioReal->predio->departamento_edificio = $this->predio->departamento_edificio;
        $this->vario->movimientoRegistral->folioReal->predio->descripcion = $this->predio->descripcion;
        $this->vario->movimientoRegistral->folioReal->predio->lote = $this->predio->lote;
        $this->vario->movimientoRegistral->folioReal->predio->manzana = $this->predio->manzana;
        $this->vario->movimientoRegistral->folioReal->predio->ejido = $this->predio->ejido;
        $this->vario->movimientoRegistral->folioReal->predio->parcela = $this->predio->parcela;
        $this->vario->movimientoRegistral->folioReal->predio->solar = $this->predio->solar;
        $this->vario->movimientoRegistral->folioReal->predio->poblado = $this->predio->poblado;
        $this->vario->movimientoRegistral->folioReal->predio->numero_exterior_2 = $this->predio->numero_exterior_2;
        $this->vario->movimientoRegistral->folioReal->predio->numero_adicional = $this->predio->numero_adicional;
        $this->vario->movimientoRegistral->folioReal->predio->numero_adicional_2 = $this->predio->numero_adicional_2;
        $this->vario->movimientoRegistral->folioReal->predio->lote_fraccionador = $this->predio->lote_fraccionador;
        $this->vario->movimientoRegistral->folioReal->predio->manzana_fraccionador = $this->predio->manzana_fraccionador;
        $this->vario->movimientoRegistral->folioReal->predio->etapa_fraccionador = $this->predio->etapa_fraccionador;
        $this->vario->movimientoRegistral->folioReal->predio->clave_edificio = $this->predio->clave_edificio;
        $this->vario->movimientoRegistral->folioReal->predio->divisa = $this->predio->divisa;

        $this->vario->movimientoRegistral->folioReal->predio->save();

        $this->vario->movimientoRegistral->folioReal->predio->colindancias()->delete();

        foreach ($this->predio->colindancias as $colindancia) {

            $colindancia->update(['predio_id' => $this->vario->movimientoRegistral->folioReal->predio->id]);

        }

        foreach ($this->vario->movimientoRegistral->folioReal->predio->propietarios() as $propietario) {
            $propietario->delete();
        }

        foreach ($this->predio->propietarios() as $propietario) {
            $propietario->update(['actorable_id' => $this->vario->movimientoRegistral->folioReal->predio->id]);
        }

        $this->predio->colindancias()->delete();

        foreach ($this->predio->propietarios() as $propietario) {
            $propietario->delete();
        }

        $this->predio->delete();

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->vario->estado = 'activo';
                $this->vario->actualizado_por = auth()->id();
                $this->vario->fecha_inscripcion = now()->toDateString();
                $this->vario->save();

                $this->vario->movimientoRegistral->update(['estado' => 'elaborado']);

                $this->propcesarPredio();

            });

            return redirect()->route('varios');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function finalizar(){

        $this->validate();

        if($this->revisarProcentajesFinal()){

            return;

        }

        /* if(!$this->vario->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        } */

        $this->modalContraseña = true;

    }

    public function mount(){

        $this->vario->acto_contenido = 'ACLARACIÓN ADMINISTRATIVA';

        $this->cargarPredioInicial();

        $this->predio = $this->vario->predio;

        foreach ($this->predio->colindancias as $colindancia) {

            $this->medidas[] = [
                'id' => $colindancia->id,
                'viento' => $colindancia->viento,
                'longitud' => $colindancia->longitud,
                'descripcion' => $colindancia->descripcion,
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
        return view('livewire.varios.aclaracion-administrativa');
    }
}
