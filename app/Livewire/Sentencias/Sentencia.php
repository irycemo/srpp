<?php

namespace App\Livewire\Sentencias;

use App\Models\File;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;
use App\Traits\Inscripciones\Sentencias\SentenciaTrait;

class Sentencia extends Component
{

    use SentenciaTrait;
    use ConsultarArchivoTrait;

    protected function rules(){
        return [
            'sentencia.acto_contenido' => 'required',
            'sentencia.descripcion' => 'required'
        ];

    }

    #[On('cambiarActo')]
    public function cambiarActo($acto){

        $this->sentencia->acto_contenido = $acto;

    }

    public function mount(){

        $this->consultarArchivo($this->sentencia->movimientoRegistral);

        $this->actos = Constantes::ACTOS_INSCRIPCION_SENTENCIAS;

    }

    public function render()
    {
        return view('livewire.sentencias.sentencia')->extends('layouts.admin');
    }

}
