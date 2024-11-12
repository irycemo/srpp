<?php

namespace App\Livewire\PaseFolio;

use App\Models\Acto;
use App\Models\Vario;
use Livewire\Component;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

class Varios extends Component
{

    public MovimientoRegistral $movimientoRegistral;
    public Vario $vario;

    public $distritos = [];
    public $actos;
    public $distritoMovimineto;

    public $antecedente = true;
    public $documento_entrada = false;
    public $datos_vario = false;

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

    public $acto_contenido;
    public $estado = 'activo';
    public $comentario;

    public $label_numero_documento = "Número de documento";

    public $propiedadOld;

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
            'datos_vario',
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
            'selected_id'
        ]);

        $this->vario = Vario::make();

    }

    public function agregarVario(){

        if(!$this->movimientoRegistral->folioReal){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los datos de propiedad."]);

            return;

        }

        $this->resetear();

        $this->modal = true;

        $this->crear = true;

        $this->distritos = Constantes::DISTRITOS;

    }

    public function actualizarVario(Vario $vario){

        $this->vario = $vario;

        $this->antecente_tomo = $this->vario->movimientoRegistral->tomo;
        $this->antecente_registro = $this->vario->movimientoRegistral->registro;
        $this->antecente_distrito = $this->vario->movimientoRegistral->getRawOriginal('distrito');
        $this->tipo_documento = $this->vario->movimientoRegistral->tipo_documento;
        $this->autoridad_cargo = $this->vario->movimientoRegistral->autoridad_cargo;
        $this->autoridad_nombre = $this->vario->movimientoRegistral->autoridad_nombre;
        $this->numero_documento = $this->vario->movimientoRegistral->numero_documento;
        $this->fecha_emision = $this->vario->movimientoRegistral->fecha_emision;
        $this->procedencia = $this->vario->movimientoRegistral->procedencia;
        $this->acto_contenido = $this->vario->acto_contenido;
        $this->estado = $this->vario->estado;
        $this->comentario = $this->vario->descripcion;

        $this->modal = true;

        $this->editar = true;

        $this->distritos = Constantes::DISTRITOS;

    }

    public function cambiar($string){

        $this->authorize('update', $this->movimientoRegistral);

        if($string == 'documento_entrada'){

            $this->validate([
                'antecente_tomo' => 'required',
                'antecente_registro' => 'required',
                'antecente_distrito' => 'required|same:distritoMovimineto'
            ]);

            $this->antecedente = false;
            $this->documento_entrada = true;
            $this->datos_vario = false;

            $this->guardarAntecedente();

        }elseif($string == 'antecedente'){

            $this->antecedente = true;
            $this->documento_entrada = false;
            $this->datos_vario = false;

        }elseif($string == 'datos_vario'){

            $this->validate([
                'tipo_documento' => 'required',
                'autoridad_cargo' => 'required',
                'autoridad_nombre' => 'required',
                'numero_documento' => 'required',
                'fecha_emision' => 'required',
                'procedencia' => 'nullable'
            ]);

            $this->antecedente = false;
            $this->documento_entrada = false;
            $this->datos_vario = true;

            $this->guardarDocumentoEntrada();

        }


    }

    public function guardarAntecedente(){

        try {

            DB::transaction(function () {

                if($this->vario->getKey()){

                    $this->vario->movimientoRegistral->update([
                        'tomo' => $this->antecente_tomo,
                        'registro' => $this->antecente_registro,
                        'distrito' => $this->antecente_distrito,
                        'folio_real' => $this->movimientoRegistral->folio_real,
                        'actualizado_por' => auth()->id()
                    ]);

                }else{

                    $movimiento_registral = MovimientoRegistral::create([
                        'estado' => 'nuevo',
                        'folio' => $this->movimientoRegistral->folioReal->ultimoFolio() + 1,
                        'seccion' => 'Varios',
                        'tomo' => $this->antecente_tomo,
                        'registro' => $this->antecente_registro,
                        'distrito' => $this->antecente_distrito,
                        'folio_real' => $this->movimientoRegistral->folio_real,
                        'actualizado_por' => auth()->id()
                    ]);

                    $this->vario = Vario::create([
                        'movimiento_registral_id' => $movimiento_registral->id,
                        'actualizado_por' => auth()->id()
                    ]);

                }

            });

        } catch (\Throwable $th) {
            Log::error("Error al guardar antecedente de varios en pase a folio usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }



    }

    public function guardarDocumentoEntrada(){

        try {

            $this->vario->movimientoRegistral->update([
                'tipo_documento' => $this->tipo_documento,
                'autoridad_cargo' => $this->autoridad_cargo,
                'autoridad_nombre' => $this->autoridad_nombre,
                'numero_documento' => $this->numero_documento,
                'fecha_emision' => $this->fecha_emision,
                'procedencia' => $this->procedencia,
                'actualizado_por' => auth()->id()
            ]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar documento de entrada de varios en pase a folio usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardarVario(){

        $this->validate([
            'acto_contenido' => 'required',
            'comentario' => 'required'
        ]);


        try {

            $this->vario->update([
                'estado' => 'nuevo',
                'acto_contenido' => $this->acto_contenido,
                'descripcion' => $this->comentario,
                'actualizado_por' => auth()->id()
            ]);

            $this->resetear();

        } catch (\Throwable $th) {
            Log::error("Error al guardar varios en pase a folio usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

                $vario = Vario::find($this->selected_id);

                $vario->movimientoRegistral->delete();

                $this->reordenar($vario->movimientoRegistral->folio);

                $this->dispatch('mostrarMensaje', ['success', "El movimiento se eliminó con éxito."]);

                $this->modalBorrar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al borrar varios en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function reordenar($folio){

        $movimientos = MovimientoRegistral::where('folio_real', $this->movimientoRegistral->folio_real)
                                            ->where('folio', '>', $folio)
                                            ->get();

        MovimientoRegistral::disableAuditing();

        foreach ($movimientos as $movimiento) {
            $movimiento->decrement('folio');
        }

        MovimientoRegistral::enableAuditing();

    }

    public function mount(){

        $this->distritoMovimineto = $this->movimientoRegistral->getRawOriginal('distrito');

        $this->actos = Constantes::ACTOS_INSCRIPCION_VARIOS;

        $this->vario = Vario::make();

    }

    public function render()
    {

        if($this->movimientoRegistral){

            $varios = Vario::withWhereHas('movimientoRegistral', function($q){
                                            $q->where('folio_real', $this->movimientoRegistral->folio_real);
                                        })
                                        ->where('movimiento_registral_id', '!=', $this->movimientoRegistral->id)
                                        ->where('estado','!=', 'precalificacion')
                                        ->get();

        }

        return view('livewire.pase-folio.varios', compact('varios'));
    }
}
