<?php

namespace App\Livewire\Inscripciones\Propiedad;

use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Spatie\LivewireFilepond\WithFilePond;
use App\Traits\Inscripciones\Propiedad\PropiedadTrait;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;

class FideicomisoCancelacion extends Component
{

    use WithFilePond;
    use PropiedadTrait;

    public $inscripcion;

    public $fideicomiso;

    protected function rules(){
        return [
            'inscripcion.descripcion_acto' => 'required',
        ];
    }

    public function finalizar(){

        $this->validate();

        $this->modalContraseña = true;

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                if($this->inscripcion->movimientoRegistral->estado != 'correccion')
                    $this->inscripcion->movimientoRegistral->estado = 'captura';

                $this->inscripcion->movimientoRegistral->actualizado_por = auth()->id();
                $this->inscripcion->movimientoRegistral->save();

                $this->inscripcion->save();

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar cancelación de fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                $this->inscripcion->actualizado_por = auth()->id();
                $this->inscripcion->fecha_inscripcion = now()->toDateString();
                $this->inscripcion->save();

                $this->inscripcion->movimientoRegistral->update(['estado' => 'elaborado']);

                (new PropiedadController())->caratula($this->inscripcion);

            });

            return redirect()->route('propiedad.fideicomisos_index');

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar cancelación de fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->fideicomiso = $this->inscripcion->movimientoRegistral->folioReal->fideicomisoActivo();

        $this->fideicomiso->load('actores.persona');

        $this->inscripcion->acto_contenido = 'REVERCIÓN O CANCELACIÓN DE FIDEICOMISO';

    }

    public function render()
    {
        return view('livewire.inscripciones.propiedad.fideicomiso-cancelacion');
    }
}
