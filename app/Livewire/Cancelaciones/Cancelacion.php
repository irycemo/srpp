<?php

namespace App\Livewire\Cancelaciones;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Services\SistemaTramitesService;
use App\Models\Cancelacion as ModelCancelacion;
use Illuminate\Http\Client\ConnectionException;

class Cancelacion extends Component
{

    public $modalContraseña = false;
    public $modalRechazar = false;
    public $link;
    public $contraseña;

    public $gravamenCancelarMovimiento;
    public $folio_gravamen;
    public $valor;

    public $motivo_rechazo;

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
        'valor' => 'parcialidad del valor',
        'motivo_rechazo' => 'motivo del rechazo'
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

        if(!$this->gravamenCancelarMovimiento){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el gravamen a cancelar."]);

            return;

        }

        if($this->valor >= $this->gravamenCancelarMovimiento->gravamen->valor_gravamen){

            $this->dispatch('mostrarMensaje', ['error', "La parcialidad del valor debe ser menor al valor del gravamen."]);

            return;

        }

        if($this->cancelacion->movimientoRegistral->tipo_servicio == 'ordinario'){

            if(!($this->calcularDiaElaboracion($this->cancelacion) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede finalizarse apartir del " . $this->calcularDiaElaboracion($this->cancelacion)->format('d-m-Y')]);

                return;

            }

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
                        'actualizado_por' => auth()->id(),
                        'observaciones' => $this->gravamenCancelarMovimiento->gravamen->observaciones . ' ' . 'Cancelado parcialmente mediante movimiento registral: ' . $this->cancelacion->movimientoRegistral->folioReal->folio . '-' . $this->cancelacion->movimientoRegistral->folio,
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
                $this->cancelacion->gravamen = $this->gravamenCancelarMovimiento->id;
                $this->cancelacion->actualizado_por = auth()->id();
                $this->cancelacion->save();

                $this->cancelacion->movimientoRegistral->update(['estado' => 'elaborado']);

            });

            $this->dispatch('imprimir_documento', ['cancelacion' => $this->cancelacion->id]);

            $this->modalContraseña = false;

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                $this->cancelacion->save();

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar cancelación de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function calcularDiaElaboracion($modelo){

        $diaElaboracion = $modelo->movimientoRegistral->fecha_pago;

        for ($i=0; $i < 2; $i++) {

            $diaElaboracion->addDays(1);

            while($diaElaboracion->isWeekend()){

                $diaElaboracion->addDay();

            }

        }

        return $diaElaboracion;

    }

    public function rechazar(){

        $this->validate([
            'motivo_rechazo' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->motivo_rechazo ;

                (new SistemaTramitesService())->rechazarTramite($this->cancelacion->movimientoRegistral->año, $this->cancelacion->movimientoRegistral->tramite, $this->cancelacion->movimientoRegistral->usuario, $observaciones);

                $this->cancelacion->movimientoRegistral->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->cancelacion->actualizado_por = auth()->user()->id;

                $this->cancelacion->observaciones = $this->cancelacion->observaciones . $observaciones;

                $this->cancelacion->save();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

                $this->modalRechazar = false;

            });

        } catch (\Throwable $th) {
            Log::error("Error al rechazar inscripción de cancelación por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->link = env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO');

        $this->gravamenCancelarMovimiento = MovimientoRegistral::where('folio_real', $this->cancelacion->movimientoRegistral->folio_real)
                                                                    ->where('tomo_gravamen', $this->cancelacion->movimientoRegistral->tomo_gravamen)
                                                                    ->where('registro_gravamen', $this->cancelacion->movimientoRegistral->registro_gravamen)
                                                                    ->whereHas('gravamen', function($q){
                                                                        $q->where('estado', 'activo');
                                                                    })
                                                                    ->first();

    }

    public function render()
    {
        return view('livewire.cancelaciones.cancelacion')->extends('layouts.admin');
    }
}
