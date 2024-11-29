<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\Actor;
use App\Models\Deudor;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioRealPersona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\Inscripciones\Varios\VariosTrait;
use Livewire\WithFileUploads;

class PersonasMorales extends Component
{

    use VariosTrait;
    use WithFileUploads;

    public $denominacion;
    public $fecha_celebracion;
    public $fecha_inscripcion;
    public $notaria;
    public $nombre_notario;
    public $numero_hojas;
    public $numero_escritura;
    public $descripcion;
    public $observaciones;

    public $rfc;
    public $razon_social;
    public $nacionalidad;
    public $calle;
    public $ciudad;
    public $numero_exterior;
    public $numero_interior;
    public $colonia;
    public $cp;
    public $entidad;
    public $municipio;

    protected $validationAttributes  = [
        'fecha_inscripcion' => 'fecha de inscripción',
        'fecha_celebracion' => 'fecha de celebarción',
        'notaria' => 'número de notaria',
        'nombre_notario' => 'nombre del notario',
        'numero_hojas' => 'número de hojas',
        'numero_escritura' => 'número de escritura',
    ];

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
            'denominacion' => 'required',
            'fecha_celebracion' => 'required',
            'fecha_inscripcion' => 'required',
            'notaria' => 'required',
            'nombre_notario' => 'required',
            'numero_hojas' => 'required',
            'descripcion' => 'required',
            'observaciones' => 'required',
         ];
    }

    public function abrirModalCrear(){

        $this->resetearPersona();

        $this->modal = true;

        $this->crear = true;

    }

    public function resetearPersona(){

        $this->reset([
            'rfc',
            'razon_social',
            'nacionalidad',
            'calle',
            'ciudad',
            'numero_exterior',
            'numero_interior',
            'colonia',
            'cp',
            'entidad',
            'municipio',
        ]);

    }

    public function guardarPersona(){

        $this->validate([
            'rfc' => [
                'required',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => 'required',
            'nacionalidad' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'calle' => 'nullable',
            'numero_exterior' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'numero_interior' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'colonia' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'entidad' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'municipio' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
        ]);

        if($this->rfc){

            $persona = Persona::where('rfc', $this->rfc)->first();

        }elseif($this->curp){

            $persona = Persona::where('curp', $this->curp)->first();

        }else{

            if($this->tipo_persona == 'FISICA'){

                $persona = Persona::query()
                            ->where('nombre', $this->nombre)
                            ->where('ap_paterno', $this->ap_paterno)
                            ->where('ap_materno', $this->ap_materno)
                            ->first();

            }else{

                $persona = Persona::where('razon_social', $this->razon_social)->first();

            }

        }

        if(!$persona){

            $persona = Persona::create([
                'tipo' => 'MORAL',
                'rfc' => $this->rfc,
                'razon_social' => $this->razon_social,
                'nacionalidad' => $this->nacionalidad,
                'calle' => $this->calle,
                'numero_exterior' => $this->numero_exterior,
                'numero_interior' => $this->numero_interior,
                'colonia' => $this->colonia,
                'cp' => $this->cp,
                'entidad' => $this->entidad,
                'municipio' => $this->municipio,
            ]);

            return $persona->id;

        }else{

            if($this->vario->actores()->where('persona_id', $persona->id)->first() && !$this->editar){

                throw new Exception("La persona ya esta en la lista.");

            }

            $persona->update([
                'razon_social' => $this->razon_social,
                'nacionalidad' => $this->nacionalidad,
                'calle' => $this->calle,
                'numero_exterior' => $this->numero_exterior,
                'numero_interior' => $this->numero_interior,
                'colonia' => $this->colonia,
                'cp' => $this->cp,
                'entidad' => $this->entidad,
                'municipio' => $this->municipio,
            ]);

            return $persona->id;

        }

    }

    public function guardarActor(){

        try {

            $this->vario->actores()->create([
                'persona_id' => $this->guardarPersona(),
                'tipo_actor' => 'persona moral'
            ]);

            $this->dispatch('mostrarMensaje', ['success', "El participante se guardó con éxito."]);

            $this->reset(['modal', 'crear']);

            $this->vario->load('actores.persona');

        } catch (\Exception $th) {

            Log::error("Error al crear participante rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al crear participante rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarActor(Actor $actor){

        $this->authorize('update', $this->vario->movimientoRegistral);

        try {

            if(Deudor::where('actor_id', $actor->id)->first()){

                $this->vario->actores()->detach($actor->id);

            }else{

                $actor->delete();

            }

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->vario->load('actores.persona');

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor envarios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                $this->vario->movimientoRegistral->update([
                                                            'estado' => 'elaborado',
                                                            'folio' => 1
                                                        ]);

                $this->crearFolioRealPersona();

            });

            return redirect()->route('varios');

        } catch (Exception $ex) {
            Log::error("Error al finalizar inscripcion de folio real de persona moral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $ex);
            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de folio real de persona moral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function crearFolioRealPersona(){

        if(!$this->vario->movimientoRegistral->folio_real_persona){

            $folioRealPersona = FolioRealPersona::create([
                'folio' => (FolioRealPersona::max('folio') ?? 0) + 1,
                'denominacion' => $this->denominacion,
                'estado' => 'activo',
                'fecha_celebracion' => $this->fecha_celebracion,
                'fecha_inscripcion' => $this->fecha_inscripcion,
                'notaria' => $this->notaria,
                'nombre_notario' => $this->nombre_notario,
                'numero_hojas' => $this->numero_hojas,
                'numero_escritura' => $this->numero_escritura,
                'descripcion' => $this->descripcion,
                'observaciones' => $this->observaciones,
                'creado_por' => auth()->id()
            ]);

            $this->vario->movimientoRegistral->update(['folio_real_persona' => $folioRealPersona->id]);

        }else{

            $this->vario->movimientoRegistral->folioRealPersona->update([
                'denominacion' => $this->denominacion,
                'estado' => 'activo',
                'fecha_celebracion' => $this->fecha_celebracion,
                'fecha_inscripcion' => $this->fecha_inscripcion,
                'notaria' => $this->notaria,
                'nombre_notario' => $this->nombre_notario,
                'numero_hojas' => $this->numero_hojas,
                'numero_escritura' => $this->numero_escritura,
                'descripcion' => $this->descripcion,
                'observaciones' => $this->observaciones,
            ]);

        }

        foreach($this->vario->actores as $actor){

            $this->vario->movimientoRegistral->folioRealPersona->actores()->create([
                'persona_id' => $actor->persona_id,
                'tipo_actor' => $actor->tipo_actor
            ]);

            $actor->delete();

        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                $this->vario->movimientoRegistral->update(['estado' => 'captura']);

                $this->vario->save();

                if(!$this->vario->movimientoRegistral->folio_real_persona){

                    $folioRealPersona = FolioRealPersona::create([
                        'folio' => (FolioRealPersona::max('folio') ?? 0) + 1,
                        'denominacion' => $this->denominacion,
                        'estado' => 'captura',
                        'fecha_celebracion' => $this->fecha_celebracion,
                        'fecha_inscripcion' => $this->fecha_inscripcion,
                        'notaria' => $this->notaria,
                        'nombre_notario' => $this->nombre_notario,
                        'numero_hojas' => $this->numero_hojas,
                        'numero_escritura' => $this->numero_escritura,
                        'descripcion' => $this->descripcion,
                        'observaciones' => $this->observaciones,
                        'creado_por' => auth()->id()
                    ]);

                    $this->vario->movimientoRegistral->update(['folio_real_persona' => $folioRealPersona->id]);

                }else{

                    $this->vario->movimientoRegistral->folioRealPersona->update([
                        'denominacion' => $this->denominacion,
                        'fecha_celebracion' => $this->fecha_celebracion,
                        'fecha_inscripcion' => $this->fecha_inscripcion,
                        'notaria' => $this->notaria,
                        'nombre_notario' => $this->nombre_notario,
                        'numero_hojas' => $this->numero_hojas,
                        'numero_escritura' => $this->numero_escritura,
                        'descripcion' => $this->descripcion,
                        'observaciones' => $this->observaciones,
                    ]);

                }

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar inscripción de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->vario->acto_contenido = 'PERSONAS MORALES';

        if($this->vario->movimientoRegistral->folioRealPersona){

            $this->fecha_celebracion = $this->vario->movimientoRegistral->folioRealPersona->fecha_celebracion;
            $this->denominacion = $this->vario->movimientoRegistral->folioRealPersona->denominacion;
            $this->fecha_inscripcion = $this->vario->movimientoRegistral->folioRealPersona->fecha_inscripcion;
            $this->notaria = $this->vario->movimientoRegistral->folioRealPersona->notaria;
            $this->nombre_notario = $this->vario->movimientoRegistral->folioRealPersona->nombre_notario;
            $this->numero_hojas = $this->vario->movimientoRegistral->folioRealPersona->numero_hojas;
            $this->numero_escritura = $this->vario->movimientoRegistral->folioRealPersona->numero_escritura;
            $this->descripcion = $this->vario->movimientoRegistral->folioRealPersona->descripcion;
            $this->observaciones = $this->vario->movimientoRegistral->folioRealPersona->observaciones;

        }

        $this->vario->load('actores.persona');

    }

    public function render()
    {
        return view('livewire.varios.personas-morales');
    }
}
