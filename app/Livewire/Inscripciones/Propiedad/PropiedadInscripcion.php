<?php

namespace App\Livewire\Inscripciones\Propiedad;

use App\Constantes\Constantes;
use App\Models\User;
use App\Models\Actor;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Propiedad;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class PropiedadInscripcion extends Component
{

    public $modalPropietario;
    public $modalTransmitente;
    public $modalRepresentante;
    public $modalContraseña;
    public $crear = false;
    public $editar = false;

    public $actos;
    public $acto;

    public $inscripcion;
    public $propiedad;
    public $predio;

    public $actor;

    public $transmitentes = [];
    public $propietarios = [];
    public $representados = [];
    public $tipo_propietario;
    public $porcentaje_propiedad;
    public $porcentaje_nuda;
    public $porcentaje_usufructo;
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
    public $representa_a;
    public $partes_iguales;

    public $tipos_propietarios;

    public $estados;

    public $tipos_vialidades;
    public $codigos_postales;

    public $propietario;

    public $descripcion;

    public $contraseña;

    protected function rules(){
        return [
            'inscripcion.cp_localidad' => 'required',
            'inscripcion.cp_oficina' => 'required',
            'inscripcion.cp_tipo_predio' => 'required',
            'inscripcion.cp_registro' => 'required',
            'inscripcion.cc_region_catastral' => 'required',
            'inscripcion.cc_municipio' => 'required',
            'inscripcion.cc_zona_catastral' => 'required',
            'inscripcion.cc_sector' => 'required',
            'inscripcion.cc_manzana' => 'required',
            'inscripcion.cc_predio' => 'required',
            'inscripcion.cc_edificio' => 'required',
            'inscripcion.cc_departamento' => 'required',
            'inscripcion.acto_contenido' => 'required',
            'inscripcion.descripcion_acto' => 'nullable',
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
                /* Rule::requiredIf($this->tipo_persona === 'FISICA'), */
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => ['nullable', /* Rule::requiredIf($this->tipo_persona === 'MORAL') */],
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior_propietario' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'numero_interior_propietario' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'colonia' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio_propietario' => 'nullable',
            'representados' => Rule::requiredIf($this->modalRepresentante === true),
         ];
    }

    public function updated($property, $value){

        if(in_array($property, ['porcentaje_nuda', 'porcentaje_usufructo', 'porcentaje_propiedad']) && $value == ''){

            $this->$property = null;

        }

        if(in_array($property, ['porcentaje_nuda', 'porcentaje_usufructo'])){

            $this->reset('porcentaje_propiedad');

        }elseif($property == 'porcentaje_propiedad'){

            $this->reset(['porcentaje_nuda', 'porcentaje_usufructo']);

        }

    }

    public function updatedTipoPersona(){

        if($this->tipo_persona == 'FISICA'){

            $this->reset('razon_social');

        }elseif($this->tipo_persona == 'MORAL'){

            $this->reset([
                'nombre',
                'ap_paterno',
                'ap_materno',
                'curp',
                'fecha_nacimiento',
                'estado_civil',
            ]);

        }

    }

    public function resetear(){

        $this->reset([
            'tipo_propietario',
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
            'modalPropietario',
            'modalTransmitente',
            'modalRepresentante',
            'modalContraseña',
            'partes_iguales'
        ]);
    }

    public function agregarPropietario(){

        $this->modalPropietario = true;
        $this->modalTransmitente = false;
        $this->modalRepresentante = false;
        $this->crear = true;

    }

    public function agregarTransmitente(){

        $this->modalPropietario = false;
        $this->modalTransmitente = true;
        $this->modalRepresentante = false;
        $this->crear = true;

    }

    public function agregarRepresentante(){

        $this->modalPropietario = false;
        $this->modalTransmitente = false;
        $this->modalRepresentante = true;
        $this->crear = true;

    }

    public function guardarPropietario(){

        $this->authorize('update', $this->inscripcion->movimientoRegistral);

        $this->validate();

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

            foreach ($this->inscripcion->propietarios() as $propietario) {

                if($persona->id == $propietario->persona_id){

                    $this->dispatch('mostrarMensaje', ['error', "La persona ya es un propietario."]);

                    return;

                }

            }

        }

        if($this->porcentaje_propiedad == 0 && $this->porcentaje_nuda == 0 && $this->porcentaje_usufructo == 0){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede ser 0."]);

            return;

        }

        if($this->revisarProcentajes()) return;

        try {

            DB::transaction(function () use ($persona){

                if($persona != null){

                    $persona->update([
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

                $actor = $this->inscripcion->actores()->create([
                    'persona_id' => $persona->id,
                    'tipo_actor' => 'propietario',
                    'porcentaje_propiedad' => $this->porcentaje_propiedad,
                    'porcentaje_nuda' => $this->porcentaje_nuda,
                    'porcentaje_usufructo' => $this->porcentaje_usufructo,
                    'creado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El propietario se guardó con éxito."]);

                $this->dispatch('recargar', ['id' => $actor->id, 'description' => $actor->persona->nombre . ' ' . $actor->persona->ap_paterno . ' ' . $actor->persona->ap_materno . ' ' . $actor->persona->razon_social]);

                $this->resetear();

                $this->inscripcion->load('actores.persona');

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar propietario en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function guardarTransmitente(){

        $this->authorize('update', $this->inscripcion->movimientoRegistral);

        $this->validate(['propietario' => 'required']);

        $propietario = Actor::find($this->propietario);

        foreach ($this->inscripcion->transmitentes() as $transmitente) {

            if($propietario->persona_id == $transmitente->persona_id){

                $this->dispatch('mostrarMensaje', ['error', "La persona ya es un transmitente."]);

                return;

            }

        }

        try {

            DB::transaction(function () use($propietario){

                $actor = $this->inscripcion->actores()->create([
                    'persona_id' => $propietario->persona_id,
                    'tipo_actor' => 'transmitente',
                    'porcentaje_propiedad' => $propietario->porcentaje_propiedad,
                    'porcentaje_nuda' => $propietario->porcentaje_nuda,
                    'porcentaje_usufructo' => $propietario->porcentaje_usufructo,
                    'creado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El transmitente se guardó con éxito."]);

                $this->dispatch('recargar', ['id' => $actor->id, 'description' => $actor->persona->nombre . ' ' . $actor->persona->ap_paterno . ' ' . $actor->persona->ap_materno . ' ' . $actor->persona->razon_social]);

                $this->resetear();

                $this->inscripcion->load('actores.persona');

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar transmitente en inscripción de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function guardarRepresentante(){

        $this->authorize('update', $this->inscripcion->movimientoRegistral);

        $this->validate();

        $persona = Persona::where('rfc', $this->rfc)->first();

        if($persona){

            foreach ($this->inscripcion->propietarios() as $propietario) {

                if($persona->id == $propietario->persona_id){

                    $this->dispatch('mostrarMensaje', ['error', "La persona ya es un representante."]);

                    return;

                }

            }

        }

        try {

            DB::transaction(function () use ($persona){

                if($persona){

                    $persona->update([
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

                $representante = $this->inscripcion->actores()->create([
                    'persona_id' => $persona->id,
                    'tipo_actor' => 'representante',
                    'creado_por' => auth()->id()
                ]);

                foreach($this->representados as $representado){

                    Actor::find($representado)->update(['representado_por' => $representante->id]);

                }

                $this->dispatch('mostrarMensaje', ['success', "El representante se guardó con éxito."]);

                $this->resetear();

                $this->inscripcion->load('actores.persona');

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar representante en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function editarActor(Actor $actor, $tipo){

        $this->resetear();

        $this->actor = $actor;

        $this->tipo_propietario = $actor->tipo_actor;
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

        if($tipo == 'propietario')
            $this->modalPropietario = true;
        elseif($tipo == 'transmitente')
            $this->modalTransmitente = true;
        elseif($tipo == 'representante'){

            $this->modalRepresentante = true;

            foreach ($actor->representados as $representado) {

                array_push($this->representados, $representado->id);
            }

        }

        $this->crear = false;

        $this->editar = true;

    }

    public function actualizarActor(){

        $this->validate();

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
                    'porcentaje_nuda' => $this->porcentaje_nuda,
                    'porcentaje_usufructo' => $this->porcentaje_usufructo,
                    'actualizado_por' => auth()->id()
                ]);

                if($this->modalRepresentante){

                    foreach($this->representados as $representado){

                        Actor::find($representado)->update(['representado_por' => $this->actor->id]);

                    }

                }

                $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito."]);

                $this->resetear();

                $this->inscripcion->refresh();

                $this->inscripcion->load('actores.persona');

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar actor en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarActor(Actor $actor){

        $this->authorize('update', $this->inscripcion->movimientoRegistral);

        if($actor->representado_por){

            $this->dispatch('mostrarMensaje', ['error', "Debe borrar primero al representante."]);

            return;

        }

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->resetear();

            $this->inscripcion->refresh();

            $this->inscripcion->load('actores.persona');

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    /* public function repartirPartesIguales($flag = false){

        $propietarios = $flag ? $this->inscripcion->propietarios()->count() + 1 : $this->inscripcion->propietarios()->count();

        $porcentaje = 100 / $propietarios;

        foreach ($this->inscripcion->propietarios() as $propietario) {

            $propietario->update([
                'porcentaje_nuda' => $porcentaje,
                'porcentaje_usufructo' => $porcentaje
            ]);

        }

        return $porcentaje;

    } */

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

        if(((float)$this->porcentaje_propiedad + $pp_adquirientes) > $pp_transmitentes){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de propiedad no puede exceder el " . $pp_transmitentes . '%.']);

            return true;

        }

        if(((float)$this->porcentaje_nuda + $pn_adquirientes) > $pn_transmitentes){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de nuda no puede exceder el " . $pn_transmitentes . '%.']);

            return true;

        }

        if(((float)$this->porcentaje_usufructo + $pu_adquirientes) > $pu_transmitentes){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes de usufructo no puede exceder el " . $pu_transmitentes . '%.']);

            return true;

        }

    }

    public function cargarPropietarios(){

        $this->inscripcion->actores()->delete();

        foreach($this->predio->propietarios() as $propietario){

            $nuevo = $propietario->replicate();

            $nuevo->actorable_type = 'App\Models\Propiedad';
            $nuevo->actorable_id =$this->inscripcion->id;

            $nuevo->save();

        }

        $this->inscripcion->refresh();

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

        if($this->revisarProcentajes()) return;

    }

    public function finalizar(){

        $this->validate([
            'inscripcion.cp_localidad' => 'required',
            'inscripcion.cp_oficina' => 'required',
            'inscripcion.cp_tipo_predio' => 'required',
            'inscripcion.cp_registro' => 'required',
            'inscripcion.cc_region_catastral' => 'required',
            'inscripcion.cc_municipio' => 'required',
            'inscripcion.cc_zona_catastral' => 'required',
            'inscripcion.cc_sector' => 'required',
            'inscripcion.cc_manzana' => 'required',
            'inscripcion.cc_predio' => 'required',
            'inscripcion.cc_edificio' => 'required',
            'inscripcion.cc_departamento' => 'required',
            'inscripcion.acto_contenido' => 'required',
            'inscripcion.descripcion_acto' => 'required'
        ]);

        if($this->validaciones()) return;

        $this->modalContraseña = true;

    }

    public function crearPdf(){

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        $pdf = Pdf::loadView('incripciones.propiedad.acto', [
            'inscripcion' => $this->inscripcion,
            'director' => $director,
            'jefe_departamento' => $jefe_departamento,
            'distrito' => $this->inscripcion->movimientoRegistral->getRawOriginal('distrito'),
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'inscripcion.pdf'
        );

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            $this->inscripcion->actualizado_por = auth()->id();
            $this->inscripcion->save();

            $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito."]);

            $this->dispatch('imprimir_documento', ['inscripcion' => $this->inscripcion->id]);

            $this->modalContraseña = false;

            $this->crearPdf();

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->inscripcion = Propiedad::with('actores')->find($this->propiedad);

        $this->predio = $this->inscripcion->movimientoRegistral->folioReal->predio;

        if(in_array($this->inscripcion->servicio, ['D114', 'D116', 'D115', 'D113']))
            $this->actos = Constantes::ACTOS_INSCRIPCION_PROPIEDAD;

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first();

        if(!$director) abort(500, message:"Es necesario registrar al director.");

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first();

        if(!$jefe_departamento) abort(500, message:"Es necesario registrar al jefe de Departamento de Registro de Inscripciones.");

        foreach ($this->inscripcion->transmitentes() as $transmitente) {
            $this->transmitentes[] = [
                'id' => $transmitente['id'],
                'nombre' => $transmitente->persona->nombre,
                'ap_paterno' => $transmitente->persona->ap_paterno,
                'ap_materno' => $transmitente->persona->ap_materno,
                'razon_social' => $transmitente->persona->razon_social,
                'porcentaje_propiedad' => (int)$transmitente->porcentaje_propiedad,
                'porcentaje_nuda' => (int)$transmitente->porcentaje_nuda,
                'porcentaje_usufructo' => (int)$transmitente->porcentaje_usufructo,
            ];
        }

    }

    public function render()
    {

        if($this->inscripcion->movimientoRegistral->folioReal->estado != 'activo') abort(401, 'El folio real no esta activo');

        return view('livewire.inscripciones.propiedad.propiedad-inscripcion')->extends('layouts.admin');
    }
}
