<?php

namespace App\Livewire\Varios;

use App\Models\User;
use App\Models\Vario;
use Livewire\Component;
use App\Models\FolioReal;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Services\AsignacionService;
use Illuminate\Http\Client\ConnectionException;
use App\Http\Services\MovimientoRegistralService;

class Varios extends Component
{

    public $actos;
    public $modalContraseña = false;
    public $link;
    public $contraseña;

    public Vario $vario;

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
         ];
    }

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

    public function finalizar(){

        $this->validate();

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
                $this->vario->save();

                $this->vario->movimientoRegistral->update(['estado' => 'concluido']);

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
                        'distrito' => $this->vario->movimientoRegistral->distrito,
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

            });

            $this->dispatch('imprimir_documento', ['vario' => $this->vario->id]);

            $this->modalContraseña = false;

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function obtenerSupervisor(){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->vario->movimientoRegistral->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->vario->movimientoRegistral->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Supervisor certificaciones');
                            })
                            ->first()->id;
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

        $this->link = env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO');

        $this->actos = Constantes::ACTOS_INSCRIPCION_VARIOS;

    }

    public function render()
    {
        return view('livewire.varios.varios')->extends('layouts.admin');
    }
}
