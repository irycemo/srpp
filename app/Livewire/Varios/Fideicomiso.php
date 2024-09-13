<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\Actor;
use App\Models\Persona;
use Livewire\Component;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\Inscripciones\Varios\VariosTrait;

class Fideicomiso extends Component
{

    use VariosTrait;

    public $tipo;
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
    public $numero_exterior;
    public $numero_interior;
    public $colonia;
    public $ciudad;
    public $cp;
    public $entidad;
    public $municipio;

    public $estados;
    public $estados_civiles;
    public $modalBorrar;
    public $fideicomitente = false;
    public $fideicomisario = false;
    public $fiduciaria = false;

    public function resetear(){

        $this->reset([
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
            'numero_exterior',
            'numero_interior',
            'colonia',
            'cp',
            'entidad',
            'municipio',
            'modal',
            'fideicomitente',
            'fideicomisario',
            'fiduciaria',
        ]);
    }

    public function agregarFideicomitente(){

        $this->resetear();

        $this->modal = true;
        $this->crear = true;
        $this->fideicomitente = true;

    }

    public function agregarFideicomisario(){

        $this->resetear();

        $this->modal = true;
        $this->crear = true;
        $this->fideicomisario = true;

    }

    public function agregarFiduciaria(){

        if($this->vario->actores()->where('tipo', 'fiduciaria')->count()){

            $this->dispatch('mostrarMensaje', ['error', "Solo puede ingresar una fiduciaria."]);

            return;

        }

        $this->resetear();

        $this->modal = true;
        $this->crear = true;
        $this->fiduciaria = true;

    }

    public function guardarActor($tipo){

        $this->validate();

        try {

            DB::transaction(function () use($tipo){

                $persona = Persona::where(function($q){
                                        $q->when($this->nombre, fn($q) => $q->where('nombre', $this->nombre))
                                            ->when($this->ap_paterno, fn($q) => $q->where('ap_paterno', $this->ap_paterno))
                                            ->when($this->ap_materno, fn($q) => $q->where('ap_materno', $this->ap_materno));
                                    })
                                    ->when($this->razon_social, fn($q) => $q->orWhere('razon_social', $this->razon_social))
                                    ->when($this->rfc, fn($q) => $q->orWhere('rfc', $this->rfc))
                                    ->when($this->curp, fn($q) => $q->orWhere('curp', $this->curp))
                                    ->first();

                if($persona){

                    $actor = $this->aviso->actores()->where('persona_id', $persona->id)->first();

                    if($actor){

                        $this->dispatch('mostrarMensaje', ['error', "La persona ya es un " . $actor->tipo . '.']);

                        return;

                    }

                }else{

                    $persona = Persona::create([
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
                        'numero_exterior' => $this->numero_exterior,
                        'numero_interior' => $this->numero_interior,
                        'colonia' => $this->colonia,
                        'ciudad' => $this->ciudad,
                        'cp' => $this->cp,
                        'entidad' => $this->entidad,
                        'municipio' => $this->municipio,
                        'creado_por' => auth()->id()
                    ]);

                }

                $this->vario->actores()->create([
                    'persona_id' => $persona->id,
                    'tipo' => $tipo,
                    'creado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El " . $tipo . " se guardó con éxito."]);

                $this->resetear();

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar " . $tipo . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function editarActor(Actor $actor){

        $this->resetear();

        $this->actor = $actor;

        $this->tipo = $this->actor->tipo;
        $this->tipo_persona = $this->actor->persona->tipo;
        $this->nombre = $this->actor->persona->nombre;
        $this->ap_paterno = $this->actor->persona->ap_paterno;
        $this->ap_materno = $this->actor->persona->ap_materno;
        $this->curp = $this->actor->persona->curp;
        $this->rfc = $this->actor->persona->rfc;
        $this->razon_social = $this->actor->persona->razon_social;
        $this->fecha_nacimiento = $this->actor->persona->fecha_nacimiento;
        $this->nacionalidad = $this->actor->persona->nacionalidad;
        $this->estado_civil = $this->actor->persona->estado_civil;
        $this->calle = $this->actor->persona->calle;
        $this->numero_exterior = $this->actor->persona->numero_exterior;
        $this->numero_interior = $this->actor->persona->numero_interior;
        $this->colonia = $this->actor->persona->colonia;
        $this->cp = $this->actor->persona->cp;
        $this->entidad = $this->actor->persona->entidad;
        $this->municipio = $this->actor->persona->municipio;

        if($this->actor->tipo === 'fideicomitente')
            $this->modal = true;

        $this->crear = false;

        $this->editar = true;

    }

    public function actualizarActor(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->actor->persona->update([
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'nacionalidad' => $this->nacionalidad,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior,
                    'numero_interior' => $this->numero_interior,
                    'colonia' => $this->colonia,
                    'ciudad' => $this->ciudad,
                    'cp' => $this->cp,
                    'entidad' => $this->entidad,
                    'municipio' => $this->municipio,
                    'actualizado_por' => auth()->id()
                ]);

                $this->actor->update([
                    'actualizado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito."]);

                $this->resetear();

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar actor de fidicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarActor($id){

        try {

            Actor::destroy($id);

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->resetear();

        } catch (\Throwable $th) {

            Log::error("Error al borrar en actor de fideicomiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                $this->vario->movimientoRegistral->update(['estado' => 'captura']);

                $this->vario->save();

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar inscripción de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

            });

            return redirect()->route('varios');

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->estados = Constantes::ESTADOS;

        $this->estados_civiles = Constantes::ESTADO_CIVIL;

    }

    public function render()
    {
        return view('livewire.varios.fideicomiso');
    }
}
