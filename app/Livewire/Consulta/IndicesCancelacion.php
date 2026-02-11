<?php

namespace App\Livewire\Consulta;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class IndicesCancelacion extends Component
{

    public $tomo;
    public $distrito;
    public $distritos;

    public $cancelaciones;

    public function buscarCancelacion(){

        $this->validate([
            'tomo' => 'required',
        ]);

        $ruta = 'srpp/digitalizacion/cancelaciones/30' . '/' . $this->tomo;

        $cancelaciones = Storage::disk('s3')->allFiles($ruta);

        $this->cancelaciones = collect($cancelaciones)->map(function($cancelacion){
            return [
                'name' => basename($cancelacion),
                'route' => $cancelacion
            ];
        });

    }

    public function render()
    {
        return view('livewire.consulta.indices-cancelacion')->extends('layouts.admin');
    }
}
