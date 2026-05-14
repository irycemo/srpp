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
use Illuminate\Support\Facades\DB;
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

    public $batchId;
    public $procesando = false;
    public $total;
    public $processed;
    public $progress;
    public $folios_generados;
    public $job_errors;

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

        $this->batchId = (string) Str::uuid();

        $import = new FolioRealImport($this->batchId);

        try {

            Excel::import($import, $this->documento);

            $imports = Import::where('batch_id', $this->batchId)->get();

            if($this->movimientoRegistral->inscripcionPropiedad->numero_inmuebles != $imports->count()){

                $this->eliminarImportRecords($this->batchId);

                throw new GeneralException("El número de propiedades del trámite (" . $this->movimientoRegistral->inscripcionPropiedad->numero_inmuebles . ") no corresponde con el numero de regsitros en el archivo");

            }

            $errores = $imports->where('errores', '!=', null);

            if($errores->count()){

                foreach($errores as $error){

                    $decoded_errors = json_decode($error->errores);

                    foreach($decoded_errors as $decoded){

                        $this->errores [] = $decoded;

                    }


                }

                $this->eliminarImportRecords($this->batchId);

                return;

            }

            DispatchFraccionamientoChain::dispatch($this->batchId, $this->movimientoRegistral->id, auth()->id());

            $this->procesando = true;

            $this->estadisticas();

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.

                $this->dispatch('mostrarMensaje', ['warning', "Error en la fila: " . $failure->row() . " ".$failure->errors()[0] ]);

                break;

            }

            $this->eliminarImportRecords($this->batchId);

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al importar ficha técnica por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error"]);

        }

    }

    public function estadisticas(){

        if($this->procesando){

            $query = DB::table('imports')
                ->where('batch_id', $this->batchId);

            $this->total = (clone $query)->count();

            $this->processed = (clone $query)
                ->where('status', 'processed')
                ->count();

            $this->job_errors = (clone $query)
                ->where('status', 'error')
                ->count();

            $this->progress = $this->total > 0
                ? intval(($this->processed / $this->total) * 100)
                : 0;

            if($this->processed === $this->total){

                $this->procesando = false;

                $this->folios_generados = Import::where('batch_id', $this->batchId)->pluck('folio_real');

                Import::where('batch_id', $this->batchId)->delete();

                $this->finalizarMovimientoRegistral();

            }

        }

    }

    public function finalizarMovimientoRegistral(){

        $this->dispatch('mostrarMensaje', ['success', "Los folios reales se generaron con éxito"]);

        $this->reset('documento');

        $this->propiedad->acto_contenido = 'FRACCIONAMIENTO';
        $this->propiedad->save();

        $this->movimientoRegistral->update(['estado' => 'concluido']);

        $this->movimientoRegistral->FolioReal->update(['estado' => 'inactivo']);

        (new SubdivisionesController())->caratula($this->propiedad);

        (new FolioRealService())->revisarCertificadosGravamenPendientes($this->propiedad->movimientoRegistral);


    }

    public function reimprimir(){

        $pdf = (new SubdivisionesController())->reimprimir($this->propiedad->movimientoRegistral->firmaElectronica);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'documento.pdf'
        );

    }

    public function eliminarImportRecords($batchId){

        Import::where('batch_id', $this->batchId)->delete();

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
