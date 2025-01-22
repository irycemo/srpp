<?php

namespace App\Livewire\Inscripciones\Propiedad;

use Exception;
use App\Models\File;
use Livewire\Component;
use App\Models\Propiedad;
use App\Constantes\Constantes;
use App\Http\Controllers\Subdivisiones\SubdivisionesController;
use App\Imports\FolioRealImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LivewireFilepond\WithFilePond;

class Fraccionamientos extends Component
{

    use WithFilePond;

    public Propiedad $propiedad;

    public $documento;
    public $documento_entrada;

    public $data;

    public $vientos;

    public $modalDocumento = false;

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

            });

            $this->modalDocumento = false;

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en subdivisiones por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function descargarFicha(){

        return response()->download(storage_path('app/public/ficha_tecnica_subdivision.xlsx'));

    }

    public function procesar(){

        if(!$this->propiedad->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['warning', "Debe subir el documento de entrada primero."]);

            return;

        }

        $this->validate([
            'documento' => 'required'
        ]);

        $import = new FolioRealImport($this->propiedad->movimientoRegistral);

        try {

            Excel::import($import, $this->documento);

            $this->data = $import->data;

            $this->dispatch('mostrarMensaje', ['success', "Los folios reales se generaron con éxito"]);

            $this->reset('documento');

            (new SubdivisionesController())->caratula($this->propiedad);

            $pdf = (new SubdivisionesController())->reimprimir($this->propiedad->movimientoRegistral->firmaElectronica);

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.

                $this->dispatch('mostrarMensaje', ['error', "Error en la fila: " . $failure->row() . " ".$failure->errors()[0] ]);

                break;

            }

        } catch (Exception $th) {

            Log::error("Error al importar ficha técnica por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al importar ficha técnica por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error"]);

        }

    }

    public function mount(){

        $this->vientos = Constantes::VIENTOS;

    }

    public function render()
    {
        return view('livewire.inscripciones.propiedad.fraccionamientos')->extends('layouts.admin');
    }
}
