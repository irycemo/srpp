<?php

namespace App\Traits\Inscripciones\Propiedad;

use App\Models\File;
use App\Models\User;
use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\FolioReal;
use App\Models\Propiedad;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;


trait PropiedadTrait{

    public $modalPropietario;
    public $modalTransmitente;
    public $modalRepresentante;
    public $modalContraseña;
    public $crear = false;
    public $editar = false;
    public $modalDocumento = false;
    public $documento;

    public $areas;
    public $divisas;
    public $vientos;
    public $tipos_asentamientos;
    public $medidas = [];

    public $propietarios = [];
    public $representados = [];
    public $tipo_propietario;
    public $porcentaje_propiedad;
    public $porcentaje_nuda;
    public $porcentaje_usufructo;
    public $tipo_persona;
    public $nombre;
    public $multiple_nombre;
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

    public $actor;

    public $inscripcion;
    public $propiedad;
    public $predio;

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
                'multiple_nombre',
            ]);

        }

    }

    public function resetear(){

        $this->reset([
            'tipo_propietario',
            'multiple_nombre',
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

        if($this->inscripcion->transmitentes()->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar los transmitentes primero."]);

            return;

        }

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

        $this->reset('representados');

        $this->modalPropietario = false;
        $this->modalTransmitente = false;
        $this->modalRepresentante = true;
        $this->crear = true;

    }

    public function guardarPropietario(){

        $this->authorize('update', $this->inscripcion->movimientoRegistral);

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
                /* Rule::requiredIf($this->tipo_persona === 'FISICA'), */
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => ['nullable', /* Rule::requiredIf($this->tipo_persona === 'MORAL') */],
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable|',
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior_propietario' => 'nullable|',
            'numero_interior_propietario' => 'nullable|',
            'colonia' => 'nullable|',
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio_propietario' => 'nullable',
        ]);

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

                    $this->dispatch('mostrarMensaje', ['error', "La persona ya es un adquiriente."]);

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
                        'multiple_nombre' => $this->multiple_nombre,
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
                        'multiple_nombre' => $this->multiple_nombre,
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

                $this->dispatch('mostrarMensaje', ['success', "El adquiriente se guardó con éxito."]);

                $this->dispatch('recargar', ['id' => $actor->id, 'description' => $actor->persona->nombre . ' ' . $actor->persona->ap_paterno . ' ' . $actor->persona->ap_materno . ' ' . $actor->persona->razon_social]);

                $this->resetear();

                $this->inscripcion->load('actores.persona');

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar adquiriente en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                $this->transmitentes[] = [
                    'id' => $actor['id'],
                    'nombre' => $actor->persona->nombre,
                    'ap_paterno' => $actor->persona->ap_paterno,
                    'ap_materno' => $actor->persona->ap_materno,
                    'razon_social' => $actor->persona->razon_social,
                    'porcentaje_propiedad' => $actor->porcentaje_propiedad,
                    'porcentaje_nuda' => $actor->porcentaje_nuda,
                    'porcentaje_usufructo' => $actor->porcentaje_usufructo,
                ];

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
                /* Rule::requiredIf($this->tipo_persona === 'FISICA'), */
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => ['nullable', /* Rule::requiredIf($this->tipo_persona === 'MORAL') */],
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable|',
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior_propietario' => 'nullable|',
            'numero_interior_propietario' => 'nullable|',
            'colonia' => 'nullable|',
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio_propietario' => 'nullable',
            'representados' => Rule::requiredIf($this->modalRepresentante === true),
        ]);

        $persona = Persona::where(function($q){
                                                $q->when($this->nombre, fn($q) => $q->where('nombre', $this->nombre))
                                                    ->when($this->ap_paterno, fn($q) => $q->where('ap_paterno', $this->ap_paterno))
                                                    ->when($this->ap_materno, fn($q) => $q->where('ap_materno', $this->ap_materno));
                                            })
                                            ->when($this->razon_social, fn($q) => $q->orWhere('razon_social', $this->razon_social))
                                            ->when($this->rfc, fn($q) => $q->orWhere('rfc', $this->rfc))
                                            ->when($this->curp, fn($q) => $q->orWhere('curp', $this->curp))
                                            ->first();

        if($persona){

            foreach ($this->inscripcion->representantes() as $propietario) {

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
                        'multiple_nombre' => $this->multiple_nombre,
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
                        'multiple_nombre' => $this->multiple_nombre,
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
                /* Rule::requiredIf($this->tipo_persona === 'FISICA'), */
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => ['nullable', /* Rule::requiredIf($this->tipo_persona === 'MORAL') */],
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable|',
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior_propietario' => 'nullable|',
            'numero_interior_propietario' => 'nullable|',
            'colonia' => 'nullable|',
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio_propietario' => 'nullable',
            'representados' => Rule::requiredIf($this->modalRepresentante === true),
        ]);

        if($this->revisarProcentajes($this->actor->id) && $this->inscripcion->movimientoRegistral->estado != 'correccion'){

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
                    'porcentaje_propiedad' => $this->porcentaje_propiedad,
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

        $tipo = $actor->tipo_actor;

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

            if($tipo == 'transmitente'){

                $this->reset('transmitentes');

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

            }

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizar(){

        $this->validate();

        if(!$this->inscripcion->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        if($this->validaciones()) return;

        $this->modalContraseña = true;

    }

    public function agregarColindancia(){

        $this->medidas[] = ['viento' => null, 'longitud' => null, 'descripcion' => null, 'id' => null];

    }

    public function borrarColindancia($index){

        $this->authorize('update',  $this->inscripcion->movimientoRegistral);

        try {

            $this->predio->colindancias()->where('id', $this->medidas[$index]['id'])->delete();

        } catch (\Throwable $th) {
            Log::error("Error al borrar colindancia en inscripcion de propipedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
        }

        unset($this->medidas[$index]);

        $this->medidas = array_values($this->medidas);

    }

    public function abrirModalDocumento(){

        $this->reset('documento');

        $this->dispatch('removeFiles');

        $this->modalDocumento = true;

    }

    public function guardarDocumento(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                $pdf = $this->documento->store('/', 'documento_entrada');

                File::create([
                    'fileable_id' => $this->inscripcion->movimientoRegistral->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en inscripción de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function generarNuevoFolioReal(){

        $folioRealNuevo = FolioReal::create([
            'antecedente' => $this->inscripcion->movimientoRegistral->folio_real,
            'estado' => 'captura',
            'folio' => (FolioReal::max('folio') ?? 0) + 1,
            'distrito_antecedente' => $this->inscripcion->movimientoRegistral->getRawOriginal('distrito'),
            'seccion_antecedente' => $this->inscripcion->movimientoRegistral->seccion,
            'tipo_documento' => $this->inscripcion->movimientoRegistral->tipo_documento,
            'numero_documento' => $this->inscripcion->movimientoRegistral->numero_documento,
            'autoridad_cargo' => $this->inscripcion->movimientoRegistral->autoridad_cargo,
            'autoridad_nombre' => $this->inscripcion->movimientoRegistral->autoridad_nombre,
            'autoridad_numero' => $this->inscripcion->movimientoRegistral->autoridad_numero,
            'fecha_emision' => $this->inscripcion->movimientoRegistral->fecha_emision,
            'fecha_inscripcion' => $this->inscripcion->movimientoRegistral->fecha_inscripcion,
            'procedencia' => $this->inscripcion->movimientoRegistral->tipo_documento,
        ]);

        $documentoEntrada = File::where('fileable_type', 'App\Models\MovimientoRegistral')
                                    ->where('fileable_id', $this->inscripcion->movimientoRegistral->id)
                                    ->where('descripcion', 'documento_entrada')
                                    ->first();

        File::create([
            'fileable_id' => $folioRealNuevo->id,
            'fileable_type' => 'App\Models\FolioReal',
            'descripcion' => 'documento_entrada',
            'url' => $documentoEntrada->url
        ]);

        $movimiento = MovimientoRegistral::create([
            'estado' => 'nuevo',
            'folio' => 1,
            'folio_real' => $folioRealNuevo->id,
            'fecha_prelacion' => $this->inscripcion->movimientoRegistral->fecha_prelacion,
            'fecha_entrega' => $this->inscripcion->movimientoRegistral->fecha_entrega,
            'fecha_pago' => $this->inscripcion->movimientoRegistral->fecha_pago,
            'tipo_servicio' => $this->inscripcion->movimientoRegistral->tipo_servicio,
            'solicitante' => $this->inscripcion->movimientoRegistral->solicitante,
            'seccion' => $this->inscripcion->movimientoRegistral->seccion,
            'año' => $this->inscripcion->movimientoRegistral->año,
            'tramite' => $this->inscripcion->movimientoRegistral->tramite,
            'usuario' => $this->inscripcion->movimientoRegistral->usuario,
            'distrito' => $this->inscripcion->movimientoRegistral->getRawOriginal('distrito'),
            'tipo_documento' => $this->inscripcion->movimientoRegistral->tipo_documento,
            'numero_documento' => $this->inscripcion->movimientoRegistral->numero_documento,
            'numero_propiedad' => $this->inscripcion->movimientoRegistral->numero_propiedad,
            'autoridad_cargo' => $this->inscripcion->movimientoRegistral->autoridad_cargo,
            'autoridad_numero' => $this->inscripcion->movimientoRegistral->autoridad_numero,
            'fecha_emision' => $this->inscripcion->movimientoRegistral->fecha_emision,
            'fecha_inscripcion' => $this->inscripcion->movimientoRegistral->fecha_inscripcion,
            'procedencia' => $this->inscripcion->movimientoRegistral->procedencia,
            'numero_oficio' => $this->inscripcion->movimientoRegistral->numero_oficio,
            'monto' => $this->inscripcion->movimientoRegistral->monto,
            'usuario_asignado' => $this->usuarioAsignado(),
            'usuario_supervisor' => $this->inscripcion->movimientoRegistral->usuario_supervisor,
            'movimiento_padre' => $this->inscripcion->movimientoRegistral->id
        ]);

        Propiedad::create([
            'movimiento_registral_id' => $movimiento->id,
            'servicio' => $this->inscripcion->servicio
        ]);

        $predioNuevo = Predio::create([
            'folio_real' => $folioRealNuevo->id,
            'status' => 'nuevo'
        ]);

        foreach($this->predio->getAttributes() as $attribute => $value){

            if(in_array($attribute, ['id', 'folio_real', 'escritura_id', 'superficie_terreno'])) continue;

            $predioNuevo->{$attribute} = $this->predio->{ $attribute};

        }

        $predioNuevo->save();

    }

    public function usuarioAsignado(){

        if(auth()->user()->hasRole(['Propiedad', 'Jefe de departamento inscripciones'])){


            if($this->inscripcion->movimientoRegistral->getRawOriginal('distrito') == 2){

                return User::where('status', 'activo')
                                ->where('ubicacion', 'Regional 4')
                                ->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Registrador Propiedad', 'Pase a folio']);
                                })
                                ->first()->id;

            }else{

                return User::where('status', 'activo')
                                ->where('ubicacion', '!=', 'Regional 4')
                                ->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Registrador Propiedad', 'Pase a folio']);
                                })
                                ->first()->id;

            }

        }else{

            return  auth()->id();

        }

    }

}
