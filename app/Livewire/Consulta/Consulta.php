<?php

namespace App\Livewire\Consulta;

use App\Models\Actor;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioReal;
use Illuminate\Support\Str;
use App\Models\CodigoPostal;
use App\Constantes\Constantes;
use App\Models\Antecedente;

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

        $this->reset(['folioReal']);

        if($this->folio_real || $this->tomo || $this->registro || $this->numero_propiedad || $this->distrito || $this->seccion || $this->codigo_postal || $this->municipio || $this->ciudad || $this->tipo_asentamiento || $this->nombre_asentamiento || $this->localidad_ubicacion || $this->tipo_vialidad || $this->nombre_vialidad || $this->numero_exterior){

            $this->folios_reales = FolioReal::with('predio')
                                ->where('estado', 'activo')
                                ->when($this->folio_real, fn($q, $folio_real) => $q->where('folio', $folio_real) )
                                ->when($this->tomo, fn($q, $tomo) => $q->where('tomo_antecedente', $tomo) )
                                ->when($this->registro, fn($q, $registro) => $q->where('registro_antecedente', $registro) )
                                ->when($this->numero_propiedad, fn($q, $numero_propiedad) => $q->where('numero_propiedad_antecedente', $numero_propiedad) )
                                ->when($this->distrito, fn($q, $distrito) => $q->where('distrito_antecedente', $distrito) )
                                ->when($this->seccion, fn($q, $seccion) => $q->where('seccion_antecedente', $seccion) )
                                ->when($this->codigo_postal, fn($q, $codigo_postal) => $q->whereHas('predio', function($q) use($codigo_postal){ $q->where('codigo_postal', $codigo_postal); }))
                                ->when($this->municipio, fn($q, $municipio) => $q->whereHas('predio', function($q) use($municipio){ $q->where('municipio', 'like' , '%' . $municipio . '%'); }))
                                ->when($this->ciudad, fn($q, $ciudad) => $q->whereHas('predio', function($q) use($ciudad){ $q->where('ciudad', 'like' , '%' .$ciudad . '%'); }))
                                ->when($this->tipo_asentamiento, fn($q, $tipo_asentamiento) => $q->whereHas('predio', function($q) use($tipo_asentamiento){ $q->where('tipo_asentamiento', 'like' , '%' . $tipo_asentamiento . '%'); }))
                                ->when($this->nombre_asentamiento, fn($q, $nombre_asentamiento) => $q->whereHas('predio', function($q) use($nombre_asentamiento){ $q->where('nombre_asentamiento', 'like' , '%' .$nombre_asentamiento . '%'); }))
                                ->when($this->localidad_ubicacion, fn($q, $localidad_ubicacion) => $q->whereHas('predio', function($q) use($localidad_ubicacion){ $q->where('localidad', 'like' , '%' .$localidad_ubicacion . '%'); }))
                                ->when($this->tipo_vialidad, fn($q, $tipo_vialidad) => $q->whereHas('predio', function($q) use($tipo_vialidad){ $q->where('tipo_vialidad', $tipo_vialidad); }))
                                ->when($this->nombre_vialidad, fn($q, $nombre_vialidad) => $q->whereHas('predio', function($q) use($nombre_vialidad){ $q->where('nombre_vialidad', $nombre_vialidad); }))
                                ->when($this->numero_exterior, fn($q, $numero_exterior) => $q->whereHas('predio', function($q) use($numero_exterior){ $q->where('numero_exterior', $numero_exterior); }))
                                ->get();

        }else{

            $this->folios_reales = collect(new FolioReal);

        }

        if($this->folio_real || $this->tomo || $this->registro || $this->numero_propiedad){

            $antecedentes = Antecedente::when($this->tomo, fn($q, $tomo) => $q->where('tomo_antecedente', $tomo))
                                            ->when($this->registro, fn($q, $registro) => $q->where('registro_antecedente', $registro))
                                            ->when($this->numero_propiedad, fn($q, $numero_propiedad) => $q->where('numero_propiedad_antecedente', $numero_propiedad))
                                            ->when($this->folio_real, function($q){
                                                $q->whereHas('folioRealAntecedente', function($q){
                                                    $q->where('folio', $this->folio_real);
                                                });
                                            })
                                            ->pluck('folio_real');

            $folios = FolioReal::with('predio')
                                    ->whereKey($antecedentes)
                                    ->get();

            if($this->folios_reales->count() == 0){

                $this->folios_reales = $folios;

            }else{

                foreach ($folios as $folio) {

                    if(!$this->folios_reales->where('id', $folio->id)->first())
                        $this->folios_reales->push($folio);

                }

            }


        }

        if($this->nombre_propietario || $this->ap_paterno || $this->ap_materno || $this->razon_social){

            $personas = Persona::when($this->nombre_propietario, fn($q, $nombre_propietario) => $q->where('nombre', 'like' , '%' . $nombre_propietario . '%'))
                                ->when($this->ap_paterno, fn($q, $ap_paterno) => $q->where('ap_paterno', 'like' , '%' . $ap_paterno . '%'))
                                ->when($this->ap_materno, fn($q, $ap_materno) => $q->where('ap_materno', 'like' , '%' . $ap_materno . '%'))
                                ->when($this->razon_social, fn($q, $razon_social) => $q->where('razon_social', 'like' , '%' . $razon_social . '%'))
                                ->pluck('id');

            $predios = Actor::whereIn('persona_id', $personas)->where('actorable_type', 'App\Models\Predio')->where('tipo_actor', 'propietario')->pluck('actorable_id');

            if(count($predios)){

                $folios = FolioReal::with('predio')
                                        ->whereHas('predio', function($q) use($predios){
                                            $q->whereKey($predios);
                                        })
                                        ->get();


                if($this->folios_reales->count() == 0){

                    $this->folios_reales = $folios;

                }else{

                    foreach ($folios as $folio) {

                        if(!$this->folios_reales->where('id', $folio->id)->first())
                            $this->folios_reales->push($folio);

                    }

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
            'antecedentes.folioRealAntecedente'
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
