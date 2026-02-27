<?php

namespace App\Livewire\Certificaciones;

use App\Constantes\Constantes;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Certificaciones\CertificadoPropiedadController;
use App\Http\Services\SistemaTramitesService;
use App\Models\MovimientoRegistral;
use App\Models\User;
use App\Traits\ComponentesTrait;
use App\Traits\Inscripciones\RechazarMovimientoTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class CertificadoBienestarIndex extends Component
{

    use WithPagination;
    use ComponentesTrait;
    USE RechazarMovimientoTrait;

    public MovimientoRegistral $modelo_editar;

    public $modalFinalizar = false;

    public $modalReasignarUsuario = false;

    public $actual;

    public $usuarios;
    public $usuarios_regionales;
    public $usuarios_regionales_fliped;
    public $usuario_asignado;

    public $años;
    public $filters = [
        'año' => '',
        'tramite' => '',
        'usuario' => '',
        'folio_real' => '',
        'folio' => '',
        'estado' => ''
    ];

    protected function rules()
    {

        return ['usuario_asignado' => 'required'];

    }

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function updatedFilters() { $this->resetPage(); }

    public function elaborar(MovimientoRegistral $movimientoRegistral){

        return redirect()->route('certificado_bienestar', $movimientoRegistral->certificacion);

    }

    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        try {

            $pdf = (new CertificadoPropiedadController())->reimprimircertificadoNegativo($movimientoRegistral->firmaElectronica, true);

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al reimiprimir caratula de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function abrirModalReasignar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignarUsuario = true;

    }

    public function reasignarUsuario(){

        $this->validate();

        try {

            $this->modelo_editar->update(['usuario_asignado' => $this->usuario_asignado]);

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            Log::error("Error al reasignar movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignarUsuarioAleatoriamente(){

        $cantidad = $this->modelo_editar->audits()->where('tags', 'Reasignó usuario')->count();

        if($cantidad >= 2){

            $this->dispatch('mostrarMensaje', ['warning', "Ya se ha reasignado multiples veces."]);

            return;

        }

        try {

            $this->modelo_editar->usuario_asignado = $this->usuarios->random()->id;
            $this->modelo_editar->actualizado_por = auth()->id();
            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function finalizarMovimientoFolio(){

        try {

            DB::transaction(function () {

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'concluido';

                $this->modelo_editar->save();

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizar(MovimientoRegistral $modelo){

        try {

            DB::transaction(function () use ($modelo){

                $modelo->actualizado_por = auth()->user()->id;

                $modelo->estado = 'concluido';

                $modelo->save();

                (new SistemaTramitesService())->finaliarTramite($modelo->año, $modelo->tramite, $modelo->usuario, 'concluido');

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizarSupervisor(){

        try {

            DB::transaction(function (){

                $this->modelo_editar->certificacion->finalizado_en = now();

                $this->modelo_editar->certificacion->firma = now();

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->estado = 'concluido';

                $this->modelo_editar->save();

                $this->modelo_editar->save();

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar certificado de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function corregir(MovimientoRegistral $movimientoRegistral){

        if($this->modelo_editar->isNot($movimientoRegistral))
            $this->modelo_editar = $movimientoRegistral;

        try {

            if($this->modelo_editar->folio_real) $this->revisarMovimientosPosteriores($this->modelo_editar);

            DB::transaction(function (){

                $this->modelo_editar->update([
                    'estado' => 'correccion',
                    'actualizado_por' => auth()->id()
                ]);

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Cambio estado a corrección']);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al enviar a corrección certificado de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->motivos_rechazo = Constantes::RECHAZO_MOTIVOS;

        $this->años = Constantes::AÑOS;

        $this->usuarios_regionales = Constantes::USUARIOS_REGIONALES;

        $this->usuarios = User::where('status', 'activo')
                                ->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Certificador Propiedad']);
                                })
                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->orderBy('name')
                                ->get();

        if(auth()->user()->hasRole(['Regional'])){

            $regional = auth()->user()->ubicacion[-1];

            $this->usuarios_regionales_fliped = array_keys($this->usuarios_regionales, $regional);

        }

    }

    public function render()
    {

        $certificados = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad', 'tipo_servicio', 'fecha_entrega', 'seccion', 'solicitante')
                                            ->with('asignadoA:id,name', 'supervisor:id,name', 'actualizadoPor:id,name', 'certificacion.actualizadoPor:id,name', 'folioReal:id,folio')
                                            ->where('servicio_nombre', 'Certificado negativo de vivienda bienestar')
                                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                $q->where('distrito', 2);
                                            })
                                            ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                $q->where('distrito', '!=', 2);
                                            })
                                            ->whereHas('certificacion', function($q){
                                                $q->where('servicio', 'DL10');
                                            })
                                            ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                            ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                            ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                            ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                            ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                            ->orderBy($this->sort, $this->direction)
                                            ->paginate($this->pagination);

        return view('livewire.certificaciones.certificado-bienestar-index', compact('certificados'))->extends('layouts.admin');

    }

}
