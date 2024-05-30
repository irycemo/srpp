<?php

namespace App\Livewire\Certificaciones;

use App\Models\User;
use App\Models\Predio;
use Livewire\Component;
use App\Models\Gravamen;
use App\Models\FolioReal;
use Livewire\WithPagination;
use App\Models\Certificacion;
use App\Traits\ComponentesTrait;
use App\Models\MovimientoRegistral;

class CertificadoGravamen extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Certificacion $modelo_editar;

    public $moviminetoRegistral;

    public $predio;

    public $gravamenes;

    public $director;

    public function crearModeloVacio(){
        $this->modelo_editar = Certificacion::make();
    }

    public function visualizarGravamenes($id){

        $this->moviminetoRegistral = MovimientoRegistral::find($id);

        $this->predio = Predio::where('folio_real', $this->moviminetoRegistral->folio_real)->first();

        $this->gravamenes = Gravamen::with('deudores.persona', 'deudores.actor.persona',  'acreedores.persona')
                                        ->withWhereHas('movimientoRegistral', function($q) {
                                            $q->where('folio_real', $this->moviminetoRegistral->folio_real);
                                        })
                                        ->where('estado', 'activo')
                                        ->get();

        $this->modal = true;

    }

    public function generarCertificado(){

        $this->dispatch('imprimir_documento', ['gravamen' => $this->moviminetoRegistral->id]);

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->director = User::where('status', 'activo')
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Director');
                                })->first();

        if(!$this->director) abort(500, message:"Es necesario registrar al director.");

    }

    public function render()
    {

        if(auth()->user()->hasRole('Certificador Gravamen')){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->where('usuario_asignado', auth()->id())
                                                ->whereHas('folioReal', function($q){
                                                    $q->where('estado', 'activo');
                                                })
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
                                                    $q->where('servicio', 'DL07')
                                                        ->whereNull('finalizado_en');
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Certificador', 'Certificador Oficialia', 'Certificador Juridico'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
                                                ->where('usuario_asignado', auth()->id())
                                                ->whereHas('folioReal', function($q){
                                                    $q->where('estado', 'activo');
                                                })
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
                                                        ->orWhere('estado', 'LIKE', '%' . $this->search . '%')
                                                        ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                                })
                                                ->where('estado', 'elaborado')
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL07')
                                                        ->whereNull('finalizado_en')
                                                        ->whereNull('folio_carpeta_copias');
                                                })

                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }else{

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
                                                ->whereHas('folioReal', function($q){
                                                    $q->where('estado', 'activo');
                                                })
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
                                                    $q->where('servicio', 'DL07');
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }

        return view('livewire.certificaciones.certificado-gravamen', compact('certificados'))->extends('layouts.admin');
    }
}
