<?php

namespace App\Livewire\Consulta;

use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Personaold;
use App\Models\Propiedadold;
use App\Constantes\Constantes;
use App\Models\Old\AntecedenteOld;
use App\Models\Old\AntecedenteSentencia;
use App\Models\Old\AntecedenteVario;
use App\Models\Old\GravamenOld;
use App\Models\Old\SentenciaOld;
use App\Models\Old\VariosOld;

class IndicesPropiedad extends Component
{

    public $distritos;
    public $distrito;
    public $tomo;
    public $registro;
    public $numero_propiedad;

    public $nombre;
    public $ap_paterno;
    public $ap_materno;

    public $propiedades = [];
    public $ventas;
    public $antecedentes;
    public $gravamenes;
    public $sentencias;
    public $varios;

    public Propiedadold $propiedad;

    public $folioReal;

    public function buscarPorPropietario(){

        $this->validate([
            'nombre' => 'required',
        ]);

        $this->propiedad = Propiedadold::make();

        $ids = Personaold::select('idPropiedad')
                            ->distinct()
                            ->where(function($q){
                                $q->where('nombre2', 'LIKE', '%' . $this->nombre . '%')
                                    ->orWhere('nombre1', 'LIKE', '%' . $this->nombre . '%');
                            })
                            ->when($this->ap_paterno, function($q){
                                $q->where('paterno', 'LIKE', '%'. $this->ap_paterno . '%');
                            })
                            ->when($this->ap_paterno, function($q){
                                $q->where('materno', 'LIKE', '%'. $this->ap_materno . '%');
                            })
                            ->get()
                            ->toArray();

        $nombre = $this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno;

        $propiedad = Propiedadold::where('propietarios', 'like', '%' . $nombre . '%')->first();

        if($propiedad) array_push($ids, $propiedad->id);

        $this->propiedades = Propiedadold::whereKey($ids)->get();

    }

    public function buscar(){

        $this->validate([
            'distrito' => 'required',
            'tomo' => 'required',
            'registro' => 'required',
        ]);

        $this->propiedad = Propiedadold::make();

        $this->propiedades = Propiedadold::where('distrito', $this->distrito)
                                            ->where('tomo', $this->tomo)
                                            ->where('registro', $this->registro)
                                            ->when($this->numero_propiedad, function($q){
                                                $q->where('noprop', $this->numero_propiedad);
                                            })
                                            ->get();

    }

    public function abrirModalVer(Propiedadold $propiedadold){

        $this->folioReal = FolioReal::where('tomo_antecedente' , $propiedadold->tomo)
                                        ->where('registro_antecedente' , $propiedadold->registro)
                                        ->where('distrito_antecedente' , $propiedadold->distrito)
                                        ->where('numero_propiedad_antecedente' , $propiedadold->noprop)
                                        ->first();

        if($this->folioReal){

            $this->dispatch('mostrarMensaje', ['warning', "La propiedad se encuentra en el folio real: " . $this->folioReal->folio . '.']);

            return;

        }

        $this->propiedad = $propiedadold;

        $this->antecedentes = AntecedenteOld::where('idPropiedad', $this->propiedad->id)->get();

        if($this->antecedentes->count()){

            foreach ($this->antecedentes as $antecedente) {

                $propiedad = Propiedadold::find($antecedente->idAntecedente);

                if($propiedad){

                    $antecedente->propiedad = $propiedad;

                }else{

                    $antecedente->propiedad = Propiedadold::make();

                }

            }

        }

        $this->ventas = AntecedenteOld::where('idAntecedente', $this->propiedad->id)->get();

        if($this->ventas->count()){

            foreach ($this->ventas as $venta) {

                $propiedad = Propiedadold::find($venta->idAntecedente);

                if($propiedad){

                    $venta->propiedad = $propiedad;

                }else{

                    $venta->propiedad = Propiedadold::make();

                }

            }

        }

        $this->gravamenes = GravamenOld::where('idPropiedad', $this->propiedad->id)->get();

        $antesedentes_sentencias = AntecedenteSentencia::where('distrito', $this->propiedad->distrito)
                                            ->where('tomo', $this->propiedad->tomo)
                                            ->where('registro', $this->propiedad->registro)
                                            ->get();

        $this->sentencias = SentenciaOld::find($antesedentes_sentencias->pluck('idSentencia'));

        $antesedentes_varios = AntecedenteVario::where('distrito', $this->propiedad->distrito)
                                            ->where('tomo', $this->propiedad->tomo)
                                            ->where('registro', $this->propiedad->registro)
                                            ->get();

        $this->varios = VariosOld::find($antesedentes_varios->pluck('idVarios'));

    }

    public function mount(){

        $this->propiedad = Propiedadold::make();

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.consulta.indices-propiedad')->extends('layouts.admin');
    }
}
