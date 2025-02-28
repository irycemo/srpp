<?php

namespace App\Livewire\PaseFolio;

use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\AsignacionService;
use App\Http\Services\SistemaTramitesService;
use Livewire\WithPagination;

class PaseFolio extends Component
{

    use ComponentesTrait;
    use WithFileUploads;
    use WithPagination;

    public $observaciones;
    public $modal = false;
    public $modalFinalizar = false;
    public $modalRechazar = false;
    public $motivos;
    public $motivo;
    public $supervisor = false;

    public MovimientoRegistral $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function rechazar(){

        $this->authorize('update', $this->modelo_editar);

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, $this->motivo . ' ' . $observaciones);

                $this->modelo_editar->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->modelo_editar->folioReal?->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

            $pdf = Pdf::loadView('rechazos.rechazo', [
                'movimientoRegistral' => $this->modelo_editar,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones
            ])->output();

            $this->reset(['modalRechazar', 'observaciones']);

            return response()->streamDownload(
                fn () => print($pdf),
                'rechazo.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al rechazar pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->reset(['modal', 'observaciones']);
        }

    }

    public function abrirModalRechazar(MovimientoRegistral $movimientoRegistral){

        $this->reset(['observaciones', 'motivo']);

        if($this->modelo_editar->isNot($movimientoRegistral))
            $this->modelo_editar = $movimientoRegistral;

        $this->modalRechazar = true;

    }

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function finalizar(){

        try {

            DB::transaction(function (){

                if($this->modelo_editar->folioReal?->folioRealAntecedente?->matriz){

                    $this->modelo_editar->update(['estado' => 'concluido']);

                }

                if($this->modelo_editar->inscripcionPropiedad)
                    $this->revisarInscripcionPropiedad();

                if($this->modelo_editar->cancelacion)
                    $this->revisarCancelaciones();

                $this->revisarMovimientosPrecalificacion();

                $this->modelo_editar->folioReal->update([
                    'estado' => 'activo'
                ]);

                $this->reasignarUsuario();

                $this->dispatch('mostrarMensaje', ['success', "El folio se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (Exception $ex) {

            Log::error("Error al finalizar folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $ex);

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        }catch (\Throwable $th) {

            Log::error("Error al finalizar folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignarUsuario(){

        $role = null;

        if($this->modelo_editar->asignadoA->hasRole(['Pase a folio'])){

            if($this->modelo_editar->inscripcionPropiedad){

                $id = (new AsignacionService())->obtenerUsuarioPropiedad(true, $this->modelo_editar->getRawOriginal('distrito'), '');

            }elseif($this->modelo_editar->gravamen){

                $id = (new AsignacionService())->obtenerUsuarioGravamen(true, $this->modelo_editar->getRawOriginal('distrito'), '');

            }elseif($this->modelo_editar->cancelacion){

                $id = (new AsignacionService())->obtenerUsuarioCancelacion(true, $this->modelo_editar->getRawOriginal('distrito'), '');

            }elseif($this->modelo_editar->sentencia){

                $id = (new AsignacionService())->obtenerUsuarioSentencias(true, $this->modelo_editar->getRawOriginal('distrito'), '');

            }elseif($this->modelo_editar->certificacion){

                if($this->modelo_editar->certificacion->servicio == 'DL07'){

                    $id = (new AsignacionService())->obtenerCertificadorGravamen($this->modelo_editar->getRawOriginal('distrito'), '', $this->modelo_editar->tipo_servicio, false, true);

                }elseif(in_array($this->modelo_editar->certificacion->servicio, ['DL11', 'DL10'])){

                    $id = (new AsignacionService())->obtenerCertificadorPropiedad($this->modelo_editar->getRawOriginal('distrito'), '', $this->modelo_editar->tipo_servicio, false, true);

                }

            }elseif($this->modelo_editar->vario){

                $id = (new AsignacionService())->obtenerUsuarioVarios(true, $this->modelo_editar->getRawOriginal('distrito'), '');

            }

            $this->modelo_editar->update(['usuario_asignado' => $id]);

        }
    }

    public function reasignarAleatoriamente(MovimientoRegistral $modelo){

        $this->modelo_editar = $modelo;

        $cantidad = $this->modelo_editar->audits()->where('tags', 'Reasignó usuario')->count();

        if($cantidad >= 2){

            $this->dispatch('mostrarMensaje', ['warning', "Ya se ha reasignado multiples veces."]);

            return;

        }

        $role = null;

        if($this->modelo_editar->inscripcionPropiedad){

            $role = ['Propiedad', 'Registrador Propiedad'];

        }elseif($this->modelo_editar->gravamen){

            $role = ['Gravamen', 'Registrador Gravamen'];

        }elseif($this->modelo_editar->cancelacion){

            $role = ['Cancelación', 'Registrador Cancelación'];

        }elseif($this->modelo_editar->sentencia){

            $role = ['Sentencias', 'Registrador Sentencias'];

        }elseif($this->modelo_editar->certificacion){

            if($this->modelo_editar->certificacion->servicio == 'DL07'){

                $role = ['Certificador Gravamen'];

            }elseif(in_array($this->modelo_editar->certificacion->servicio, ['DL11', 'DL10'])){

                $role = ['Certificador Propiedad'];

            }

        }elseif($this->modelo_editar->vario){

            $role = ['Varios', 'Registrador Varios'];

        }

        try {

            $usuarios = $this->obtenerUsuarios($role);

            if($usuarios->count() === 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay usuarios con rol de " . $role[0] . " disponibles."]);

                throw new Exception("No hay usuarios con rol de " . $role[0] . " disponibles.");

            }

            $id = $usuarios->random()->id;

            while($this->modelo_editar->usuario_asignado == $id){

                $id = $usuarios->random()->id;

            }

            $this->modelo_editar->update(['usuario_asignado' => $id]);

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

        } catch (\Throwable $th) {
            Log::error("Error al reasignar aleatoriamente folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }


    }

    public function pasarCaptura(MovimientoRegistral $modelo){

        try {

            $modelo->folioReal->update(['estado' => 'captura']);

            $modelo->update(['estado' => 'nuevo']);

        } catch (\Throwable $th) {

            Log::error("Error al pasar a captura el folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }
    }

    public function revisarMovimientosPrecalificacion(){

        $mRegsitrales = MovimientoRegistral::where('tomo', $this->modelo_editar->tomo)
                                            ->where('registro', $this->modelo_editar->registro)
                                            ->where('numero_propiedad', $this->modelo_editar->numero_propiedad)
                                            ->where('distrito', $this->modelo_editar->getRawOriginal('distrito'))
                                            ->whereNull('folio_real')
                                            ->where('estado', 'precalificacion')
                                            ->get();

        foreach ($mRegsitrales as $movimiento) {

            $movimiento->update([
                'estado' => 'nuevo',
                'folio_real' => $this->modelo_editar->folio_real
            ]);

        }

    }

    public function revisarInscripcionPropiedad(){

        /* Inscripciones de propiedad sin antecedente para RAN */
        if(
            in_array($this->modelo_editar->inscripcionPropiedad->servicio, ['D114', 'D113', 'D116', 'D115']) &&
            $this->modelo_editar->tomo == null &&
            $this->modelo_editar->registro == null &&
            $this->modelo_editar->numero_propiedad == null
        ){

            $this->modelo_editar->update(['estado' => 'concluido']);

            (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

        /* Fusion */
        }elseif($this->modelo_editar->inscripcionPropiedad->servicio == 'D157'){

            $this->modelo_editar->update(['estado' => 'concluido']);

            (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

        /* Movimientos provenientes de una subdivisión */
        }elseif($this->modelo_editar->inscripcionPropiedad->servicio == 'D127' && $this->modelo_editar->movimiento_padre){

            $this->modelo_editar->update(['estado' => 'concluido']);

            (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

        }

        /* Captura especial de folio real */
        if($this->modelo_editar->inscripcionPropiedad->servicio == 'D118' && $this->modelo_editar->monto <= 3){

            $this->modelo_editar->update(['estado' => 'concluido']);

        }

    }

    public function revisarCancelaciones(){

        $cancelacion = $this->modelo_editar->folioReal->movimientosRegistrales->where('tomo_gravamen', $this->modelo_editar->tomo_gravamen)
                                                                                        ->where('registro_gravamen', $this->modelo_editar->registro_gravamen)
                                                                                        ->where('folio', '>', 1)
                                                                                        ->first();

        if(!$cancelacion){

            (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'Se rechaza en pase a folio debido a que el folio real no tiene gravamenes con la información ingresada.');

            $this->modelo_editar->update(['estado' => 'rechazado']);

        }

    }

    public function seleccionarMotivo($key){

        $this->motivo = $this->motivos[$key];

    }

    public function obtenerUsuarios($role){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->modelo_editar->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->modelo_editar->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q) use ($role){
                                $q->whereIn('name', $role);
                            })
                            ->get();
    }

    public function mount(){

        $this->crearModeloVacio();

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

        $this->supervisor = in_array(auth()->user()->getRoleNames()->first(), ['Supervisor inscripciones', 'Supervisor certificaciones', 'Supervisor uruapan']);

    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'asignadoA', 'folioReal', 'supervisor')
                                                    ->doesnthave('reformaMoral')
                                                    ->where('folio', 1)
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado']);
                                                            });
                                                    })
                                                    ->where(function($q){
                                                        $q->where('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('usuario', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            })
                                                            ->orWhereHas('supervisor', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor certificaciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal', 'asignadoA')
                                                    ->doesnthave('reformaMoral')
                                                    ->where('folio', 1)
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado']);
                                                            });
                                                    })
                                                    ->where(function($q){
                                                        $q->where('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('usuario', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('usuario_supervisor', auth()->user()->id)
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones', 'Jefe de departamento inscripciones'])){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal', 'asignadoA')
                                                    ->doesnthave('reformaMoral')
                                                    ->where('folio', 1)
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado']);
                                                            });
                                                    })
                                                    ->where(function($q){
                                                        $q->where('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('usuario', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);
        }else{

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal', 'asignadoA')
                                                    ->doesnthave('reformaMoral')
                                                    ->where('folio', 1)
                                                    ->whereIn('estado', ['nuevo', 'correccion'])
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado']);
                                                            });
                                                    })
                                                    ->where(function($q){
                                                        $q->where('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('usuario', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhereHas('asignadoA', function($q){
                                                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('usuario_asignado', auth()->user()->id)
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.pase-folio.pase-folio', compact('movimientos'))->extends('layouts.admin');
    }

}
