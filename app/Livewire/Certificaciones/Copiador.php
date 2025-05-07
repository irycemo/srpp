<?php

namespace App\Livewire\Certificaciones;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificacion;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ComponentesTrait;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class Copiador extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Certificacion $modelo_editar;

    public $usuarios;
    public $usuarios_regionales;

    public $modalReasignar = false;
    public $modalCarga = false;
    public $modalRechazar = false;

    public $observaciones;

    public $fecha_final;
    public $fecha_inicio;

    public function crearModeloVacio(){
        $this->modelo_editar = Certificacion::make();
    }

    public function abrirModalReasignar(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignar = true;

    }

    public function abrirModalRechazar(Certificacion $modelo){

        $this->resetearTodo();
            $this->modalRechazar = true;
            $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones . '<|>';

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, $observaciones);

                $this->modelo_editar->movimientoRegistral->update(['estado' => 'rechazado']);

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

    public function copiasElaboradas(MovimientoRegistral $movimientoRegistral){

        try {

            $movimientoRegistral->update(['estado' => 'elaborado']);

            $this->dispatch('mostrarMensaje', ['success', "La información de guardo con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al elaborar copias por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

        $carga = MovimientoRegistral::with('certificacion')
                                        ->where('estado', 'nuevo')
                                        ->whereBetween('created_at', [$fecha_inicio, $fecha_final])
                                        ->where('usuario_asignado', auth()->user()->id)
                                        ->whereHas('certificacion', function ($q){
                                            $q->whereIn('servicio', ['DL13', 'DL14']);
                                        })
                                        ->get();

        $pdf = Pdf::loadView('certificaciones.cargaTrabajo', compact(
            'fecha_inicio',
            'fecha_final',
            'carga',
        ))->output();

        $this->modalCarga = false;

        return response()->streamDownload(
            fn () => print($pdf),
            'carga_de_trabajo.pdf'
        );

    }

    #[Computed]
    public function copias(){

        if(auth()->user()->hasRole(['Copiador'])){

           return MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
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
                                            $q->whereIn('servicio', ['DL13', 'DL14']);
                                        })
                                        ->orderBy($this->sort, $this->direction)
                                        ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor certificaciones', 'Supervisor uruapan'])){

            return MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
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
                                        ->where('estado', 'nuevo')
                                        ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                            $q->where('distrito', 2);
                                        })
                                        ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                            $q->where('distrito', '!=', 2);
                                        })
                                        ->whereHas('certificacion', function($q){
                                            $q->whereIn('servicio', ['DL13', 'DL14']);
                                        })
                                        ->orderBy($this->sort, $this->direction)
                                        ->paginate($this->pagination);


        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico', 'Jefe de departamento certificaciones'])){

           return MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
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
                                                    $q->whereIn('servicio', ['DL13', 'DL14']);
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Copiador');
                                        })
                                        ->orderBy('name')
                                        ->get();

        $this->usuarios_regionales = Constantes::USUARIOS_REGIONALES;

    }

    public function render()
    {
        return view('livewire.certificaciones.copiador')->extends('layouts.admin');
    }
}
