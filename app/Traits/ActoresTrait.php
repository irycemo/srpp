<?php

namespace App\Traits;

use App\Models\Actor;
use App\Models\Persona;
use Illuminate\Validation\Rule;

trait ActoresTrait{

    public Actor $actor;
    public Persona $persona;
    public $personas = [];

    public $tipo_actor;
    public $sub_tipos;
    public $sub_tipo;
    public $modelo_id;
    public $modelo;

    public $porcentaje_propiedad = 0.00;
    public $porcentaje_nuda = 0.00;
    public $porcentaje_usufructo = 0.00;
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
    public $numero_exterior;
    public $numero_interior;
    public $colonia;
    public $cp;
    public $entidad;
    public $ciudad;
    public $municipio;

    public $modal = false;
    public $crear = false;
    public $editar = false;
    public $flag_agregar = false;
    public $size = 'lg';

    public $partes_iguales_flag = false;
    public $partes_iguales = false;

    public function getListeners()
    {
        return $this->listeners + [
            'cargarModelo' => 'cargarModelo'
        ];
    }

    public function traitRules(){
        return  [
            'porcentaje_propiedad' => 'nullable|numeric|min:0|max:100',
            'porcentaje_nuda' => 'nullable|numeric|min:0|max:100',
            'porcentaje_usufructo' => 'nullable|numeric|min:0|max:100',
            'tipo_persona' => 'required',
            'multiple_nombre' => 'nullable',
            'nombre' => [
                Rule::requiredIf($this->tipo_persona === 'FISICA')
            ],
            'ap_paterno' => 'nullable',
            'ap_materno' => 'nullable',
            'razon_social' => [Rule::requiredIf($this->tipo_persona === 'MORAL')],
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable',
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior' => 'nullable',
            'numero_interior' => 'nullable',
            'colonia' => 'nullable',
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio' => 'nullable',
        ];
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
                'multiple_nombre'
            ]);

        }

    }

    public function resetearTodo(){

        $this->resetearCampos();

        $this->modal = false;

        $this->flag_agregar = false;

        $this->persona = Persona::make();
    }

    public function resetearCampos(){

        $this->reset([
            'porcentaje_propiedad',
            'porcentaje_nuda',
            'porcentaje_usufructo',
            'tipo_persona',
            'nombre',
            'multiple_nombre',
            'ap_paterno',
            'ap_materno',
            'curp',
            'rfc',
            'razon_social',
            'fecha_nacimiento',
            'nacionalidad',
            'estado_civil',
            'calle',
            'numero_exterior',
            'numero_interior',
            'colonia',
            'cp',
            'entidad',
            'ciudad',
            'municipio',
            'sub_tipo',
            'personas',
        ]);

        $this->persona = Persona::make();

        $this->flag_agregar = false;

        $this->resetErrorBag();

        $this->resetValidation();

    }

    public function agregarNuevo(){

        $this->resetearCampos();

        $this->flag_agregar = true;

    }

    public function abrirModal(){

        if($this->actor->getKey()){

            $this->editar = true;

        }else{

            if(!$this->modelo){

                $this->dispatch('mostrarMensaje', ['error', "Debe guardar primero."]);

                return;

            }

            $this->crear = true;

        }

        $this->modal = true;

        $this->dispatch('cargarSeleccion');

    }

    public function cargarModelo($object){

        $this->modelo = $object[0]::find($object[1]);

    }

    public function buscarPersonas(){

        $this->flag_agregar = false;

        if(!$this->nombre && !$this->ap_materno && !$this->ap_paterno && !$this->razon_social && !$this->rfc && !$this->curp){

            $this->dispatch('mostrarMensaje', ['warning', "Debe ingresar información."]);

            return;

        }

        $this->validate([
            'nombre' => Rule::requiredIf($this->ap_materno || $this->ap_paterno),
            'ap_materno' => 'nullable',
            'ap_paterno' => 'nullable',
            'curp' => [
                'nullable',
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
        ]);

        $this->personas = Persona::when($this->rfc && $this->rfc != '', function($q){
                                            $q->where('rfc', $this->rfc);
                                        })
                                        ->when($this->curp && $this->curp != '', function($q){
                                            $q->where('curp', $this->curp);
                                        })
                                        ->when($this->nombre && $this->nombre != '', function($q){
                                            $q->where('nombre', $this->nombre);
                                        })
                                        ->when($this->ap_materno && $this->ap_materno != '', function($q){
                                            $q->where('ap_materno', $this->ap_materno);
                                        })
                                        ->when($this->ap_paterno && $this->ap_paterno != '', function($q){
                                            $q->where('ap_paterno', $this->ap_paterno);
                                        })
                                        ->when($this->razon_social && $this->razon_social != '', function($q){
                                            $q->where('razon_social', 'like' . '%' . $this->razon_social . '%');
                                        })
                                        ->get();

    }

    public function seleccionar($id){

        $this->persona = collect($this->personas)->where('id', $id)->first();

        $this->flag_agregar = true;

        $this->editar = true;

        $this->tipo_persona = $this->persona->tipo;
        $this->nombre = $this->persona->nombre;
        $this->multiple_nombre = $this->persona->multiple_nombre;
        $this->ap_paterno = $this->persona->ap_paterno;
        $this->ap_materno = $this->persona->ap_materno;
        $this->curp = $this->persona->curp;
        $this->rfc = $this->persona->rfc;
        $this->razon_social = $this->persona->razon_social;
        $this->fecha_nacimiento = $this->persona->fecha_nacimiento;
        $this->nacionalidad = $this->persona->nacionalidad;
        $this->estado_civil = $this->persona->estado_civil;
        $this->calle = $this->persona->calle;
        $this->numero_exterior = $this->persona->numero_exterior;
        $this->numero_interior = $this->persona->numero_interior;
        $this->colonia = $this->persona->colonia;
        $this->cp = $this->persona->cp;
        $this->entidad = $this->persona->entidad;
        $this->ciudad = $this->persona->ciudad;
        $this->municipio = $this->persona->municipio;

        $this->reset('personas');

    }

}
