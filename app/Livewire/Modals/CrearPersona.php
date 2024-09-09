<?php

namespace App\Livewire\Modals;

use App\Livewire\PaseFolio\ModalGravamen;
use App\Models\Persona;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LivewireUI\Modal\ModalComponent;

class CrearPersona extends ModalComponent
{

    public Persona $persona;

    public $id;

    public $crear = false;
    public $editar = false;
    public $title;

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

    protected function rules(){
        return [
            'persona.tipo' => 'required',
            'persona.nombre' => [
                'nullable',
                Rule::requiredIf($this->persona->tipo === 'FISICA'),
                utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/')
            ],
            'persona.ap_paterno' => ['nullable',Rule::requiredIf($this->tipo_persona === 'FISICA'), utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/')],
            'persona.ap_materno' => ['nullable',Rule::requiredIf($this->tipo_persona === 'FISICA'), utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/')],
            'persona.curp' => [
                'nullable',
                /* 'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i' */
            ],
            'persona.rfc' => [
                'nullable',
                Rule::requiredIf($this->tipo_persona === 'FISICA'),
                /* 'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/' */
            ],
            'persona.razon_social' => Rule::requiredIf($this->persona->tipo === 'MORAL'),
            'persona.fecha_nacimiento' => 'nullable|date',
            'persona.nacionalidad' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'persona.estado_civil' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'persona.calle' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'persona.numero_exterior' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'persona.numero_interior' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'persona.colonia' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'persona.cp' => 'nullable|numeric',
            'persona.entidad' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'persona.municipio' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
        ];
    }

    public function updatedPersonaTipo(){

        if($this->persona->tipo == 'FISICA'){

            $this->persona->razon_social = null;

        }else{

            $this->persona->nombre = null;
            $this->persona->ap_paterno = null;
            $this->persona->ap_materno = null;
            $this->persona->curp = null;
            $this->persona->fecha_nacimiento = null;
            $this->persona->estado_civil = null;

        }

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $persona = Persona::query()
                                    ->where('tipo', $this->persona->tipo)
                                    ->when($this->persona->nombre, fn($q) => $q->where('nombre', $this->persona->nombre))
                                    ->when($this->persona->ap_paterno, fn($q) => $q->where('ap_paterno', $this->persona->ap_paterno))
                                    ->when($this->persona->ap_materno, fn($q) => $q->where('ap_materno', $this->persona->ap_materno))
                                    ->when($this->persona->razon_social, fn($q) => $q->where('razon_social', $this->persona->razon_social))
                                    ->first();

                if($persona){

                    $this->persona = $persona;

                    $this->persona->actualizado_por = auth()->id();

                    $this->persona->save();

                }else{

                    $this->persona->creado_por = auth()->id();

                    $this->persona->save();

                }

                $this->dispatch('mostrarMensaje', ['success', "El " . strtolower($this->title) . " se guardó con éxito."]);

                if($this->title == 'Acreedor'){

                    $this->dispatch('agregarAcreedor', persona: $this->persona->id)->to(ModalGravamen::class);

                }else{

                    $this->dispatch('agregarDeudor', persona: $this->persona->id)->to(ModalGravamen::class);

                }

                $this->persona = persona::make();

                $this->closeModal();

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar " . strtolower($this->title) . " en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function actualizar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->persona->actualizado_por = auth()->id();

                $this->persona->save();

                $this->dispatch('mostrarMensaje', ['success', "El " . strtolower($this->title) . " se guardó con éxito."]);

                $this->dispatch('actualizarDeudores')->to(ModalGravamen::class);

                $this->persona = persona::make();

                $this->closeModal();

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar " . strtolower($this->title) . " en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if($this->id){

            $this->persona = Persona::find($this->id);

        }else{

            $this->persona = persona::make();

        }

    }

    public function render()
    {
        return view('livewire.modals.crear-persona');
    }
}
