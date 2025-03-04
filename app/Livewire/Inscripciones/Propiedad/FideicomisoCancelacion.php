<?php

namespace App\Livewire\Inscripciones\Propiedad;

use Exception;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Spatie\LivewireFilepond\WithFilePond;
use App\Traits\Inscripciones\Propiedad\PropiedadTrait;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;
use App\Traits\Inscripciones\RecuperarPropietariosTrait;

class FideicomisoCancelacion extends Component
{

    use WithFilePond;
    use PropiedadTrait;
    use RecuperarPropietariosTrait;

    public $inscripcion;

    public $movimientoFideicomiso;
    public $fideicomiso;

    public $movimiento_folio;

    protected function rules(){
        return [
            'inscripcion.descripcion_acto' => 'required',
            'movimiento_folio' => [Rule::requiredIf($this->fideicomiso == null), 'numeric'],
        ];
    }

    protected $validationAttributes  = [
        'movimiento_folio' => 'folio del fideicomiso',
    ];

    public function finalizar(){

        if(!$this->inscripcion->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        if(!$this->fideicomiso){

            $this->dispatch('mostrarMensaje', ['error', "Debe buscar el fideicomiso."]);

            return;

        }

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

                $this->fideicomiso->update(['estado' => 'inactivo']);

                $this->inscripcion->actualizado_por = auth()->id();
                $this->inscripcion->fecha_inscripcion = now()->toDateString();
                $this->inscripcion->save();

                $this->inscripcion->movimientoRegistral->update(['estado' => 'elaborado']);

                if($this->fideicomiso->tipo == 'FIDEICOMISO TRASLATIVO')
                    $this->obtenerMovimientoConPropietarios($this->movimientoFideicomiso);

                (new PropiedadController())->caratula($this->inscripcion);

            });

            return redirect()->route('propiedad');

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar cancelación de fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function buscarFideicomiso(){

        $this->validate();

        try {

            $this->movimientoFideicomiso = $this->inscripcion->movimientoRegistral->folioReal->movimientosRegistrales()
                                                                                        ->whereHas('fideicomiso', function($q){
                                                                                            $q->where('tipo', 'FIDEICOMISO TRASLATIVO');
                                                                                        })
                                                                                        ->where('folio', $this->movimiento_folio)
                                                                                        ->firstOrFail();

            $this->fideicomiso = $this->movimientoFideicomiso->fideicomiso->load('actores.persona');

        } catch (\Throwable $th) {
            $this->dispatch('mostrarMensaje', ['warning', "No se encontro fideicomiso con la información ingresada."]);
        }

    }

    public function mount(){

        if($this->inscripcion->movimientoRegistral->folioReal->fideicomisoActivo()){

            $this->fideicomiso = $this->inscripcion->movimientoRegistral->folioReal->fideicomisoActivo();

            $this->fideicomiso->load('actores.persona');

        }

        $this->inscripcion->acto_contenido = 'REVERCIÓN O CANCELACIÓN DE FIDEICOMISO';

    }

    public function render()
    {
        return view('livewire.inscripciones.propiedad.fideicomiso-cancelacion');
    }
}
