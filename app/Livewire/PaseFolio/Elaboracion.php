<?php

namespace App\Livewire\PaseFolio;

use App\Models\User;
use App\Models\Predio;
use Livewire\Component;
use App\Models\Escritura;
use App\Models\FolioReal;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Livewire\PaseFolio\PaseFolio;
use App\Http\Services\AsignacionService;
use Exception;

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
    public Predio $propiedad;
    public Escritura $escritura;

    public $distritos;
    public $estados;

    protected function rules(){
        return [
            'tipo_documento' => 'required',
            'autoridad_cargo' => Rule::requiredIf($this->tipo_documento === "oficio"),
            'autoridad_nombre' => Rule::requiredIf($this->tipo_documento === "oficio"),
            'autoridad_numero' => Rule::requiredIf($this->tipo_documento === "oficio"),
            'numero_documento' => Rule::requiredIf($this->tipo_documento === "oficio"),
            'fecha_emision' => Rule::requiredIf($this->tipo_documento === "oficio"),
            'fecha_inscripcion' => Rule::requiredIf($this->tipo_documento === "oficio"),
            'procedencia' => Rule::requiredIf($this->tipo_documento === "oficio"),
            'escritura_numero' => Rule::requiredIf($this->tipo_documento === "escritura"),
            'escritura_fecha_inscripcion' => Rule::requiredIf($this->tipo_documento === "escritura"),
            'escritura_fecha_escritura' => Rule::requiredIf($this->tipo_documento === "escritura"),
            'escritura_numero_hojas' => Rule::requiredIf($this->tipo_documento === "escritura"),
            'escritura_numero_paginas' => Rule::requiredIf($this->tipo_documento === "escritura"),
            'escritura_notaria' => Rule::requiredIf($this->tipo_documento === "escritura"),
            'escritura_nombre_notario' => Rule::requiredIf($this->tipo_documento === "escritura"),
            'escritura_estado_notario' => Rule::requiredIf($this->tipo_documento === "escritura"),
            'escritura_observaciones' => 'nullable|' . utf8_encode('regex:/^[áéíóúÁÉÍÓÚñÑa-zA-Z-0-9$#.()\/\-," ]*$/'),
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
        ]);

    }

    public function updatedTipoDocumento(){

        $this->resetAll();

    }

    public function generarFolioReal(){

        $this->authorize('update', $this->movimientoRegistral);

        $this->validate();

        try {

            DB::transaction(function () {

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
                ]);

                $this->movimientoRegistral->update(['folio_real' => $folioReal->id]);

                if ($this->tipo_documento == 'escritura'){

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
                    ]);

                    $this->propiedad = Predio::create([
                        'escritura_id' => $this->escritura->id,
                        'folio_real' => $folioReal->id,
                        'status' => 'nuevo'
                    ]);

                }else{

                    $this->propiedad = Predio::create([
                        'folio_real' => $folioReal->id,
                        'status' => 'nuevo'
                    ]);

                }

            });

        } catch (\Throwable $th) {
            Log::error("Error al generar folio real en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardarDocumentoEntrada(){

        $this->authorize('update', $this->movimientoRegistral);

        $this->validate();

        if(!$this->movimientoRegistral->folio_real){

            $this->generarFolioReal();

            $this->movimientoRegistral->refresh();

        }

        try {

            DB::transaction(function () {

                $this->movimientoRegistral->folioReal->update([
                    'tipo_documento' => $this->tipo_documento,
                    'autoridad_cargo' => $this->autoridad_cargo,
                    'autoridad_nombre' => $this->autoridad_nombre,
                    'autoridad_numero' => $this->autoridad_numero,
                    'numero_documento' => $this->numero_documento,
                    'fecha_emision' => $this->fecha_emision,
                    'fecha_inscripcion' => $this->fecha_inscripcion,
                    'procedencia' => $this->procedencia,
                ]);

                if(!$this->propiedad->escritura_id){

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
                    ]);

                    $this->propiedad->update(['escritura_id' => $this->escritura->id]);

                }else{

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
                    ]);

                }

                $this->dispatch('mostrarMensaje', ['success', "El documento de entrada se guardó con éxito."]);

                $this->dispatch('cargarPropiedad', id: $this->propiedad->id);

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    #[On('finalizarPaseAFolio')]
    public function finalizarPaseAFolio(){

        $this->authorize('update', $this->movimientoRegistral);

        if($this->propiedad)
            $this->propiedad->refresh();

        $pn = 0;

        $pu = 0;

        $pp = 0;

        foreach($this->propiedad->propietarios() as $propietario){

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        if($pp == 0){

            if(($pn - 100) >= 0.01){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de nuda propiedad no es el 100%."]);

                return;

            }

            if(($pu - 100) >= 0.01){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de usufructo no es el 100%."]);

                return;

            }

        }else{


            if(($pn + $pp - 100) >= 0.01){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de nuda propiedad no es el 100%."]);

                return;

            }

            if(($pu + $pp - 100) >= 0.01){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de usufructo no es el 100%."]);

                return;

            }

        }

        if($this->propiedad->colindancias->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener al menos una colindancia."]);

            return;

        }

        if(!$this->propiedad->superficie_terreno){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener superficie de terreno."]);

            return;

        }

        if(!$this->propiedad->superficie_construccion){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener superficie de construcción."]);

            return;

        }

        if(!$this->propiedad->monto_transaccion){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener monto de transacción."]);

            return;

        }

        if(!$this->propiedad->codigo_postal){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tener código postal."]);

            return;

        }

        /* if(!$this->propiedad->nombre_asentamiento){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe nombre de asentamiento."]);

            return;

        } */

        if(!$this->propiedad->municipio){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe municipio."]);

            return;

        }

        if(!$this->propiedad->tipo_asentamiento){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe tipo de asentamiento."]);

            return;

        }

        if(!$this->propiedad->localidad){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe localidad."]);

            return;

        }

        if(!$this->propiedad->nombre_vialidad){

            $this->dispatch('mostrarMensaje', ['error', "El predio debe nombre de vialidad."]);

            return;

        }

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

            $role = null;

            if($this->movimientoRegistral->inscripcionPropiedad){

                $role = 'Propiedad';

            }elseif($this->movimientoRegistral->gravamen){

                $role = 'Gravamen';

            }elseif($this->movimientoRegistral->cancelacion){

                $role = 'Cancelación';

            }elseif($this->movimientoRegistral->sentencia){

                $role = 'Sentencias';

            }

            DB::transaction(function () use ($role){

                $this->movimientoRegistral->folioReal->update([
                    'estado' => 'activo'
                ]);


                if(auth()->user()->hasRole(['Pase a folio', 'Administrador'])){

                    $usuarios = $this->obtenerUsuarios($role);

                    if($usuarios->count() === 0){

                        $this->dispatch('mostrarMensaje', ['error', "No hay usuarios con rol de " . $role . " disponibles."]);

                        throw new Exception("No hay usuarios con rol de " . $role . " disponibles.");

                    }

                    $id = (new AsignacionService())->obtenerUltimoUsuarioConAsignacion($usuarios);

                    $this->movimientoRegistral->update(['usuario_asignado' => $id]);

                }

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

    public function obtenerUsuarios($role){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->movimientoRegistral->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->movimientoRegistral->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q) use ($role){
                                $q->where('name', $role);
                            })
                            ->get();
    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

        $this->estados = Constantes::ESTADOS;

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

                }

            }

        }else{

            $this->propiedad = Predio::make();

        }

    }

    public function render()
    {

        $this->authorize('view', $this->movimientoRegistral);

        return view('livewire.pase-folio.elaboracion')->extends('layouts.admin');
    }
}
