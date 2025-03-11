<?php

namespace App\Livewire\Admin;

use App\Models\Persona;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Traits\ComponentesTrait;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;

class Personas extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Persona $modelo_editar;

    public $personas = [];

    public $nombre;
    public $ap_paterno;
    public $ap_materno;
    public $rfc;
    public $curp;
    public $razon_social;
    public $tipo_persona;
    public $multiple_nombre;
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

    protected function rules(){
        return [
            'modelo_editar.name' => 'required',
            'modelo_editar.area' => 'required'
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.name' => 'nombre',
        'area' => 'área',
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Persona::make();
    }

    public function abrirModalEditar(Persona $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function actualizar(){

        $this->validate();

        try{

            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La persona se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar persona por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function buscar(){

        if(!$this->nombre && !$this->ap_materno && !$this->ap_paterno && !$this->razon_social && !$this->rfc && !$this->curp){

            $this->dispatch('mostrarMensaje', ['warning', "Debe ingresar información."]);

            return;

        }

        $this->validate([
            'nombre' => Rule::requiredIf($this->ap_materno || $this->ap_paterno),
            'ap_materno' => 'nullable',
            'ap_paterno' => Rule::requiredIf($this->nombre || $this->ap_materno),
            'curp' => [
                'nullable',
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
        ]);

        $this->personas = Persona::with('creadoPor', 'actualizadoPor')
                                    ->when($this->rfc && $this->rfc != '', function($q){
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
                                        $q->where('razon_social', $this->razon_social);
                                    })
                                    ->get();

    }

    public function mount(){

        $this->crearModeloVacio();

    }

    public function render()
    {
        return view('livewire.admin.personas')->extends('layouts.admin');
    }
}
