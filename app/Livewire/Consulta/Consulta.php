<?php

namespace App\Livewire\Consulta;

use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Propietario;
use Illuminate\Support\Str;
use App\Models\CodigoPostal;
use App\Constantes\Constantes;

class Consulta extends Component
{

    public $folio_real;
    public $tomo;
    public $registro;
    public $numero_propiedad;
    public $distrito;
    public $seccion;

    public $tipo_vialidad;
    public $tipo_asentamiento;
    public $nombre_vialidad;
    public $numero_exterior;
    public $nombre_asentamiento;
    public $codigo_postal;
    public $municipio;
    public $ciudad;
    public $localidad;
    public $localidad_ubicacion;

    public $distritos;
    public $tipos_asentamientos;
    public $nombres_asentamientos = [];
    public $tipos_vialidades;
    public $codigos_postales;

    public $nombre_propietario;
    public $ap_paterno;
    public $ap_materno;
    public $razon_social;

    public $folios_reales;
    public $folioReal;

    public function updatedCodigoPostal(){

        $this->codigos_postales = CodigoPostal::where('codigo', $this->codigo_postal)->get();

        if($this->codigos_postales->count()){

            $this->municipio = $this->codigos_postales->first()->municipio;

            $this->ciudad = $this->codigos_postales->first()->ciudad;

            $this->tipo_asentamiento = Str::upper($this->codigos_postales->first()->tipo_asentamiento);

            foreach ($this->codigos_postales as $codigo) {

                array_push($this->nombres_asentamientos, $codigo->nombre_asentamiento);
            }

        }

    }

    public function limpiar(){

        $this->reset([
            'folio_real',
            'tomo',
            'registro',
            'numero_propiedad',
            'distrito',
            'seccion',
            'tipo_vialidad',
            'tipo_asentamiento',
            'nombre_vialidad',
            'numero_exterior',
            'nombre_asentamiento',
            'codigo_postal',
            'municipio',
            'ciudad',
            'localidad',
            'localidad_ubicacion',
            'nombre_propietario',
            'ap_paterno',
            'ap_materno',
            'razon_social',
            'folios_reales',
            'folioReal',
        ]);

    }

    public function buscar(){

        $this->reset(['folioReal', 'folios_reales']);

        $this->folios_reales = FolioReal::with('predio')
                            ->where('folio', $this->folio_real)
                            ->where('tomo_antecedente', $this->tomo)
                            ->where('registro_antecedente', $this->registro)
                            ->where('numero_propiedad_antecedente', $this->numero_propiedad)
                            ->where('distrito_antecedente', $this->distrito)
                            ->where('seccion_antecedente', $this->seccion)
                            ->whereHas('predio', function($q) { $q->where('codigo_postal', $this->codigo_postal); })
                            ->whereHas('predio', function($q) { $q->where('municipio', $this->municipio); })
                            ->whereHas('predio', function($q) { $q->where('ciudad', $this->ciudad); })
                            ->whereHas('predio', function($q) { $q->where('tipo_asentamiento', $this->tipo_asentamiento); })
                            ->whereHas('predio', function($q) { $q->where('nombre_asentamiento', $this->nombre_asentamiento); })
                            ->whereHas('predio', function($q) { $q->where('localidad', $this->localidad); })
                            ->whereHas('predio', function($q) { $q->where('tipo_vialidad', $this->tipo_vialidad); })
                            ->whereHas('predio', function($q) { $q->where('nombre_vialidad', $this->nombre_vialidad); })
                            ->whereHas('predio', function($q) { $q->where('numero_exterior', $this->numero_exterior); })
                            ->get();

        if($this->nombre_propietario || $this->ap_paterno || $this->ap_materno || $this->razon_social){

            $personas = Persona::when($this->nombre_propietario, fn($q, $nombre_propietario) => $q->where('nombre', $nombre_propietario))
                                ->when($this->ap_paterno, fn($q, $ap_paterno) => $q->where('ap_paterno', $ap_paterno))
                                ->when($this->ap_materno, fn($q, $ap_materno) => $q->where('ap_materno', $ap_materno))
                                ->when($this->razon_social, fn($q, $razon_social) => $q->where('razon_social', $razon_social))
                                ->pluck('id');

            $predios = Actor::whereIn('persona_id', $personas)->where('actorable_type', 'App\Models\Predio')->where('tipo_actor', 'propietario')->pluck('actorable_id');

            if($predios->count() > 0){

                foreach ($predios as $predio) {

                    $predio = Predio::find($predio);

                    if(!$this->folios_reales->where('id', $predio->folioReal->id)->first())
                        $this->folios_reales->push($predio->folioReal);

                }

            }

        }

        if($this->folios_reales->count() === 0){

            $this->dispatch('mostrarMensaje', ['error', "No hay resultado con los parametros ingresados."]);

        }

    }

    public function ver(FolioReal $folioReal){

        $this->reset([
            'folio_real',
            'tomo',
            'registro',
            'numero_propiedad',
            'distrito',
            'seccion',
            'tipo_vialidad',
            'tipo_asentamiento',
            'nombre_vialidad',
            'numero_exterior',
            'nombre_asentamiento',
            'codigo_postal',
            'municipio',
            'ciudad',
            'localidad',
            'nombres_asentamientos',
            'codigos_postales',
            'nombre_propietario',
            'ap_paterno',
            'ap_materno',
            'razon_social',
            'folios_reales',
        ]);

        $this->folioReal = $folioReal;

        $this->folioReal->load(
            'predio',
            'predio.escritura',
            'predio.colindancias',
            'sentencias.movimientoRegistral',
            'varios.movimientoRegistral',
            'propiedad.movimientoRegistral',
            'certificaciones.movimientoRegistral',
            'cancelaciones.movimientoRegistral',
            'gravamenes.movimientoRegistral',
            'gravamenes.deudores',
            'gravamenes.acreedores',
        );

    }

    public function mount(){

        $this->tipos_vialidades = Constantes::TIPO_VIALIDADES;

        $this->tipos_asentamientos = Constantes::TIPO_ASENTAMIENTO;

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.consulta.consulta')->extends('layouts.admin');
    }
}
