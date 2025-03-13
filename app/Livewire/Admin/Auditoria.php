<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Audit;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;

class Auditoria extends Component
{

    use ComponentesTrait;
    use WithPagination;

    public $usuarios;

    public $pagination = 10;

    public $usuario;
    public $evento;
    public $modelo;
    public $modelo_id;
    public $selecetedAudit;
    public $oldValues;
    public $newValues;
    public $modelos = [
        'User' => 'App\Models\User',
        'MovimientoRegistral' => 'App\Models\MovimientoRegistral',
        'Certificacion' => 'App\Models\Certificacion',
        'Rol' => 'App\Models\Rol',
        'Permiso' => 'App\Models\Permiso',
        'Propiedadold' => 'App\Models\Propiedadold',
        'FolioReal' => 'App\Models\FolioReal',
        'FolioRealPersonaMoral' => 'App\Models\FolioRealPersona',
        'Gravamen' => 'App\Models\Gravamen',
        'Propiedad' => 'App\Models\Propiedad',
        'Cancelacion' => 'App\Models\Cancelacion',
        'Sentencia' => 'App\Models\Sentencia',
        'Vario' => 'App\Models\Vario',
        'Reforma' => 'App\Models\ReformaMoral',
    ];

    public function ver(Audit $audit){

        $this->selecetedAudit = $audit;

        if($this->selecetedAudit->event === 'attach' || $this->selecetedAudit->event === 'sync'){

            $this->oldValues = json_decode($this->selecetedAudit->old_values, true);

            $this->newValues = json_decode($this->selecetedAudit->new_values, true);

            /* dd($this->oldValues['roles'][0]); */

        }

        $this->modal = true;

    }

    public function mount(){

        $this->usuarios = User::orderBy('name')->get();

        if(request()->query('modelo')) $this->modelo = $this->modelos[request()->query('modelo')];

        $this->modelo_id = request()->query('modelo_id');

    }

    public function render()
    {

        $audits = Audit::with('user')
                            ->when(isset($this->usuario) && $this->usuario != "", function($q){
                                return $q->where('user_id', $this->usuario);

                            })
                            ->when(isset($this->evento) && $this->evento != "", function($q){
                                return $q->where('event', $this->evento);

                            })
                            ->when(isset($this->modelo) && $this->modelo != "", function($q){
                                return $q->where('auditable_type', $this->modelo);

                            })
                            ->when(isset($this->modelo_id) && $this->modelo_id != "", function($q){
                                return $q->where('auditable_id', $this->modelo_id);

                            })
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);


        return view('livewire.admin.auditoria', compact('audits'))->extends('layouts.admin');
    }

}
