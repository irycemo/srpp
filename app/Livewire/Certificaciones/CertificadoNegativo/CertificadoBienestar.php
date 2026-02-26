<?php

namespace App\Livewire\Certificaciones\CertificadoNegativo;

use App\Models\Certificacion;
use App\Traits\Certificaciones\CertificadoPropiedadTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CertificadoBienestar extends Component
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

    public function messages(): array
    {
        return [
            'propietarios.required' => 'Debe ingresar todos los campos',
            'propietarios.min' => 'Debe ingresar todos los campos'
        ];
    }

    public function generarCertificado(){

         try{

             DB::transaction(function (){

                 $this->certificacion->movimientoRegistral->estado = 'elaborado';
                 $this->certificacion->movimientoRegistral->actualizado_por = auth()->user()->id;
                 $this->certificacion->movimientoRegistral->save();

                 $this->certificacion->actualizado_por = auth()->user()->id;
                 $this->certificacion->observaciones_certificado = $this->observaciones;

                 if(count($this->prediosEliminados)){

                     $this->certificacion->observaciones_certificado = $this->observaciones . $this->procesarPrediosEliminados();

                 }

                 $this->certificacion->save();

                 $this->procesarPersonas($this->propietarios);

             });

             $this->dispatch('imprimir_negativo', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

         } catch (\Throwable $th) {

             Log::error("Error al generar certificado vivienda bienestar por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
             $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

         }

    }

    public function mount(Certificacion $certificacion){

        $this->certificacion = $certificacion;

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-negativo.certificado-bienestar')->extends('layouts.admin');
    }

}
