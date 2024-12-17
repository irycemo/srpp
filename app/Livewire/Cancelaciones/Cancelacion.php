<?php

namespace App\Livewire\Cancelaciones;

use App\Constantes\Constantes;
use App\Models\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\SistemaTramitesService;
use App\Models\Cancelacion as ModelCancelacion;
use Illuminate\Http\Client\ConnectionException;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use Spatie\LivewireFilepond\WithFilePond;

class Cancelacion extends Component
{

    use WithFileUploads;
    use WithFilePond;

    public $modalContraseña = false;
    public $modalRechazar = false;
    public $modalDocumento = false;
    public $link;
    public $contraseña;
    public $documento;
    public $actos;

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
            'documento' => 'nullable|mimes:pdf|max:100000'
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

                }elseif($this->cancelacion->acto_contenido == 'CANCELACIÓN TOTAL DE GRAVAMEN'){

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

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar cancelación de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

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

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

            return redirect()->route('cancelacion');

        } catch (\Throwable $th) {
            Log::error("Error al rechazar inscripción de cancelación por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

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
                    'fileable_id' => $this->cancelacion->movimientoRegistral->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de cancelación por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if(!$this->cancelacion->movimientoRegistral->documentoEntrada()){

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

                    $contents = file_get_contents($data['url']);

                    $filename = basename($data['url']);

                    Storage::disk('documento_entrada')->put($filename, $contents);

                    File::create([
                        'fileable_id' => $this->cancelacion->movimientoRegistral->id,
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

        $this->gravamenCancelarMovimiento = MovimientoRegistral::where('folio_real', $this->cancelacion->movimientoRegistral->folio_real)
                                                                    ->where('tomo_gravamen', $this->cancelacion->movimientoRegistral->tomo_gravamen)
                                                                    ->where('registro_gravamen', $this->cancelacion->movimientoRegistral->registro_gravamen)
                                                                    ->whereHas('gravamen', function($q){
                                                                        $q->where('estado', 'activo');
                                                                    })
                                                                    ->first();

        if($this->gravamenCancelarMovimiento && !$this->cancelacion->gravamen){

            $this->cancelacion->gravamen = $this->gravamenCancelarMovimiento->id;
            $this->cancelacion->save();

        }

        $this->actos = Constantes::ACTOS_INSCRIPCION_CANCELACIONES;

    }

    public function render()
    {

        $this->authorize('view', $this->cancelacion->movimientoRegistral);

        return view('livewire.cancelaciones.cancelacion')->extends('layouts.admin');

    }
}
