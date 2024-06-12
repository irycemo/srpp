<?php

namespace App\Livewire\Cancelaciones;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\Cancelacion as ModelCancelacion;
use Illuminate\Http\Client\ConnectionException;

class Cancelacion extends Component
{

    public $modalContraseña = false;
    public $link;
    public $contraseña;

    public $gravamenCancelarMovimiento;
    public $folio_gravamen;
    public $valor;

    public ModelCancelacion $cancelacion;

    protected function rules(){
        return [
            'cancelacion.acto_contenido' => 'required',
            'cancelacion.tipo' => 'required',
            'cancelacion.observaciones' => 'required',
            'gravamenCancelarMovimiento' => 'required',
            'valor' => Rule::requiredIf($this->cancelacion->acto_contenido === 'PARCIAL'),
         ];
    }

    protected $validationAttributes  = [
        'gravamenCancelarMovimiento' => 'folio del gravamen',
        'valor' => 'parcialidad del valor'
    ];

    public function updatedCancelacionActoContenido(){

        $this->valor = null;

    }

    public function consultarArchivo(){

        try {

            $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                ->accept('application/json')
                                ->asForm()
                                ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                    'año' => $this->cancelacion->movimientoRegistral->año,
                                                                                    'tramite' => $this->cancelacion->movimientoRegistral->tramite,
                                                                                    'usuario' => $this->cancelacion->movimientoRegistral->usuario,
                                                                                    'estado' => 'nuevo'
                                                                                ]);

            $data = collect(json_decode($response, true));

            if($response->status() == 200){

                $this->dispatch('ver_documento', ['url' => $data['url']]);

            }else{

                $this->dispatch('mostrarMensaje', ['error', "No se encontro el documento."]);

            }

        } catch (ConnectionException $th) {

            Log::error("Error al cargar archivo en cancelacion: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizar(){

        $this->validate();

        if($this->valor >= $this->gravamenCancelarMovimiento->gravamen->valor_gravamen){

            $this->dispatch('mostrarMensaje', ['error', "La parcialidad del valor debe ser menor al valor del gravamen."]);

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

                if($this->cancelacion->acto_contenido == 'PARCIAL'){

                    $this->gravamenCancelarMovimiento->gravamen->update([
                        'valor_gravamen' => $this->gravamenCancelarMovimiento->gravamen->valor_gravamen - $this->valor,
                        'actualizado_por' => auth()->id()
                    ]);

                }elseif($this->cancelacion->acto_contenido == 'TOTAL'){

                    $this->gravamenCancelarMovimiento->gravamen->update([
                        'estado' => 'cancelado',
                        'actualizado_por' => auth()->id(),
                        'observaciones' => $this->gravamenCancelarMovimiento->gravamen->observaciones . ' ' . 'Cancelado mediante movimiento registral: ' . $this->cancelacion->movimientoRegistral->folioReal->folio . '-' . $this->cancelacion->movimientoRegistral->folio,
                    ]);
                }

                $this->gravamenCancelarMovimiento->update([
                    'movimiento_padre' => $this->cancelacion->movimientoRegistral->id,
                    'actualizado_por' => auth()->id()
                ]);

                $this->cancelacion->estado = 'activo';
                $this->cancelacion->actualizado_por = auth()->id();
                $this->cancelacion->save();

                $this->cancelacion->movimientoRegistral->update(['estado' => 'concluido']);

            });

            $this->dispatch('imprimir_documento', ['cancelacion' => $this->cancelacion->id]);

            $this->modalContraseña = false;

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function buscarGravamen(){

        $this->gravamenCancelarMovimiento = MovimientoRegistral::with('gravamen')
                                                ->where('folio_real', $this->cancelacion->movimientoRegistral->folio_real)
                                                ->where('folio', $this->folio_gravamen)
                                                ->where('estado', 'concluido')
                                                ->first();

        if(!$this->gravamenCancelarMovimiento){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el gravamen."]);

            return;

        }

        if(!$this->gravamenCancelarMovimiento->gravamen->exists()){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el gravamen."]);

            return;

        }

    }

    public function mount(){

        $this->link = env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO');

    }

    public function render()
    {
        return view('livewire.cancelaciones.cancelacion')->extends('layouts.admin');
    }
}
