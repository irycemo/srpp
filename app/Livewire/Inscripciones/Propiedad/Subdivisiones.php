<?php

namespace App\Livewire\Inscripciones\Propiedad;

use App\Models\File;
use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Propiedad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\AsignacionService;
use Spatie\LivewireFilepond\WithFilePond;
use App\Http\Controllers\Subdivisiones\SubdivisionesController;

class Subdivisiones extends Component
{

    use WithFilePond;

    public Propiedad $propiedad;

    public $documento;
    public $documento_entrada;

    public $modalDocumento = false;

    public $folioIds = [];
    public $foliosReales = [];

    public function abrirModalDocumento(){

        $this->reset('documento_entrada');

        $this->modalDocumento = true;

    }

    public function guardarDocumento(){

        $this->validate(['documento_entrada' => 'required']);

        try {

            DB::transaction(function (){

                $pdf = $this->documento_entrada->store('/', 'documento_entrada');

                File::create([
                    'fileable_id' => $this->propiedad->movimientoRegistral->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en subdivisiones por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function subdividir(){

        if(!$this->propiedad->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['warning', "Debe subir el documento de entrada primero."]);

            return;

        }

        try {

            DB::transaction(function (){

                for ($i=0; $i < $this->propiedad->numero_inmuebles; $i++) {

                    $folioReal = $this->propiedad->movimientoRegistral->folioReal->replicate();
                    $folioReal->matriz = false;
                    $folioReal->estado = 'captura';
                    $folioReal->folio = (FolioReal::max('folio') ?? 0) + 1;
                    $folioReal->antecedente = $this->propiedad->movimientoRegistral->folioReal->id;
                    $folioReal->creado_por = auth()->id();
                    $folioReal->save();

                    array_push($this->folioIds, $folioReal->id);

                    File::create([
                        'fileable_id' => $folioReal->id,
                        'fileable_type' => 'App\Models\FolioReal',
                        'descripcion' => 'documento_entrada',
                        'url' => $this->propiedad->movimientoRegistral->archivos()->where('descripcion', 'documento_entrada')->first()->url
                    ]);

                    $movimientoRegistral = $this->propiedad->movimientoRegistral->replicate();
                    $movimientoRegistral->movimiento_padre = $this->propiedad->movimientoRegistral->id;
                    $movimientoRegistral->folio = 1;
                    $movimientoRegistral->estado = 'nuevo';
                    $movimientoRegistral->folio_real = $folioReal->id;
                    $movimientoRegistral->usuario_asignado = (new AsignacionService())->obtenerUsuarioPropiedad(null, $this->propiedad->movimientoRegistral->getRawOriginal('distrito'), null);
                    $movimientoRegistral->save();

                    Propiedad::create([
                        'movimiento_registral_id' => $movimientoRegistral->id,
                        'servicio' => $this->propiedad->servicio,
                        'descripcion_acto' => 'Movimiento registral que da origen al Folio Real'
                    ]);

                    $predio = $this->propiedad->movimientoRegistral->folioReal->predio->replicate();
                    $predio->folio_real = $folioReal->id;
                    $predio->creado_por = auth()->id();
                    $predio->save();

                    foreach ($this->propiedad->movimientoRegistral->folioReal->predio->colindancias as $colindancia) {

                        $colindanciaNueva = $colindancia->replicate();
                        $colindanciaNueva->predio_id = $predio->id;
                        $colindanciaNueva->creado_por = auth()->id();
                        $colindanciaNueva->save();

                    }

                    foreach ($this->propiedad->movimientoRegistral->folioReal->predio->propietarios() as $propietario) {

                        $propietarioNuevo = $propietario->replicate();
                        $propietarioNuevo->actorable_id = $predio->id;
                        $propietarioNuevo->creado_por = auth()->id();
                        $propietarioNuevo->save();

                        $transmitente = $propietarioNuevo->replicate();
                        $transmitente->actorable_id = $predio->id;
                        $transmitente->porcentaje_propiedad = null;
                        $transmitente->porcentaje_nuda = null;
                        $transmitente->porcentaje_usufructo = null;
                        $transmitente->tipo_actor = 'transmitente';
                        $transmitente->creado_por = auth()->id();
                        $transmitente->save();

                    }

                }

                $this->propiedad->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->propiedad->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de subdivisión']);

            });

            $this->foliosReales = Folioreal::whereKey($this->folioIds)->with('folioRealAntecedente')->get();

            $this->dispatch('mostrarMensaje', ['success', "Los folios reales se generaron con éxito"]);

            (new SubdivisionesController())->caratula($this->propiedad);

            $pdf = (new SubdivisionesController())->reimprimir($this->propiedad->movimientoRegistral->firmaElectronica);

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al procesar subdivisión por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error"]);

        }

    }

    public function render()
    {
        return view('livewire.inscripciones.propiedad.subdivisiones')->extends('layouts.admin');
    }
}
