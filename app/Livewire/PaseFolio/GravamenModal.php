<?php

namespace App\Livewire\PaseFolio;

use App\Models\Actor;
use Livewire\Component;
use App\Models\Gravamen;
use App\Models\FolioReal;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Livewire\Comun\Actores\DeudorCrear;
use App\Livewire\Comun\Actores\AcreedorCrear;

class GravamenModal extends Component
{

    public $folioReal;
    public $gravamen;

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

    protected function messages()
    {
        return [
            'antecente_tomo.required_if' => 'El campo tomo es obligatorio.',
            'antecente_registro.required_if' => 'El campo tomo es obligatorio.',
            'fecha_inscripcion.before' => 'La fecha debe ser anterior al día de hoy'
        ];
    }

    protected $listeners = ['refresh', 'cargarGravamen'];

    public function updatedReservaDominio(){

        if($this->reserva_dominio){

            $this->acto_contenido = 'RESERVA DE DOMINIO';

        }

    }

    public function agregarGravamen(){

        if(!$this->editar){

            $this->reset([
                'editar',
                'reserva_dominio',
                'antecedente',
                'documento_entrada',
                'datos_gravamen',
                'deudores',
                'acreedores',
                'antecente_tomo',
                'antecente_registro',
                'antecente_distrito',
                'distritoMovimineto',
                'tipo_documento',
                'autoridad_cargo',
                'autoridad_nombre',
                'numero_documento',
                'fecha_emision',
                'procedencia',
                'tipo',
                'expediente',
                'acto_contenido',
                'valor_gravamen',
                'divisa',
                'fecha_inscripcion',
                'estado',
                'comentario',
                'label_numero_documento'
            ]);

        }

    }

    public function cargarGravamen($id){

        $this->gravamen = Gravamen::with('actores.persona', 'acreedores.persona')->whereKey($id)->first();

        $this->antecente_tomo = $this->gravamen->movimientoRegistral->tomo_gravamen;
        $this->antecente_registro = $this->gravamen->movimientoRegistral->registro_gravamen;
        $this->antecente_distrito = $this->gravamen->movimientoRegistral->getOriginal('distrito');
        $this->tipo_documento = $this->gravamen->movimientoRegistral->tipo_documento;
        $this->autoridad_cargo = $this->gravamen->movimientoRegistral->autoridad_cargo;
        $this->autoridad_nombre = $this->gravamen->movimientoRegistral->autoridad_nombre;
        $this->numero_documento = $this->gravamen->movimientoRegistral->numero_documento;
        $this->fecha_emision = $this->gravamen->movimientoRegistral->fecha_emision;
        $this->procedencia = $this->gravamen->movimientoRegistral->procedencia;
        $this->tipo = $this->gravamen->tipo;
        $this->expediente = $this->gravamen->expediente;
        $this->acto_contenido = $this->gravamen->acto_contenido;
        $this->valor_gravamen = $this->gravamen->valor_gravamen;
        $this->divisa = $this->gravamen->divisa;
        $this->fecha_inscripcion = $this->gravamen->fecha_inscripcion;
        $this->estado = $this->gravamen->estado;
        $this->comentario = $this->gravamen->observaciones;

        $this->folioReal = FolioReal::find($this->gravamen->movimientoRegistral->folio_real);

        $this->editar = true;

        if($this->gravamen->acto_contenido == 'RESERVA DE DOMINIO') $this->reserva_dominio = true;

    }

    public function abrirModalCrear($tipo){

        $this->modal = false;

        if($tipo == 'deudor'){

            $this->dispatch('abrir')->to(DeudorCrear::class);

        }else{

            $this->dispatch('abrir')->to(AcreedorCrear::class);

        }

    }

    public function refresh(){

        $this->gravamen->load('deudores.persona', 'acreedores.persona');

    }

    public function cambiar($string){

        if($string == 'documento_entrada'){

            $this->validate([
                'antecente_tomo' => 'required_if:reserva_dominio,false',
                'antecente_registro' => 'required_if:reserva_dominio,false',
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
                'valor_gravamen' => ['required', 'numeric', 'regex:/^[\d]{0,15}(\.[\d]{1,2})?$/'],
                'divisa' => ['required' , Rule::in(Constantes::DIVISAS)],
                'fecha_inscripcion' => ['required', 'date', 'before:today'],
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

                $this->dispatch('mostrarMensaje', ['warning', "Debe ingresar la información de los actores."]);

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

    public function regresarA($string){

        if($string == 'documento_entrada'){

            $this->antecedente = false;
            $this->documento_entrada = true;
            $this->datos_gravamen = false;
            $this->deudores = false;
            $this->acreedores = false;

        }elseif($string == 'antecedente'){

            $this->antecedente = true;
            $this->documento_entrada = false;
            $this->datos_gravamen = false;
            $this->deudores = false;
            $this->acreedores = false;

        }elseif($string == 'datos_gravamen'){

            $this->antecedente = false;
            $this->documento_entrada = false;
            $this->datos_gravamen = true;
            $this->deudores = false;
            $this->acreedores = false;

        }elseif($string == 'deudores'){

            $this->antecedente = false;
            $this->documento_entrada = false;
            $this->datos_gravamen = false;
            $this->deudores = true;
            $this->acreedores = false;

        }elseif($string == 'acreedores'){

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
                'folio_real' => $this->folioReal->id,
                'actualizado_por' => auth()->id()
            ]);

        }else{

            $this->cambiarFolioMovimientoInicial();

            $movimiento_registral = MovimientoRegistral::create([
                'estado' => 'pase_folio',
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

    public function guardarDocumentoEntrada(){

        $this->gravamen->movimientoRegistral->update([
            'tipo_documento' => $this->tipo_documento,
            'autoridad_cargo' => $this->autoridad_cargo,
            'autoridad_nombre' => $this->autoridad_nombre,
            'numero_documento' => $this->numero_documento,
            'fecha_emision' => $this->fecha_emision,
            'procedencia' => $this->procedencia,
            'actualizado_por' => auth()->id()
        ]);

    }

    public function guardarGravamen(){

        $this->gravamen->update([
            'tipo' => $this->tipo,
            'acto_contenido' => $this->acto_contenido,
            'valor_gravamen' => $this->valor_gravamen,
            'divisa' => $this->divisa,
            'expediente' => $this->expediente,
            'fecha_inscripcion' => $this->fecha_inscripcion,
            'estado' => $this->estado,
            'observaciones' => $this->comentario,
            'actualizado_por' => auth()->id()
        ]);

    }

    public function eliminarActor(Actor $actor){

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->refresh();

        } catch (\Throwable $th) {

            Log::error("Error al eliminar actor en gravamen de pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function cerrar(){

        $this->dispatch('cargarGravamenes');

    }

    public function finalizar(){

        if(!$this->gravamen->acreedores()->count()){

            $this->dispatch('mostrarMensaje', ['warning', "Debe ingresar los acreedores."]);

            return;

        }

        $this->dispatch('cargarGravamenes');

    }

    public function cambiarFolioMovimientoInicial(){

        if(!$this->folioReal->movimientosRegistrales()->where('folio', 0)->first()){

            $this->folioReal->movimientosRegistrales()
                                ->where('folio', 1)
                                ->first()
                                ->update(['folio' => 0]);

        }

    }

    public function mount(){

        $this->actores = Constantes::ACTORES_GRAVAMEN;

        $this->divisas = Constantes::DIVISAS;

        $this->actos = Constantes::ACTOS_INSCRIPCION_GRAVAMEN;

        if(!$this->gravamen){

            $this->gravamen = Gravamen::make();

        }

    }

    public function render()
    {
        return view('livewire.pase-folio.gravamen-modal');
    }
}
