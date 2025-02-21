<?php

namespace App\Livewire\PaseFolio;

use App\Models\Actor;
use App\Models\Predio;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

class Propietarios extends Component
{

    public $estados;

    public MovimientoRegistral $movimientoRegistral;
    public Predio $propiedad;

    public $propiedadOld;

    protected $listeners = ['refresh'];

    #[On('cargarPropiedad')]
    public function cargarPropiedad($id){

        $this->propiedad = Predio::with('actores.persona')->find($id);

        $this->dispatch('cargarModelo', [get_class($this->propiedad), $this->propiedad->id]);

    }

    public function refresh(){

        $this->propiedad->load('actores.persona');

    }

    public function borrarActor(Actor $actor){

        $this->authorize('update', $this->movimientoRegistral);

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->propiedad->refresh();

            $this->propiedad->load('actores.persona');

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        $this->estados = Constantes::ESTADOS;

        if($this->movimientoRegistral->folio_real)
            $this->cargarPropiedad($this->movimientoRegistral->folioReal->predio->id);

    }

    public function render()
    {
        return view('livewire.pase-folio.propietarios');
    }
}
