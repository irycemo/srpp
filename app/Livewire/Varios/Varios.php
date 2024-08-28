<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\File;
use App\Models\User;
use App\Models\Actor;
use App\Models\Vario;
use App\Models\Deudor;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioReal;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use App\Models\FolioRealPersona;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\AsignacionService;
use Illuminate\Http\Client\ConnectionException;

class Varios extends Component
{

    use WithFileUploads;

    public $actos;
    public $modalContraseña = false;
    public $modalDocumento = false;
    public $documento;
    public $link;
    public $contraseña;

    public Vario $vario;

    public $denominacion;
    public $fecha_celebracion;
    public $fecha_inscripcion;
    public $notaria;
    public $nombre_notario;
    public $numero_hojas;
    public $numero_escritura;
    public $descripcion;
    public $observaciones;

    public $modal = false;
    public $crear = false;
    public $editar = false;

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

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
         ];
    }

    protected $validationAttributes  = [
        'fecha_inscripcion' => 'fecha de inscripción',
        'fecha_celebracion' => 'fecha de celebarción',
        'notaria' => 'número de notaria',
        'nombre_notario' => 'nombre del notario',
        'numero_hojas' => 'número de hojas',
        'numero_escritura' => 'número de escritura',
    ];

    public function consultarArchivo(){

        try {

            $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                ->accept('application/json')
                                ->asForm()
                                ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                    'año' => $this->vario->movimientoRegistral->año,
                                                                                    'tramite' => $this->vario->movimientoRegistral->tramite,
                                                                                    'usuario' => $this->vario->movimientoRegistral->usuario,
                                                                                    'estado' => 'nuevo'
                                                                                ]);

            $data = collect(json_decode($response, true));

            if($response->status() == 200){

                $this->dispatch('ver_documento', ['url' => $data['url']]);

            }else{

                $this->dispatch('mostrarMensaje', ['error', "No se encontro el documento."]);

            }

        } catch (ConnectionException $th) {

            Log::error("Error al cargar archivo en varios: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

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

        $persona = Persona::where('rfc', $this->rfc)->first();

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

                throw new Exception("La persona ya esata en la lista.");

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

    public function finalizar(){

        $this->validate();

        if($this->vario->acto_contenido == 'PERSONAS MORALES'){

            $this->validate([
                'denominacion' => 'required',
                'fecha_celebracion' => 'required',
                'fecha_inscripcion' => 'required',
                'notaria' => 'required',
                'nombre_notario' => 'required',
                'numero_hojas' => 'required',
                'descripcion' => 'required',
                'observaciones' => 'required',
            ]);

            if($this->vario->actores->count() == 0){

                $this->dispatch('mostrarMensaje', ['error', "Debe haber participantes."]);

                return;

            }

        }

        if(!$this->vario->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

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

                $this->vario->estado = 'activo';
                $this->vario->actualizado_por = auth()->id();
                $this->vario->fecha_inscripcion = now()->toDateString();
                $this->vario->save();

                $this->vario->movimientoRegistral->update(['estado' => 'elaborado']);

                if($this->vario->servicio == 'DL09'){

                    $movimiento = MovimientoRegistral::create([
                        'estado' => 'nuevo',
                        'folio' => FolioReal::find($this->vario->movimientoRegistral->folio_real)->ultimoFolio() + 1,
                        'folio_real' => $this->vario->movimientoRegistral->folio_real,
                        'fecha_prelacion' => $this->vario->movimientoRegistral->fecha_prelacion,
                        'fecha_entrega' => $this->vario->movimientoRegistral->fecha_entrega,
                        'fecha_pago' => $this->vario->movimientoRegistral->fecha_pago,
                        'tipo_servicio' => $this->vario->movimientoRegistral->tipo_servicio,
                        'solicitante' => $this->vario->movimientoRegistral->solicitante,
                        'seccion' => $this->vario->movimientoRegistral->seccion,
                        'año' => $this->vario->movimientoRegistral->año,
                        'tramite' => $this->vario->movimientoRegistral->tramite,
                        'usuario' => $this->vario->movimientoRegistral->usuario,
                        'distrito' => $this->vario->movimientoRegistral->getRawOriginal('distrito'),
                        'tipo_documento' => $this->vario->movimientoRegistral->tipo_documento,
                        'numero_documento' => $this->vario->movimientoRegistral->numero_documento,
                        'numero_propiedad' => $this->vario->movimientoRegistral->numero_propiedad,
                        'autoridad_cargo' => $this->vario->movimientoRegistral->autoridad_cargo,
                        'autoridad_numero' => $this->vario->movimientoRegistral->autoridad_numero,
                        'fecha_emision' => $this->vario->movimientoRegistral->fecha_emision,
                        'fecha_inscripcion' => $this->vario->movimientoRegistral->fecha_inscripcion,
                        'procedencia' => $this->vario->movimientoRegistral->procedencia,
                        'numero_oficio' => $this->vario->movimientoRegistral->numero_oficio,
                        'folio_real' => $this->vario->movimientoRegistral->folio_real,
                        'monto' => $this->vario->movimientoRegistral->monto,
                        'usuario_asignado' => (new AsignacionService())->obtenerUltimoUsuarioConAsignacion($this->obtenerUsuarios()),
                        'usuario_supervisor' => $this->obtenerSupervisor(),
                        'movimiento_padre' => $this->vario->movimientoRegistral->id
                    ]);

                    $movimiento->certificacion()->create([
                        'servicio' => 'DL07',
                        'observaciones' => 'Trámite generado por inscripción de un primer aviso preventivo ' . $this->vario->movimientoRegistral->año . '-' . $this->vario->movimientoRegistral->tramite . '-' . $this->vario->movimientoRegistral->usuario
                    ]);

                }

                if($this->vario->acto_contenido == 'PERSONAS MORALES'){

                    $folioRealPersona = FolioRealPersona::create([
                        'folio' => (FolioRealPersona::max('folio') ?? 0) + 1,
                        'denominacion' => $this->denominacion,
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

                    foreach($this->vario->actores as $actor){

                        $folioRealPersona->actores()->create([
                            'persona_id' => $actor->persona_id,
                            'tipo_actor' => $actor->tipo_actor
                        ]);

                        $actor->delete();

                    }

                }

            });

            return redirect()->route('varios');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

    public function obtenerSupervisor(){

        if($this->vario->movimientoRegistral->getRawOriginal('distrito') == 2){

            return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Supervisor uruapan');
                            })
                            ->first()->id;

        }else{

            return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Supervisor certificaciones');
                            })
                            ->first()->id;

        }

    }

    public function obtenerUsuarios(){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->vario->movimientoRegistral->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->vario->movimientoRegistral->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Certificador Gravamen');
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

                if(env('LOCAL') == "1"){

                    $pdf = $this->documento->store('srpp/documento_entrada', 's3');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada_s3',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "0"){

                    $pdf = $this->documento->store('/', 'documento_entrada');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "2"){

                    $pdf = $this->documento->store('srpp/documento_entrada', 's3');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada_s3',
                        'url' => $pdf
                    ]);

                    $pdf = $this->documento->store('/', 'documento_entrada');

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $pdf
                    ]);

                }

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de cancelación por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if(!$this->vario->movimientoRegistral->documentoEntrada()){

            try {

                $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                    ->accept('application/json')
                                    ->asForm()
                                    ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                        'año' => $this->vario->movimientoRegistral->año,
                                                                                        'tramite' => $this->vario->movimientoRegistral->tramite,
                                                                                        'usuario' => $this->vario->movimientoRegistral->usuario,
                                                                                        'estado' => 'nuevo'
                                                                                    ]);

                $data = collect(json_decode($response, true));

                if($response->status() == 200){

                    $contents = file_get_contents($data['url']);

                    $filename = basename($data['url']);

                    Storage::disk('documento_entrada')->put($filename, $contents);

                    File::create([
                        'fileable_id' => $this->vario->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $filename
                    ]);

                }

            } catch (ConnectionException $th) {

                Log::error("Error al cargar archivo en cancelación: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

                $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

            }

        }

        $this->actos = Constantes::ACTOS_INSCRIPCION_VARIOS;

        $this->vario->load('actores.persona');

    }

    public function render()
    {
        return view('livewire.varios.varios')->extends('layouts.admin');
    }
}
