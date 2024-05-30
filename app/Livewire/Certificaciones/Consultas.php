<?php

namespace App\Livewire\Certificaciones;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificacion;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class Consultas extends Component
{

    use ComponentesTrait;
    use WithPagination;

    public Certificacion $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = Certificacion::make();
    }

    public function abrirModalEditar(Certificacion $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function finalizar(){

        try{

            DB::transaction(function () {

                $this->modelo_editar->movimientoRegistral->update(['estado' => 'concluido']);
                $this->modelo_editar->actualizado_por = auth()->user()->id;
                $this->modelo_editar->finalizado_en = now();

                $this->modelo_editar->save();

                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, 'finalizado');

                $this->resetearTodo();

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de consulta por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $consultas = MovimientoRegistral::with('asignadoA', 'actualizadoPor', 'certificacion')
                                                ->where(function($q){
                                                    $q->whereHas('asignadoA', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DC90', 'DC91', 'DC92', 'DC93']);
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);


        }else{

            $consultas = MovimientoRegistral::with('asignadoA', 'actualizadoPor', 'certificacion')
                                                ->where(function($q){
                                                    $q->whereHas('asignadoA', function($q){
                                                            $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                        })
                                                        ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                })
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->whereIn('servicio', ['DC90', 'DC91', 'DC92', 'DC93'])
                                                    ->whereNull('finalizado_en');
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }

        return view('livewire.certificaciones.consultas', compact('consultas'))->extends('layouts.admin');

    }

}
