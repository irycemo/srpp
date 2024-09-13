<?php

namespace App\Livewire\Admin;

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

    public function abrirModalEnviarCaptura(FolioReal $folioReal){

        $movimiento = $folioReal->movimientosRegistrales()->whereIn('estado', ['elaborado', 'finalizado', 'concluido'])->first();

        if($movimiento){

            $this->dispatch('mostrarMensaje', ['error', "El folio tiene movimientos registrales elaborados no es posible enviarlo a captura."]);

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

    public function mount(): void
    {

        $this->crearModeloVacio();

        $this->distritos = Constantes::DISTRITOS;

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
