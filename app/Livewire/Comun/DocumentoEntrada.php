<?php

namespace App\Livewire\Comun;

use App\Models\File;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentoEntrada extends Component
{

    public $movimientoRegistral;
    public $folioReal;

    public $modal = false;

    public $documento;
    public $modelo;
    public $id;

    public function abrirModal(){

        $this->reset('documento');

        $this->modal = true;

    }

    public function guardarDocumento(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                $pdf = $this->documento->store('/', 'documento_entrada');

                File::create([
                    'fileable_id' => $this->id,
                    'fileable_type' => $this->modelo,
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modal = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if($this->movimientoRegistral){

            $this->modelo = 'App\Models\MovimientoRegistral';

            $this->id = $this->movimientoRegistral->id;

        }else{

            $this->modelo = 'App\Models\FolioReal';

            $this->id = $this->folioReal->id;

        }

    }

    public function render()
    {
        return view('livewire.comun.documento-entrada');
    }
}
