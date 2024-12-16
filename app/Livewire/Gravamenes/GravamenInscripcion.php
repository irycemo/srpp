<?php

namespace App\Livewire\Gravamenes;

use Exception;
use App\Models\File;
use App\Models\User;
use App\Models\Actor;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Gravamen;
use App\Models\FolioReal;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\AsignacionService;
use Illuminate\Http\Client\ConnectionException;
use App\Http\Controllers\Gravamen\GravamenController;
use Spatie\LivewireFilepond\WithFilePond;

class GravamenInscripcion extends Component
{

    use WithFileUploads;
    use WithFilePond;

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
    public $modalDocumento = false;
    public $title;
    public $documento;

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

    public $folio_gravamen;
    public $gravamenHipoteca;
    public $folio_real_division;
    public $folios_reales = [];

    protected function rules(){
        return [
            'antecente_tomo' => 'nullable',
            'antecente_registro' => 'nullable',
            'antecente_distrito' => 'nullable',
            'gravamen.tipo' => 'required',
            'gravamen.acto_contenido' => 'required',
            'gravamen.valor_gravamen' => 'required|numeric',
            'gravamen.divisa' => ['required', Rule::in($this->divisas)],
            'gravamen.estado' => 'required',
            'gravamen.expediente' => 'nullable',
            'gravamen.observaciones' => 'required',
            'documento' => 'nullable|mimes:pdf|max:10000'
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

    public function updatedTipoDeudor(){

        if($this->tipo_deudor == 'D-GARANTE(S) HIPOTECARIO(S)'){

            foreach ($this->propiedad->propietarios() as $propietario) {

                Actor::create([
                    'actorable_type' => 'App\Models\Gravamen',
                    'actorable_id' => $this->gravamen->id,
                    'tipo_actor' => 'deudor',
                    'persona_id' => $propietario->persona_id,
                    'tipo_deudor' => $this->tipo_deudor
                ]);
            }

        }

        $this->actualizarDeudores();

    }

    public function updatedPropietario(){

        if($this->propietario === "") return;

        $this->guardarDeudor(null, $this->propietario);

    }

    public function updatedPropietariosAlicuotas(){

        foreach($this->propietarios_alicuotas as $persona){

            if(!$this->gravamen->deudores()->where('persona_id', (int)$persona)->where('tipo_deudor', 'P-PARTE ALICUOTA')->first()){

                $this->resetearDeudores();

                Actor::create([
                    'actorable_type' => 'App\Models\Gravamen',
                    'actorable_id' => $this->gravamen->id,
                    'tipo_actor' => 'deudor',
                    'persona_id' => $persona,
                    'tipo_deudor' => $this->tipo_deudor
                ]);

            }

        }

    }

    public function updatedPropietariosGarantesCoopropiedad(){

        foreach($this->propietarios_garantes_coopropiedad as $persona){

            if(!$this->gravamen->deudores()->where('persona_id', (int)$persona)->where('tipo_deudor', 'G-GARANTES EN COOPROPIEDAD')->first()){

                $this->resetearDeudores();

                Actor::create([
                    'actorable_type' => 'App\Models\Gravamen',
                    'actorable_id' => $this->gravamen->id,
                    'tipo_actor' => 'deudor',
                    'persona_id' => $persona,
                    'tipo_deudor' => $this->tipo_deudor
                ]);

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

    public function abrirModalEditar($string, Actor $deudor){

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

    public function abrirModalEditarAcreedor($string, Actor $acreedor){

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

                    $tipo_deudor = 'deudor';

                }else{

                    $persona_id = Actor::Find($actor)->persona->id;

                    $tipo_deudor = $this->tipo_deudor;

                }

                Actor::create([
                    'actorable_type' => 'App\Models\Gravamen',
                    'actorable_id' => $this->gravamen->id,
                    'tipo_actor' => 'deudor',
                    'persona_id' => $persona_id,
                    'tipo_deudor' => $tipo_deudor
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
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
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

            Actor::create([
                'actorable_type' => 'App\Models\Gravamen',
                'actorable_id' => $this->gravamen->id,
                'tipo_actor' => 'acreedor',
                'persona_id' => $this->guardarPersona(),
            ]);

            $this->dispatch('mostrarMensaje', ['success', "El acreedor se guardó con éxito."]);

            $this->reset(['modal', 'crear', 'title']);

            $this->gravamen->load('acreedores.persona');

        } catch (\Exception $th) {

            Log::error("Error al crear acreedor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

            $this->gravamen->load('acreedores.persona');

        } catch (\Throwable $th) {

            Log::error("Error al actualizar acreedor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarAcreedor($id){

        try {

            Actor::find($id)->delete();

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
            'deudoresUnicos',
            'garantesHipotecarios',
            'parteAlicuota',
            'garantesCoopropiedad',
        );

    }

    public function finalizar(){

        $this->validate();

        if($this->gravamen->acto_contenido === 'DIVISIÓN DE HIPOTECA' && count($this->folios_reales) == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar los folios reales de la división."]);

            return;

        }

        if(!$this->gravamen->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        if(!$this->gravamen->acreedores()->count()){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar los acreedores."]);

            return;

        }

        if(!$this->gravamen->deudores()->count()){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar los deudores."]);

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

            DB::transaction(function () {

                $this->gravamen->estado = 'activo';
                $this->gravamen->fecha_inscripcion = now()->toDateString();
                $this->gravamen->actualizado_por = auth()->id();
                $this->gravamen->save();

                if($this->gravamen->acto_contenido === 'DIVISIÓN DE HIPOTECA'){

                    foreach($this->folios_reales as $folio){

                        $movimiento = $folio->movimientosRegistrales()->create([
                            'estado' => 'concluido',
                            'folio' => FolioReal::find($this->gravamen->movimientoRegistral->folio_real)->ultimoFolio() + 1,
                            'folio_real' => $this->gravamen->movimientoRegistral->folio_real,
                            'fecha_prelacion' => $this->gravamen->movimientoRegistral->fecha_prelacion,
                            'fecha_entrega' => $this->gravamen->movimientoRegistral->fecha_entrega,
                            'fecha_pago' => $this->gravamen->movimientoRegistral->fecha_pago,
                            'tipo_servicio' => $this->gravamen->movimientoRegistral->tipo_servicio,
                            'solicitante' => $this->gravamen->movimientoRegistral->solicitante,
                            'seccion' => $this->gravamen->movimientoRegistral->seccion,
                            'año' => $this->gravamen->movimientoRegistral->año,
                            'tramite' => $this->gravamen->movimientoRegistral->tramite,
                            'usuario' => $this->gravamen->movimientoRegistral->usuario,
                            'distrito' => $this->gravamen->movimientoRegistral->getRawOriginal('distrito'),
                            'tipo_documento' => $this->gravamen->movimientoRegistral->tipo_documento,
                            'numero_documento' => $this->gravamen->movimientoRegistral->numero_documento,
                            'numero_propiedad' => $this->gravamen->movimientoRegistral->numero_propiedad,
                            'autoridad_cargo' => $this->gravamen->movimientoRegistral->autoridad_cargo,
                            'autoridad_numero' => $this->gravamen->movimientoRegistral->autoridad_numero,
                            'fecha_emision' => $this->gravamen->movimientoRegistral->fecha_emision,
                            'fecha_inscripcion' => $this->gravamen->movimientoRegistral->fecha_inscripcion,
                            'procedencia' => $this->gravamen->movimientoRegistral->procedencia,
                            'numero_oficio' => $this->gravamen->movimientoRegistral->numero_oficio,
                            'folio_real' => $this->gravamen->movimientoRegistral->folio_real,
                            'monto' => $this->gravamen->movimientoRegistral->monto,
                            'usuario_asignado' => (new AsignacionService())->obtenerUltimoUsuarioConAsignacion($this->obtenerUsuarios()),
                            'usuario_supervisor' => $this->obtenerSupervisor(),
                            'movimiento_padre' => $this->gravamen->movimientoRegistral->id
                        ]);

                        $movimiento->gravamen()->create([
                            'servicio' => 'DL66',
                            'fecha_inscripcion' => now(),
                            'estado' => 'activo',
                            'acto_contenido' => 'HIPOTECA',
                            'valor_gravamen' => $this->gravamen->valor_gravamen / count($this->folios_reales),
                            'divisa' => $this->gravamen->divisa,
                            'observaciones' => 'Trámite generado por división de hipoteca ' . $this->gravamen->movimientoRegistral->año . '-' . $this->gravamen->movimientoRegistral->tramite . '-' . $this->gravamen->movimientoRegistral->usuario
                        ]);

                    }

                }

                $this->gravamen->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->gravamen->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de gravamen']);

                (new GravamenController())->caratula($this->gravamen);

            });

            return redirect()->route('gravamen');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                if($this->gravamen->movimientoRegistral->estado != 'correccion')
                    $this->gravamen->movimientoRegistral->estado = 'captura';

                $this->gravamen->movimientoRegistral->actualizado_por = auth()->id();
                $this->gravamen->movimientoRegistral->save();

                $this->gravamen->save();

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function buscarGravamen(){

        $this->reset('folios_reales');

        $this->gravamenHipoteca = MovimientoRegistral::with('gravamen')
                                                ->where('folio_real', $this->gravamen->movimientoRegistral->folio_real)
                                                ->where('folio', $this->folio_gravamen)
                                                ->where('estado', 'concluido')
                                                ->first();

        if(!$this->gravamenHipoteca){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el gravamen."]);

            return;

        }

        if(!$this->gravamenHipoteca->gravamen->exists()){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el gravamen."]);

            return;

        }

    }

    public function agregarFolioReal(){

        $folio_real = FolioReal::where('folio', $this->folio_real_division)
                                ->where('estado', 'activo')
                                ->first();

        if(!$folio_real){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el gravamen."]);

            return;

        }

        if(collect($this->folios_reales)->where('id', $folio_real->id)->first()){

            $this->dispatch('mostrarMensaje', ['error', "El folio ya esta en la lista."]);

            return;

        }

        array_push($this->folios_reales, $folio_real);

    }

    public function quitarFolio($key){

        unset($this->folios_reales[$key]);

    }

    public function obtenerSupervisor(){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->gravamen->movimientoRegistral->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->gravamen->movimientoRegistral->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Supervisor gravamen');
                            })
                            ->first()->id;
    }

    public function obtenerUsuarios(){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->gravamen->movimientoRegistral->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->gravamen->movimientoRegistral->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Gravamen');
                            })
                            ->get();
    }

    public function abrirModalFinalizar(){

        $this->reset('documento');

        $this->dispatch('removeFiles');

        $this->modalDocumento = true;

    }

    public function guardarDocumento(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                $pdf = $this->documento->store('/', 'documento_entrada');

                File::create([
                    'fileable_id' => $this->gravamen->movimientoRegistral->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if(!$this->gravamen->movimientoRegistral->documentoEntrada()){

            try {

                $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                    ->accept('application/json')
                                    ->asForm()
                                    ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                        'año' => $this->gravamen->movimientoRegistral->año,
                                                                                        'tramite' => $this->gravamen->movimientoRegistral->tramite,
                                                                                        'usuario' => $this->gravamen->movimientoRegistral->usuario,
                                                                                        'estado' => 'nuevo'
                                                                                    ]);

                $data = collect(json_decode($response, true));

                if($response->status() == 200){

                    $contents = file_get_contents($data['url']);

                    $filename = basename($data['url']);

                    Storage::disk('documento_entrada')->put($filename, $contents);

                    File::create([
                        'fileable_id' => $this->gravamen->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $filename
                    ]);

                }

            } catch (ConnectionException $th) {

                Log::error("Error al cargar archivo en gravamen: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

                $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

            }

        }

        $this->distritos = Constantes::DISTRITOS;

        $this->actos = Constantes::ACTOS_INSCRIPCION_GRAVAMEN;

        $this->divisas = Constantes::DIVISAS;

        $this->tipo_deudores = Constantes::TIPO_DEUDOR;

        $this->propiedad = $this->gravamen->movimientoRegistral->folioReal->predio;

        $this->gravamen->estado = 'nuevo';

        $this->gravamen->load('acreedores');

        $this->tipo_deudor = $this->gravamen->deudores()->first()?->tipo_deudor;

        if($this->tipo_deudor === 'I-DEUDOR ÚNICO'){

            $this->propietario = $this->gravamen->deudores()->first()->id;

        }elseif($this->tipo_deudor === 'P-PARTE ALICUOTA'){

            foreach($this->gravamen->parteAlicuota as $deudor){

                array_push($this->propietarios_alicuotas, (string)($deudor->id));

            }

        }

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first();

        if(!$director) abort(500, message:"Es necesario registrar al director.");

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento inscripciones');
        })->first();

        if(!$jefe_departamento) abort(500, message:"Es necesario registrar al jefe de Departamento de Registro de Inscripciones.");

    }

    public function render()
    {

        $this->authorize('view', $this->gravamen->movimientoRegistral);

        return view('livewire.gravamenes.gravamen-inscripcion')->extends('layouts.admin');
    }

}
