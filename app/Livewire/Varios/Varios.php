<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\File;
use App\Models\User;
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
use App\Traits\Inscripciones\Varios\VariosTrait;
use Illuminate\Http\Client\ConnectionException;

class Varios extends Component
{


    use VariosTrait;

    public $actos;

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
         ];
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

                    $this->crearCertificadoGravamen();

                }

                if($this->vario->acto_contenido == 'PERSONAS MORALES'){

                    $this->crearFolioRealPersona();

                }

                if($this->vario->acto_contenido == 'CONSOLIDACIÓN DEL USUFRUCTO'){

                    $this->actualizarPropietarios();

                }

            });

            return redirect()->route('varios');

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function crearCertificadoGravamen(){

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

    public function guardar(){

        try {

            DB::transaction(function () {

                $this->vario->movimientoRegistral->update(['estado' => 'captura']);

                $this->vario->save();

                if($this->vario->acto_contenido == 'PERSONAS MORALES'){

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

                }

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

    }

    public function render()
    {
        return view('livewire.varios.varios')->extends('layouts.admin');
    }
}
