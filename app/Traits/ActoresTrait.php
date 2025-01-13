<?php

namespace App\Traits;

use App\Models\Actor;
use Illuminate\Validation\Rule;

trait ActoresTrait{

    public Actor $actor;

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
            'numero_exterior' => 'nullable',
            'numero_interior' => 'nullable',
            'colonia' => 'nullable',
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio' => 'nullable',
        ];
    }

    public function resetearTodo(){

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
            'modal',
            'editar',
            'crear',
            'sub_tipo'
        ]);
    }

    public function abrirModal(){

        if(!$this->modelo){

            $this->dispatch('mostrarMensaje', ['error', "Debe cargar primero el modelo."]);

            return;

        }

        if($this->actor->getKey()){

            $this->editar = true;

        }else{

            $this->crear = true;

        }

        $this->modal = true;

    }

    public function cargarModelo($object){

        $this->modelo = $object[0]::find($object[1]);

    }

}
