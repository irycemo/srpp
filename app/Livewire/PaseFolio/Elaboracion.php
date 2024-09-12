<?php

namespace App\Livewire\PaseFolio;

use Exception;
use Carbon\Carbon;
use App\Models\Predio;
use Livewire\Component;
use App\Models\Gravamen;
use App\Models\Escritura;
use App\Models\FolioReal;
use App\Models\Antecedente;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Livewire\PaseFolio\PaseFolio;
use App\Http\Services\AsignacionService;
use App\Http\Services\SistemaTramitesService;
use App\Models\Propiedadold;

class Elaboracion extends Component
{

    /* Documento entrada */
    public $tipo_documento;
    public $autoridad_cargo;
    public $autoridad_nombre;
    public $autoridad_numero;
    public $numero_documento;
    public $fecha_emision;
    public $fecha_inscripcion;
    public $procedencia;
    public $acto_contenido_antecedente;
    public $observaciones_antecedente;

    /* Escritura */
    public $escritura_numero;
    public $escritura_fecha_inscripcion;
    public $escritura_fecha_escritura;
    public $escritura_numero_hojas;
    public $escritura_numero_paginas;
    public $escritura_notaria;
    public $escritura_nombre_notario;
    public $escritura_estado_notario;
    public $escritura_observaciones;

    public MovimientoRegistral $movimientoRegistral;
    public $propiedad;
    public $escritura;

    public $distritos;
    public $estados;

    public $tomo;
    public $registro;
    public $numero_propiedad;
    public $modal = false;
    public $editar = false;
    public $crear = false;
    public $antecedente;
    public $documentos_de_entrada;
    public $actos_contenidos;

    public $propiedadOld;

    protected function rules(){
        return [
            'tipo_documento' => 'required',
            'autoridad_cargo' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD'])),
            'autoridad_nombre' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD'])),
            'autoridad_numero' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD'])),
            'numero_documento' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD'])),
            'fecha_emision' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD'])),
            'fecha_inscripcion' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD'])),
            'procedencia' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD'])),
            'escritura_numero' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_fecha_inscripcion' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_fecha_escritura' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_numero_hojas' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_numero_paginas' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_notaria' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_nombre_notario' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_estado_notario' => Rule::requiredIf(in_array($this->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])),
            'escritura_observaciones' => 'nullable',
            'acto_contenido_antecedente' => Rule::requiredIf(in_array($this->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD'])),
            'observaciones_antecedente' => 'nullable'
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
        'escritura_numero_hojas' => 'número de hojas',
        'escritura_numero_paginas' => 'número de paginas',
        'escritura_notaria' => 'número de notaría',
        'escritura_nombre_notario' => 'nombre del notario',
        'escritura_estado_notario' => 'estado de la notaría',
        'escritura_observaciones' => 'observaciones',
        'acto_contenido_antecedente' => 'acto contenido',
        'observaciones_antecedente' => 'observaciones'
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
            'seccion_antecedente' => $this->movimientoRegistral->seccion,
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
                'observaciones_antecedente' => $this->observaciones_antecedente
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

                $this->dispatch('mostrarMensaje', ['error', "Ya existe un folio real con el mismo antecedente."]);

                return;

            }

        }

        try {

            DB::transaction(function () {

                if(!$this->movimientoRegistral->folio_real) {

                    $this->generarFolioReal();

                    $this->movimientoRegistral->refresh();

                    if(
                        $this->movimientoRegistral->tomo &&
                        $this->movimientoRegistral->registro &&
                        $this->movimientoRegistral->numero_propiedad
                    ){

                        $gravamenes = DB::connection('mysql2')->select("call spTractoGravamenes(" .
                                                                            $this->movimientoRegistral->getRawOriginal('distrito') .
                                                                            "," . $this->movimientoRegistral->tomo .
                                                                            "," . ($this->movimientoRegistral->tomo_bis ?? '\'\'') .
                                                                            "," . $this->movimientoRegistral->registro .
                                                                            "," . ($this->movimientoRegistral->registro_bis ?? '\'\'') .
                                                                            "," . $this->movimientoRegistral->numero_propiedad .
                                                                            ")");

                        foreach($gravamenes as $gravamen){

                            if(isset($gravamen->fcancelacion) && isset($gravamen->stGravamen) && $gravamen->stGravamen == 'C') continue;

                            $this->creargravamen($gravamen);

                        }

                    }

                }

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

            $this->dispatch('cargarGravamenes');

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function creargravamen($gravamen){

        $movimientoRegistralGravamenNuevo = MovimientoRegistral::create([
            'registro_gravamen' => $gravamen->registrog ? ltrim($gravamen->registrog, '0') : null,
            'tomo_gravamen' => $gravamen->tomog ? ltrim($gravamen->tomog, '0') : null,
            'seccion' => 'Gravamen',
            'folio_real' => $this->movimientoRegistral->folio_real,
            'folio' => $this->movimientoRegistral->folioReal->ultimoFolio() + 1,
            'distrito' => $this->movimientoRegistral->getRawOriginal('distrito'),
            'estado' => 'concluido',
            'usuario_asignado' => auth()->id(),
        ]);

        $monto = null;

        if($gravamen->monto){

            $monto = $gravamen->monto / 100;

        }

        Gravamen::create([
            'movimiento_registral_id' => $movimientoRegistralGravamenNuevo->id,
            'fecha_inscripcion' => $gravamen->finscripcion ? Carbon::createFromFormat('Y-m-d', $gravamen->finscripcion)->toDateString() : null,
            'estado' => 'activo',
            'acto_contenido' => $gravamen->descGravamen ?? null,
            'valor_gravamen' => $monto,
            'observaciones' => "Gravamen ingresado mediante pase a folio: | Tomo gravamen:" . $gravamen->tomog .
                                " | Tomo propiedad: " .  $gravamen->tomp .
                                " | Registro propiedad: " .  $gravamen->registrop .
                                " | Número de propiedad: " .  $gravamen->noprop .
                                " | Registro gravamen: " . $gravamen->registrog . "/" . $gravamen->rbisg .
                                " | Divisa:" . $gravamen->tmoneda .
                                " | Monto de la transacción:" . $monto .
                                " | Acto contenido:" . $gravamen->descGravamen .
                                " | Fecha de inscripción:" . $gravamen->finscripcion .
                                " | Tipo de deudor:" . $gravamen->stDeudor .
                                " | Acreedores:" . $gravamen->acreedores .
                                " | Deudores:" . $gravamen->deudores .
                                " | Garantes:" . $gravamen->garantes .
                                " | Comentarios:" . $gravamen->comentarios
        ]);

    }

    #[On('finalizarPaseAFolio')]
    public function finalizarPaseAFolio(){

        $this->authorize('update', $this->movimientoRegistral);

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

            if($pn <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de nuda propiedad no es el 100%."]);

                return;

            }

            if($pu <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de usufructo no es el 100%."]);

                return;

            }

        }else{


            if(($pn + $pp) <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de nuda propiedad no es el 100%."]);

                return;

            }

            if(($pu + $pp) <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de usufructo no es el 100%."]);

                return;

            }

        }

        /* if($this->propiedad->colindancias->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener al menos una colindancia."]);

            return;

        } */

        if(!$this->propiedad->superficie_terreno){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener superficie de terreno."]);

            return;

        }

        /* if(!$this->propiedad->superficie_construccion){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener superficie de construcción."]);

            return;

        } */

        if(!$this->propiedad->monto_transaccion){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener monto de transacción."]);

            return;

        }

        /* if(!$this->propiedad->codigo_postal){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener código postal."]);

            return;

        } */

        /* if(!$this->propiedad->nombre_asentamiento){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe nombre de asentamiento."]);

            return;

        } */

        if(!$this->propiedad->municipio){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener municipio."]);

            return;

        }

        if(!$this->propiedad->tipo_asentamiento){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener tipo de asentamiento."]);

            return;

        }

        /* if(!$this->propiedad->localidad){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe localidad."]);

            return;

        } */

        /* if(!$this->propiedad->nombre_vialidad){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe nombre de vialidad."]);

            return;

        } */

        /* if(!$this->propiedad->numero_exterior){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe nombre de numero_exterior."]);

            return;

        } */

        if($this->propiedad->propietarios()->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe tener almenos un propietario."]);

            return;

        }

        if($this->propiedad->transmitentes()->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe tener almenos un transmitente."]);

            return;

        }

        try {

            DB::transaction(function (){

                $this->procesarMovimientos();

                if($this->movimientoRegistral->inscripcionPropiedad){

                    $this->revisarInscripcionPropiedad();

                }elseif($this->movimientoRegistral->cancelacion){

                    $this->revisarCancelaciones();

                }

                $this->movimientoRegistral->folioReal->update(['estado' => 'elaborado']);

                $this->dispatch('imprimir_documento', ['documento' => $this->movimientoRegistral->folio_real]);

                $this->redirect(PaseFolio::class);

            });

        } catch (\Exception $th) {

            Log::error("Error al finalizar folio real en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al finalizar folio real en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function abrirModalCrear(){

        $this->reset(['tomo', 'registro', 'numero_propiedad']);

        $this->modal = true;

        $this->crear = true;

    }

    public function abrirModalEditar(Antecedente $antecedente){

        $this->antecedente = $antecedente;

        $this->tomo = $antecedente->tomo_antecedente;

        $this->registro = $antecedente->registro_antecedente;

        $this->numero_propiedad = $antecedente->numero_propiedad_antecedente;

        $this->modal = true;

        $this->editar = true;

    }

    public function actualizarAntecedente(){

        $this->validate(
            [
                'tomo' => 'required',
                'registro' => 'required',
                'numero_propiedad' => 'required'
            ]
        );

        try {

            $this->antecedente->tomo_antecedente = $this->tomo;
            $this->antecedente->registro_antecedente = $this->registro;
            $this->antecedente->numero_propiedad_antecedente = $this->numero_propiedad;
            $this->antecedente->save();

            $this->movimientoRegistral->load('folioReal.antecedentes');

            $this->dispatch('mostrarMensaje', ['success', "El antecedente se actualizó con éxito."]);

            $this->modal = false;

        } catch (\Throwable $th) {
            Log::error("Error al crear antecedente en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardarAntecedente(){

        $this->validate(
            [
                'tomo' => 'required',
                'registro' => 'required',
                'numero_propiedad' => 'required'
            ]
        );

        if($this->movimientoRegistral->inscripcionPropiedad?->servicio == 'D731' && $this->movimientoRegistral->inscripcionPropiedad?->numero_inmuebles == $this->movimientoRegistral->folioReal->antecedentes->count()){

            $this->dispatch('mostrarMensaje', ['warning', "No puede agregar mas antecedentes a fusionar."]);

            return;

        }

        $antecedente = Antecedente::where('tomo_antecedente', $this->tomo)
                                    ->where('registro_antecedente', $this->registro)
                                    ->where('numero_propiedad_antecedente', $this->numero_propiedad)
                                    ->where('distrito_antecedente', $this->movimientoRegistral->getRawOriginal('distrito'))
                                    ->where('seccion_antecedente', $this->movimientoRegistral->seccion)
                                    ->where('folio_real', $this->movimientoRegistral->folio_real)
                                    ->first();

        if($antecedente){

            $this->dispatch('mostrarMensaje', ['warning', "El antecedente ya se encuentra en la lista."]);

            return;

        }

        $folioReal = FolioReal::where('tomo_antecedente', $this->tomo)
                                    ->where('registro_antecedente', $this->registro)
                                    ->where('numero_propiedad_antecedente', $this->numero_propiedad)
                                    ->where('distrito_antecedente', $this->movimientoRegistral->getRawOriginal('distrito'))
                                    ->where('seccion_antecedente', $this->movimientoRegistral->seccion)
                                    ->first();

        if($folioReal){

            $this->dispatch('mostrarMensaje', ['warning', "El antecedente ya tiene folio real."]);

            return;

        }

        try {

            Antecedente::create([
                'tomo_antecedente' => $this->tomo,
                'registro_antecedente' => $this->registro,
                'numero_propiedad_antecedente' => $this->numero_propiedad,
                'distrito_antecedente' => $this->movimientoRegistral->getRawOriginal('distrito'),
                'seccion_antecedente' => $this->movimientoRegistral->seccion,
                'folio_real' => $this->movimientoRegistral->folio_real,
            ]);

            $this->movimientoRegistral->load('folioReal.antecedentes');

            $this->dispatch('mostrarMensaje', ['success', "El antecedente se eliminó con éxito."]);

            $this->modal = false;

        } catch (\Throwable $th) {
            Log::error("Error al crear antecedente en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function borrarAntecedente(Antecedente $antecedente){

        $this->authorize('update', $this->movimientoRegistral);

        try {

            $antecedente->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->movimientoRegistral->load('folioReal.antecedentes');

        } catch (\Throwable $th) {

            Log::error("Error al borrar antecedente en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function procesarMovimientos(){

        $this->movimientoRegistral->folioReal->load('gravamenes.movimientoRegistral', 'sentencias.movimientoRegistral', 'varios.movimientoRegistral');

        if($this->movimientoRegistral->folioReal->gravamenes->count()){

            foreach($this->movimientoRegistral->folioReal->gravamenes as $gravamen){

                if($gravamen->movimientoRegistral->folio == 1) continue;

                $gravamen->movimientoRegistral->update([
                    'usuario_supervisor' => (new AsignacionService())->obtenerSupervisorGravamen($this->movimientoRegistral->getRawOriginal('distrito')),
                    'estado' => 'concluido'
                ]);

                if(!$gravamen->acreedores()->count()){

                    throw new Exception("Debe finalizar los gravamenes.");

                }

            }

        }

        if($this->movimientoRegistral->folioReal->sentencias->count()){

            foreach($this->movimientoRegistral->folioReal->sentencias as $sentencia){

                if($sentencia->movimientoRegistral->folio == 1) continue;

                $sentencia->movimientoRegistral->update([
                    'usuario_supervisor' => (new AsignacionService())->obtenerSupervisorGravamen($this->movimientoRegistral->getRawOriginal('distrito')),
                    'estado' => 'concluido'
                ]);

                if(!$sentencia->acto_contenido){

                    throw new Exception("Debe finalizar las sentencias.");

                }

            }

        }

        if($this->movimientoRegistral->folioReal->varios->count()){

            foreach($this->movimientoRegistral->folioReal->varios as $vario){

                if($vario->movimientoRegistral->folio == 1) continue;

                $vario->movimientoRegistral->update([
                    'usuario_supervisor' => (new AsignacionService())->obtenerSupervisorGravamen($this->movimientoRegistral->getRawOriginal('distrito')),
                    'estado' => 'concluido'
                ]);

                if(!$vario->acto_contenido){

                    throw new Exception("Debe finalizar los varios.");

                }

            }

        }

    }

    public function revisarCancelaciones(){

        $cancelacion = $this->movimientoRegistral->folioReal->movimientosRegistrales->where('tomo_gravamen', $this->movimientoRegistral->tomo_gravamen)
                                                                                        ->where('registro_gravamen', $this->movimientoRegistral->registro_gravamen)
                                                                                        ->where('folio', '>', 1)
                                                                                        ->first();

        if(!$cancelacion)
            (new SistemaTramitesService())->rechazarTramite($this->movimientoRegistral->año, $this->movimientoRegistral->tramite, $this->movimientoRegistral->usuario, 'Se rechaza en pase a folio debido a que el folio real no tiene gravamenes con la información ingresada.');

        $this->movimientoRegistral->update(['estado' => 'rechazado']);

    }

    public function revisarInscripcionPropiedad(){

        if(
            in_array($this->movimientoRegistral->inscripcionPropiedad->servicio, ['D114', 'D113', 'D116', 'D115', 'D731']) &&
            $this->movimientoRegistral->tomo == null &&
            $this->movimientoRegistral->registro == null &&
            $this->movimientoRegistral->numero_propiedad == null
        ){

            $this->movimientoRegistral->update(['estado' => 'concluido']);

            (new SistemaTramitesService())->finaliarTramite($this->movimientoRegistral->año, $this->movimientoRegistral->tramite, $this->movimientoRegistral->usuario, 'concluido');

        }

    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

        $this->estados = Constantes::ESTADOS;

        $this->actos_contenidos = Constantes::ACTOS_INSCRIPCION_PROPIEDAD;

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

        }else{

            $this->propiedad = Predio::make();

        }

        $this->documentos_de_entrada = Constantes::DOCUMENTOS_DE_ENTRADA;

        $this->propiedadOld = Propiedadold::where('distrito', $this->movimientoRegistral->getRawOriginal('distrito'))
                                                ->where('tomo', $this->movimientoRegistral->tomo)
                                                ->where('registro', $this->movimientoRegistral->registro)
                                                ->where('noprop', $this->movimientoRegistral->numero_propiedad)
                                                ->first();

    }

    public function render()
    {
        $this->authorize('view', $this->movimientoRegistral);

        if($this->movimientoRegistral->folioReal)
            $this->authorize('view', $this->movimientoRegistral->folioReal);

        return view('livewire.pase-folio.elaboracion')->extends('layouts.admin');
    }
}
