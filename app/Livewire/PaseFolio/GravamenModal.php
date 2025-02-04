<?php

namespace App\Livewire\PaseFolio;

use Livewire\Component;
use App\Models\Gravamen;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use App\Models\MovimientoRegistral;

class GravamenModal extends Component
{

    public $folioReal;
    public Gravamen $gravamen;

    public $modal = false;
    public $editar = false;
    public $crear = false;
    public $reserva_dominio = false;

    public $actores;
    public $divisas;
    public $actos;

    public $antecedente = true;
    public $documento_entrada = false;
    public $datos_gravamen = false;
    public $deudores = false;
    public $acreedores = false;

    public $antecente_tomo = null;
    public $antecente_registro = null;
    public $antecente_distrito = null;
    public $distritoMovimineto;

    public $tipo_documento = null;
    public $autoridad_cargo = null;
    public $autoridad_nombre = null;
    public $numero_documento = null;
    public $fecha_emision = null;
    public $procedencia = null;

    public $tipo = null;
    public $expediente = null;
    public $acto_contenido = null;
    public $valor_gravamen = null;
    public $divisa = null;
    public $fecha_inscripcion = null;
    public $estado = 'activo';
    public $comentario = null;

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
        'fecha_emision' =>  'fecha de emisión',
        'acto_contenido' => 'acto contenido',
        'valor_gravamen' => 'valor del gravamen',
        'fecha_inscripcion' => 'fecha de inscripción',
    ];

    protected $listeners = ['abrir'];

    public function abrir(){
        $this->modal = true;
    }

    public function agregarGravamen(){

        $this->modal = true;

    }

    public function modalClick(){

        $this->modal = false;

        $this->dispatch('abrir');

    }

    public function cambiar($string){

        if($string == 'documento_entrada'){

            $this->validate([
                'antecente_tomo' => 'required',
                'antecente_registro' => 'required',
            ]);

            $this->antecedente = false;
            $this->documento_entrada = true;
            $this->datos_gravamen = false;
            $this->deudores = false;
            $this->acreedores = false;

            $this->guardarAntecedente();

        }elseif($string == 'antecedente'){

            $this->antecedente = true;
            $this->documento_entrada = false;
            $this->datos_gravamen = false;
            $this->deudores = false;
            $this->acreedores = false;

        }elseif($string == 'datos_gravamen'){

            $this->validate([
                'tipo_documento' => 'nullable',
                'autoridad_cargo' => 'nullable',
                'autoridad_nombre' => 'nullable',
                'numero_documento' => 'nullable',
                'fecha_emision' => 'nullable',
                'procedencia' => 'nullable'
            ]);

            $this->antecedente = false;
            $this->documento_entrada = false;
            $this->datos_gravamen = true;
            $this->deudores = false;
            $this->acreedores = false;

            $this->guardarDocumentoEntrada();

        }elseif($string == 'deudores'){

            $this->validate([
                'tipo' => 'required',
                'expediente' => 'nullable',
                'acto_contenido' => ['required', Rule::in(Constantes::ACTOS_INSCRIPCION_GRAVAMEN)],
                'valor_gravamen' => 'required',
                'divisa' => ['required' , Rule::in(Constantes::DIVISAS)],
                'fecha_inscripcion' => 'required',
                'estado' => 'required',
                'comentario' => 'required',
            ]);

            $this->antecedente = false;
            $this->documento_entrada = false;
            $this->datos_gravamen = false;
            $this->deudores = true;
            $this->acreedores = false;

            $this->guardarGravamen();

        }elseif($string == 'acreedores'){

            if(!$this->gravamen->deudores->count() && $this->acto_contenido != 'POR ANTECEDENTE'){

                $this->dispatch('mostrarMensaje', ['error', "Debe ingresar la información de deudores."]);

                return;

            }

            $this->antecedente = false;
            $this->documento_entrada = false;
            $this->datos_gravamen = false;
            $this->deudores = false;
            $this->acreedores = true;
            $this->crear = true;

        }


    }

    public function guardarAntecedente(){

        if($this->gravamen->getKey()){

            $this->gravamen->movimientoRegistral->update([
                'tomo_gravamen' => $this->antecente_tomo,
                'registro_gravamen' => $this->antecente_registro,
                'folio_real' => $this->movimientoRegistral->folio_real,
                'actualizado_por' => auth()->id()
            ]);

        }else{

            $movimiento_registral = MovimientoRegistral::create([
                'estado' => 'nuevo',
                'folio' => $this->folioReal->ultimoFolio() + 1,
                'seccion' => 'Gravamen',
                'tomo_gravamen' => $this->antecente_tomo,
                'registro_gravamen' => $this->antecente_registro,
                'distrito' => $this->folioReal->getRawOriginal('distrito_antecedente'),
                'folio_real' => $this->folioReal->id,
                'actualizado_por' => auth()->id()
            ]);

            $this->gravamen = Gravamen::create([
                'movimiento_registral_id' => $movimiento_registral->id,
                'estado' => 'activo',
                'actualizado_por' => auth()->id()
            ]);

        }

    }

    public function inactivar(){}

    public function mount(){

        $this->actores = Constantes::ACTORES_GRAVAMEN;

        $this->divisas = Constantes::DIVISAS;

        $this->actos = Constantes::ACTOS_INSCRIPCION_GRAVAMEN;

        $this->gravamen = Gravamen::make();

    }

    public function render()
    {
        return view('livewire.pase-folio.gravamen-modal');
    }
}
