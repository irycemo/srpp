<?php

namespace App\Traits\Inscripciones;

use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Traits\CalcularDiaElaboracionTrait;
use App\Http\Services\SistemaTramitesService;
use App\Http\Controllers\Varios\VariosController;
use App\Traits\RevisarMovimientosPosterioresTrait;
use App\Http\Controllers\Reformas\ReformaController;
use App\Http\Controllers\Gravamen\GravamenController;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use App\Http\Controllers\Subdivisiones\SubdivisionesController;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;
use App\Http\Controllers\InscripcionesPropiedad\FideicomisoController;

trait InscripcionesIndex{

    use CalcularDiaElaboracionTrait;
    use RevisarMovimientosPosterioresTrait;

    public $modalFinalizar = false;
    public $modalConcluir = false;
    public $modalReasignarUsuario = false;
    public $documento;
    public $observaciones;
    public $motivos;
    public $motivo;
    public $usuarios_regionales;
    public $usuarios_regionales_fliped;

    public $años;
    public $filters = [
        'año' => '',
        'tramite' => '',
        'usuario' => '',
        'folio_real' => '',
        'folio' => '',
        'estado' => ''
    ];

    public MovimientoRegistral $modelo_editar;

    public $actual;

    protected function rules()
    {

        return ['modelo_editar.usuario_asignado' => 'required'];

    }

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function updatedFilters() { $this->resetPage(); }

    public function estaBloqueado(){

        $movimientos = $this->actual->folioReal->movimientosRegistrales()->whereIn('estado', ['nuevo', 'captura', 'correccion'])->orderBy('folio')->get();

        if($movimientos->count()){

            $primerMovimiento = $movimientos->first();

            if($this->actual->folioReal->avisoPreventivo()){

                $aviso = $this->actual->folioReal->avisoPreventivo();

                if(($this->actual->inscripcionPropiedad || $this->actual->gravamen) &&
                    $aviso->movimientoRegistral->tipo_documento == $this->actual->tipo_documento &&
                    $aviso->movimientoRegistral->numero_documento == $this->actual->numero_documento &&
                    $aviso->movimientoRegistral->autoridad_cargo == $this->actual->autoridad_cargo &&
                    $aviso->movimientoRegistral->autoridad_nombre == $this->actual->autoridad_nombre &&
                    $aviso->movimientoRegistral->autoridad_numero == $this->actual->autoridad_numero &&
                    $aviso->movimientoRegistral->fecha_emision == $this->actual->fecha_emision &&
                    $aviso->movimientoRegistral->procedencia == $this->actual->procedencia
                ){

                    $this->ruta($this->actual);

                    return;

                }

                if($this->actual->vario?->acto_contenido == 'SEGUNDO AVISO PREVENTIVO'){

                    $this->ruta($this->modelo_editar);

                    return false;

                }

                if(in_array($this->actual->vario?->acto_contenido, ['CANCELACIÓN DE SEGUNDO AVISO PREVENTIVO', 'CANCELACIÓN DE PRIMER AVISO PREVENTIVO'])){

                    $this->ruta($this->modelo_editar);

                    return false;

                }

                $this->dispatch('mostrarMensaje', ['warning', "El folio real tiene un aviso preventivo vigente."]);

                return true;

            }elseif($this->actual->folio > $primerMovimiento->folio){

                $this->dispatch('mostrarMensaje', ['warning', "El movimiento registral: (" . $this->actual->folioReal->folio . '-' . $primerMovimiento->folio . ') debe elaborarse primero.']);

                return true;

            }else{

               return false;

            }

        }else{

            return false;

        }

    }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        $this->modelo_editar = $movimientoRegistral;

        if($this->modelo_editar->folioReal->estado == 'centinela'){

            $this->dispatch('mostrarMensaje', ['warning', "El folio real esta en centinela."]);

            return;

        }

        if(auth()->user()->hasRole('Jefe de departamento inscripciones')){

            $this->actual = $this->modelo_editar;

            if($this->estaBloqueado()){

                return;

            }else{

                $this->ruta($this->modelo_editar);

                return;
            }

        }

        $aclaracion = $this->modelo_editar->folioReal->aclaracionAdministrativa();

        if($aclaracion){

            if($aclaracion->id == $this->modelo_editar->id){

                $this->ruta($this->modelo_editar);

                return;

            }else{

                $this->dispatch('mostrarMensaje', ['warning', "El folio real tiene aclaración administrativa vigente."]);

                return;

            }

        }

        $this->actual = $this->modelo_editar;

        if($this->estaBloqueado()){

            return;

        }else{

            $this->ruta($this->modelo_editar);

        }

        /* $movimientoAsignados = MovimientoRegistral::with('vario')
                                                        ->whereIn('estado', ['nuevo', 'captura', 'correccion'])
                                                        ->where('usuario_Asignado', auth()->id())
                                                        ->withWhereHas('folioReal', function($q){
                                                            $q->where('estado', 'activo');
                                                        })
                                                        ->orderBy('created_at')
                                                        ->get();

        foreach($movimientoAsignados as $movimiento){

            $this->actual = $movimiento;

            if($this->estaBloqueado()){

                //Esta bloqueado y es el que esta intentando hacer
                if($this->modelo_editar->id == $this->actual->id){

                    break;

                }else{

                    continue;

                }

            }else{

                //Si solo hay un movimiento por realizar y no esta bloqueado
                if($movimientoAsignados->count() == 1 && $this->actual->id == $this->modelo_editar->id){

                    $this->ruta($this->modelo_editar);

                }

                //Revisar si es el que debe hacer ($this->actual)
                if($this->modelo_editar->id != $this->actual->id){

                    $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $this->actual->folioReal->folio . '-' . $this->actual->folio . ' primero.']);

                    return;

                }else{

                    $this->ruta($this->modelo_editar);

                    break;

                }

            }

        } */

    }

    public function ruta($movimientoRegistral){

        if($movimientoRegistral->inscripcionPropiedad){

            if(in_array($movimientoRegistral->inscripcionPropiedad->servicio, ['D121', 'D120', 'D123', 'D122', 'D119', 'D124'])){

                return redirect()->route('propiedad.fraccionamiento', $movimientoRegistral->inscripcionPropiedad);

            }elseif(in_array($movimientoRegistral->inscripcionPropiedad->servicio, ['D127'])){

                return redirect()->route('propiedad.subdivision', $movimientoRegistral->inscripcionPropiedad);

            }else{

                return redirect()->route('propiedad.inscripcion', $movimientoRegistral->inscripcionPropiedad);

            }

        }

        if($movimientoRegistral->fideicomiso){

            return redirect()->route('propiedad.fideicomiso', $movimientoRegistral->fideicomiso);

        }

        if($movimientoRegistral->gravamen){

            return redirect()->route('gravamen.inscripcion', $movimientoRegistral->gravamen);

        }

        if($movimientoRegistral->vario){

            return redirect()->route('varios.inscripcion', $movimientoRegistral->vario);

        }

        if($movimientoRegistral->cancelacion){

            return redirect()->route('cancelacion.inscripcion', $movimientoRegistral->cancelacion);

        }

        if($movimientoRegistral->sentencia){

            return redirect()->route('sentencias.inscripcion', $movimientoRegistral->sentencia);

        }

        if($movimientoRegistral->reformaMoral){

            return redirect()->route('reformas.inscripcion', $movimientoRegistral->reformaMoral);

        }

    }

    public function imprimir(MovimientoRegistral $movimientoRegistral){

        if($movimientoRegistral->getRawOriginal('distrito') != 2 && !auth()->user()->hasRole(['Jefe de departamento inscripciones'])){

            if($this->calcularDiaElaboracion($movimientoRegistral)) return;

        }

        try {

            if($movimientoRegistral->inscripcionPropiedad){

                if(in_array($movimientoRegistral->inscripcionPropiedad->servicio, ['D127', 'D121', 'D120', 'D123', 'D122', 'D119', 'D124', 'D125', 'D126'])){

                    $pdf = (new SubdivisionesController())->reimprimir($movimientoRegistral->firmaElectronica);

                }else{

                    $pdf = (new PropiedadController())->reimprimir($movimientoRegistral->firmaElectronica);

                }

            }

            if($movimientoRegistral->fideicomiso){

                $pdf = (new FideicomisoController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->gravamen){

                $pdf = (new GravamenController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->vario){

                $pdf = (new VariosController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->cancelacion){

                $pdf = (new CancelacionController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->sentencia){

                $pdf = (new SentenciasController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->reformaMoral){

                $pdf = (new ReformaController())->reimprimir($movimientoRegistral->firmaElectronica);

            }

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {
            Log::error("Error al reimiprimir caratula de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function abrirModalConcluir(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalConcluir = true;

    }

    public function concluir(){

        try {

            DB::transaction(function () {

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'concluido';

                $this->modelo_editar->save();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Concluyó inscripción']);

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se concluyó con éxito."]);

            $this->modalConcluir = false;

        } catch (\Throwable $th) {

            Log::error("Error al concluir inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

}