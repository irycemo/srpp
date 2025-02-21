<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\Actor;
use Livewire\Component;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\Inscripciones\Varios\VariosTrait;
use App\Http\Controllers\Varios\VariosController;

class Fideicomiso extends Component
{

    use VariosTrait;

    public $actores;
    public $actos;

    protected $listeners = ['refresh'];

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
        ];

    }

    public function refresh(){

        $this->vario->load('actores.persona');

    }

    public function borrarActor(Actor $actor){

        $this->authorize('update', $this->vario->movimientoRegistral);

        try {

            $actor->delete();

            $this->refresh();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->vario->movimientoRegistral->update(['estado' => 'captura']);

                $this->vario->save();

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->vario->estado = 'activo';
                $this->vario->actualizado_por = auth()->id();
                $this->vario->fecha_inscripcion = now()->toDateString();
                $this->vario->save();

                $this->vario->movimientoRegistral->update(['estado' => 'elaborado']);

                (new VariosController())->caratula($this->vario);

            });

            return redirect()->route('varios');

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->actos = [
            'FIDEICOMISO TRASLATIVO',
            'FIDEICOMISO REPRESENTATIVO',
        ];

        $this->actores = Constantes::ACTORES_FIDEICOMISO;

    }

    public function render()
    {
        return view('livewire.varios.fideicomiso');
    }
}
