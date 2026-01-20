<?php

namespace App\Livewire\PaseFolio\PaseFolioSimplificado;

use App\Models\Predio;
use Livewire\Component;
use App\Models\Escritura;
use App\Models\FolioReal;
use Livewire\Attributes\On;
use App\Models\Propiedadold;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Services\OldBDService;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use Spatie\LivewireFilepond\WithFilePond;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Http\Controllers\PaseFolio\PaseFolioController;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;

class PaseFolioSimplificadoElaborar extends Component
{

    use WithFileUploads;
    use WithFilePond;
    use GuardarDocumentoEntradaTrait;
    use ConsultarArchivoTrait;

    public $distritos;
    public $estados;
    public $actos_contenidos;
    public $cargos_autoridad;
    public $documentos_de_entrada;

    public $tipo_documento;
    public $autoridad_cargo;
    public $autoridad_nombre;
    public $autoridad_numero;
    public $numero_documento;
    public $fecha_emision;
    public $fecha_inscripcion;
    public $procedencia;
    public $escritura_numero;
    public $escritura_fecha_inscripcion;
    public $escritura_fecha_escritura;
    public $escritura_numero_hojas;
    public $escritura_numero_paginas;
    public $escritura_notaria;
    public $escritura_nombre_notario;
    public $escritura_estado_notario;
    public $escritura_observaciones;
    public $acto_contenido_antecedente;
    public $observaciones_antecedente;

    public $editar = false;
    public $crear = false;

    public $escritura;
    public $propiedad;

    public MovimientoRegistral $movimientoRegistral;

    protected function rules(){
        return [
            'tipo_documento' => 'required',
            'autoridad_cargo' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD', 'RESOLUCIÓN JUDICIAL', 'ESCRITURA INSTITUCIONAL'])),
            'autoridad_nombre' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD', 'RESOLUCIÓN JUDICIAL', 'ESCRITURA INSTITUCIONAL'])),
            'autoridad_numero' => 'nullable',
            'numero_documento' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD', 'RESOLUCIÓN JUDICIAL', 'ESCRITURA INSTITUCIONAL'])),
            'fecha_emision' => [Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD', 'RESOLUCIÓN JUDICIAL', 'ESCRITURA INSTITUCIONAL'])), 'nullable', 'date', 'date_format:Y-m-d'],
            'fecha_inscripcion' => [Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD', 'RESOLUCIÓN JUDICIAL', 'ESCRITURA INSTITUCIONAL'])), 'nullable', 'date', 'date_format:Y-m-d'],
            'procedencia' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD', 'RESOLUCIÓN JUDICIAL', 'ESCRITURA INSTITUCIONAL'])),
            'escritura_numero' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_fecha_inscripcion' => [Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])), 'nullable', 'date', 'date_format:Y-m-d'],
            'escritura_fecha_escritura' => [Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])), 'nullable', 'date', 'date_format:Y-m-d'],
            'escritura_numero_hojas' => 'nullable',
            'escritura_numero_paginas' => 'nullable',
            'escritura_notaria' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_nombre_notario' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_estado_notario' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_observaciones' => 'nullable',
            'acto_contenido_antecedente' => 'required',
            'observaciones_antecedente' => 'nullable',
        ];
    }

    protected $validationAttributes  = [
        'tipo_documento' => 'tipo de documento',
        'autoridad_cargo' => 'cargo de la autoridad',
        'autoridad_nombre' => 'nombre de la autoridad',
        'autoridad_numero' => 'número de la autoridad',
        'numero_documento' => 'número del documento',
        'fecha_emision' => 'fecha de emisión',
        'fecha_inscripcion' => 'fecha de inscripción',
        'escritura_fecha_escritura' => 'fecha de la escitura',
        'escritura_fecha_inscripcion' => 'fecha de inscripción',
        'escritura_numero_hojas' => 'número de hojas',
        'escritura_numero_paginas' => 'número de paginas',
        'escritura_notaria' => 'número de notaría',
        'escritura_nombre_notario' => 'nombre del notario',
        'escritura_estado_notario' => 'estado de la notaría',
        'escritura_observaciones' => 'observaciones',
        'acto_contenido_antecedente' => 'acto contenido',
        'observaciones_antecedente' => 'observaciones'
    ];

    protected $messages = [
        'fecha_emision.date_format' => 'El formato de la fecha no es valido.',
        'fecha_inscripcion.date_format' => 'El formato de la fecha no es valido.',
        'escritura_fecha_escritura.date_format' => 'El formato de la fecha no es valido.',
        'escritura_fecha_inscripcion.date_format' => 'El formato de la fecha no es valido.',
    ];

    public function resetAll(){

        $this->reset([
            'autoridad_cargo',
            'autoridad_nombre',
            'autoridad_numero',
            'numero_documento',
            'fecha_emision',
            'fecha_inscripcion',
            'procedencia',
            'escritura_numero',
            'escritura_fecha_inscripcion',
            'escritura_fecha_escritura',
            'escritura_numero_hojas',
            'escritura_numero_paginas',
            'escritura_notaria',
            'escritura_nombre_notario',
            'escritura_estado_notario',
            'escritura_observaciones',
            'acto_contenido_antecedente',
            'observaciones_antecedente'
        ]);

    }

    public function updatedTipoDocumento(){

        $this->resetAll();

    }

    public function generarFolioReal(){

        $folioReal = FolioReal::create([
            'estado' => 'captura',
            'folio' => (FolioReal::max('folio') ?? 0) + 1,
            'tomo_antecedente' => $this->movimientoRegistral->tomo,
            'tomo_antecedente_bis' => $this->movimientoRegistral->tomo_bis,
            'registro_antecedente' => $this->movimientoRegistral->registro,
            'registro_antecedente_bis' => $this->movimientoRegistral->registro_bis,
            'numero_propiedad_antecedente' => $this->movimientoRegistral->numero_propiedad,
            'distrito_antecedente' => $this->movimientoRegistral->getRawOriginal('distrito'),
            'seccion_antecedente' => 'Propiedad',
            'tipo_documento' => $this->tipo_documento,
            'acto_contenido_antecedente' => $this->acto_contenido_antecedente,
            'observaciones_antecedente' => $this->observaciones_antecedente
        ]);

        $this->movimientoRegistral->update(['folio_real' => $folioReal->id]);

        if (in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])){

            $this->escritura = Escritura::where('numero', $this->escritura_numero)
                                    ->where('notaria', $this->escritura_notaria)
                                    ->where('estado_notario', $this->escritura_estado_notario)
                                    ->first();

            if(!$this->escritura){

                $this->escritura = Escritura::create([
                    'numero' => $this->escritura_numero,
                    'fecha_inscripcion' => $this->escritura_fecha_inscripcion,
                    'fecha_escritura' => $this->escritura_fecha_escritura,
                    'numero_hojas' => $this->escritura_numero_hojas,
                    'numero_paginas' => $this->escritura_numero_paginas,
                    'notaria' => $this->escritura_notaria,
                    'nombre_notario' => $this->escritura_nombre_notario,
                    'estado_notario' => $this->escritura_estado_notario,
                    'comentario' => $this->escritura_observaciones,
                    'acto_contenido_antecedente' => $this->acto_contenido_antecedente,
                ]);

            }

            $this->propiedad = Predio::create([
                'escritura_id' => $this->escritura->id,
                'folio_real' => $folioReal->id,
                'status' => 'nuevo'
            ]);

        }else{

            $folioReal->update([
                'autoridad_cargo' => $this->autoridad_cargo,
                'autoridad_nombre' => $this->autoridad_nombre,
                'autoridad_numero' => $this->autoridad_numero,
                'numero_documento' => $this->numero_documento,
                'fecha_emision' => $this->fecha_emision,
                'fecha_inscripcion' => $this->fecha_inscripcion,
                'procedencia' => $this->procedencia,
                'acto_contenido_antecedente' => $this->acto_contenido_antecedente,
                'observaciones_antecedente' => $this->observaciones_antecedente,
            ]);

            $this->propiedad = Predio::create([
                'folio_real' => $folioReal->id,
                'status' => 'nuevo'
            ]);

        }

    }

    public function guardarDocumentoEntrada(){

        $this->authorize('update', $this->movimientoRegistral);

        $this->validate();

        if
        (
            !$this->movimientoRegistral->folio_real&&
            $this->movimientoRegistral->tomo &&
            $this->movimientoRegistral->registro &&
            $this->movimientoRegistral->numero_propiedad
        ){

            $folioRealExistente = FolioReal::where('tomo_antecedente', $this->movimientoRegistral->tomo)
                                            ->where('registro_antecedente', $this->movimientoRegistral->registro)
                                            ->where('numero_propiedad_antecedente', $this->movimientoRegistral->numero_propiedad)
                                            ->where('distrito_antecedente', $this->movimientoRegistral->getRawOriginal('distrito'))
                                            ->where('seccion_antecedente', $this->movimientoRegistral->seccion)
                                            ->first();

            if($folioRealExistente){

                $this->dispatch('mostrarMensaje', ['warning', "Ya existe un folio real con el mismo antecedente."]);

                return;

            }

        }

        try {

            $this->revisarGravamenes();

            DB::transaction(function () {

                if(!$this->movimientoRegistral->folio_real) {

                    $this->generarFolioReal();

                }

                $this->movimientoRegistral->refresh();

                if(!$this->propiedad->escritura_id && in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])){

                    $this->escritura = Escritura::Create([
                        'numero' => $this->escritura_numero,
                        'fecha_inscripcion' => $this->escritura_fecha_inscripcion,
                        'fecha_escritura' => $this->escritura_fecha_escritura,
                        'numero_hojas' => $this->escritura_numero_hojas,
                        'numero_paginas' => $this->escritura_numero_paginas,
                        'notaria' => $this->escritura_notaria,
                        'nombre_notario' => $this->escritura_nombre_notario,
                        'estado_notario' => $this->escritura_estado_notario,
                        'comentario' => $this->escritura_observaciones,
                        'acto_contenido_antecedente' => $this->acto_contenido_antecedente,
                    ]);

                    $this->propiedad->update(['escritura_id' => $this->escritura->id]);

                }elseif($this->propiedad->escritura_id){

                    $this->escritura->update([
                        'numero' => $this->escritura_numero,
                        'fecha_inscripcion' => $this->escritura_fecha_inscripcion,
                        'fecha_escritura' => $this->escritura_fecha_escritura,
                        'numero_hojas' => $this->escritura_numero_hojas,
                        'numero_paginas' => $this->escritura_numero_paginas,
                        'notaria' => $this->escritura_notaria,
                        'nombre_notario' => $this->escritura_nombre_notario,
                        'estado_notario' => $this->escritura_estado_notario,
                        'comentario' => $this->escritura_observaciones,
                        'acto_contenido_antecedente' => $this->acto_contenido_antecedente,
                    ]);

                }

                $this->movimientoRegistral->folioReal->update([
                    'tipo_documento' => $this->tipo_documento,
                    'autoridad_cargo' => $this->autoridad_cargo,
                    'autoridad_nombre' => $this->autoridad_nombre,
                    'autoridad_numero' => $this->autoridad_numero,
                    'numero_documento' => $this->numero_documento,
                    'fecha_emision' => $this->fecha_emision,
                    'fecha_inscripcion' => $this->fecha_inscripcion,
                    'procedencia' => $this->procedencia,
                    'acto_contenido_antecedente' => $this->acto_contenido_antecedente,
                    'observaciones_antecedente' => $this->observaciones_antecedente
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El documento de entrada se guardó con éxito."]);

                $this->dispatch('cargarPropiedad', id: $this->propiedad->id);

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en pase a folio simplificado por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    #[On('finalizarPaseAFolio')]
    public function finalizarPaseAFolio(){

        $this->validate();

        if($this->propiedad) $this->propiedad->refresh();

        $pn = 0;

        $pu = 0;

        $pp = 0;

        foreach($this->propiedad->propietarios() as $propietario){

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        if($pp == 0){

            if($pn < 99.9){

                $this->dispatch('mostrarMensaje', ['warning', "El porcentaje de nuda propiedad no es el 100%."]);

                return;

            }

            if($pu < 99.9){

                $this->dispatch('mostrarMensaje', ['warning', "El porcentaje de usufructo no es el 100%."]);

                return;

            }

        }else{

            if(($pn + $pp) < 99.9){

                $this->dispatch('mostrarMensaje', ['warning', "El porcentaje de nuda propiedad no es el 100%."]);

                return;

            }

            if(($pu + $pp) < 99.9){

                $this->dispatch('mostrarMensaje', ['warning', "El porcentaje de usufructo no es el 100%."]);

                return;

            }

        }

        /* if($this->propiedad->colindancias->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener al menos una colindancia."]);

            return;

        } */

        if(!$this->propiedad->superficie_terreno){

            $this->dispatch('mostrarMensaje', ['warning', "El predio debe tener superficie de terreno."]);

            return;

        }

        if(
            !in_array($this->movimientoRegistral->inscripcionPropiedad?->servicio, ['D114', 'D113', 'D116', 'D115', 'D157']) &&
            $this->movimientoRegistral->tomo != null &&
            $this->movimientoRegistral->registro != null &&
            $this->movimientoRegistral->numero_propiedad != null
        ){

            if(!$this->propiedad->monto_transaccion){

                $this->dispatch('mostrarMensaje', ['warning', "El predio debe tener monto de transacción."]);

                return;

            }

        }

        if(!$this->propiedad->municipio){

            $this->dispatch('mostrarMensaje', ['warning', "El predio debe tener municipio."]);

            return;

        }

        if($this->propiedad->propietarios()->count() == 0){

            $this->dispatch('mostrarMensaje', ['warning', "Debe tener al menos un propietario."]);

            return;

        }

        if($this->propiedad->transmitentes()->count() == 0){

            $this->dispatch('mostrarMensaje', ['warning', "Debe tener al menos un transmitente."]);

            return;

        }

        try {

            DB::transaction(function (){

                $this->movimientoRegistral->folioReal->update([
                    'estado' => 'elaborado',
                    'asignado_por' => auth()->user()->name
                ]);

                if($this->movimientoRegistral->inscripcionPropiedad){

                    $this->revisarInscripcionPropiedad();

                }

                $this->procesarInscripcionPropiedad();

                (new PaseFolioController())->caratula($this->movimientoRegistral->folioReal);

            });

            $this->redirectRoute('pase_folio_simplificado');

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al finalizar folio real en pase a folio simplificado por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function revisarInscripcionPropiedad(){

        if(
            in_array($this->movimientoRegistral->inscripcionPropiedad->servicio, ['D114', 'D113', 'D116', 'D115']) &&
            $this->movimientoRegistral->tomo == null &&
            $this->movimientoRegistral->registro == null &&
            $this->movimientoRegistral->numero_propiedad == null
        ){

            if(!$this->movimientoRegistral->folioReal->documentoEntrada()){

                throw new GeneralException('El documento de entrada es obligatorio');

            }

            $this->movimientoRegistral->folioReal->update([
                'estado' => 'pendiente'
            ]);

        }

    }

    public function procesarInscripcionPropiedad(){

        foreach ($this->propiedad->actores as $actor) {

            $actor_nuevo = $actor->replicate();

            $actor_nuevo->actorable_type = 'App\Models\Propiedad';
            $actor_nuevo->actorable_id = $this->movimientoRegistral->inscripcionPropiedad->id;
            $actor_nuevo->save();

        }

        $this->movimientoRegistral->inscripcionPropiedad->update([
            'acto_contenido' => $this->acto_contenido_antecedente,
            'descripcion_acto' => $this->observaciones_antecedente,
        ]);

        (new PropiedadController())->caratula($this->movimientoRegistral->inscripcionPropiedad);

    }

    public function revisarGravamenes(){

        $propiedad = Propiedadold::where("distrito", $this->movimientoRegistral->getRawOriginal('distrito'))
                                    ->where("tomo", $this->movimientoRegistral->tomo)
                                    ->where("registro", $this->movimientoRegistral->registro)
                                    ->where("noprop", $this->movimientoRegistral->numero_propiedad)
                                    ->first();

        if($propiedad){

            $gravamenes = (new OldBDService())->tractoGravamenes($propiedad->id);

            foreach($gravamenes as $gravamen){

                if(isset($gravamen->fcancelacion) && isset($gravamen->stGravamen) && $gravamen->stGravamen == 'C') continue;

                throw new GeneralException("La propiedad tiene un gravamen vigente.");

            }

        }

    }

    public function mount(){

        $this->consultarArchivo($this->movimientoRegistral);

        $this->distritos = Constantes::DISTRITOS;

        $this->estados = Constantes::ESTADOS;

        $this->actos_contenidos = Constantes::ACTOS_INSCRIPCION_PROPIEDAD;

        $this->actos_contenidos[] = 'FIDEICOMISO';

        $this->actos_contenidos[] = 'AVISO PREVENTIVO';

        $this->cargos_autoridad = Constantes::CARGO_AUTORIDAD;

        $this->documentos_de_entrada = Constantes::DOCUMENTOS_DE_ENTRADA;

        if($this->movimientoRegistral->folioReal){

            /* Documento entrada */
            $this->tipo_documento = $this->movimientoRegistral->folioReal->tipo_documento;
            $this->autoridad_cargo = $this->movimientoRegistral->folioReal->autoridad_cargo;
            $this->autoridad_nombre = $this->movimientoRegistral->folioReal->autoridad_nombre;
            $this->autoridad_numero = $this->movimientoRegistral->folioReal->autoridad_numero;
            $this->numero_documento = $this->movimientoRegistral->folioReal->numero_documento;
            $this->fecha_emision = $this->movimientoRegistral->folioReal->fecha_emision;
            $this->fecha_inscripcion = $this->movimientoRegistral->folioReal->fecha_inscripcion;
            $this->procedencia = $this->movimientoRegistral->folioReal->procedencia;
            $this->acto_contenido_antecedente = $this->movimientoRegistral->folioReal->acto_contenido_antecedente;
            $this->observaciones_antecedente = $this->movimientoRegistral->folioReal->observaciones_antecedente;

            $this->propiedad = Predio::where('folio_real', $this->movimientoRegistral->folio_real)->first();

            if($this->propiedad){

                if($this->propiedad->escritura){

                    $this->escritura = $this->propiedad->escritura;

                    $this->escritura_numero = $this->propiedad->escritura->numero;
                    $this->escritura_fecha_inscripcion = $this->propiedad->escritura->fecha_inscripcion;
                    $this->escritura_fecha_escritura = $this->propiedad->escritura->fecha_escritura;
                    $this->escritura_numero_hojas = $this->propiedad->escritura->numero_hojas;
                    $this->escritura_numero_paginas = $this->propiedad->escritura->numero_paginas;
                    $this->escritura_notaria = $this->propiedad->escritura->notaria;
                    $this->escritura_nombre_notario = $this->propiedad->escritura->nombre_notario;
                    $this->escritura_estado_notario = $this->propiedad->escritura->estado_notario;
                    $this->escritura_observaciones = $this->propiedad->escritura->comentario;
                    $this->acto_contenido_antecedente = $this->propiedad->escritura->acto_contenido_antecedente;

                }

            }

            $this->movimientoRegistral->load('folioReal.antecedentes.folioRealAntecedente');

        }

    }

    public function render()
    {
        return view('livewire.pase-folio.pase-folio-simplificado.pase-folio-simplificado-elaborar')->extends('layouts.admin');
    }
}
