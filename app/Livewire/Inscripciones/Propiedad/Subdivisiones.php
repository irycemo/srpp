<?php

namespace App\Livewire\Inscripciones\Propiedad;

use App\Models\File;
use Livewire\Component;
use App\Models\Gravamen;
use App\Models\Escritura;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\LivewireFilepond\WithFilePond;
use App\Traits\Inscripciones\ColindanciasTrait;
use App\Http\Controllers\Subdivisiones\SubdivisionesController;

class Subdivisiones extends Component
{

    use WithFilePond;
    use ColindanciasTrait;

    public Propiedad $propiedad;

    public $documento;
    public $documento_entrada;

    public $modalDocumento = false;
    public $modalContraseña = false;

    public $folioIds = [];
    public $foliosReales = [];

    public $actos;

    public $gravamenes;
    public $escritura;

    protected function rules(){
        return [
            'propiedad.acto_contenido' => 'required',
            'propiedad.descripcion_acto' => 'required',
            'propiedad.superficie_terreno' => [
                'nullable',
                'numeric',
                Rule::requiredIf($this->propiedad->acto_contenido == 'SUBDIVISIÓN CON RESTO'),
                'lt:' . $this->propiedad->movimientoRegistral->folioReal->predio->superficie_terreno,
                'gt:0'
            ],
         ];
    }

    protected function validationAttributes()
    {
        return $this->validationAttributesColindancias;
    }

    public function updatedPropiedadActoContenido(){

        if($this->propiedad->acto_contenido == 'SUBDIVISIÓN CON RESTO'){

            $this->cargarColindancias($this->propiedad->movimientoRegistral->folioReal->predio);

        }

    }

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

    public function finalizar(){

        $this->validate();

        if(!$this->propiedad->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        $this->modalContraseña = true;

    }

    public function inscribir(){

        try {

            DB::transaction(function (){

                $this->gravamenes = Gravamen::with('actores', 'movimientoRegistral')->whereHas('movimientoRegistral', function($q){ $q->where('folio_real', $this->propiedad->movimientoRegistral->folioReal->id); })->get();

                if($this->propiedad->acto_contenido != 'SUBDIVISIÓN CON RESTO'){

                    $this->propiedad->movimientoRegistral->folioReal->predio->update(['superficie_terreno' => 0]);

                    $this->propiedad->movimientoRegistral->folioReal->update(['estado' => 'inactivo']);

                    $cantidad = $this->propiedad->numero_inmuebles;

                    $this->propiedad->superficie_terreno = 0;

                }else{

                    $this->propiedad->movimientoRegistral->folioReal->predio->update(['superficie_terreno' => $this->propiedad->superficie_terreno]);

                    $this->guardarColindancias($this->propiedad->movimientoRegistral->folioReal->predio);

                    $cantidad = $this->propiedad->numero_inmuebles - 1;

                }

                for ($i=0; $i < $cantidad; $i++) {

                    if(in_array($this->propiedad->movimientoRegistral->tipo_documento, ['ESCRITURA PÚBLICA','ESCRITURA PRIVADA'])){

                        $this->crearEscritura();

                        $folioReal = FolioReal::Create([
                            'matriz' => false,
                            'estado' => 'captura',
                            'distrito_antecedente' => $this->propiedad->movimientoRegistral->getOriginal('distrito'),
                            'folio' => (FolioReal::max('folio') ?? 0) + 1,
                            'antecedente' => $this->propiedad->movimientoRegistral->folioReal->id,
                            'seccion_antecedente' => 'Propiedad',
                            'tipo_documento' => $this->propiedad->movimientoRegistral->tipo_documento,
                            'creado_por' => auth()->id(),
                        ]);

                    }else{

                        $folioReal = FolioReal::Create([
                            'matriz' => false,
                            'estado' => 'captura',
                            'folio' => (FolioReal::max('folio') ?? 0) + 1,
                            'antecedente' => $this->propiedad->movimientoRegistral->folioReal->id,
                            'seccion_antecedente' => 'Propiedad',
                            'distrito_antecedente' => $this->propiedad->movimientoRegistral->getOriginal('distrito_antecedente'),
                            'tipo_documento' => $this->propiedad->movimientoRegistral->tipo_documento,
                            'numero_documento' => $this->propiedad->movimientoRegistral->numero_documento,
                            'autoridad_cargo' => $this->propiedad->movimientoRegistral->autoridad_cargo,
                            'autoridad_nombre' => $this->propiedad->movimientoRegistral->autoridad_nombre,
                            'autoridad_numero' => $this->propiedad->movimientoRegistral->autoridad_numero,
                            'fecha_emision' => $this->propiedad->movimientoRegistral->fecha_emision,
                            'fecha_inscripcion' => $this->propiedad->movimientoRegistral->fecha_inscripcion,
                            'procedencia' => $this->propiedad->movimientoRegistral->procedencia,
                            'creado_por' => auth()->id(),
                        ]);

                    }


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
                    $movimientoRegistral->usuario_asignado = auth()->id();
                    $movimientoRegistral->save();

                    Propiedad::create([
                        'movimiento_registral_id' => $movimientoRegistral->id,
                        'servicio' => $this->propiedad->servicio,
                        'descripcion_acto' => 'Movimiento registral que da origen al Folio Real'
                    ]);

                    $predio = $this->propiedad->movimientoRegistral->folioReal->predio->replicate();
                    $predio->superficie_terreno = null;
                    $predio->superficie_construccion = null;
                    $predio->superficie_notarial = null;
                    $predio->superficie_judicial = null;
                    $predio->area_comun_terreno = null;
                    $predio->area_comun_construccion = null;
                    $predio->valor_terreno_comun = null;
                    $predio->valor_construccion_comun = null;
                    $predio->valor_total_terreno = null;
                    $predio->valor_total_construccion = null;
                    $predio->valor_catastral = null;
                    $predio->monto_transaccion = null;
                    $predio->folio_real = $folioReal->id;
                    $predio->creado_por = auth()->id();
                    $predio->escritura_id = $this->escritura?->id;
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

                    $this->generarGravamenes($folioReal);

                }

                $this->propiedad->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->propiedad->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de subdivisión']);

                (new SubdivisionesController())->caratula($this->propiedad);

            });

            $this->foliosReales = Folioreal::whereKey($this->folioIds)->with('folioRealAntecedente')->get();

            $this->dispatch('mostrarMensaje', ['success', "Los folios reales se generaron con éxito"]);

            $pdf = (new SubdivisionesController())->reimprimir($this->propiedad->movimientoRegistral->firmaElectronica);

            $this->modalContraseña = false;

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al procesar subdivisión por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error"]);

        }

    }

    public function generarGravamenes(FolioReal $folioReal){

        foreach ($this->gravamenes as $gravamen) {

            if($gravamen->estado == 'activo'){

                $movimientoRegistral = $this->propiedad->movimientoRegistral->replicate();
                $movimientoRegistral->folio = $folioReal->ultimoFolio() + 1;
                $movimientoRegistral->estado = 'concluido';
                $movimientoRegistral->folio_real = $folioReal->id;
                $movimientoRegistral->save();

                $gravamenCopia = $gravamen->replicate();
                $gravamenCopia->observaciones = $gravamen->observaciones . ' GRAVAMEN GENERADO POR ANTECEDENTE DEL FOLIO REAL: ' . $this->propiedad->movimientoRegistral->folioReal->folio . '-' . $gravamen->movimientoRegistral->folio;
                $gravamenCopia->movimiento_registral_id = $movimientoRegistral->id;
                $gravamenCopia->save();

                foreach($gravamen->actores as $actor){

                    $actor->replicate();
                    $actor->actorable_id = $gravamenCopia->id;
                    $actor->save();

                }

            }

        }

    }

    public function crearEscritura(){

        $this->escritura = Escritura::create([
            'numero' => $this->propiedad->movimientoRegistral->numero_documento,
            'fecha_inscripcion' => $this->propiedad->movimientoRegistral->fecha_inscripcion,
            'fecha_escritura' => $this->propiedad->movimientoRegistral->fecha_emision,
            'notaria' => $this->propiedad->movimientoRegistral->autoridad_numero,
            'nombre_notario' => $this->propiedad->movimientoRegistral->autoridad_nombre,
        ]);

    }

    public function mount(){

        $this->actos = Constantes::ACTOS_SUBDIVISIONES;

        $this->vientos = Constantes::VIENTOS;

    }

    public function render()
    {
        return view('livewire.inscripciones.propiedad.subdivisiones')->extends('layouts.admin');
    }
}
