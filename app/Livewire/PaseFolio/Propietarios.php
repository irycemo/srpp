<?php

namespace App\Livewire\PaseFolio;

use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Propietario;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

class Propietarios extends Component
{

    public $modal = false;
    public $crear = false;
    public $editar = false;

    public $propietarios = [];
    public $tipo_propietario;
    public $porcentaje;
    public $tipo_persona;
    public $nombre;
    public $ap_paterno;
    public $ap_materno;
    public $curp;
    public $rfc;
    public $razon_social;
    public $fecha_nacimiento;
    public $nacionalidad;
    public $estado_civil;
    public $calle;
    public $numero_exterior_propietario;
    public $numero_interior_propietario;
    public $colonia;
    public $cp;
    public $entidad;
    public $municipio_propietario;

    public $propietario;

    public $tipos_propietarios;

    public $estados;

    public MovimientoRegistral $movimientoRegistral;
    public Predio $propiedad;

    public function resetear(){

        $this->reset([
            'tipo_propietario',
            'porcentaje',
            'tipo_persona',
            'nombre',
            'ap_paterno',
            'ap_materno',
            'curp',
            'rfc',
            'razon_social',
            'fecha_nacimiento',
            'nacionalidad',
            'estado_civil',
            'calle',
            'numero_exterior_propietario',
            'numero_interior_propietario',
            'colonia',
            'cp',
            'entidad',
            'municipio_propietario',
            'modal'
        ]);
    }

    #[On('cargarPropiedad')]
    public function cargarPropiedad($id){

        $this->propiedad = Predio::with('propietarios.persona')->find($id);

    }

    public function agregarPropietario(){

        if(!$this->movimientoRegistral->inscripcionPropiedad->cp_oficina){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los datos del predio."]);

            return;

        }

        $this->modal = true;
        $this->crear = true;

    }

    public function guardarPropietario(){

        $this->validate([
            'tipo_propietario' => 'required',
            'porcentaje' => 'required',
            'tipo_persona' => 'required',
            'nombre' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'curp' => 'required',
            'rfc' => 'required',
            'razon_social' => 'required',
            'fecha_nacimiento' => 'required',
            'nacionalidad' => 'required',
            'estado_civil' => 'required',
            'calle' => 'required',
            'numero_exterior_propietario' => 'required',
            'numero_interior_propietario' => 'required',
            'colonia' => 'required',
            'cp' => 'required',
            'entidad' => 'required',
            'municipio_propietario' => 'required',
        ]);

        if($this->revisarProcentajes() + $this->porcentaje > 100){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede exceder el 100%."]);

            return;

        }

        try {

            DB::transaction(function () {

                $persona = Persona::Create([
                    'tipo' => $this->tipo_persona,
                    'nombre' => $this->nombre,
                    'ap_paterno' => $this->ap_paterno,
                    'ap_materno' => $this->ap_materno,
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'razon_social' => $this->razon_social,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'nacionalidad' => $this->nacionalidad,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior_propietario,
                    'numero_interior' => $this->numero_interior_propietario,
                    'colonia' => $this->colonia,
                    'cp' => $this->cp,
                    'entidad' => $this->entidad,
                    'municipio' => $this->municipio_propietario,
                    'creado_por' => auth()->id()
                ]);

                $this->propiedad->propietarios()->create([
                    'persona_id' => $persona->id,
                    'tipo' => $this->tipo_propietario,
                    'porcentaje' => $this->porcentaje,
                    'creado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El propietario se guardó con éxito."]);

                $this->resetear();

                $this->propiedad->refresh();

                $this->propiedad->load('propietarios.persona');
            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar propietario en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function editarPropietario(Propietario $propietario){

        $this->propietario = $propietario;

        $this->tipo_propietario = $propietario->tipo;
        $this->porcentaje = $propietario->porcentaje;
        $this->tipo_persona = $propietario->persona->tipo;
        $this->nombre = $propietario->persona->nombre;
        $this->ap_paterno = $propietario->persona->ap_paterno;
        $this->ap_materno = $propietario->persona->ap_materno;
        $this->curp = $propietario->persona->curp;
        $this->rfc = $propietario->persona->rfc;
        $this->razon_social = $propietario->persona->razon_social;
        $this->fecha_nacimiento = $propietario->persona->fecha_nacimiento;
        $this->nacionalidad = $propietario->persona->nacionalidad;
        $this->estado_civil = $propietario->persona->estado_civil;
        $this->calle = $propietario->persona->calle;
        $this->numero_exterior_propietario = $propietario->persona->numero_exterior;
        $this->numero_interior_propietario = $propietario->persona->numero_interior;
        $this->colonia = $propietario->persona->colonia;
        $this->cp = $propietario->persona->cp;
        $this->entidad = $propietario->persona->entidad;
        $this->municipio_propietario = $propietario->persona->municipio;

        $this->modal = true;

        $this->editar = true;

    }

    public function actualizarPropietario(){

        $this->validate([
            'tipo_propietario' => 'required',
            'porcentaje' => 'required',
            'tipo_persona' => 'required',
            'nombre' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'curp' => 'required',
            'rfc' => 'required',
            'razon_social' => 'required',
            'fecha_nacimiento' => 'required',
            'nacionalidad' => 'required',
            'estado_civil' => 'required',
            'calle' => 'required',
            'numero_exterior_propietario' => 'required',
            'numero_interior_propietario' => 'required',
            'colonia' => 'required',
            'cp' => 'required',
            'entidad' => 'required',
            'municipio_propietario' => 'required',
        ]);

        if($this->revisarProcentajes() + $this->porcentaje > 100){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede exceder el 100%."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->propietario->persona->update([
                    'tipo' => $this->tipo_persona,
                    'nombre' => $this->nombre,
                    'ap_paterno' => $this->ap_paterno,
                    'ap_materno' => $this->ap_materno,
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'razon_social' => $this->razon_social,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'nacionalidad' => $this->nacionalidad,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior_propietario,
                    'numero_interior' => $this->numero_interior_propietario,
                    'colonia' => $this->colonia,
                    'cp' => $this->cp,
                    'entidad' => $this->entidad,
                    'municipio' => $this->municipio_propietario,
                    'creado_por' => auth()->id()
                ]);

                $this->propietario->update([
                    'tipo' => $this->tipo_propietario,
                    'porcentaje' => $this->porcentaje,
                    'actualizado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El propietario se actualizó con éxito."]);

                $this->resetear();

                $this->propiedad->refresh();

                $this->propiedad->load('propietarios.persona');
            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar propietario en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarPropietario(Propietario $propietario){

        try {

            $propietario->delete();

            $this->dispatch('mostrarMensaje', ['success', "El propietario se eliminó con éxito."]);

            $this->resetear();

            $this->propiedad->refresh();

            $this->propiedad->load('propietarios.persona');

        } catch (\Throwable $th) {

            Log::error("Error al borrar propietario en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function revisarProcentajes(){

        $porcentaje = 0;

        foreach($this->propiedad->propietarios as $propietario){

            $porcentaje = $porcentaje + $propietario->porcentaje;

        }

        return $porcentaje;

    }

    public function mount(){

        $this->tipos_propietarios = Constantes::TIPO_PROPIETARIO;

        $this->estados = Constantes::ESTADOS;

        if($this->movimientoRegistral->folio_real)
            $this->cargarPropiedad($this->movimientoRegistral->folioReal->predio->id);

    }

    public function render()
    {
        return view('livewire.pase-folio.propietarios');
    }
}
