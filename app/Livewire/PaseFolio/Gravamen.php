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
    public $gravamen_seleccionado;
    public $selected_id;

    public $modalBorrar = false;
    public $modalCancelacion = false;

    public $folio_cancelacion;
    public $tomo_cancelacion;

    public Predio $propiedad;

    public $label_numero_documento = "Número de documento";

    #[On('cargarPropiedad')]
    public function cargarPropiedad($id){

        $this->propiedad = Predio::find($id);

    }

    public function agregarGravamen(){

        if(!$this->movimientoRegistral->folioReal){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los datos de propiedad."]);

            return;

        }

        if($this->propiedad->propietarios()->count() == 0){

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

    public function abrirModalCancelar(GravamenModelo $gravamen){

        $this->modalCancelacion = true;

        $this->gravamen_seleccionado = $gravamen;

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

    public function cancelar(){

        try {

            DB::transaction(function () {

                $cancelacionMovimiento = $this->movimientoRegistral->replicate();

                $cancelacionMovimiento->folio = $this->movimientoRegistral->folioReal->ultimoFolio() + 1;
                $cancelacionMovimiento->estado = 'elaborado';
                $cancelacionMovimiento->save();

                $cancelacionMovimiento->cancelacion()->create([
                    'estado' => 'activo',
                    'acto_contenido' => 'Cancelación en captura',
                    'gravamen' => $this->gravamen_seleccionado->movimientoRegistral->id,
                    'observaciones' => 'Cancelación en captura de asignación de folio real en base al Tomo de cancelación: ' . $this->tomo_cancelacion . ' Folio de cancelación: ' . $this->folio_cancelacion . '.',
                    'fecha_inscripcion' => now()->toDateString(),
                    'actualizado_por' => auth()->id(),
                ]);

                $this->gravamen_seleccionado->update([
                    'estado' => 'cancelado',
                    'actualizado_por' => auth()->id(),
                    'observaciones' => $this->gravamen_seleccionado->observaciones .  ' Cancelado mediante movimiento registral: ' . $cancelacionMovimiento->folioReal->folio . '-' . $cancelacionMovimiento->folio,
                ]);

                $this->gravamen_seleccionado->movimientoRegistral->update([
                    'movimiento_padre' => $cancelacionMovimiento->id,
                ]);

            });

            $this->reset(['modalCancelacion', 'tomo_cancelacion', 'folio_cancelacion']);

            $this->cargarGravamenes();

            $this->dispatch('mostrarMensaje', ['success', "El gravamen se canceló con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al cancelar gravamen en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

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
