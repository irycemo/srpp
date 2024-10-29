<?php

namespace App\Livewire\PaseFolio;

use App\Models\Predio;
use Livewire\Component;
use App\Models\Colindancia;
use Livewire\Attributes\On;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

class DescripcionPredio extends Component
{

    public $localidad;
    public $oficina;
    public $tipo;
    public $registro;
    public $region;
    public $municipio;
    public $zona;
    public $sector;
    public $manzana;
    public $predio;
    public $edificio;
    public $departamento;
    public $curt;
    public $superficie_terreno;
    public $superficie_construccion;
    public $superficie_judicial;
    public $superficie_notarial;
    public $area_comun_terreno;
    public $area_comun_construccion;
    public $valor_terreno_comun;
    public $valor_construccion_comun;
    public $valor_total_terreno;
    public $valor_total_construccion;
    public $valor_catastral;
    public $monto_transaccion;
    public $divisa;
    public $observaciones;
    public $descripcion;
    public $unidad_area;

    public $medidas = [];
    public $vientos;
    public $areas;
    public $divisas;

    public MovimientoRegistral $movimientoRegistral;
    public Predio $propiedad;

    public $propiedadOld;

    protected function rules(){
        return [
            'localidad' => 'nullable',
            'oficina' => 'nullable',
            'tipo' => 'nullable',
            'registro' => 'nullable',
            'region' => 'nullable',
            'municipio' => 'nullable',
            'zona' => 'nullable',
            'sector' => 'nullable',
            'manzana' => 'nullable',
            'predio' => 'nullable',
            'edificio' => 'nullable',
            'departamento' => 'nullable',
            'curt' => 'nullable',
            'superficie_terreno' => 'nullable',
            'superficie_construccion' => 'nullable',
            'superficie_judicial' => 'nullable',
            'superficie_notarial' => 'nullable',
            'area_comun_terreno' => 'nullable',
            'area_comun_construccion' => 'nullable',
            'valor_terreno_comun' => 'nullable',
            'valor_construccion_comun' => 'nullable',
            'valor_total_terreno' => 'nullable',
            'valor_total_construccion' => 'nullable',
            'valor_catastral' => 'nullable',
            'monto_transaccion' => 'nullable',
            'divisa' => ['required', Rule::in(Constantes::DIVISAS)],
            'observaciones' => 'nullable',
            'medidas.*' => 'nullable',
            'medidas.*.viento' => 'nullable|string',
            'medidas.*.longitud' => [
                                        'nullable',
                                        'numeric',
                                        'min:0',
                                    ],
            'medidas.*.descripcion' => 'nullable',
            'predio' => 'nullable',
            'unidad_area' => ['required', Rule::in(Constantes::UNIDADES)]
         ];
    }

    protected $validationAttributes  = [
        'superficie_terreno' => 'superficie del terreno',
        'superficie_construccion' => 'superficie de construcción',
        'superficie_judicial' => 'superficie judicial',
        'superficie_notarial' => 'superficie notarial',
        'area_comun_terreno' => 'área común de terreno',
        'area_comun_construccion' => 'área común de construcción',
        'valor_terreno_comun' => 'valor del terreno en común',
        'valor_total_construccion' => 'valor de la contrucción en común',
        'valor_catastral' => 'valor catastral',
        'monto_transaccion' => 'monto de la transacción',
        'monto_transaccion' => 'monto de la transacción',
        'medidas.*.viento' => 'viento',
        'medidas.*.longitud' => 'longitud',
        'medidas.*.descripcion' => 'descripción',
    ];

    #[On('cargarPropiedad')]
    public function cargarPropiedad($id){

        $this->propiedad = Predio::find($id);

        $this->curt = $this->propiedad->curt;
        $this->localidad = $this->propiedad->cp_localidad;
        $this->oficina = $this->propiedad->cp_oficina;
        $this->tipo = $this->propiedad->cp_tipo_predio;
        $this->registro = $this->propiedad->cp_registro;
        $this->region = $this->propiedad->cc_region_catastral;
        $this->municipio = $this->propiedad->cc_municipio;
        $this->zona = $this->propiedad->cc_zona_catastral;
        $this->sector = $this->propiedad->cc_sector;
        $this->manzana = $this->propiedad->cc_manzana;
        $this->predio = $this->propiedad->cc_predio;
        $this->edificio = $this->propiedad->cc_edificio;
        $this->departamento = $this->propiedad->cc_departamento;
        $this->superficie_terreno = $this->propiedad->superficie_terreno;
        $this->superficie_construccion = $this->propiedad->superficie_construccion;
        $this->superficie_judicial = $this->propiedad->superficie_judicial;
        $this->superficie_notarial = $this->propiedad->superficie_notarial;
        $this->area_comun_terreno = $this->propiedad->area_comun_terreno;
        $this->area_comun_construccion = $this->propiedad->area_comun_construccion;
        $this->valor_terreno_comun = $this->propiedad->valor_terreno_comun;
        $this->valor_construccion_comun = $this->propiedad->valor_construccion_comun;
        $this->valor_total_terreno = $this->propiedad->valor_total_terreno;
        $this->valor_total_construccion = $this->propiedad->valor_total_construccion;
        $this->valor_catastral = $this->propiedad->valor_catastral;
        $this->monto_transaccion = $this->propiedad->monto_transaccion;
        $this->divisa = $this->divisa;
        $this->unidad_area = $this->propiedad->unidad_area;
        $this->observaciones = $this->propiedad->descripcion;

        $this->reset('medidas');

        foreach ($this->propiedad->colindancias as $colindancia) {

            $this->medidas[] = [
                'id' => $colindancia->id,
                'viento' => $colindancia->viento,
                'longitud' => $colindancia->longitud,
                'descripcion' => $colindancia->descripcion,
            ];

        }

    }

    public function guardarDescripcionPredio(){

        $this->authorize('update', $this->movimientoRegistral);

        $this->validate();

        if(!$this->movimientoRegistral->folio_real){

            $this->dispatch('mostrarMensaje', ['error', "Primero ingrese la información del documento de entrada."]);

            return;

        }

        try {

            DB::transaction(function () {

                if($this->movimientoRegistral->inscripcionPropiedad){

                    $this->movimientoRegistral->inscripcionPropiedad->update([
                        'cc_estado' => 16,
                        'cc_region_catastral' => $this->region,
                        'cc_municipio' => $this->municipio,
                        'cc_zona_catastral' => $this->zona,
                        'cc_sector' => $this->sector,
                        'cc_manzana' => $this->manzana,
                        'cc_predio' => $this->predio,
                        'cc_edificio' => $this->edificio,
                        'cc_departamento' => $this->departamento,
                        'cp_localidad' => $this->localidad,
                        'cp_oficina' => $this->oficina,
                        'cp_tipo_predio' => $this->tipo,
                        'cp_registro' => $this->registro,
                        'superficie_terreno' => $this->superficie_terreno,
                        'superficie_construccion' => $this->superficie_construccion,
                        'superficie_judicial' => $this->superficie_judicial,
                        'superficie_notarial' => $this->superficie_notarial,
                        'area_comun_terreno' => $this->area_comun_terreno,
                        'area_comun_construccion' => $this->area_comun_construccion,
                        'valor_terreno_comun' => $this->valor_terreno_comun,
                        'valor_construccion_comun' => $this->valor_construccion_comun,
                        'valor_total_terreno' => $this->valor_total_terreno,
                        'valor_total_construccion' => $this->valor_total_construccion,
                        'valor_catastral' => $this->valor_catastral,
                        'monto_transaccion' => $this->monto_transaccion,
                        'unidad_area' => $this->unidad_area,
                        'divisa' => $this->divisa,
                        'descripcion' => $this->observaciones
                    ]);

                }

                $this->propiedad->update([
                    'cc_estado' => 16,
                    'cc_region_catastral' => $this->region,
                    'cc_municipio' => $this->municipio,
                    'cc_zona_catastral' => $this->zona,
                    'cc_sector' => $this->sector,
                    'cc_manzana' => $this->manzana,
                    'cc_predio' => $this->predio,
                    'cc_edificio' => $this->edificio,
                    'cc_departamento' => $this->departamento,
                    'cp_localidad' => $this->localidad,
                    'cp_oficina' => $this->oficina,
                    'cp_tipo_predio' => $this->tipo,
                    'cp_registro' => $this->registro,
                    'curt' => $this->curt,
                    'superficie_terreno' => $this->superficie_terreno,
                    'superficie_construccion' => $this->superficie_construccion,
                    'superficie_judicial' => $this->superficie_judicial,
                    'superficie_notarial' => $this->superficie_notarial,
                    'area_comun_terreno' => $this->area_comun_terreno,
                    'area_comun_construccion' => $this->area_comun_construccion,
                    'valor_terreno_comun' => $this->valor_terreno_comun,
                    'valor_construccion_comun' => $this->valor_construccion_comun,
                    'valor_total_terreno' => $this->valor_total_terreno,
                    'valor_total_construccion' => $this->valor_total_construccion,
                    'valor_catastral' => $this->valor_catastral,
                    'monto_transaccion' => $this->monto_transaccion,
                    'divisa' => $this->divisa,
                    'unidad_area' => $this->unidad_area,
                    'descripcion' =>$this->observaciones
                ]);

                foreach ($this->medidas as $key =>$medida) {

                    if($medida['id'] == null){

                        $aux = $this->propiedad->colindancias()->create([
                            'viento' => $medida['viento'],
                            'longitud' => $medida['longitud'],
                            'descripcion' => $medida['descripcion'],
                        ]);

                        $this->medidas[$key]['id'] = $aux->id;

                    }else{

                        Colindancia::find($medida['id'])->update([
                            'viento' => $medida['viento'],
                            'longitud' => $medida['longitud'],
                            'descripcion' => $medida['descripcion'],
                        ]);

                    }

                }

                $this->dispatch('mostrarMensaje', ['success', "La descripción del predio se guardó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar descripción del predio en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function agregarColindancia(){

        $this->medidas[] = ['viento' => null, 'longitud' => null, 'descripcion' => null, 'id' => null];

    }

    public function borrarColindancia($index){

        $this->authorize('update', $this->movimientoRegistral);

        try {

            $this->propiedad->colindancias()->where('id', $this->medidas[$index]['id'])->delete();

        } catch (\Throwable $th) {
            Log::error("Error al borrar colindancia en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
        }

        unset($this->medidas[$index]);

        $this->medidas = array_values($this->medidas);

    }

    public function mount(){

        $this->vientos = Constantes::VIENTOS;

        $this->divisas = Constantes::DIVISAS;

        $this->divisa = $this->divisas[0];

        $this->areas = Constantes::UNIDADES;

        if($this->movimientoRegistral->folio_real)
            $this->cargarPropiedad($this->movimientoRegistral->folioReal->predio->id);

    }

    public function render()
    {
        return view('livewire.pase-folio.descripcion-predio');
    }
}
