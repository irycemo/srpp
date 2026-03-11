<?php

namespace App\Livewire\Inscripciones\Propiedad;

use Exception;
use Livewire\Component;
use App\Models\Propiedad;
use App\Constantes\Constantes;
use App\Imports\FolioRealImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LivewireFilepond\WithFilePond;
use App\Http\Controllers\Subdivisiones\SubdivisionesController;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;

class Fraccionamientos extends Component
{

    use WithFilePond;
    use GuardarDocumentoEntradaTrait;

    public Propiedad $propiedad;
    public $movimientoRegistral;

    public $data;

    public $documento;

    public $vientos;

    protected function rules(){
        return [
            'documento_entrada_pdf' => 'nullable|mimes:pdf|max:100000',
            'propiedad.descripcion_acto' => 'required'
        ];
    }

    public function descargarFicha(){

        return response()->download(storage_path('app/public/ficha_tecnica_subdivision.xlsx'));

    }

    public function procesar(){

        $this->validate();

        if(!$this->propiedad->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['warning', "Debe subir el documento de entrada primero."]);

            return;

        }

        $import = new FolioRealImport($this->propiedad->movimientoRegistral);

        try {

            set_time_limit(300);

            Excel::import($import, $this->documento);

            $this->data = $import->data;

            $this->dispatch('mostrarMensaje', ['success', "Los folios reales se generaron con éxito"]);

            $this->reset('documento');

            $this->propiedad->acto_contenido = 'FRACCIONAMIENTO';
            $this->propiedad->save();

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

        $this->movimientoRegistral = $this->propiedad->movimientoRegistral;

    }

    public function render()
    {
        return view('livewire.inscripciones.propiedad.fraccionamientos')->extends('layouts.admin');
    }

}
