<?php

namespace App\Livewire\Gravamenes;

use Exception;
use App\Models\Deudor;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Acreedor;
use App\Models\Gravamen;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class GravamenInscripcion extends Component
{

    public $distritos;
    public $actos;
    public $divisas;
    public $tipo_deudores;
    public $tipo_deudor;

    public Gravamen $gravamen;

    public $propiedad;

    public $crear = false;
    public $editar = false;
    public $modal = false;
    public $modalContraseña = false;
    public $title;

    public $contraseña;

    public $antecente_tomo;
    public $antecente_registro;
    public $antecente_distrito;

    public $propietario;
    public $propietarios_alicuotas = [];
    public $propietarios_garantes_coopropiedad = [];

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
    public $ciudad;
    public $numero_exterior;
    public $numero_interior;
    public $colonia;
    public $cp;
    public $entidad;
    public $municipio;

    protected function rules(){
        return [
            'antecente_tomo' => 'required',
            'antecente_registro' => 'required',
            'antecente_distrito' => 'required',
            'gravamen.tipo' => 'required',
            'gravamen.acto_contenido' => 'required',
            'gravamen.valor_gravamen' => 'required|numeric',
            'gravamen.divisa' => 'required',
            'gravamen.fecha_inscripcion' => 'required',
            'gravamen.estado' => 'required',
            'gravamen.comentario' => utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
         ];
    }

    public function updated($property, $value){

        if($value === ''){

            $this->$property = null;

        }

    }

    public function updatedTipoPersona(){

        if($this->tipo_persona == 'FISICA'){

            $this->razon_social = null;

        }else{

            $this->nombre = null;
            $this->ap_paterno = null;
            $this->ap_materno = null;
            $this->curp = null;
            $this->fecha_nacimiento = null;
            $this->estado_civil = null;

        }

    }

    public function updatedPropietario(){

        if($this->propietario === "") return;

        $this->guardarDeudor(null, $this->propietario);

    }

    public function updatedPropietariosAlicuotas(){

        foreach($this->propietarios_alicuotas as $propietario){

            if(!$this->gravamen->deudores()->where('actor_id', (int)$propietario)->where('tipo', 'P-PARTE ALICUOTA')->first()){

                $this->guardarDeudor(null, $propietario);

            }

        }

    }

    public function updatedPropietariosGarantesCoopropiedad(){

        foreach($this->propietarios_garantes_coopropiedad as $propietario){

            if(!$this->gravamen->deudores()->where('actor_id', (int)$propietario)->where('tipo', 'G-GARANTES EN COOPROPIEDAD')->first()){

                $this->guardarDeudor(null, $propietario);

            }

        }

    }

    public function resetearPersona(){

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
            'ciudad',
            'numero_exterior',
            'numero_interior',
            'colonia',
            'cp',
            'entidad',
            'municipio',
        ]);

    }

    public function resetearDeudores(){

        if($this->tipo_deudor === 'I-DEUDOR ÚNICO'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->garantesHipotecarios()->delete();

            $this->gravamen->parteAlicuota()->delete();

            $this->gravamen->garantesCoopropiedad()->delete();

            $this->gravamen->fianza()->delete();

        }elseif($this->tipo_deudor === 'D-GARANTE(S) HIPOTECARIO(S)'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->parteAlicuota()->delete();

            $this->gravamen->garantesCoopropiedad()->delete();

            $this->gravamen->fianza()->delete();

        }elseif($this->tipo_deudor === 'P-PARTE ALICUOTA'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->garantesHipotecarios()->delete();

            $this->gravamen->garantesCoopropiedad()->delete();

            $this->gravamen->fianza()->delete();

        }elseif($this->tipo_deudor === 'G-GARANTES EN COOPROPIEDAD'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->garantesHipotecarios()->delete();

            $this->gravamen->fianza()->delete();

            $this->gravamen->parteAlicuota()->delete();

        }elseif($this->tipo_deudor === 'F-FIANZA'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->garantesHipotecarios()->delete();

            $this->gravamen->garantesCoopropiedad()->delete();

            $this->gravamen->parteAlicuota()->delete();

        }

    }

    public function abrirModalCrear($string){

        $this->resetearPersona();

        $this->title = $string;

        $this->modal = true;

        $this->crear = true;

    }

    public function abrirModalEditar($string, Deudor $deudor){

        $this->resetearPersona();

        $this->title = $string;

        $this->modal = true;

        $this->editar = true;

        $this->tipo_persona = $deudor->persona->tipo;
        $this->nombre = $deudor->persona->nombre;
        $this->ap_paterno = $deudor->persona->ap_paterno;
        $this->ap_materno = $deudor->persona->ap_materno;
        $this->curp = $deudor->persona->curp;
        $this->rfc = $deudor->persona->rfc;
        $this->razon_social = $deudor->persona->razon_social;
        $this->fecha_nacimiento = $deudor->persona->fecha_nacimiento;
        $this->nacionalidad = $deudor->persona->nacionalidad;
        $this->estado_civil = $deudor->persona->estado_civil;
        $this->calle = $deudor->persona->calle;
        $this->ciudad = $deudor->persona->ciudad;
        $this->numero_exterior = $deudor->persona->numero_exterior;
        $this->numero_interior = $deudor->persona->numero_interior;
        $this->colonia = $deudor->persona->colonia;
        $this->cp = $deudor->persona->cp;
        $this->entidad = $deudor->persona->entidad;
        $this->municipio = $deudor->persona->municipio;

    }

    public function abrirModalEditarAcreedor($string, Acreedor $acreedor){

        $this->resetearPersona();

        $this->title = $string;

        $this->modal = true;

        $this->editar = true;

        $this->tipo_persona = $acreedor->persona->tipo;
        $this->nombre = $acreedor->persona->nombre;
        $this->ap_paterno = $acreedor->persona->ap_paterno;
        $this->ap_materno = $acreedor->persona->ap_materno;
        $this->curp = $acreedor->persona->curp;
        $this->rfc = $acreedor->persona->rfc;
        $this->razon_social = $acreedor->persona->razon_social;
        $this->fecha_nacimiento = $acreedor->persona->fecha_nacimiento;
        $this->nacionalidad = $acreedor->persona->nacionalidad;
        $this->estado_civil = $acreedor->persona->estado_civil;
        $this->calle = $acreedor->persona->calle;
        $this->ciudad = $acreedor->persona->ciudad;
        $this->numero_exterior = $acreedor->persona->numero_exterior;
        $this->numero_interior = $acreedor->persona->numero_interior;
        $this->colonia = $acreedor->persona->colonia;
        $this->cp = $acreedor->persona->cp;
        $this->entidad = $acreedor->persona->entidad;
        $this->municipio = $acreedor->persona->municipio;

    }

    public function guardarDeudor($persona_id = null, $actor = null){

        try {

            DB::transaction(function () use ($persona_id, $actor){

                $this->resetearDeudores();

                if($actor === null){

                    $persona_id = $this->guardarPersona();

                }

                Deudor::create([
                    'gravamen_id' => $this->gravamen->id,
                    'persona_id' => $persona_id,
                    'actor_id' => $actor,
                    'tipo' => $this->tipo_deudor
                ]);

            });

            $this->dispatch('mostrarMensaje', ['success', "El deudor se guardó con éxito."]);

            $this->reset(['modal', 'crear', 'title']);

            $this->actualizarDeudores();

        } catch (\Exception $th) {

            Log::error("Error al crear deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al crear deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function guardarPersona(){

        $this->validate([
            'tipo_persona' => 'required',
            'nombre' => [
                'nullable',
                Rule::requiredIf($this->tipo_persona === 'FISICA'),
                utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            ],
            'ap_paterno' => ['nullable',Rule::requiredIf($this->tipo_persona === 'FISICA'), utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/')],
            'ap_materno' => ['nullable',Rule::requiredIf($this->tipo_persona === 'FISICA'), utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/')],
            'curp' => [
                'nullable',
                Rule::requiredIf($this->tipo_persona === 'FISICA'),
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'required',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => Rule::requiredIf($this->tipo_persona === 'MORAL'),
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'numero_interior' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'colonia' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'entidad' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
            'municipio' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.() ]*$/'),
        ]);

        $persona = Persona::where('rfc', $this->rfc)->first();

        if(!$persona){

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
                'cp' => $this->cp,
                'entidad' => $this->entidad,
                'municipio' => $this->municipio,
            ]);

            return $persona->id;

        }else{

            if($this->gravamen->deudores()->where('persona_id', $persona->id)->first() && !$this->editar){

                throw new Exception("La persona ya actua en el gravamen.");

            }

            $persona->update([
                'tipo' => $this->tipo_persona,
                'nombre' => $this->nombre,
                'ap_paterno' => $this->ap_paterno,
                'ap_materno' => $this->ap_materno,
                'curp' => $this->curp,
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
                'municipio' => $this->municipio,
            ]);

            return $persona->id;

        }

    }

    public function actualizarDeudor(){

        try {

            $this->guardarPersona();

            $this->dispatch('mostrarMensaje', ['success', "El deudor se actualizó con éxito."]);

            $this->reset(['modal', 'editar', 'title']);

            $this->actualizarDeudores();

        } catch (\Throwable $th) {

            Log::error("Error al actualizar deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarDeudor($id){

        try {

            $this->gravamen->deudores()->where('id', $id)->first()->delete();

            $this->dispatch('mostrarMensaje', ['success', "El deudor se eliminó con éxito."]);

            $this->actualizarDeudores();

        } catch (\Throwable $th) {
            Log::error("Error al eliminar deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardarAcreedor(){

        try {

            $this->gravamen->acreedores()->create([
                'persona_id' => $this->guardarPersona()
            ]);

            $this->dispatch('mostrarMensaje', ['success', "El deudor se guardó con éxito."]);

            $this->reset(['modal', 'crear', 'title']);

            $this->gravamen->load('acreedores');

        } catch (\Exception $th) {

            Log::error("Error al crear deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al crear acreedor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function actualizarAcreedor(){

        try {

            $this->guardarPersona();

            $this->dispatch('mostrarMensaje', ['success', "El acreedor se actualizó con éxito."]);

            $this->reset(['modal', 'editar', 'title']);

            $this->gravamen->load('acreedores');

        } catch (\Throwable $th) {

            Log::error("Error al actualizar acreedor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarAcreedor($id){

        try {

            Acreedor::find($id)->delete();

            $this->dispatch('mostrarMensaje', ['success', "El acreedor se eliminó con éxito."]);

            $this->gravamen->load('acreedores');

        } catch (\Throwable $th) {
            Log::error("Error al eliminar acreedor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function actualizarDeudores(){

        $this->gravamen->load(
            'deudoresUnicos',
            'garantesHipotecarios',
            'parteAlicuota',
            'garantesCoopropiedad',
            'fianza',
            'deudoresUnicos.actor.persona',
            'garantesHipotecarios.actor.persona',
            'parteAlicuota.actor.persona',
            'garantesCoopropiedad.actor.persona',
            'fianza.actor.persona'
        );

    }

    public function finalizar(){

        $this->validate();

        $this->modalContraseña = true;

    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

        $this->actos = Constantes::ACTOS_INSCRIPCION_GRAVAMEN;

        $this->divisas = Constantes::DIVISAS;

        $this->tipo_deudores = Constantes::TIPO_DEUDOR;

        $this->propiedad = $this->gravamen->movimientoRegistral->folioReal->predio;

        $this->gravamen->estado = 'nuevo';

        $this->tipo_deudor = $this->gravamen->deudores()->first()?->tipo;

        if($this->tipo_deudor === 'I-DEUDOR ÚNICO'){

            $this->propietario = $this->gravamen->deudores()->first()->actor_id;

        }elseif($this->tipo_deudor === 'P-PARTE ALICUOTA'){

            foreach($this->gravamen->parteAlicuota as $deudor){

                array_push($this->propietarios_alicuotas, (string)($deudor->id));

            }

        }

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            $this->gravamen->estado = 'activo';
            $this->gravamen->actualizado_por = auth()->id();
            $this->gravamen->save();

            $this->dispatch('imprimir_documento', ['inscripcion' => $this->gravamen->id]);

            $this->modalContraseña = false;

            $this->crearPdf();

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function crearPdf(){

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        $pdf = Pdf::loadView('incripciones.propiedad.acto', [
            'inscripcion' => $this->gravamen,
            'director' => $director,
            'jefe_departamento' => $jefe_departamento,
            'distrito' => $this->gravamen->movimientoRegistral->getRawOriginal('distrito'),
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'inscripcion.pdf'
        );

    }

    public function render()
    {
        return view('livewire.gravamenes.gravamen-inscripcion')->extends('layouts.admin');
    }

}