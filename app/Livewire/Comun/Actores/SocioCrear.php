<?php

namespace App\Livewire\Comun\Actores;

use Exception;
use App\Models\Actor;
use App\Models\Persona;
use Livewire\Component;
use App\Traits\ActoresTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\PersonaService;

class SocioCrear extends Component
{

    use ActoresTrait;

    protected function rules(){

        return $this->traitRules() +[
            'curp' => [
                'nullable',
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'sub_tipo' => 'required'
        ];

    }

    public function guardar(){

        $this->validate();

        $personaService = new PersonaService();

        try {

            $persona = $personaService->buscarPersona($this->rfc, $this->curp, $this->tipo_persona, $this->nombre, $this->ap_materno, $this->ap_paterno, $this->razon_social);

            if($this->persona->getKey() && $persona){

                foreach($this->modelo->actores as $actor){

                    if($actor->persona_id == $persona->id) throw new Exception('La persona ya es un socio.');

                }

                $this->modelo->actores()->create([
                    'persona_id' => $persona->id,
                    'tipo_actor' => 'socio',
                    'tipo_socio' => $this->sub_tipo,
                    'creado_por' => auth()->id()
                ]);

            }elseif($persona){

                foreach($this->modelo->actores as $actor){

                    if($actor->persona_id == $persona->id) throw new Exception('La persona ya es un socio.');

                }

                throw new Exception('Ya existe un persona registrada con la información ingresada.');

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

            $this->dispatch('mostrarMensaje', ['success', "El socio se creó con éxito."]);

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

            if($persona && ($persona->id != $this->persona->id)){

                throw new Exception("Ya existe una persona con el RFC o CURP ingresada.");

            }else{

                $this->persona->update([
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior,
                    'numero_interior' => $this->numero_interior,
                    'colonia' => $this->colonia,
                    'cp' => $this->cp,
                    'ciudad' => $this->ciudad,
                    'entidad' => $this->entidad,
                    'nacionalidad' => $this->nacionalidad,
                    'municipio' => $this->municipio,
                    'actualizado_por' => auth()->id()
                ]);

            }

            $this->dispatch('mostrarMensaje', ['success', "La persona se actualizó con éxito."]);

            $this->dispatch('refresh');


        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar socio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        $this->tipo_actor = 'socio';

        $this->actor = Actor::make();

        $this->persona = Persona::make();

    }

    public function render()
    {
        return view('livewire.comun.actores.socio-crear');
    }
}
