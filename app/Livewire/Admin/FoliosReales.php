<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\FolioReal;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;

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

            $this->dispatch('mostrarMensaje', ['error', "El folio esta bloqueado no es posible enviarlo a captura."]);

            return;

        }

        $movimientos = $folioReal->movimientosRegistrales()->where('estado', '!=', 'pase_folio')->get();

        if(!in_array($movimientos->first()->estado, ['nuevo', 'correccion'])){

            $this->dispatch('mostrarMensaje', ['warning', "El movimiento inicial debe estar nuevo o en corrección."]);

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

        $folios = FolioReal::with('actualizadoPor', 'creadoPor')
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
