<?php

namespace App\Livewire\Comun\Actores;

use Exception;
use App\Models\Actor;
use Livewire\Component;
use App\Traits\ActoresTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\PersonaService;

class Socio extends Component
{

    use ActoresTrait;

    protected function rules(){

       return $this->traitRules();

    }

    public function guardar(){

        $this->validate();

        $personaService = new PersonaService();

        try {

            $persona = $personaService->buscarPersona($this->rfc, $this->curp, $this->tipo_persona, $this->nombre, $this->ap_materno, $this->ap_paterno, $this->razon_social);

            if($persona){

                foreach($this->modelo->actores as $actor){

                    if($actor->persona_id == $persona->id) throw new Exception('La persona ya es un participante.');

                }

                $this->modelo->actores()->create([
                    'persona_id' => $persona->id,
                    'tipo_actor' => 'socio',
                    'tipo_socio' => $this->sub_tipo,
                    'creado_por' => auth()->id()
                ]);

            }else{

                DB::transaction(function () use($personaService){

                    $persona = $personaService->crearPersona([
                        'tipo' => $this->tipo_persona,
                        'nombre' => $this->nombre,
                        'multiple_nombre' => $this->multiple_nombre,
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
                        'cp' => $this->cp,
                        'entidad' => $this->entidad,
                        'ciudad' => $this->ciudad,
                        'municipio' => $this->municipio,
                    ]);

                    $this->modelo->actores()->create([
                        'persona_id' => $persona->id,
                        'tipo_actor' => 'socio',
                        'tipo_socio' => $this->sub_tipo,
                        'creado_por' => auth()->id()
                    ]);

                });

            }

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "El participante se creó con éxito."]);

            $this->dispatch('refresh');


        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al crear socio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function actualizar(){

        $this->validate();

        $personaService = new PersonaService();

        try {

            $persona = $personaService->buscarPersona($this->rfc, $this->curp, $this->tipo_persona, $this->nombre, $this->ap_materno, $this->ap_paterno, $this->razon_social);

            if($persona){

                DB::transaction(function () use($persona){

                    $persona->update([
                        'estado_civil' => $this->estado_civil,
                        'calle' => $this->calle,
                        'numero_exterior' => $this->numero_exterior,
                        'numero_interior' => $this->numero_interior,
                        'colonia' => $this->colonia,
                        'cp' => $this->cp,
                        'entidad' => $this->entidad,
                        'municipio' => $this->municipio,
                        'actualizado_por' => auth()->id()
                    ]);

                    $this->actor->delete();

                    $this->modelo->actores()->create([
                        'persona_id' => $persona->id,
                        'tipo_actor' => 'socio',
                        'tipo_socio' => $this->sub_tipo,
                        'creado_por' => auth()->id()
                    ]);

                });

            }else{

                DB::transaction(function () use($personaService){

                    $persona = $personaService->crearPersona([
                        'tipo' => $this->tipo_persona,
                        'nombre' => $this->nombre,
                        'multiple_nombre' => $this->multiple_nombre,
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
                        'cp' => $this->cp,
                        'entidad' => $this->entidad,
                        'ciudad' => $this->ciudad,
                        'municipio' => $this->municipio,
                    ]);

                    $this->actor->delete();

                    $this->modelo->actores()->create([
                        'persona_id' => $persona->id,
                        'tipo_actor' => 'socio',
                        'tipo_socio' => $this->sub_tipo,
                        'creado_por' => auth()->id()
                    ]);

                });

            }

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "El participante se actualizó con éxito."]);

            $this->dispatch('refresh');


        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar socio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if(isset($this->actor)){

            $this->tipo_actor = $this->actor->tipo_actor;

            $this->tipo_persona = $this->actor->persona->tipo;
            $this->nombre = $this->actor->persona->nombre;
            $this->multiple_nombre = $this->actor->persona->multiple_nombre;
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
            $this->ciudad = $this->actor->persona->ciudad;
            $this->municipio = $this->actor->persona->municipio;

        }else{

            $this->actor = Actor::make();

        }

    }

    public function render()
    {
        return view('livewire.comun.actores.socio');
    }
}
