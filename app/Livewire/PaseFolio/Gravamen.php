<?php

namespace App\Livewire\PaseFolio;

use App\Models\Predio;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Models\Gravamen as GravamenModelo;

class Gravamen extends Component
{

    public MovimientoRegistral $movimientoRegistral;

    public $gravamenes;
    public $selected_id;

    public $modalBorrar = false;

    public Predio $propiedad;

    public $label_numero_documento = "Número de documento";

    #[On('cargarPropiedad')]
    public function cargarPropiedad($id){

        $this->propiedad = Predio::with('propietarios.persona')->find($id);

    }

    public function agregarGravamen(){

        if(!$this->movimientoRegistral->folioReal){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los datos de propiedad."]);

            return;

        }

        if($this->propiedad->propietarios->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los propietarios."]);

            return;

        }

        $this->dispatch("openModal", 'pase-folio.modal-gravamen', ['movimientoRegistral' => $this->movimientoRegistral->id, 'crear' => false]);

    }

    #[On('cargarGravamenes')]
    public function cargarGravamenes(){

        $this->gravamenes = GravamenModelo::withWhereHas('movimientoRegistral', function($q){
                                                            $q->where('folio_real', $this->movimientoRegistral->folio_real);
                                                        })
                                                        ->get();

    }

    public function abrirModalBorrar($id){

        $this->modalBorrar = true;

        $this->selected_id = $id;

    }

    public function borrar(){

        $this->authorize('update', $this->movimientoRegistral);

        try{

            DB::transaction(function () {

                $gravamen = GravamenModelo::find($this->selected_id);

                $gravamen->movimientoRegistral->delete();

                $this->reordenar($gravamen->movimientoRegistral->folio);

                $this->dispatch('mostrarMensaje', ['success', "El gravamen se eliminó con éxito."]);

                $this->cargarGravamenes();

                $this->modalBorrar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al borrar gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function reordenar($folio){

        $movimientos = MovimientoRegistral::where('folio', '>', $folio)->get();

        MovimientoRegistral::disableAuditing();

        foreach ($movimientos as $movimiento) {
            $movimiento->decrement('folio');
        }

        MovimientoRegistral::enableAuditing();

    }

    public function mount(){

        if($this->movimientoRegistral->folio_real){

            $this->cargarPropiedad($this->movimientoRegistral->folioReal->predio->id);

            $this->cargarGravamenes();

        }

    }

    public function render()
    {
        return view('livewire.pase-folio.gravamen');
    }
}
