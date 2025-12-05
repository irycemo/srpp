<?php

namespace App\Livewire\Certificaciones;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificacion;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Traits\CalcularDiaElaboracionTrait;
use App\Http\Services\SistemaTramitesService;
use App\Http\Controllers\Varios\VariosController;
use App\Http\Controllers\Gravamen\GravamenController;
use App\Http\Controllers\PaseFolio\PaseFolioController;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;

class CopiasCertificadas extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use CalcularDiaElaboracionTrait;

    public Certificacion $modelo_editar;
    public $observaciones;
    public $modalRechazar;
    public $usuarios;
    public $modalReasignarUsuario = false;

    public $años;
    public $año;
    public $tramite;
    public $usuario;

    public $usuarios_regionales;

    public $copiaConsultada;

    protected function rules(){
        return [
            'modelo_editar.folio_carpeta_copias' => 'required|string|min:1|unique:certificacions,folio_carpeta_copias,' . $this->modelo_editar->id,
         ];
    }

        protected $validationAttributes  = [
        'modelo_editar.folio_carpeta_copias' => 'folio de carpeta'
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Certificacion::make();
    }

    public function consultar(){

        $this->validate([
            'año' => 'required',
            'tramite' => 'required',
            'usuario' => 'required',
        ]);

        $this->copiaConsultada = MovimientoRegistral::where('año', $this->año)
                                                    ->where('tramite', $this->tramite)
                                                    ->where('usuario', $this->usuario)
                                                    ->whereHas('certificacion', function($q){
                                                        $q->where('servicio', 'DL13');
                                                    })
                                                    ->first();

        if(!$this->copiaConsultada){

            $this->dispatch('mostrarMensaje', ['warning', "No hay certificaciones con ese trámtie."]);

            return;

        }

        if($this->copiaConsultada->estado != 'elaborado'){

            $this->reset('copiaConsultada');

            $this->dispatch('mostrarMensaje', ['warning', "Las copias no estan elaboradas o han sido concluidas."]);

            return;

        }

    }

    public function abrirModalFolio(Certificacion $modelo){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia','Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($modelo->movimientoRegistral)) return;

        }


        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function abrirModalRechazar(Certificacion $modelo){

        $this->resetearTodo();
            $this->modalRechazar = true;
            $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function abrirModalReasignar(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignarUsuario = true;

    }

    public function reasignarUsuario(){

        try {

            $this->modelo_editar->movimientoRegistral->usuario_asignado = $this->usuario_asignado;

            $this->modelo_editar->movimientoRegistral->save();

            $this->modelo_editar->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            Log::error("Error al reasignar movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignarUsuarioAleatoriamente(){

        $cantidad = $this->modelo_editar->movimientoRegistral->audits()->where('tags', 'Reasignó usuario')->count();

        if($cantidad >= 2){

            $this->dispatch('mostrarMensaje', ['warning', "Ya se ha reasignado multiples veces."]);

            return;

        }

        try {

            $this->modelo_editar->movimientoRegistral->usuario_asignado = $this->usuarios->random()->id;
            $this->modelo_editar->movimientoRegistral->actualizado_por = auth()->id();
            $this->modelo_editar->movimientoRegistral->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function generarCertificacion(Certificacion $modelo){

        if(!$modelo->folio_carpeta_copias && !auth()->user()->hasRole(['Certificador Oficialia', 'Certificador Juridico','Jefe de departamento certificaciones'])){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite no tiene folio de carpeta"]);

            return;

        }

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia', 'Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($modelo->movimientoRegistral)) return;

        }

        try {

            DB::transaction(function (){

                $this->modelo_editar->finalizado_en = now();

                $this->modelo_editar->firma = now();

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->movimientoRegistral->save();

                $this->modelo_editar->save();

                $this->dispatch('imprimir_documento_oficialia', ['documento' => $this->modelo_editar->id]);

                if(auth()->user()->hasRole(['Certificador Oficialia', 'Certificador Juridico'])){

                    $this->modelo_editar->movimientoRegistral->update(['estado' => 'concluido']);

                    (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, 'finalizado');

                }

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function concluir(Certificacion $modelo){

        if(!$modelo->folio_carpeta_copias && !auth()->user()->hasRole(['Certificador Oficialia', 'Certificador Juridico','Jefe de departamento certificaciones'])){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite no tiene folio de carpeta"]);

            return;

        }

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia', 'Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($modelo->movimientoRegistral)) return;

        }

        try {

            DB::transaction(function (){

                $this->modelo_editar->finalizado_en = now();

                $this->modelo_editar->firma = now();

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->movimientoRegistral->estado = 'concluido';

                $this->modelo_editar->movimientoRegistral->save();

                $this->modelo_editar->save();


                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, 'concluido');

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function asignarFolio(){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia', 'Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($this->modelo_editar->movimientoRegistral)) return;

        }

        $this->modelo_editar->folio_carpeta_copias = 'A-' . $this->modelo_editar->folio_carpeta_copias;

        $this->validate();

        try{

            DB::transaction(function (){

                $this->modelo_editar->movimientoRegistral->estado = 'elaborado';

                $this->modelo_editar->movimientoRegistral->save();

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->save();

                $this->dispatch('imprimir_documento', ['documento' => $this->modelo_editar->id]);

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->modal = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, $observaciones);

                $this->modelo_editar->movimientoRegistral->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->observaciones = $this->modelo_editar->observaciones . $observaciones;

                $this->modelo_editar->save();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

                $this->resetearTodo();

            });

        } catch (\Throwable $th) {
            Log::error("Error al rechazar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function reimprimir(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia', 'Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($this->modelo_editar->movimientoRegistral)) return;

        }

        try {

            $this->dispatch('imprimir_documento', ['documento' => $this->modelo_editar->id]);

            $this->modelo_editar->reimpreso_en = now();

            $this->modelo_editar->actualizado_por = auth()->user()->id;

            $this->modelo_editar->save();


        } catch (\Throwable $th) {

            Log::error("Error al reimprimir trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function imprimirCaratulaMovimiento(Certificacion $modelo){

        $movimientoRegistral = $modelo->movimientoRegistral->folioReal->movimientosRegistrales()->where('folio', $modelo->movimiento_registral)->first();

        try {

            if($movimientoRegistral->inscripcionPropiedad){

                $pdf = (new PropiedadController())->reimprimirFirmado($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->gravamen){

                $pdf = (new GravamenController())->reimprimirFirmado($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->vario){

                $pdf = (new VariosController())->reimprimirFirmado($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->cancelacion){

                $pdf = (new CancelacionController())->reimprimirFirmado($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->sentencia){

                $pdf = (new SentenciasController())->reimprimirFirmado($movimientoRegistral->firmaElectronica);

            }

            if($movimientoRegistral->certificacion){

                $pdf = (new SentenciasController())->reimprimirFirmado($movimientoRegistral->firmaElectronica);

            }

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {
            Log::error("Error al reimiprimir caratula de inscripción en copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function imprimirDocumentoEntradaMovimiento(Certificacion $modelo){

        $movimientoRegistral = $modelo->movimientoRegistral->folioReal->movimientosRegistrales()->where('folio', $modelo->movimiento_registral)->first();

        $this->js('window.open(\' '. $movimientoRegistral->documentoEntrada() . '\', \'_blank\');');

    }

    public function imprimirCaratulaFolio(Certificacion $modelo){

        $folio = $modelo->movimientoRegistral->folioReal;

        try {

            $pdf = (new PaseFolioController())->reimprimirFirmado($folio->firmaElectronica);

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {
            Log::error("Error al reimiprimir caratula de folio real en copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function imprimirDocumentoEntradaFolio(Certificacion $modelo){

        $folio = $modelo->movimientoRegistral->folioReal;

        $this->js('window.open(\' '. $folio->documentoEntrada() . '\', \'_blank\');');

    }

    public function mount(){

        array_push($this->fields, 'modalRechazar', 'observaciones', 'copiaConsultada');

        $this->crearModeloVacio();

        $this->años = Constantes::AÑOS;

        $this->año = now()->format('Y');

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereIn('name', ['Certificador']);
                                        })
                                        ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                            $q->where('ubicacion', 'Regional 4');
                                        })
                                        ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                            $q->where('ubicacion', '!=', 'Regional 4');
                                        })
                                        ->orderBy('name')
                                        ->get();

        $this->usuarios_regionales = Constantes::USUARIOS_REGIONALES;

    }

    public function render()
    {
        return view('livewire.certificaciones.copias-certificadas')->extends('layouts.admin');
    }

}
