<?php

namespace App\Livewire\PaseFolio;

use Livewire\Component;
use App\Models\Sentencia;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

class Sentencias extends Component
{

    public MovimientoRegistral $movimientoRegistral;
    public Sentencia $sentencia;

    public $distritos = [];
    public $actos;
    public $distritoMovimineto;

    public $antecedente = true;
    public $documento_entrada = false;
    public $datos_sentencia = false;

    public $modal = false;
    public $crear = false;
    public $editar = false;
    public $modalBorrar = false;
    public $selected_id;

    public $antecente_tomo;
    public $antecente_registro;
    public $antecente_distrito;

    public $tipo_documento;
    public $autoridad_cargo;
    public $autoridad_nombre;
    public $numero_documento;
    public $fecha_emision;
    public $procedencia;
    public $hojas;
    public $expediente;
    public $fecha_inscripcion;

    public $acto_contenido;
    public $estado = 'activo';
    public $comentario;

    public $propiedadOld;

    public $label_numero_documento = "Número de documento";

    protected $validationAttributes  = [
        'movimientoRegistral.distrito' => 'distrito de propiedad',
        'antecente_tomo' => 'tomo',
        'antecente_registro' => 'registro',
        'antecente_distrito' => 'distrito',
        'tipo_documento' => 'tipo de documento',
        'autoridad_cargo' => 'cargo de la autoridad',
        'autoridad_nombre' => 'nombre de la autoridad',
        'numero_documento' => 'número del documento',
    ];

    public function resetear(){

        $this->reset([
            'antecedente',
            'documento_entrada',
            'datos_sentencia',
            'modalBorrar',
            'modal',
            'crear',
            'editar',
            'antecente_tomo',
            'antecente_registro',
            'antecente_distrito',
            'tipo_documento',
            'autoridad_cargo',
            'autoridad_nombre',
            'numero_documento',
            'fecha_emision',
            'procedencia',
            'acto_contenido',
            'comentario',
            'label_numero_documento',
            'selected_id',
            'hojas',
            'expediente',
            'fecha_inscripcion'
        ]);

        $this->sentencia = Sentencia::make();

    }

    public function agregarSentencia(){

        if(!$this->movimientoRegistral->folioReal){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los datos de propiedad."]);

            return;

        }

        $this->resetear();

        $this->modal = true;

        $this->crear = true;

        $this->distritos = Constantes::DISTRITOS;

    }

    public function actualizarSentencia(Sentencia $sentencia){

        $this->sentencia = $sentencia;

        $this->antecente_tomo = $this->sentencia->tomo;
        $this->antecente_registro = $this->sentencia->registro;
        $this->antecente_distrito = $this->sentencia->movimientoRegistral->getRawOriginal('distrito');
        $this->tipo_documento = $this->sentencia->movimientoRegistral->tipo_documento;
        $this->autoridad_cargo = $this->sentencia->movimientoRegistral->autoridad_cargo;
        $this->autoridad_nombre = $this->sentencia->movimientoRegistral->autoridad_nombre;
        $this->numero_documento = $this->sentencia->movimientoRegistral->numero_documento;
        $this->fecha_emision = $this->sentencia->movimientoRegistral->fecha_emision;
        $this->procedencia = $this->sentencia->movimientoRegistral->procedencia;
        $this->acto_contenido = $this->sentencia->acto_contenido;
        $this->estado = $this->sentencia->estado;
        $this->comentario = $this->sentencia->descripcion;

        $this->modal = true;

        $this->editar = true;

        $this->distritos = Constantes::DISTRITOS;

    }

    public function cambiar($string){

        if($string == 'documento_entrada'){

            $this->validate([
                'antecente_tomo' => 'required',
                'antecente_registro' => 'required',
                'antecente_distrito' => 'required|same:distritoMovimineto'
            ]);

            $this->antecedente = false;
            $this->documento_entrada = true;
            $this->datos_sentencia = false;

            $this->guardarAntecedente();

        }elseif($string == 'antecedente'){

            $this->antecedente = true;
            $this->documento_entrada = false;
            $this->datos_sentencia = false;

        }elseif($string == 'datos_sentencia'){

            $this->validate([
                'tipo_documento' => 'required',
                'autoridad_cargo' => 'required',
                'autoridad_nombre' => 'required',
                'numero_documento' => 'required',
                'fecha_emision' => 'required',
                'procedencia' => 'nullable',
                'hojas' => 'nullable',
                'expediente' => 'nullable',
            ]);

            $this->antecedente = false;
            $this->documento_entrada = false;
            $this->datos_sentencia = true;

            $this->guardarDocumentoEntrada();

        }


    }

    public function guardarAntecedente(){

        try {

            DB::transaction(function () {

                if($this->sentencia->getKey()){

                    $this->sentencia->movimientoRegistral->update([
                        'distrito' => $this->antecente_distrito,
                        'folio_real' => $this->movimientoRegistral->folio_real,
                        'actualizado_por' => auth()->id()
                    ]);

                    $this->sentencia->update([
                        'tomo' => $this->antecente_tomo,
                        'registro' => $this->antecente_registro,
                    ]);

                }else{

                    $movimiento_registral = MovimientoRegistral::create([
                        'estado' => 'concluido',
                        'folio' => $this->movimientoRegistral->folioReal->ultimoFolio() + 1,
                        'seccion' => 'Sentencias',
                        'distrito' => $this->antecente_distrito,
                        'folio_real' => $this->movimientoRegistral->folio_real,
                        'actualizado_por' => auth()->id()
                    ]);

                    $this->sentencia = Sentencia::create([
                        'tomo' => $this->antecente_tomo,
                        'registro' => $this->antecente_registro,
                        'movimiento_registral_id' => $movimiento_registral->id,
                        'actualizado_por' => auth()->id()
                    ]);

                }

            });

        } catch (\Throwable $th) {
            Log::error("Error al guardar antecedente de sentencia en pase a folio usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }



    }

    public function guardarDocumentoEntrada(){

        try {

            $this->sentencia->movimientoRegistral->update([
                'tipo_documento' => $this->tipo_documento,
                'autoridad_cargo' => $this->autoridad_cargo,
                'autoridad_nombre' => $this->autoridad_nombre,
                'numero_documento' => $this->numero_documento,
                'fecha_emision' => $this->fecha_emision,
                'procedencia' => $this->procedencia,
                'actualizado_por' => auth()->id()
            ]);

            $this->sentencia->update([
                'hojas' => $this->hojas,
                'expediente' => $this->expediente,
                'fecha_inscripcion' => $this->fecha_inscripcion,
            ]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar documento de entrada de sentencia en pase a folio usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardarSentencia(){

        $this->validate([
            'acto_contenido' => 'required',
            'comentario' => 'required',
            'fecha_inscripcion' => 'required'
        ]);


        try {

            $this->sentencia->update([
                'estado' => 'concluido',
                'acto_contenido' => $this->acto_contenido,
                'descripcion' => $this->comentario,
                'actualizado_por' => auth()->id()
            ]);

            $this->resetear();

        } catch (\Throwable $th) {
            Log::error("Error al guardar sentencia en pase a folio usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function abrirModalBorrar($id){

        $this->modalBorrar = true;

        $this->selected_id = $id;

    }

    public function borrar(){

        try{

            DB::transaction(function () {

                $sentencia = Sentencia::find($this->selected_id);

                $sentencia->movimientoRegistral->delete();

                $this->reordenar($sentencia->movimientoRegistral->folio);

                $this->dispatch('mostrarMensaje', ['success', "La sentencia se eliminó con éxito."]);

                $this->modalBorrar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al borrar gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function reordenar($folio){

        $movimientos = MovimientoRegistral::where('folio', '>', $folio)->get();

        MovimientoRegistral::disableAuditing();

        foreach ($movimientos as $movimiento) {
            $movimiento->decrement('folio');
        }

        MovimientoRegistral::enableAuditing();

    }

    public function mount(){

        $this->distritoMovimineto = $this->movimientoRegistral->getRawOriginal('distrito');

        $this->actos = Constantes::ACTOS_INSCRIPCION_SENTENCIAS;

        $this->sentencia = Sentencia::make();

    }

    public function render()
    {

        if($this->movimientoRegistral){

            $sentencias = Sentencia::withWhereHas('movimientoRegistral', function($q){
                                            $q->where('folio_real', $this->movimientoRegistral->folio_real);
                                        })
                                        ->where('movimiento_registral_id', '!=', $this->movimientoRegistral->id)
                                        ->where('estado','!=', 'precalificacion')
                                        ->get();

        }

        return view('livewire.pase-folio.sentencias', compact('sentencias'));
    }
}
