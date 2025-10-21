<?php

namespace App\Livewire\Consulta;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PreguntaLeida;
use App\Traits\ComponentesTrait;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pregunta as ModelPregunta;

class Preguntas extends Component
{

    use ComponentesTrait;
    use WithPagination;

    public ModelPregunta $modelo_editar;

    public $pregunta;

    public $modalUsuarios = false;

    public $usuarios = [];

    public $certificaciones;
    public $inscripciones;
    public $consultas;
    public $recepcion;

    public function crearModeloVacio(){
        $this->modelo_editar =  ModelPregunta::make();
    }

    public function verPregunta(ModelPregunta $pregunta){

        $this->modal = true;

        $this->pregunta = $pregunta;

    }

    public function verUsuarios(ModelPregunta $pregunta){

        $this->modalUsuarios = true;

        $this->pregunta = $pregunta;

        $this->usuarios =  PreguntaLeida::with('usuario')
                                                ->where('pregunta_id', $pregunta->id)
                                                ->get();

    }

    public function marcarComoLeido(){

        PreguntaLeida::create(['user_id' => auth()->id(), 'pregunta_id' => $this->pregunta->id]);

        $this->dispatch('mostrarMensaje', ['success', "La información de guardó con éxito."]);

        $this->modal = false;

    }

    public function borrarPregunta(){

        try {

            DB::transaction(function () {

                PreguntaLeida::where('pregunta_id', $this->selected_id)->get()->each->delete();

                ModelPregunta::find($this->selected_id)->delete();

            });

            $this->modalBorrar = false;

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al borrar pregunta por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    #[Computed]
    public function preguntas(){

        return ModelPregunta::when(!auth()->user()->hasRole('Administrador'),function($q){
                                    $q->where('estado', 'publicado');
                                })
                                ->where(function($q){
                                    $q->where('titulo', 'LIKE',  '%' . $this->search . '%')
                                        ->orWhere('contenido', 'LIKE',  '%' . $this->search . '%');
                                })
                                ->orderBy('id', 'desc')
                                ->simplePaginate(20);

    }

    public function mount(){

        $this->certificaciones = [
            'Copiador',
            'Regional',
            'Jefe de departamento certificaciones',
            'Certificador Oficialia',
            'Certificador Juridico',
            'Certificador',
            'Supervisor certificaciones',
            'Jefe de departamento jurídico',
            'Supervisor uruapan'
        ];

        $this->inscripciones = [
            'Registrador fraccionamientos',
            'Regional',
            'Folio real moral',
            'Supervisor inscripciones',
            'Jefe de departamento jurídico',
            'Aclaraciones administrativas',
            'Avisos preventivos',
            'Jefe de departamento inscripciones',
            'Cancelación',
            'Varios',
            'Registrador Propiedad',
            'Registrador Gravamen',
            'Registrador Varios',
            'Registrador Sentencias',
            'Gravamen',
            'Registrador Cancelación',
            'Supervisor uruapan',
            'Pase a folio',
            'Propiedad'
        ];

        $this->recepcion = ['Consulta'];

    }

    public function render()
    {
        return view('livewire.consulta.preguntas')->extends('layouts.admin');
    }
}
