<?php

namespace App\Livewire\Certificaciones\CertificadoNegativo;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Certificaciones\CertificadoPropiedadTrait;

class PropiedadNoRegistrada extends Component
{

    use CertificadoPropiedadTrait;

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

    public function generarCertificado(){

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
                $this->certificacion->temporalidad = $this->temporalidad;
                $this->certificacion->observaciones_certificado = $this->observaciones;
                $this->certificacion->save();

                $this->procesarPersonas($this->propietarios);

            });

            $this->dispatch('imprimir_negativo', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

        } catch (\Throwable $th) {

            Log::error("Error al generar certificado negativo sin propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-negativo.propiedad-no-registrada');
    }
}
