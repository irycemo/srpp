<?php

namespace App\Livewire\PaseFolio;

use App\Models\Predio;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

class UbicacionPredio extends Component
{

    public $tipo_vialidad;
    public $tipo_asentamiento;
    public $nombre_vialidad;
    public $nombre_asentamiento;
    public $numero_exterior;
    public $numero_exterior_2;
    public $numero_adicional;
    public $numero_adicional_2;
    public $numero_interior;
    public $lote;
    public $manzana_ubicacion;
    public $codigo_postal;
    public $lote_fraccionador;
    public $manzana_fraccionador;
    public $etapa_fraccionador;
    public $nombre_edificio;
    public $clave_edificio;
    public $departamento_edificio;
    public $estado_ubicacion;
    public $municipio_ubicacion;
    public $ciudad;
    public $localidad_ubicacion;
    public $poblado;
    public $ejido;
    public $parcela;
    public $solar;
    public $observaciones;

    public $tipos_asentamientos;
    public $tipos_vialidades;

    public MovimientoRegistral $movimientoRegistral;
    public Predio $propiedad;

    protected function rules(){

        return [
            'tipo_vialidad' => 'required',
            'tipo_asentamiento' => 'required',
            'nombre_vialidad' => 'required',
            'nombre_asentamiento' => 'required',
            'numero_exterior' => 'required',
            'numero_exterior_2' => 'required',
            'numero_adicional' => 'required',
            'numero_adicional_2' => 'required',
            'numero_interior' => 'required',
            'lote' => 'required',
            'manzana_ubicacion' => 'required',
            'codigo_postal' => 'required',
            'lote_fraccionador' => 'required',
            'manzana_fraccionador' => 'required',
            'etapa_fraccionador' => 'required',
            'nombre_edificio' => 'required',
            'clave_edificio' => 'required',
            'departamento_edificio' => 'required',
            'municipio_ubicacion' => 'required',
            'ciudad' => 'required',
            'localidad_ubicacion' => 'required',
            'poblado' => 'required',
            'ejido' => 'required',
            'parcela' => 'required',
            'solar' => 'required',
        ];

    }

    protected $validationAttributes  = [
        'tipo_vialidad' => 'tipo de vialidad',
        'tipo_asentamiento' => 'tipo de asentamiento',
        'nombre_vialidad' => 'nombre de vialidad',
        'nombre_asentamiento' => 'nombre de asentamiento',
        'numero_exterior' => 'número exterior',
        'numero_exterior_2' => 'número exterior 2',
        'numero_adicional' => 'número adicional',
        'numero_adicional_2' => 'número adicional 2',
        'numero_interior' => 'número interior',
        'manzana_ubicacion' => 'manzana',
        'etapa_fraccionador' => 'etapa del fraccionador',
        'nombre_edificio' => 'nombre del edificio',
        'municipio_ubicacion' => 'municipio',
        'localidad_ubicacion' => 'localidad',
    ];

    #[On('cargarPropiedad')]
    public function cargarPropiedad($id){

        $this->propiedad = Predio::find($id);

        $this->tipo_vialidad = $this->movimientoRegistral->inscripcionPropiedad->tipo_vialidad;
        $this->tipo_asentamiento = $this->movimientoRegistral->inscripcionPropiedad->tipo_asentamiento;
        $this->nombre_vialidad = $this->movimientoRegistral->inscripcionPropiedad->nombre_vialidad;
        $this->nombre_asentamiento = $this->movimientoRegistral->inscripcionPropiedad->nombre_asentamiento;
        $this->numero_exterior = $this->movimientoRegistral->inscripcionPropiedad->numero_exterior;
        $this->numero_exterior_2 = $this->movimientoRegistral->inscripcionPropiedad->numero_exterior_2;
        $this->numero_adicional = $this->movimientoRegistral->inscripcionPropiedad->numero_adicional;
        $this->numero_adicional_2 = $this->movimientoRegistral->inscripcionPropiedad->numero_adicional_2;
        $this->numero_interior = $this->movimientoRegistral->inscripcionPropiedad->numero_interior;
        $this->lote = $this->movimientoRegistral->inscripcionPropiedad->lote;
        $this->manzana_ubicacion = $this->movimientoRegistral->inscripcionPropiedad->manzana;
        $this->codigo_postal = $this->movimientoRegistral->inscripcionPropiedad->codigo_postal;
        $this->lote_fraccionador = $this->movimientoRegistral->inscripcionPropiedad->lote_fraccionador;
        $this->manzana_fraccionador = $this->movimientoRegistral->inscripcionPropiedad->manzana_fraccionador;
        $this->etapa_fraccionador = $this->movimientoRegistral->inscripcionPropiedad->etapa_fraccionador;
        $this->nombre_edificio = $this->movimientoRegistral->inscripcionPropiedad->nombre_edificio;
        $this->clave_edificio = $this->movimientoRegistral->inscripcionPropiedad->clave_edificio;
        $this->departamento_edificio = $this->movimientoRegistral->inscripcionPropiedad->departamento_edificio;
        $this->municipio_ubicacion = $this->movimientoRegistral->inscripcionPropiedad->municipio;
        $this->ciudad = $this->movimientoRegistral->inscripcionPropiedad->ciudad;
        $this->localidad_ubicacion = $this->movimientoRegistral->inscripcionPropiedad->localidad;
        $this->poblado = $this->movimientoRegistral->inscripcionPropiedad->poblado;
        $this->ejido = $this->movimientoRegistral->inscripcionPropiedad->ejido;
        $this->parcela = $this->movimientoRegistral->inscripcionPropiedad->parcela;
        $this->solar = $this->movimientoRegistral->inscripcionPropiedad->solar;
        $this->observaciones = $this->movimientoRegistral->inscripcionPropiedad->descripcion;


    }

    public function guardarUbicacionPredio(){

        $this->validate();

        if(!$this->movimientoRegistral->folio_real){

            $this->generarFolioReal();

        }

        try {

            DB::transaction(function () {

                $this->movimientoRegistral->inscripcionPropiedad->update([
                    'tipo_vialidad' => $this->tipo_vialidad,
                    'tipo_asentamiento' => $this->tipo_asentamiento,
                    'nombre_vialidad' => $this->nombre_vialidad,
                    'nombre_asentamiento' => $this->nombre_asentamiento,
                    'numero_exterior' => $this->numero_exterior,
                    'numero_exterior_2' => $this->numero_exterior_2,
                    'numero_adicional' => $this->numero_adicional,
                    'numero_adicional_2' => $this->numero_adicional_2,
                    'numero_interior' => $this->numero_interior,
                    'lote' => $this->lote,
                    'manzana' => $this->manzana_ubicacion,
                    'codigo_postal' => $this->codigo_postal,
                    'lote_fraccionador' => $this->lote_fraccionador,
                    'manzana_fraccionador' => $this->manzana_fraccionador,
                    'etapa_fraccionador' => $this->etapa_fraccionador,
                    'nombre_edificio' => $this->nombre_edificio,
                    'clave_edificio' => $this->clave_edificio,
                    'departamento_edificio' => $this->departamento_edificio,
                    'municipio' => $this->municipio_ubicacion,
                    'ciudad' => $this->ciudad,
                    'localidad' => $this->localidad_ubicacion,
                    'poblado' => $this->poblado,
                    'ejido' => $this->ejido,
                    'parcela' => $this->parcela,
                    'solar' => $this->solar,
                    'observaciones' => $this->observaciones
                ]);

                $this->propiedad->update([
                    'tipo_vialidad' => $this->tipo_vialidad,
                    'tipo_asentamiento' => $this->tipo_asentamiento,
                    'nombre_vialidad' => $this->nombre_vialidad,
                    'nombre_asentamiento' => $this->nombre_asentamiento,
                    'numero_exterior' => $this->numero_exterior,
                    'numero_exterior_2' => $this->numero_exterior_2,
                    'numero_adicional' => $this->numero_adicional,
                    'numero_adicional_2' => $this->numero_adicional_2,
                    'numero_interior' => $this->numero_interior,
                    'lote' => $this->lote,
                    'manzana' => $this->manzana_ubicacion,
                    'codigo_postal' => $this->codigo_postal,
                    'lote_fraccionador' => $this->lote_fraccionador,
                    'manzana_fraccionador' => $this->manzana_fraccionador,
                    'etapa_fraccionador' => $this->etapa_fraccionador,
                    'nombre_edificio' => $this->nombre_edificio,
                    'clave_edificio' => $this->clave_edificio,
                    'departamento_edificio' => $this->departamento_edificio,
                    'municipio' => $this->municipio_ubicacion,
                    'ciudad' => $this->ciudad,
                    'localidad' => $this->localidad_ubicacion,
                    'poblado' => $this->poblado,
                    'ejido' => $this->ejido,
                    'parcela' => $this->parcela,
                    'solar' => $this->solar,
                    'descripcion' => $this->observaciones
                ]);

                $this->dispatch('mostrarMensaje', ['success', "La ubicación del predio se guardó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar ubicación del predio en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        $this->tipos_vialidades = Constantes::TIPO_VIALIDADES;

        $this->tipos_asentamientos = Constantes::TIPO_ASENTAMIENTO;

        $this->cargarPropiedad($this->movimientoRegistral->folioReal->predio->id);

    }

    public function render()
    {
        return view('livewire.pase-folio.ubicacion-predio');
    }
}
