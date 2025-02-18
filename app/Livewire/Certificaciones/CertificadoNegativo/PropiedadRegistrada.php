<?php

namespace App\Livewire\Certificaciones\CertificadoNegativo;

use Livewire\Component;

class PropiedadRegistrada extends Component
{

    protected function rules(){
        return [
            'propietarios.*' => 'required',
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

    public function mount(){

        if($this->certificacion->movimientoRegistral->folioReal)
            $this->cargarColindancias($this->certificacion->movimientoRegistral->folioReal->predio);

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-negativo.propiedad-registrada');
    }
}
