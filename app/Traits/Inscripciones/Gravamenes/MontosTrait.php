<?php

namespace App\Traits\Inscripciones\Gravamenes;

trait MontosTrait{

    public array $montos;

    public function agregarMonto(){

        $this->montos[] = ['monto' => null, 'divisa' => null];

    }

    public function borrarMonto(int $index){

        unset($this->montos[$index]);

        $this->montos = array_values($this->montos);

    }

    public function cargarMontos(array | null $montos){

        if(is_null($montos)) return;

        $this->montos = [];

        foreach ($montos as $monto) {

            $this->montos[] = [
                'monto' => $monto['monto'],
                'divisa' => $monto['divisa']
            ];

        }

    }

}
