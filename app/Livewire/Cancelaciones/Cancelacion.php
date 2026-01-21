<?php

namespace App\Livewire\Cancelaciones;

use App\Constantes\Constantes;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Spatie\LivewireFilepond\WithFilePond;
use App\Models\Cancelacion as ModelCancelacion;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Traits\Inscripciones\DocumentoEntradaTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;

class Cancelacion extends Component
{

    use WithFilePond;
    use DocumentoEntradaTrait;
    use ConsultarArchivoTrait;
    USE GuardarDocumentoEntradaTrait;

    public $modalContraseña = false;
    public $modalRechazar = false;
    public $link;
    public $contraseña;
    public $actos;

    public $gravamenCancelarMovimiento;
    public $folio_gravamen;
    public $valor;

    public $motivo_rechazo;

    public ModelCancelacion $cancelacion;
    public $movimientoRegistral;

    protected function rules(){
        return [
            'cancelacion.acto_contenido' => 'required',
            'cancelacion.tipo' => 'required',
            'cancelacion.observaciones' => 'required',
            'gravamenCancelarMovimiento' => 'required',
            'valor' => Rule::requiredIf($this->cancelacion->acto_contenido === 'PARCIAL'),
            'documento' => 'nullable|mimes:pdf|max:100000',
            'tipo_documento' => 'required',
            'autoridad_cargo' => 'required',
            'autoridad_nombre' => 'required',
            'numero_documento' => 'nullable',
            'fecha_emision' => 'required',
            'procedencia' => 'nullable',

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

    public function finalizar(){

        $this->validate();

        if(!$this->gravamenCancelarMovimiento){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el gravamen a cancelar."]);

            return;

        }

        if($this->cancelacion->acto_contenido == 'CANCELACIÓN PARCIAL DE GRAVAMEN'){

            if($this->valor >= $this->gravamenCancelarMovimiento->gravamen->valor_gravamen){

                $this->dispatch('mostrarMensaje', ['error', "La parcialidad del valor debe ser menor al valor del gravamen."]);

                return;

            }

        }

        if(!$this->cancelacion->movimientoRegistral->documentoEntrada()){

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

                if($this->cancelacion->acto_contenido == 'CANCELACIÓN PARCIAL DE GRAVAMEN'){

                    $this->gravamenCancelarMovimiento->gravamen->valor_gravamen = $this->gravamenCancelarMovimiento->gravamen->valor_gravamen - $this->valor;
                    $this->gravamenCancelarMovimiento->gravamen->actualizado_por = auth()->id();
                    $this->gravamenCancelarMovimiento->gravamen->observaciones = $this->gravamenCancelarMovimiento->gravamen->observaciones . ' ' . 'Cancelado parcialmente mediante movimiento registral: ' . $this->cancelacion->movimientoRegistral->folioReal->folio . '-' . $this->cancelacion->movimientoRegistral->folio;

                    if($this->gravamenCancelarMovimiento->gravamen->valor_gravamen <= 0){

                        $this->gravamenCancelarMovimiento->gravamen->estado = 'cancelado';

                    }else{

                        $this->gravamenCancelarMovimiento->gravamen->estado = 'parcial';
                    }

                    $this->gravamenCancelarMovimiento->gravamen->save();

                }else{

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
                $this->cancelacion->fecha_inscripcion = now()->toDateString();
                $this->cancelacion->save();

                $this->actualizarDocumentoEntrada($this->cancelacion->movimientoRegistral);

                $this->cancelacion->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->cancelacion->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de cancelación']);

                (new CancelacionController())->caratula($this->cancelacion);

            });

            return redirect()->route('cancelacion');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de cancelación por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                if($this->cancelacion->movimientoRegistral->estado != 'correccion')
                    $this->cancelacion->movimientoRegistral->estado = 'captura';

                $this->cancelacion->movimientoRegistral->actualizado_por = auth()->id();
                $this->cancelacion->movimientoRegistral->save();

                $this->cancelacion->save();

                $this->actualizarDocumentoEntrada($this->cancelacion->movimientoRegistral);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar cancelación de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->movimientoRegistral = $this->cancelacion->movimientoRegistral;

        $this->consultarArchivo($this->movimientoRegistral);

        if(!$this->cancelacion->gravamen){

            $this->gravamenCancelarMovimiento = MovimientoRegistral::where('folio_real', $this->cancelacion->movimientoRegistral->folio_real)
                                                                        ->where('tomo_gravamen', $this->cancelacion->movimientoRegistral->tomo_gravamen)
                                                                        ->where('registro_gravamen', $this->cancelacion->movimientoRegistral->registro_gravamen)
                                                                        ->whereHas('gravamen', function($q){
                                                                            $q->whereIn('estado', ['activo', 'inactivo']);
                                                                        })
                                                                        ->first();

            if($this->gravamenCancelarMovimiento){

                $this->cancelacion->gravamen = $this->gravamenCancelarMovimiento->id;
                $this->cancelacion->save();

            }

        }else{

            $this->gravamenCancelarMovimiento = $this->cancelacion->gravamenCancelado;

        }

        $this->actos = Constantes::ACTOS_INSCRIPCION_CANCELACIONES;

        $this->cargarDocumentoEntrada($this->cancelacion->movimientoRegistral);

    }

    public function render()
    {

        $this->authorize('view', $this->cancelacion->movimientoRegistral);

        return view('livewire.cancelaciones.cancelacion')->extends('layouts.admin');

    }

}
