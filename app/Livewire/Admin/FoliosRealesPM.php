<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Models\FolioRealPersona;
use App\Traits\ComponentesTrait;

class FoliosRealesPM extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public FolioRealPersona $modelo_editar;

    public $distritos;

    public $modalReasignar = false;

    public $filters = [
        'folio' => '',
        'estado' => '',
        'tomo' => '',
        'registro' => '',
        'distrito' => '',
    ];

    public function updatedFilters() { $this->resetPage(); }

    public function crearModeloVacio(){

        $this->modelo_editar = FolioRealPersona::make();

    }

    public function mount(): void
    {

        $this->crearModeloVacio();

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {

        $folios = FolioRealPersona::select('id', 'estado', 'folio', 'tomo_antecedente', 'registro_antecedente', 'distrito', 'actualizado_por', 'creado_por', 'created_at', 'updated_at')
                            ->with('actualizadoPor', 'creadoPor')
                            ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                            ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                            ->when($this->filters['tomo'], fn($q, $tomo) => $q->where('tomo_antecedente', $tomo))
                            ->when($this->filters['registro'], fn($q, $registro) => $q->where('registro_antecedente', $registro))
                            ->when($this->filters['distrito'], fn($q, $distrito) => $q->where('distrito', $distrito))
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.folios-reales-p-m', compact('folios'))->extends('layouts.admin');

    }
}
