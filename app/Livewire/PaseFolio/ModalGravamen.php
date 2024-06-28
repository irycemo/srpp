<?php

namespace App\Livewire\PaseFolio;

use App\Models\Deudor;
use App\Models\Predio;
use App\Models\Acreedor;
use App\Models\Gravamen;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use LivewireUI\Modal\ModalComponent;
use App\Livewire\PaseFolio\Gravamen as PaseFolioGravamen;

class ModalGravamen extends ModalComponent
{

    public MovimientoRegistral $movimientoRegistral;

    public Predio $propiedad;

    public Gravamen $gravamen;

    public $distritos;
    public $tipo_deudores;
    public $divisas;
    public $actos;

    public $crear = false;
    public $editar = false;

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
    public $acto_contenido = null;
    public $valor_gravamen = null;
    public $divisa = null;
    public $fecha_inscripcion = null;
    public $estado = 'activo';
    public $comentario = null;

    public $tipo_deudor;
    public $propietario;
    public $propietarios_alicuotas = [];
    public $propietarios_garantes = [];
    public $garante_coopropiedad;

    public $modalD = false;

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

    public function updatedTipoDocumento(){

        if($this->tipo_documento == "escritura")
            $this->label_numero_documento = "Número de escritura";

        if($this->tipo_documento == "oficio")
            $this->label_numero_documento = "Número de oficio";

        if($this->tipo_documento == "contrato")
            $this->label_numero_documento = "Número de contrato";

        if($this->tipo_documento == "embargo")
            $this->label_numero_documento = "Número de expediente";

    }

    public function updatedPropietario(){

        if($this->propietario === "") return;

        $this->agregarDeudor(actor: $this->propietario);

    }

    public function updatedPropietariosAlicuotas(){

        foreach($this->propietarios_alicuotas as $propietario){

            if(!$this->gravamen->deudores()->where('actor_id', (int)$propietario)->first()){

                $this->agregarDeudor(actor: $propietario);

            }

        }

    }

    public function updatedPropietariosGarantes(){

        foreach($this->propietarios_garantes as $propietario){

            if(!$this->gravamen->deudores()->where('actor_id', (int)$propietario)->first()){

                $this->agregarDeudor(actor: $propietario);

            }

        }

    }

    public function updatedGaranteCoopropiedad(){


        if($this->garante_coopropiedad === "") return;

        $this->agregarDeudor(actor: $this->garante_coopropiedad);


    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    #[On('cargarPropiedad')]
    public function cargarPropiedad($id){

        $this->propiedad = Predio::find($id);

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
                'tipo_documento' => 'required',
                'autoridad_cargo' => 'required',
                'autoridad_nombre' => 'required',
                'numero_documento' => 'required',
                'fecha_emision' => 'required',
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
                'acto_contenido' => ['required', Rule::in(Constantes::ACTOS_INSCRIPCION_GRAVAMEN)],
                'valor_gravamen' => 'required',
                'divisa' => 'required',
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
                'distrito' => $this->antecente_distrito,
                'folio_real' => $this->movimientoRegistral->folio_real,
                'actualizado_por' => auth()->id()
            ]);

        }else{

            $movimiento_registral = MovimientoRegistral::create([
                'estado' => 'nuevo',
                'folio' => $this->movimientoRegistral->folioReal->ultimoFolio() + 1,
                'seccion' => 'Gravamen',
                'tomo_gravamen' => $this->antecente_tomo,
                'registro_gravamen' => $this->antecente_registro,
                'distrito' => $this->antecente_distrito,
                'folio_real' => $this->movimientoRegistral->folio_real,
                'usurio_asignado' => $this->movimientoRegistral->usurio_asignado,
                'usurio_supervisor' => $this->movimientoRegistral->usurio_supervisor,
                'año' => $this->movimientoRegistral->año,
                'tramite' => $this->movimientoRegistral->tramite,
                'usuario' => $this->movimientoRegistral->usuario,
                'actualizado_por' => auth()->id()
            ]);

            $this->gravamen = Gravamen::create([
                'movimiento_registral_id' => $movimiento_registral->id,
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
            'fecha_inscripcion' => $this->fecha_inscripcion,
            'estado' => $this->estado,
            'observaciones' => $this->comentario,
            'actualizado_por' => auth()->id()
        ]);

        $this->dispatch('cargarGravamenes')->to(PaseFolioGravamen::class);

    }

    #[On('actualizarDeudores')]
    public function actualizarDeudores(){

        $this->gravamen->load(
            'deudoresUnicos.persona',
            'garantesHipotecarios.persona',
            'parteAlicuota.persona',
            'garantesCoopropiedad.persona',
            'fianza.persona',
            'deudoresUnicos.actor.persona',
            'garantesHipotecarios.actor.persona',
            'parteAlicuota.actor.persona',
            'garantesCoopropiedad.actor.persona',
            'fianza.actor.persona'
        );

    }

    #[On('agregarDeudor')]
    public function agregarDeudor($persona = null, $actor = null){

        try {

            DB::transaction(function () use($persona, $actor){

                $this->resetearDeudores();

                Deudor::create([
                    'gravamen_id' => $this->gravamen->id,
                    'persona_id' => $persona,
                    'actor_id' => $actor,
                    'tipo' => $this->tipo_deudor
                ]);

                $this->actualizarDeudores();

            });

        } catch (\Throwable $th) {
            Log::error("Error al crear deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    #[On('agregarAcreedor')]
    public function agregarAcreedor($persona = null, $actor = null){

        try {

            DB::transaction(function () use($persona, $actor){

                Acreedor::create([
                    'gravamen_id' => $this->gravamen->id,
                    'persona_id' => $persona
                ]);

                $this->gravamen->load('acreedores.persona');

            });

        } catch (\Throwable $th) {
            Log::error("Error al crear deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function resetearDeudores(){

        if($this->tipo_deudor === 'I-DEUDOR ÚNICO'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->garantesHipotecarios()->delete();

            $this->gravamen->parteAlicuota()->delete();

            $this->gravamen->garantesCoopropiedad()->delete();

            $this->gravamen->fianza()->delete();

        }elseif($this->tipo_deudor === 'D-GARANTE(S) HIPOTECARIO(S)'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->parteAlicuota()->delete();

            $this->gravamen->garantesCoopropiedad()->delete();

            $this->gravamen->fianza()->delete();

        }elseif($this->tipo_deudor === 'P-PARTE ALICUOTA'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->garantesHipotecarios()->delete();

            $this->gravamen->garantesCoopropiedad()->delete();

            $this->gravamen->fianza()->delete();

        }elseif($this->tipo_deudor === 'G-GARANTES EN COOPROPIEDAD'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->garantesHipotecarios()->delete();

            $this->gravamen->fianza()->delete();

            $this->gravamen->parteAlicuota()->delete();

        }elseif($this->tipo_deudor === 'F-FIANZA'){

            $this->gravamen->deudoresUnicos()->delete();

            $this->gravamen->garantesHipotecarios()->delete();

            $this->gravamen->garantesCoopropiedad()->delete();

            $this->gravamen->parteAlicuota()->delete();

        }

    }

    public function borrarParteAlicuota($id){

        try {

            $this->gravamen->deudores()->where('actor_id', $id)->first()->delete();

            $this->gravamen->load('deudoresUnicos.persona', 'garantesHipotecarios.persona', 'parteAlicuota.persona', 'garantesCoopropiedad.persona', 'fianza.persona');

            $this->dispatch('mostrarMensaje', ['success', "El deudor se eliminó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al eliminar deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function borrarDeudor($id){

        try {

            Deudor::destroy($id);

            $this->actualizarDeudores();

            $this->dispatch('mostrarMensaje', ['success', "El deudor se eliminó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al eliminar deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function borrarAcreedor($id){

        try {

            Acreedor::destroy($id);

            $this->gravamen->load('acreedores');

            $this->dispatch('mostrarMensaje', ['success', "El acreedor se eliminó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al eliminar deudor rol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        if(!$this->gravamen->acreedores->count()){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar la información de acreedores."]);

            return;

        }

        $this->dispatch('closeModal');

    }

    public function cerrar(){

        $this->reset([
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
            'acto_contenido',
            'valor_gravamen',
            'divisa',
            'fecha_inscripcion',
            'estado',
            'comentario',
        ]);

        $this->dispatch('closeModal');

    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

        $this->distritoMovimineto = $this->movimientoRegistral->getRawOriginal('distrito');

        $this->divisas = Constantes::DIVISAS;

        $this->divisa = $this->divisas[0];

        $this->tipo_deudores = Constantes::TIPO_DEUDOR;

        $this->actos = Constantes::ACTOS_INSCRIPCION_GRAVAMEN;

        if($this->movimientoRegistral->folio_real){

            $this->cargarPropiedad($this->movimientoRegistral->folioReal->predio->id);

            if($this->editar){

                $this->antecente_tomo = $this->movimientoRegistral->tomo_gravamen;
                $this->antecente_registro = $this->movimientoRegistral->registro_gravamen;
                $this->antecente_distrito = $this->distritoMovimineto;
                $this->tipo_documento = $this->movimientoRegistral->tipo_documento;
                $this->autoridad_cargo = $this->movimientoRegistral->autoridad_cargo;
                $this->autoridad_nombre = $this->movimientoRegistral->autoridad_nombre;
                $this->numero_documento = $this->movimientoRegistral->numero_documento;
                $this->fecha_emision = $this->movimientoRegistral->fecha_emision;
                $this->procedencia = $this->movimientoRegistral->procedencia;

                if($this->movimientoRegistral->gravamen){

                    $this->gravamen = $this->movimientoRegistral->gravamen;

                    $this->actualizarDeudores();

                    $this->gravamen->load('acreedores.persona');

                    $this->tipo = $this->movimientoRegistral->gravamen->tipo;
                    $this->acto_contenido = $this->movimientoRegistral->gravamen->acto_contenido;
                    $this->valor_gravamen = $this->movimientoRegistral->gravamen->valor_gravamen;
                    $this->divisa = $this->movimientoRegistral->gravamen->divisa;
                    $this->fecha_inscripcion = $this->movimientoRegistral->gravamen->fecha_inscripcion;
                    $this->estado = $this->movimientoRegistral->gravamen->estado;
                    $this->comentario = $this->movimientoRegistral->gravamen->observaciones;

                    foreach ($this->gravamen->parteAlicuota as $deudor) {

                        array_push($this->propietarios_alicuotas, $deudor->actor_id);

                    }

                    foreach ($this->gravamen->garantesCoopropiedad as $deudor) {

                        if($deudor->actor_id)
                            array_push($this->propietarios_garantes, $deudor->actor_id);

                    }

                }

            }else{

                $this->gravamen = Gravamen::make();

            }

        }

    }

    public function render()
    {
        return view('livewire.pase-folio.modal-gravamen');
    }
}
