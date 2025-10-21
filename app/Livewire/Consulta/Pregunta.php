<?php

namespace App\Livewire\Consulta;

use Livewire\Component;
use App\Models\PreguntaLeida;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Pregunta as ModelPregunta;

class Pregunta extends Component
{

    use WithFileUploads;

    public ModelPregunta $pregunta;

    public $titulo;
    public $contenido;
    public $images = [];
    public $categorias = [];
    public $categoria;
    public $areas = [];
    public $area;

    protected function rules(){
        return [
            'titulo' => 'required',
            'contenido' => 'required',
            'categoria' => 'required',
         ];
    }

    public function completeUplad($uploadedUrl, $eventName){

            foreach($this->images as $image){

                if($image->getFileName() === $uploadedUrl){

                    $newFileName = $image->store('/', 'preguntas');

                    $url = Storage::disk('preguntas')->url($newFileName);

                    $this->dispatch($eventName, ['url' => $url, 'href' => $url]);

                    return;

                }

            }

    }

    public function deleteImage($url){

            $name = substr($url, strrpos($url, '/') + 1);

            Storage::disk('preguntas')->delete($name);

    }

    public function revisarContenido(){

        if(isset($this->pregunta)){

            $this->dispatch('loadInitial', $this->contenido);

        }

    }

    public function guardar(){

        $this->validate();

        try {

            $this->pregunta = ModelPregunta::create([
                'titulo' => $this->titulo,
                'contenido' => $this->contenido,
                'area' => $this->categoria,
                'categoria' => $this->categoria,
                'estado' => 'nuevo',
                'creado_por' => auth()->id()
            ]);

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);


        } catch (\Throwable $th) {

            Log::error("Error al crear pregunta por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function actualizar(){

        $this->validate();

        try {

            DB::transaction(function () {

                PreguntaLeida::where('pregunta_id', $this->pregunta->id)->get()->each->delete();

                $this->pregunta->update([
                    'titulo' => $this->titulo,
                    'contenido' => $this->contenido,
                    'estado' => 'nuevo',
                    'area' => $this->categoria,
                    'categoria' => $this->categoria,
                    'actualizado_por' => auth()->id()
                ]);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito. Todas los registros de lectura han sido eliminados."]);


        } catch (\Throwable $th) {

            Log::error("Error al crear pregunta por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function publicar(){

        try {

            $this->pregunta->update(['estado' => 'publicado']);

            return redirect()->route('consultas.preguntas');


        } catch (\Throwable $th) {

            Log::error("Error al crear pregunta por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if(isset($this->pregunta)){

            $this->titulo = $this->pregunta->titulo;
            $this->contenido = $this->pregunta->contenido;
            $this->area = $this->pregunta->area;
            $this->categoria = $this->pregunta->categoria;

            $this->dispatch('loadInitial', $this->contenido);

        }

        $this->areas = Constantes::AREAS_ADSCRIPCION;

        $this->categorias = Constantes::CATEGORIAS_PREGUNTAS;

    }

    public function render()
    {
        return view('livewire.consulta.pregunta')->extends('layouts.admin');
    }
}
