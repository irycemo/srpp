<?php

namespace App\Livewire\PaseFolio;

use Exception;
use App\Models\File;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\AsignacionService;
use App\Http\Services\SistemaTramitesService;

class PaseFolio extends Component
{

    use ComponentesTrait;
    use WithPagination;
    use WithFileUploads;

    public $observaciones;
    public $modal = false;
    public $modalFinalizar = false;
    public $modalRechazar = false;
    public $documento;
    public $motivos;
    public $motivo;

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

            $this->reset(['modal', 'observaciones']);

            $pdf = Pdf::loadView('rechazos.rechazo', [
                'movimientoRegistral' => $this->modelo_editar,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones
            ])->output();

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

        $this->reset('documento');

        $this->dispatch('removeFiles');

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function finalizar(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                if(env('LOCAL') == "0"){

                    $pdf = $this->documento->store('srpp/caratulas', 's3');

                    File::create([
                        'fileable_id' => $this->modelo_editar->folioReal->id,
                        'fileable_type' => 'App\Models\FolioReal',
                        'descripcion' => 'caratula',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "1"){

                    $pdf = $this->documento->store('/', 'caratulas');

                    File::create([
                        'fileable_id' => $this->modelo_editar->folioReal->id,
                        'fileable_type' => 'App\Models\FolioReal',
                        'descripcion' => 'caratula_s3',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "2"){

                    $pdf = $this->documento->store('srpp/caratulas', 's3');

                    File::create([
                        'fileable_id' => $this->modelo_editar->folioReal->id,
                        'fileable_type' => 'App\Models\FolioReal',
                        'descripcion' => 'caratula_s3',
                        'url' => $pdf
                    ]);

                    $pdf = $this->documento->store('/', 'caratulas');

                    File::create([
                        'fileable_id' => $this->modelo_editar->folioReal->id,
                        'fileable_type' => 'App\Models\FolioReal',
                        'descripcion' => 'caratula',
                        'url' => $pdf
                    ]);

                }

                $this->revisarMovimientosPrecalificacion();

                $this->modelo_editar->folioReal->update([
                    'estado' => 'activo'
                ]);

                $this->reasignarUsuario();

                $this->dispatch('mostrarMensaje', ['success', "El folio se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (Exception $ex) {

            Log::error("Error al subir archivo de folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $ex);

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        }catch (\Throwable $th) {

            Log::error("Error al subir archivo de folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function reasignarUsuario(){

        $role = null;

        if($this->modelo_editar->inscripcionPropiedad){

            $role = 'Propiedad';

        }elseif($this->modelo_editar->gravamen){

            $role = 'Gravamen';

        }elseif($this->modelo_editar->cancelacion){

            $role = 'Cancelación';

        }elseif($this->modelo_editar->sentencia){

            $role = 'Sentencias';

        }elseif($this->modelo_editar->certificacion){

            $role = 'Certificador Gravamen';

        }

        if($this->modelo_editar->asignadoA->hasRole('Pase a folio')){

            $usuarios = $this->obtenerUsuarios($role);

            if($usuarios->count() === 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay usuarios con rol de " . $role . " disponibles."]);

                throw new Exception("No hay usuarios con rol de " . $role . " disponibles.");

            }

            $id = (new AsignacionService())->obtenerUltimoUsuarioConAsignacion($usuarios);

            $this->modelo_editar->update(['usuario_asignado' => $id]);

        }

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
                                $q->where('name', $role);
                            })
                            ->get();
    }

    public function pasarCaptura(MovimientoRegistral $modelo){

        try {

            $modelo->folioReal->update(['estado' => 'captura']);

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

    public function seleccionarMotivo($key){

        $this->motivo = $this->motivos[$key];

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'asignadoA', 'folioReal', 'supervisor')
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
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor sentencias', 'Supervisor varios', 'Supervisor cancelación', 'Supervisor gravamen', 'Supervisor propiedad', 'Supervisor uruapan', 'Supervisor certificaciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal', 'asignadoA')
                                                    ->where('folio', 1)
                                                    ->whereIn('estado', ['elaborado', 'concluido', 'nuevo', 'captura'])
                                                    ->whereHas('folioReal', function($q){
                                                        $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado']);
                                                    })
                                                    ->where(function($q){
                                                        $q->where('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('usuario', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }else{

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal', 'asignadoA')
                                                    ->where('folio', 1)
                                                    ->where('estado', 'nuevo')
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura']);
                                                            });
                                                    })
                                                    ->where(function($q){
                                                        $q->where('tramite', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('usuario', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->where('usuario_asignado', auth()->user()->id)
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);
        }

        return view('livewire.pase-folio.pase-folio', compact('movimientos'))->extends('layouts.admin');
    }
}
