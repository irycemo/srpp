<?php

namespace App\Livewire\Certificaciones;

use App\Models\User;
use App\Models\Predio;
use App\Traits\QrTrait;
use Livewire\Component;
use App\Models\Gravamen;
use Livewire\WithPagination;
use App\Models\Certificacion;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class CertificadoGravamen extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;
    use QrTrait;

    public Certificacion $modelo_editar;

    public $moviminetoRegistral;

    public $predio;

    public $gravamenes;

    public $director;

    public $modalRechazar = false;

    public $modalFinalizar = false;

    public $observaciones;

    public $motivos;
    public $motivo;

    public function crearModeloVacio(){
        $this->modelo_editar = Certificacion::make();
    }

    public function abrirModalRechazar(Certificacion $modelo){

        if(!auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia'])){

            if($modelo->movimientoRegistral->tipo_servicio == 'ordinario'){

                if(!($this->calcularDiaElaboracion($modelo) <= now())){

                    $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($modelo)->format('d-m-Y')]);

                    return;

                }

            }

        }

        $this->resetearTodo();
            $this->modalRechazar = true;
            $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function visualizarGravamenes(Certificacion $modelo){

        $this->modelo_editar = $modelo;

        $this->moviminetoRegistral = $modelo->movimientoRegistral;

        if($this->moviminetoRegistral->tipo_servicio == 'ordinario' && $this->moviminetoRegistral->getRawOriginal('distrito') != 2){

            if(!($this->calcularDiaElaboracion($this->moviminetoRegistral) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->moviminetoRegistral)->format('d-m-Y')]);

                return;

            }

        }

        $movimientoAsignados = MovimientoRegistral::whereIn('estado', ['nuevo'])
                                                        ->where('usuario_Asignado', auth()->id())
                                                        ->withWhereHas('folioReal', function($q){
                                                            $q->where('estado', 'activo');
                                                        })
                                                        ->whereHas('certificacion', function($q){
                                                            $q->where('servicio', 'DL07');
                                                        })
                                                        ->orderBy('created_at')
                                                        ->get();

        foreach($movimientoAsignados as $movimiento){

            if($movimiento->tipo_servicio == 'ordinario'){

                if($movimiento->fecha_entrega <= now()){

                    if($this->moviminetoRegistral->id == $movimiento->id){

                        break;

                    }else{

                        $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $movimiento->folioReal->folio . '-' . $movimiento->folio . ' primero.']);

                        return;

                    }

                }else{

                    continue;

                }

            }else{

                if($this->moviminetoRegistral->id != $movimiento->id){

                    $this->dispatch('mostrarMensaje', ['error', "Debe elaborar el movimiento registral " . $movimiento->folioReal->folio . '-' . $movimiento->folio . ' primero.']);

                    return;

                }else{

                    break;

                }

            }

        }

        $this->predio = Predio::where('folio_real', $this->moviminetoRegistral->folio_real)->first();

        $this->gravamenes = Gravamen::with('deudores',  'acreedores')
                                        ->withWhereHas('movimientoRegistral', function($q) {
                                            $q->where('folio_real', $this->moviminetoRegistral->folio_real);
                                        })
                                        ->where('estado', 'activo')
                                        ->get();

        $this->modal = true;

    }

    public function calcularDiaElaboracion($modelo){

        $diaElaboracion = $modelo->fecha_pago;

        for ($i=0; $i < 2; $i++) {

            $diaElaboracion->addDays(1);

            while($diaElaboracion->isWeekend()){

                $diaElaboracion->addDay();

            }

        }

        return $diaElaboracion;

    }

    public function generarCertificado(){

        if($this->modelo_editar->movimientoRegistral->tipo_servicio == 'ordinario' && $this->modelo_editar->movimientoRegistral->distrito != '02 Uruapan'){

            if(!($this->calcularDiaElaboracion($this->modelo_editar->movimientoRegistral) <= now())){

                $this->dispatch('mostrarMensaje', ['error', "El trámite puede elaborarse apartir del " . $this->calcularDiaElaboracion($this->modelo_editar->movimientoRegistral)->format('d-m-Y')]);

                return;

            }

        }

        $this->modal = false;

        try{

            DB::transaction(function (){

                $this->moviminetoRegistral->estado = 'elaborado';

                $this->moviminetoRegistral->save();

                if(auth()->user()->hasRole(['Supervisor certificaciones', 'Supervisor uruapan'])){

                    $this->modelo_editar->reimpreso_en = now();

                }

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->save();

                $this->dispatch('imprimir_documento', ['gravamen' => $this->moviminetoRegistral->id]);

                $this->modal = false;

                $this->reset('predio');

            });



        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function abrirModalFinalizar(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function finalizarSupervisor(){

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

                $this->modalFinalizar = false;

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

            DB::transaction(function () {

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->movimientoRegistral->año, $this->modelo_editar->movimientoRegistral->tramite, $this->modelo_editar->movimientoRegistral->usuario, $this->motivo . ' ' . $observaciones);

                $this->modelo_editar->movimientoRegistral->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

            $this->modalRechazar = false;

            $pdf = Pdf::loadView('rechazos.rechazo', [
                'movimientoRegistral' => $this->modelo_editar->movimientoRegistral,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones
            ])->output();

            return response()->streamDownload(
                fn () => print($pdf),
                'rechazo.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al rechazar certificado de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reimprimir(MovimientoRegistral $movimientoRegistral){

        try {

            $movimientoRegistral->certificacion->update(['reimpreso_en' => now()]);

            $firmaElectronica = $movimientoRegistral->firmaElectronica;

            $objeto = json_decode($firmaElectronica->cadena_original);

            $pdf = Pdf::loadView('certificaciones.certificadoGravamen', [
                'predio' => $objeto->predio,
                'director' => $objeto->director,
                'gravamenes' => $objeto->gravamenes,
                'folioReal' => $objeto->folioReal,
                'varios' => $objeto->varios,
                'sentencias' => $objeto->sentencias,
                'datos_control' => $objeto->datos_control,
                'firma_electronica' => false,
                'qr'=> $this->generadorQr($firmaElectronica->uuid)
            ]);

            $pdf->render();

            $dom_pdf = $pdf->getDomPDF();

            $canvas = $dom_pdf->get_canvas();

            $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1,1,1));

            $canvas->page_text(35, 745, $movimientoRegistral->folioReal->folio . '-' .$movimientoRegistral->folio, null, 9, array(1, 1, 1));

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al reimiprimir certificado de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function seleccionarMotivo($key){

        $this->motivo = $this->motivos[$key];

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->director = User::where('status', 'activo')
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Director');
                                })->first();

        if(!$this->director) abort(500, message:"Es necesario registrar al director.");

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Certificador Gravamen', 'Certificador Oficialia', 'Certificador Juridico'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->where('usuario_asignado', auth()->id())
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela']);
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
                                                ->whereIn('estado', ['nuevo', 'correccion'])
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

        }elseif(auth()->user()->hasRole(['Supervisor certificaciones', 'Supervisor uruapan'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela']);
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
                                                ->whereIn('estado', ['nuevo', 'elaborado', 'correccion'])
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL07');
                                                })

                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela']);
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
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->whereHas('certificacion', function($q){
                                                    $q->where('servicio', 'DL07');
                                                })
                                                ->whereIn('estado', ['nuevo', 'elaborado', 'correccion'])
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela', 'bloqueado']);
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

        }elseif(auth()->user()->hasRole(['Regional'])){

            $certificados = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor', 'folioReal:id,folio')
                                                ->where('estado', 'elaborado')
                                                ->whereHas('folioReal', function($q){
                                                    $q->whereIn('estado', ['activo', 'centinela', 'bloqueado']);
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
                                                ->when(auth()->user()->ubicacion === 'Regional 1', function($q){
                                                    $q->whereIn('distrito', [3, 9]);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 2', function($q){
                                                    $q->whereIn('distrito', [12, 19]);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 3', function($q){
                                                    $q->whereIn('distrito', [4, 17]);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 4', function($q){
                                                    $q->whereIn('distrito', [2, 18]);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 5', function($q){
                                                    $q->where('distrito', 13);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 6', function($q){
                                                    $q->where('distrito', 15);
                                                })
                                                ->when(auth()->user()->ubicacion === 'Regional 7', function($q){
                                                    $q->whereIn('distrito', [5, 14, 8]);
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
