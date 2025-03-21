<?php

namespace App\Livewire\Certificaciones;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificacion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;
use App\Http\Controllers\Varios\VariosController;
use App\Http\Controllers\Gravamen\GravamenController;
use App\Http\Controllers\PaseFolio\PaseFolioController;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Http\Controllers\Cancelaciones\CancelacionController;
use App\Http\Controllers\InscripcionesPropiedad\PropiedadController;
use App\Traits\CalcularDiaElaboracionTrait;

class CopiasCertificadas extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use CalcularDiaElaboracionTrait;

    public Certificacion $modelo_editar;
    public $observaciones;
    public $modalRechazar;
    public $modalCarga;
    public $fecha_inicio;
    public $fecha_final;
    public $usuarios;
    public $modalReasignar = false;

    protected function rules(){
        return [
            'modelo_editar.folio_carpeta_copias' => 'required|numeric|min:1|unique:certificacions,folio_carpeta_copias,' . $this->modelo_editar->id,
         ];
    }

        protected $validationAttributes  = [
        'modelo_editar.folio_carpeta_copias' => 'folio de carpeta'
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Certificacion::make();
    }

    public function abrirModalEditar(Certificacion $modelo){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia','Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($modelo->movimientoRegistral)) return;

        }

        $this->resetearTodo();
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

        $this->modalReasignar = true;

    }

    public function reasignar(){

        $cantidad = $this->modelo_editar->movimientoRegistral->audits()->where('tags', 'Reasignó usuario')->count();

        if($cantidad >= 2){

            $this->dispatch('mostrarMensaje', ['warning', "Ya se ha reasignado multiples veces."]);

            return;

        }

        try {

            $this->modelo_editar->movimientoRegistral->usuario_asignado = $this->usuarios->where('id', '!=', $this->modelo_editar->movimientoRegistral->usuario_asignado)->random()->id;

            $this->modelo_editar->movimientoRegistral->actualizado_por = auth()->user()->id;

            $this->modelo_editar->movimientoRegistral->save();

            $this->modelo_editar->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reasignó con éxito."]);

            $this->modalReasignar = false;

        } catch (\Throwable $th) {

            Log::error("Error al reasignar movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function imprimirCarga(){

        $this->validate([
            'fecha_final' => 'required',
            'fecha_inicio' => 'required',
        ]);

        $fecha_final = $this->fecha_final . ' 23:59:59';
        $fecha_inicio = $this->fecha_inicio . ' 00:00:00';
        $servicio = 'DL13';

        $carga = MovimientoRegistral::with('certificacion')
                                        ->where('estado', 'nuevo')
                                        ->whereBetween('created_at', [$fecha_inicio, $fecha_final])
                                        ->where('usuario_asignado', auth()->user()->id)
                                        ->whereHas('certificacion', function ($q){
                                            $q->where('servicio', 'DL13')
                                                ->whereNull('finalizado_en')
                                                ->whereNull('folio_carpeta_copias');
                                        })
                                        ->get();

        $pdf = Pdf::loadView('certificaciones.cargaTrabajo', compact(
            'fecha_inicio',
            'fecha_final',
            'carga',
            'servicio'
        ))->output();

        $this->resetearTodo();

        return response()->streamDownload(
            fn () => print($pdf),
            'carga_de_trabajo.pdf'
        );

    }

    public function finalizarSupervisor(Certificacion $modelo){

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

            DB::transaction(function () use ($modelo){

                $this->modelo_editar->finalizado_en = now();

                $this->modelo_editar->firma = now();

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->movimientoRegistral->estado = 'concluido';

                $this->modelo_editar->movimientoRegistral->save();

                $this->modelo_editar->save();

                if(auth()->user()->hasRole(['Certificador Oficialia', 'Certificador Juridico'])){

                    $this->dispatch('imprimir_documento_oficialia', ['documento' => $this->modelo_editar->id]);

                    (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, 'finalizado');

                }else{

                    (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, 'concluido');

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

    public function finalizar(){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia', 'Jefe de departamento certificaciones'])){

            if($this->calcularDiaElaboracion($this->modelo_editar->movimientoRegistral)) return;

        }

        $this->validate();

        try{

            DB::transaction(function (){

                $this->modelo_editar->movimientoRegistral->estado = 'elaborado';

                $this->modelo_editar->movimientoRegistral->save();

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->save();

                $this->dispatch('imprimir_documento', ['documento' => $this->modelo_editar->id]);

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->resetearTodo();

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
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

            $this->resetearTodo();

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

        array_push($this->fields, 'modalRechazar', 'observaciones', 'modalCarga', 'fecha_inicio', 'fecha_final');

        $this->crearModeloVacio();

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereIn('name', ['Certificador']);
                                        })
                                        ->orderBy('name')
                                        ->get();

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Supervisor certificaciones', 'Supervisor uruapan'])){

            $copias = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
                                                ->where(function($q){
                                                    $q->whereHas('asignadoA', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhereHas('supervisor', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('estado', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                })
                                                ->whereIn('estado', ['nuevo', 'elaborado'])
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL13')
                                                        ->whereNull('finalizado_en');
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Certificador', 'Certificador Oficialia', 'Certificador Juridico'])){

            $copias = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
                                                ->where(function($q){
                                                    $q->whereHas('asignadoA', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhereHas('supervisor', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('estado', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                })
                                                ->where('usuario_asignado', auth()->user()->id)
                                                ->where('estado', 'nuevo')
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL13')
                                                        ->whereNull('finalizado_en')
                                                        ->whereNull('folio_carpeta_copias');
                                                })

                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones'])){

            $copias = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
                                                ->where(function($q){
                                                    $q->whereHas('asignadoA', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhereHas('supervisor', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('estado', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL13');
                                                })
                                                ->whereIn('estado', ['nuevo', 'elaborado'])
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico'])){

            $copias = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
                                                ->where(function($q){
                                                    $q->whereHas('asignadoA', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhereHas('supervisor', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('estado', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL13');
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }

        return view('livewire.certificaciones.copias-certificadas', compact('copias'))->extends('layouts.admin');
    }

}
