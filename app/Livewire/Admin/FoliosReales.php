<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\FolioReal;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use App\Http\Services\FolioRealService;

class FoliosReales extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public FolioReal $modelo_editar;

    public $distritos;
    public $usuarios;

    public $modalReasignar = false;

    public $usuario_id;

    public $filters = [
        'folio' => '',
        'estado' => '',
        'tomo' => '',
        'registro' => '',
        'numero_propiedad' => '',
        'distrito' => '',
    ];

    public function updatedFilters() { $this->resetPage(); }

    public function crearModeloVacio(){

        $this->modelo_editar = FolioReal::make();

    }

    public function enviarCaptura(FolioReal $folioReal){

        if(in_array($folioReal->estado, ['bloqueado', 'centinela'])){

            $this->dispatch('mostrarMensaje', ['warning', "El folio esta bloqueado no es posible enviarlo a captura."]);

            return;

        }

        if(! in_array($folioReal->movimientosRegistrales()->where('folio', 1)->first()->estado, ['nuevo', 'correccion', 'no recibido', 'rechazado'])){

            $this->dispatch('mostrarMensaje', ['warning', "El estado del movimiento 1 debe estar nuevo ó en correccion."]);

            return;

        }

        $movimientos = $folioReal->movimientosRegistrales()->where('estado', '!=', 'pase_folio')->get();

        if(!in_array($movimientos->first()->estado, ['nuevo', 'correccion', 'no recibido', 'captura', 'rechazado'])){

            $this->dispatch('mostrarMensaje', ['warning', "El folio real tiene movimientos posteriores elaborados."]);

            return;

        }

        try {

            $folioReal->update([
                'estado' => 'captura',
                'actualizado_por' => auth()->id()
            ]);

            $folioReal->audits()->latest()->first()->update(['tags' => 'Envió folio a captura']);

            $this->dispatch('mostrarMensaje', ['success', "El folio esta en captura."]);

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al enviar a captura folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function cambiarAFolioMatriz(FolioReal $folioReal){

        try {

            $folioReal->update([
                'matriz' => true,
                'actualizado_por' => auth()->id()
            ]);

            $folioReal->audits()->latest()->first()->update(['tags' => 'Convirtio a folio matriz']);

            $this->dispatch('mostrarMensaje', ['success', "El folio ahora es matriz."]);

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);

            Log::error("Error al cambiar a folio matriz por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function abrirModalReasignar(FolioReal $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalReasignar = true;

    }

    public function reasignar(){

        try {

            $this->modelo_editar->movimientosRegistrales()->first()->update([
                'usuario_asignado' => $this->usuario_id,
                'actualizado_por' => auth()->id()
            ]);

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó folio real']);

            $this->dispatch('mostrarMensaje', ['success', "El folio se reasgnó con éxito."]);

            $this->modalReasignar = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function borrarFolioReal(FolioReal $folioReal){

        try {

            DB::transaction(function () use($folioReal){

                (new FolioRealService())->borrarFolioReal($folioReal->id);

            });

            $this->dispatch('mostrarMensaje', ['success', "El folio se eliminó con éxito."]);

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al eliminar folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function mount(): void
    {

        $this->crearModeloVacio();

        $this->distritos = Constantes::DISTRITOS;

        $this->usuarios = User::whereHas('roles', function($q){
            $q->whereIn('name', [
                                    'Registrador Sentencias',
                                    'Registrador Varios',
                                    'Registrador Cancelación',
                                    'Registrador Gravamen',
                                    'Registrador Propiedad',
                                    'Certificador Propiedad',
                                    'Certificador Gravamen',
                                    'Certificador Oficialia',
                                    'Certificador Juridico',
                                    'Pase a folio'
                                ]);
        })
        ->orderBy('name')
        ->get();

    }

    public function render()
    {

        $folios = FolioReal::select('id', 'matriz', 'folio', 'estado', 'tomo_antecedente', 'registro_antecedente', 'distrito_antecedente', 'numero_propiedad_antecedente', 'created_at', 'updated_at', 'actualizado_por', 'creado_por')
                            ->with('actualizadoPor', 'creadoPor')
                            ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                            ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                            ->when($this->filters['tomo'], fn($q, $tomo) => $q->where('tomo_antecedente', $tomo))
                            ->when($this->filters['registro'], fn($q, $registro) => $q->where('registro_antecedente', $registro))
                            ->when($this->filters['distrito'], fn($q, $distrito) => $q->where('distrito_antecedente', $distrito))
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.folios-reales', compact('folios'))->extends('layouts.admin');
    }
}
