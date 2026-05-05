<?php

namespace App\Livewire\Inscripciones\Propiedad;

use App\Constantes\Constantes;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Subdivisiones\SubdivisionesController;
use App\Http\Services\FolioRealService;
use App\Imports\FolioRealImport;
use App\Jobs\Fraccionamientos\DispatchFraccionamientoChain;
use App\Models\Import;
use App\Models\Propiedad;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LivewireFilepond\WithFilePond;

class Fraccionamientos extends Component
{

    use WithFilePond;
    use GuardarDocumentoEntradaTrait;

    public Propiedad $propiedad;
    public $movimientoRegistral;

    public $vientos;
    public $documento;
    public $errores = [];

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

        $this->reset('errores');

        $this->validate();

        if(!$this->propiedad->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['warning', "Debe subir el documento de entrada primero."]);

            return;

        }

        $batchId = (string) Str::uuid();

        $import = new FolioRealImport($batchId);

        try {

            Excel::import($import, $this->documento);

            $imports = Import::where('batch_id', $batchId)->get();

            if($this->movimientoRegistral->inscripcionPropiedad->numero_inmuebles != $imports->count()){

                $this->eliminarImportRecords($batchId);

                throw new GeneralException("El número de propiedades del trámite (" . $this->movimientoRegistral->inscripcionPropiedad->numero_inmuebles . ") no corresponde con el numero de regsitros en el archivo");

            }

            $errores = $imports->whereNotNull('errors')->get();

            if(! $errores->count()){

                DispatchFraccionamientoChain::dispatch($batchId, $this->movimientoRegistral->id);

            }else{

                foreach($errores as $error){

                    $decoded_errors = json_decode($error->errores);

                    foreach($decoded_errors as $decoded){

                        $this->errores [] = $decoded;

                    }


                }

                $this->eliminarImportRecords($batchId);

            }

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

            $this->eliminarImportRecords($batchId);

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al importar ficha técnica por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error"]);

        }

    }

    public function finalizarMovimientoRegistral(){

        $this->dispatch('mostrarMensaje', ['success', "Los folios reales se generaron con éxito"]);

        $this->reset('documento');

        $this->propiedad->acto_contenido = 'FRACCIONAMIENTO';
        $this->propiedad->save();

        (new SubdivisionesController())->caratula($this->propiedad);

        (new FolioRealService())->revisarCertificadosGravamenPendientes($this->propiedad->movimientoRegistral);

        $pdf = (new SubdivisionesController())->reimprimir($this->propiedad->movimientoRegistral->firmaElectronica);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'documento.pdf'
        );

    }

    public function eliminarImportRecords($batchId){

        Import::where('batch_id', $batchId)->delete();

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
