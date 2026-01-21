<?php

namespace App\Livewire\PersonaMoral;

use App\Models\Actor;
use Livewire\Component;
use App\Models\ReformaMoral;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Reformas\ReformaController;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Traits\Inscripciones\DocumentoEntradaTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;

class Reformas extends Component
{
    use DocumentoEntradaTrait;
    use ConsultarArchivoTrait;
    use GuardarDocumentoEntradaTrait;

    public ReformaMoral $reformaMoral;

    public $actores;

    public $denominacion;
    public $capital;
    public $duracion;
    public $tipo;
    public $domicilio;

    public $objeto;
    public $nuevo_objeto;

    public $modalContraseña = false;
    public $contraseña;

    protected $listeners = ['refresh' => 'refreshActores'];

    protected function rules(){
        return [
            'reformaMoral.descripcion' => 'required',
            'denominacion' => 'required',
            'capital' => 'required|numeric|min:0',
            'duracion' => 'required|numeric|min:0',
            'tipo' => 'required',
            'domicilio' => 'required',
            'nuevo_objeto' => 'nullable',
            'tipo_documento' => 'required',
            'autoridad_cargo' => 'required',
            'autoridad_nombre' => 'required',
            'numero_documento' => 'nullable',
            'fecha_emision' => 'required',
            'procedencia' => 'nullable',
         ];
    }

    protected $validationAttributes  = [
        'denominacion' => 'denominación',
        'duracion' => 'duración',
        'nuevo_objeto' => 'nuevo objeto',
        'reformaMoral.descripcion' => 'descripción del acto'
    ];

    public function refreshActores(){

        $this->reformaMoral->movimientoRegistral->folioRealPersona->load('actores.persona');

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->reformaMoral->acto_contenido = 'ACTA DE ASAMBLEA';
                $this->reformaMoral->save();

                $this->actualizarDocumentoEntrada($this->reformaMoral->movimientoRegistral);

                $this->reformaMoral->movimientoRegistral->folioRealPersona->actualizado_por = auth()->id();
                $this->reformaMoral->movimientoRegistral->folioRealPersona->save();

                if($this->nuevo_objeto){

                    $objeto = $this->reformaMoral->movimientoRegistral->folioRealPersona->objetos()->where('estado', 'captura')->first();

                    if($objeto){

                        $objeto->update(['objeto' => $this->nuevo_objeto]);

                    }else{

                        $this->reformaMoral->movimientoRegistral->folioRealPersona->objetos()->create(['estado' => 'captura', 'objeto' => $this->nuevo_objeto]);
                    }

                }

            });

            $this->refreshActores();

            $this->dispatch('mostrarMensaje', ['success', "La información se guardo con éxito."]);


        } catch (\Throwable $th) {
            Log::error("Error al guardar acta de asamblea por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function finalizar(){

        if(!$this->reformaMoral->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe guardar el documento de entrada."]);

            return;

        }

        if(!$this->reformaMoral->movimientoRegistral?->folioRealPersona->actores()->count()){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar al menos un participante."]);

            return;
        }

        $this->modalContraseña = true;

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            $this->guardar();

            DB::transaction(function () {

                $this->reformaMoral->update(['fecha_inscripcion' => now()->toDateString()]);

                $this->reformaMoral->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->actualizarDocumentoEntrada($this->reformaMoral->movimientoRegistral);

                $this->reformaMoral->movimientoRegistral->folioRealPersona->update([
                    'denominacion' => $this->denominacion,
                    'capital' => $this->capital,
                    'duracion' => $this->duracion,
                    'tipo' => $this->tipo,
                    'domicilio' => $this->domicilio,
                ]);

                if($this->nuevo_objeto){

                    foreach ($this->reformaMoral->movimientoRegistral->folioRealPersona->objetos as $objeto) {

                        if($objeto->estado == 'activo'){

                            $objeto->update(['estado' => 'inactivo']);

                        }elseif($objeto->estado == 'captura'){

                            $objeto->update(['estado' => 'activo']);

                        }

                    }

                }

                (new ReformaController())->caratula($this->reformaMoral);

            });

            return redirect()->route('reformas');

        } catch (\Throwable $th) {
            Log::error("Error al inscribir acta de asamblea por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function eliminarActor(Actor $actor){

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "El actor se eliminó con éxito."]);

            $this->refreshActores();

        } catch (\Throwable $th) {

            Log::error("Error al eliminar socio en acta de asamblea por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        $this->consultarArchivo($this->reformaMoral->movimientoRegistral);

        $this->actores = Constantes::ACTORES_FOLIO_REAL_PERSONA_MORAL;

        $this->denominacion = $this->reformaMoral->movimientoRegistral->folioRealPersona->denominacion;
        $this->capital = $this->reformaMoral->movimientoRegistral->folioRealPersona->capital;
        $this->duracion = $this->reformaMoral->movimientoRegistral->folioRealPersona->duracion;
        $this->tipo = $this->reformaMoral->movimientoRegistral->folioRealPersona->tipo;
        $this->domicilio = $this->reformaMoral->movimientoRegistral->folioRealPersona->domicilio;
        $this->objeto = $this->reformaMoral->movimientoRegistral->folioRealPersona->objetos()->where('estado', 'activo')->first()->objeto;
        $this->nuevo_objeto = $this->reformaMoral->movimientoRegistral->folioRealPersona->objetos()->where('estado', 'captura')->first()?->objeto;

        $this->refreshActores();

        $this->cargarDocumentoEntrada($this->reformaMoral->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.persona-moral.reformas')->extends('layouts.admin');
    }
}
