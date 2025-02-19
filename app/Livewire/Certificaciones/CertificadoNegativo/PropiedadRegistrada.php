<?php

namespace App\Livewire\Certificaciones\CertificadoNegativo;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Inscripciones\ColindanciasTrait;
use App\Traits\Certificaciones\CertificadoPropiedadTrait;

class PropiedadRegistrada extends Component
{

    use CertificadoPropiedadTrait;
    use ColindanciasTrait;

    public $certificacion;

    public $flagGenerar = false;

    public $propietarios = [];

    protected function rules(){
        return [
            'propietarios' => ['required', 'array', 'min:' . $this->certificacion->numero_paginas],
            'propietarios.*' => ['required'],
            'propietarios.*.nombre' => ['required', 'string'],
            'propietarios.*.ap_paterno' => ['required', 'string'],
            'propietarios.*.ap_materno' => ['required', 'string'],
            'temporalidad' => ['nullable', 'numeric'],
         ];
    }

    protected $validationAttributes  = [
        'propietarios.*.nombre' => 'nombre',
        'propietarios.*.ap_paterno' => 'apellido paterno',
        'propietarios.*.ap_materno' => 'apellido materno',
    ];

    public function messages(): array
    {
        return [
            'propietarios.required' => 'Debe ingresar todos los campos',
            'propietarios.min' => 'Debe ingresar todos los campos'
        ];
    }

    public function buscarPropietariosEnFolio(){

        $this->validate();

        $predio = $this->certificacion->movimientoRegistral->folioReal->predio;

        $existe = null;

        foreach ($this->propietarios as $propietario) {

            $existe = $predio->propietarios()->filter(function ($user) use($propietario){

                                                return
                                                    strtolower($user->persona->nombre) == strtolower($propietario['nombre']) &&
                                                    strtolower($user->persona->ap_paterno) == strtolower($propietario['ap_paterno']) &&
                                                    strtolower($user->persona->ap_materno) == strtolower($propietario['ap_materno']);

                                            })->first();

        }

        if(!$existe) {

            $this->dispatch('mostrarMensaje', ['success', 'Las personas ingresadas no son propietarios.']);

            $this->flagGenerar = true;

        }else{

            $this->dispatch('mostrarMensaje', ['warning', 'Al menos una persona es propietario.']);

        }

    }

    public function generarCertificado(){

        if($this->certificacion->movimientoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($this->certificacion) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->certificacion)->format('d-m-Y')]);

                return;

            }

        }

        try{

            DB::transaction(function (){

                $this->guardarColindancias($this->certificacion->movimientoRegistral->folioReal->predio);

                $this->certificacion->movimientoRegistral->estado = 'elaborado';
                $this->certificacion->movimientoRegistral->save();

                $this->certificacion->actualizado_por = auth()->user()->id;
                $this->certificacion->tipo_certificado = 1;
                $this->certificacion->temporalidad = $this->temporalidad;
                $this->certificacion->observaciones_certificado = $this->observaciones;
                $this->certificacion->save();

                $this->procesarPersonas($this->personas);

            });

            $this->dispatch('imprimir_negativo_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

        } catch (\Throwable $th) {

            Log::error("Error al generar certificado negativo con propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if($this->certificacion->movimientoRegistral->folioReal)
            $this->cargarColindancias($this->certificacion->movimientoRegistral->folioReal->predio);

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-negativo.propiedad-registrada');
    }
}
