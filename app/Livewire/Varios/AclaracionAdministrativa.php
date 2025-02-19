<?php

namespace App\Livewire\Varios;

use App\Models\Actor;
use App\Models\Predio;
use Livewire\Component;
use App\Models\Colindancia;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\Inscripciones\Varios\VariosTrait;
use App\Http\Controllers\Varios\VariosController;
use App\Traits\Inscripciones\ColindanciasTrait;
use Spatie\LivewireFilepond\WithFilePond;

class AclaracionAdministrativa extends Component
{

    use VariosTrait;
    use WithFilePond;
    use ColindanciasTrait;

    public $areas;
    public $divisas;
    public $tipos_vialidades;
    public $tipos_asentamientos;

    public $predio;

    protected $listeners = ['refresh'];

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
            'vario.predio.superficie_terreno' => 'required',
            'vario.predio.unidad_area' => 'required',
            'vario.predio.superficie_construccion' => 'required',
            'vario.predio.monto_transaccion' => 'required',
            'vario.predio.observaciones' => 'nullable',
            'vario.predio.curt' => 'nullable',
            'vario.predio.superficie_judicial' => 'nullable',
            'vario.predio.superficie_notarial' => 'nullable',
            'vario.predio.area_comun_terreno' => 'nullable',
            'vario.predio.area_comun_construccion' => 'nullable',
            'vario.predio.valor_terreno_comun' => 'nullable',
            'vario.predio.valor_construccion_comun' => 'nullable',
            'vario.predio.valor_total_terreno' => 'nullable',
            'vario.predio.valor_total_construccion' => 'nullable',
            'vario.predio.valor_catastral' => 'nullable',
            'vario.predio.divisa' => 'required',
            'vario.predio.codigo_postal' => 'nullable',
            'vario.predio.nombre_asentamiento' => 'nullable',
            'vario.predio.municipio' => 'nullable',
            'vario.predio.ciudad' => 'nullable',
            'vario.predio.tipo_asentamiento' => 'nullable',
            'vario.predio.localidad' => 'nullable',
            'vario.predio.tipo_vialidad' => 'nullable',
            'vario.predio.nombre_vialidad' => 'nullable',
            'vario.predio.numero_exterior' => 'nullable',
            'vario.predio.numero_interior' => 'nullable',
            'vario.predio.nombre_edificio' => 'nullable',
            'vario.predio.departamento_edificio' => 'nullable',
            'vario.predio.departamento_edificio' => 'nullable',
            'vario.predio.descripcion' => 'nullable',
            'vario.predio.lote' => 'nullable',
            'vario.predio.manzana' => 'nullable',
            'vario.predio.ejido' => 'nullable',
            'vario.predio.parcela' => 'nullable',
            'vario.predio.solar' => 'nullable',
            'vario.predio.poblado' => 'nullable',
            'vario.predio.numero_exterior_2' => 'nullable',
            'vario.predio.numero_adicional' => 'nullable',
            'vario.predio.numero_adicional_2' => 'nullable',
            'vario.predio.lote_fraccionador' => 'nullable',
            'vario.predio.manzana_fraccionador' => 'nullable',
            'vario.predio.etapa_fraccionador' => 'nullable',
            'vario.predio.clave_edificio' => 'nullable',
            'documento' => 'nullable|mimes:pdf|max:100000'
         ];
    }

    public function updated($property, $value){

        if(in_array($property, ['porcentaje_nuda', 'porcentaje_usufructo', 'porcentaje_propiedad']) && $value == ''){

            $this->$property = 0;

        }

        if(in_array($property, ['porcentaje_nuda', 'porcentaje_usufructo'])){

            $this->reset('porcentaje_propiedad');

        }elseif($property == 'porcentaje_propiedad'){

            $this->reset(['porcentaje_nuda', 'porcentaje_usufructo']);

        }

    }

    public function refresh(){

        $this->vario->predio->load('actores.persona');

    }

    public function cargarPredioInicial(){

        if(!$this->vario->predio_id){

            $predio = Predio::create([
                'status' => 'varios',
                'cp_localidad' => $this->vario->movimientoRegistral->folioReal->predio->cp_localidad,
                'cp_oficina' => $this->vario->movimientoRegistral->folioReal->predio->cp_oficina,
                'cp_tipo_predio' => $this->vario->movimientoRegistral->folioReal->predio->cp_tipo_predio,
                'cp_registro' => $this->vario->movimientoRegistral->folioReal->predio->cp_registro,
                'superficie_terreno' => $this->vario->movimientoRegistral->folioReal->predio->superficie_terreno,
                'unidad_area' => $this->vario->movimientoRegistral->folioReal->predio->unidad_area,
                'superficie_construccion' => $this->vario->movimientoRegistral->folioReal->predio->superficie_construccion,
                'monto_transaccion' => $this->vario->movimientoRegistral->folioReal->predio->monto_transaccion,
                'divisa' => $this->vario->movimientoRegistral->folioReal->predio->divisa,
                'observaciones' => $this->vario->movimientoRegistral->folioReal->predio->observaciones,
                'superficie_judicial' => $this->vario->movimientoRegistral->folioReal->predio->superficie_judicial,
                'superficie_notarial' => $this->vario->movimientoRegistral->folioReal->predio->superficie_notarial,
                'area_comun_terreno' => $this->vario->movimientoRegistral->folioReal->predio->area_comun_terreno,
                'area_comun_construccion' => $this->vario->movimientoRegistral->folioReal->predio->area_comun_construccion,
                'valor_terreno_comun' => $this->vario->movimientoRegistral->folioReal->predio->valor_terreno_comun,
                'valor_construccion_comun' => $this->vario->movimientoRegistral->folioReal->predio->valor_construccion_comun,
                'valor_total_terreno' => $this->vario->movimientoRegistral->folioReal->predio->valor_total_terreno,
                'valor_total_construccion' => $this->vario->movimientoRegistral->folioReal->predio->valor_total_construccion,
                'valor_catastral' => $this->vario->movimientoRegistral->folioReal->predio->valor_catastral,
                'codigo_postal' => $this->vario->movimientoRegistral->folioReal->predio->codigo_postal,
                'nombre_asentamiento' => $this->vario->movimientoRegistral->folioReal->predio->nombre_asentamiento,
                'municipio' => $this->vario->movimientoRegistral->folioReal->predio->municipio,
                'ciudad' => $this->vario->movimientoRegistral->folioReal->predio->ciudad,
                'tipo_asentamiento' => $this->vario->movimientoRegistral->folioReal->predio->tipo_asentamiento,
                'localidad' => $this->vario->movimientoRegistral->folioReal->predio->localidad,
                'tipo_vialidad' => $this->vario->movimientoRegistral->folioReal->predio->tipo_vialidad,
                'nombre_vialidad' => $this->vario->movimientoRegistral->folioReal->predio->nombre_vialidad,
                'numero_exterior' => $this->vario->movimientoRegistral->folioReal->predio->numero_exterior,
                'numero_interior' => $this->vario->movimientoRegistral->folioReal->predio->numero_interior,
                'nombre_edificio' => $this->vario->movimientoRegistral->folioReal->predio->nombre_edificio,
                'departamento_edificio' => $this->vario->movimientoRegistral->folioReal->predio->departamento_edificio,
                'departamento_edificio' => $this->vario->movimientoRegistral->folioReal->predio->departamento_edificio,
                'descripcion' => $this->vario->movimientoRegistral->folioReal->predio->descripcion,
                'lote' => $this->vario->movimientoRegistral->folioReal->predio->lote,
                'manzana' => $this->vario->movimientoRegistral->folioReal->predio->manzana,
                'ejido' => $this->vario->movimientoRegistral->folioReal->predio->ejido,
                'parcela' => $this->vario->movimientoRegistral->folioReal->predio->parcela,
                'solar' => $this->vario->movimientoRegistral->folioReal->predio->solar,
                'poblado' => $this->vario->movimientoRegistral->folioReal->predio->poblado,
                'numero_exterior_2' => $this->vario->movimientoRegistral->folioReal->predio->numero_exterior_2,
                'numero_adicional' => $this->vario->movimientoRegistral->folioReal->predio->numero_adicional,
                'numero_adicional_2' => $this->vario->movimientoRegistral->folioReal->predio->numero_adicional_2,
                'lote_fraccionador' => $this->vario->movimientoRegistral->folioReal->predio->lote_fraccionador,
                'manzana_fraccionador' => $this->vario->movimientoRegistral->folioReal->predio->manzana_fraccionador,
                'etapa_fraccionador' => $this->vario->movimientoRegistral->folioReal->predio->etapa_fraccionador,
                'clave_edificio' => $this->vario->movimientoRegistral->folioReal->predio->clave_edificio,
            ]);

            $this->vario->update(['predio_id' => $predio->id]);

            foreach ($this->vario->movimientoRegistral->folioReal->predio->colindancias as $colindancia) {

                $predio->colindancias()->create([
                    'viento' => $colindancia->viento,
                    'longitud' => $colindancia->longitud,
                    'descripcion' => $colindancia->descripcion,
                ]);

            }

            foreach ($this->vario->movimientoRegistral->folioReal->predio->propietarios() as $actor) {

                $predio->actores()->create([
                    'persona_id' => $actor->persona_id,
                    'tipo_actor' => $actor->tipo_actor,
                    'porcentaje_propiedad' => $actor->porcentaje_propiedad,
                    'porcentaje_nuda' => $actor->porcentaje_nuda,
                    'porcentaje_usufructo' => $actor->porcentaje_usufructo,
                ]);

            }

            $this->cargarColindancias($this->vario->predio);

        }else{

            $this->cargarColindancias($this->vario->predio);

            $this->vario->predio->load('actores.persona');

        }

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->vario->movimientoRegistral->update(['estado' => 'captura']);

                $this->vario->save();

                $this->vario->predio->save();

                $this->guardarColindancias($this->vario->predio);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar aclaración administrativa por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function borrarActor(Actor $actor){

        $this->authorize('update', $this->vario->movimientoRegistral);

        try {

            $actor->delete();

            $this->refresh();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en aclaración administrativa por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function procesarPredio(){

        $this->vario->movimientoRegistral->folioReal->predio->cp_localidad = $this->vario->predio->cp_localidad;
        $this->vario->movimientoRegistral->folioReal->predio->cp_oficina = $this->vario->predio->cp_oficina;
        $this->vario->movimientoRegistral->folioReal->predio->cp_tipo_predio = $this->vario->predio->cp_tipo_predio;
        $this->vario->movimientoRegistral->folioReal->predio->cp_registro = $this->vario->predio->cp_registro;
        $this->vario->movimientoRegistral->folioReal->predio->superficie_terreno = $this->vario->predio->superficie_terreno;
        $this->vario->movimientoRegistral->folioReal->predio->unidad_area = $this->vario->predio->unidad_area;
        $this->vario->movimientoRegistral->folioReal->predio->superficie_construccion = $this->vario->predio->superficie_construccion;
        $this->vario->movimientoRegistral->folioReal->predio->monto_transaccion = $this->vario->predio->monto_transaccion;
        $this->vario->movimientoRegistral->folioReal->predio->observaciones = $this->vario->predio->observaciones;
        $this->vario->movimientoRegistral->folioReal->predio->superficie_judicial = $this->vario->predio->superficie_judicial;
        $this->vario->movimientoRegistral->folioReal->predio->superficie_notarial = $this->vario->predio->superficie_notarial;
        $this->vario->movimientoRegistral->folioReal->predio->area_comun_terreno = $this->vario->predio->area_comun_terreno;
        $this->vario->movimientoRegistral->folioReal->predio->area_comun_construccion = $this->vario->predio->area_comun_construccion;
        $this->vario->movimientoRegistral->folioReal->predio->valor_terreno_comun = $this->vario->predio->valor_terreno_comun;
        $this->vario->movimientoRegistral->folioReal->predio->valor_construccion_comun = $this->vario->predio->valor_construccion_comun;
        $this->vario->movimientoRegistral->folioReal->predio->valor_total_terreno = $this->vario->predio->valor_total_terreno;
        $this->vario->movimientoRegistral->folioReal->predio->valor_total_construccion = $this->vario->predio->valor_total_construccion;
        $this->vario->movimientoRegistral->folioReal->predio->valor_catastral = $this->vario->predio->valor_catastral;
        $this->vario->movimientoRegistral->folioReal->predio->codigo_postal = $this->vario->predio->codigo_postal;
        $this->vario->movimientoRegistral->folioReal->predio->nombre_asentamiento = $this->vario->predio->nombre_asentamiento;
        $this->vario->movimientoRegistral->folioReal->predio->municipio = $this->vario->predio->municipio;
        $this->vario->movimientoRegistral->folioReal->predio->ciudad = $this->vario->predio->ciudad;
        $this->vario->movimientoRegistral->folioReal->predio->tipo_asentamiento = $this->vario->predio->tipo_asentamiento;
        $this->vario->movimientoRegistral->folioReal->predio->localidad = $this->vario->predio->localidad;
        $this->vario->movimientoRegistral->folioReal->predio->tipo_vialidad = $this->vario->predio->tipo_vialidad;
        $this->vario->movimientoRegistral->folioReal->predio->nombre_vialidad = $this->vario->predio->nombre_vialidad;
        $this->vario->movimientoRegistral->folioReal->predio->numero_exterior = $this->vario->predio->numero_exterior;
        $this->vario->movimientoRegistral->folioReal->predio->numero_interior = $this->vario->predio->numero_interior;
        $this->vario->movimientoRegistral->folioReal->predio->nombre_edificio = $this->vario->predio->nombre_edificio;
        $this->vario->movimientoRegistral->folioReal->predio->departamento_edificio = $this->vario->predio->departamento_edificio;
        $this->vario->movimientoRegistral->folioReal->predio->departamento_edificio = $this->vario->predio->departamento_edificio;
        $this->vario->movimientoRegistral->folioReal->predio->descripcion = $this->vario->predio->descripcion;
        $this->vario->movimientoRegistral->folioReal->predio->lote = $this->vario->predio->lote;
        $this->vario->movimientoRegistral->folioReal->predio->manzana = $this->vario->predio->manzana;
        $this->vario->movimientoRegistral->folioReal->predio->ejido = $this->vario->predio->ejido;
        $this->vario->movimientoRegistral->folioReal->predio->parcela = $this->vario->predio->parcela;
        $this->vario->movimientoRegistral->folioReal->predio->solar = $this->vario->predio->solar;
        $this->vario->movimientoRegistral->folioReal->predio->poblado = $this->vario->predio->poblado;
        $this->vario->movimientoRegistral->folioReal->predio->numero_exterior_2 = $this->vario->predio->numero_exterior_2;
        $this->vario->movimientoRegistral->folioReal->predio->numero_adicional = $this->vario->predio->numero_adicional;
        $this->vario->movimientoRegistral->folioReal->predio->numero_adicional_2 = $this->vario->predio->numero_adicional_2;
        $this->vario->movimientoRegistral->folioReal->predio->lote_fraccionador = $this->vario->predio->lote_fraccionador;
        $this->vario->movimientoRegistral->folioReal->predio->manzana_fraccionador = $this->vario->predio->manzana_fraccionador;
        $this->vario->movimientoRegistral->folioReal->predio->etapa_fraccionador = $this->vario->predio->etapa_fraccionador;
        $this->vario->movimientoRegistral->folioReal->predio->clave_edificio = $this->vario->predio->clave_edificio;
        $this->vario->movimientoRegistral->folioReal->predio->divisa = $this->vario->predio->divisa;

        $this->vario->movimientoRegistral->folioReal->predio->save();

        $this->vario->movimientoRegistral->folioReal->predio->colindancias()->delete();

        foreach ($this->vario->predio->colindancias as $colindancia) {

            $colindancia->update(['predio_id' => $this->vario->movimientoRegistral->folioReal->predio->id]);

        }

        foreach ($this->vario->movimientoRegistral->folioReal->predio->propietarios() as $propietario) {
            $propietario->delete();
        }

        foreach ($this->vario->predio->propietarios() as $propietario) {
            $propietario->update(['actorable_id' => $this->vario->movimientoRegistral->folioReal->predio->id]);
        }

        $this->vario->predio->colindancias()->delete();

        foreach ($this->vario->predio->propietarios() as $propietario) {
            $propietario->delete();
        }

        $this->vario->predio->delete();

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->guardarColindancias($this->vario->predio);

                $this->vario->estado = 'activo';
                $this->vario->actualizado_por = auth()->id();
                $this->vario->fecha_inscripcion = now()->toDateString();
                $this->vario->predio_id = null;
                $this->vario->save();

                $this->vario->movimientoRegistral->update(['estado' => 'elaborado']);

                $this->procesarPredio();

                (new VariosController())->caratula($this->vario);

            });

            return redirect()->route('varios');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function revisarProcentajesFinal(){

        $pn = 0;

        $pu = 0;

        $pp = 0;

        foreach($this->vario->predio->propietarios() as $propietario){

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        if($pp == 0){

            if($pn <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de nuda propiedad no es el 100%."]);

                return true;

            }

            if($pu <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de usufructo no es el 100%."]);

                return true;

            }

        }else{


            if(($pn + $pp) <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de nuda propiedad no es el 100%."]);

                return true;

            }

            if(($pu + $pp) <= 99.99){

                $this->dispatch('mostrarMensaje', ['error', "El porcentaje de usufructo no es el 100%."]);

                return true;

            }

        }

    }

    public function finalizar(){

        $this->validate();

        if($this->revisarProcentajesFinal()){

            return;

        }

        if(!$this->vario->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        $this->modalContraseña = true;

    }

    public function mount(){

        $this->vario->acto_contenido = $this->vario->acto_contenido ?? 'ACLARACIÓN ADMINISTRATIVA';

        $this->cargarPredioInicial();

        $this->areas = Constantes::UNIDADES;

        $this->divisas = Constantes::DIVISAS;

        $this->vientos = Constantes::VIENTOS;

        $this->tipos_vialidades = Constantes::TIPO_VIALIDADES;

        $this->tipos_asentamientos = Constantes::TIPO_ASENTAMIENTO;

    }

    public function render()
    {
        return view('livewire.varios.aclaracion-administrativa');
    }

}
