<?php

namespace App\Livewire\Sentencias;

use App\Models\File;
use App\Models\Actor;
use App\Models\Predio;
use Livewire\Component;
use App\Models\Sentencia;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\DB;
use App\Exceptions\PredioException;
use Illuminate\Support\Facades\Log;
use App\Http\Services\PredioService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\LivewireFilepond\WithFilePond;
use App\Traits\Inscripciones\ColindanciasTrait;
use Illuminate\Http\Client\ConnectionException;
use App\Traits\Inscripciones\Sentencias\SentenciaTrait;
use App\Http\Controllers\Sentencias\SentenciasController;
use App\Traits\Inscripciones\DocumentoEntradaTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;

class Rectificatoria extends Component
{

    use WithFileUploads;
    use SentenciaTrait;
    use WithFilePond;
    use ColindanciasTrait;
    use DocumentoEntradaTrait;
    use GuardarDocumentoEntradaTrait;

    public $areas;
    public $divisas;
    public $vientos;
    public $tipos_vialidades;
    public $tipos_asentamientos;

    public Sentencia $sentencia;

    public $predio;
    public $sentenciaPredio;

    public $folio_real;
    public $folio_movimiento;
    public $movimientoCancelar;

    protected $listeners = ['refresh'];

    protected function rules(){
        return [
            'documento' => 'nullable|mimes:pdf|max:100000',
            'sentencia.acto_contenido' => 'required',
            'sentencia.descripcion' => 'required',
            'sentencia.tipo' => 'required',
            'sentencia.hojas' => 'nullable',
            'sentencia.expediente' => 'nullable',
            'sentencia.tomo' => 'nullable',
            'sentencia.registro' => 'nullable',
            'sentenciaPredio.superficie_terreno' => 'required',
            'sentenciaPredio.unidad_area' => 'required',
            'sentenciaPredio.superficie_construccion' => 'required',
            'sentenciaPredio.monto_transaccion' => 'required',
            'sentenciaPredio.observaciones' => 'nullable',
            'sentenciaPredio.curt' => 'nullable',
            'sentenciaPredio.superficie_judicial' => 'nullable',
            'sentenciaPredio.superficie_notarial' => 'nullable',
            'sentenciaPredio.area_comun_terreno' => 'nullable',
            'sentenciaPredio.area_comun_construccion' => 'nullable',
            'sentenciaPredio.valor_terreno_comun' => 'nullable',
            'sentenciaPredio.valor_construccion_comun' => 'nullable',
            'sentenciaPredio.valor_total_terreno' => 'nullable',
            'sentenciaPredio.valor_total_construccion' => 'nullable',
            'sentenciaPredio.valor_catastral' => 'nullable',
            'sentenciaPredio.divisa' => 'required',
            'sentenciaPredio.codigo_postal' => 'nullable',
            'sentenciaPredio.nombre_asentamiento' => 'nullable',
            'sentenciaPredio.municipio' => 'nullable',
            'sentenciaPredio.ciudad' => 'nullable',
            'sentenciaPredio.tipo_asentamiento' => 'nullable',
            'sentenciaPredio.localidad' => 'nullable',
            'sentenciaPredio.tipo_vialidad' => 'nullable',
            'sentenciaPredio.nombre_vialidad' => 'nullable',
            'sentenciaPredio.numero_exterior' => 'nullable',
            'sentenciaPredio.numero_interior' => 'nullable',
            'sentenciaPredio.nombre_edificio' => 'nullable',
            'sentenciaPredio.departamento_edificio' => 'nullable',
            'sentenciaPredio.departamento_edificio' => 'nullable',
            'sentenciaPredio.descripcion' => 'nullable',
            'sentenciaPredio.lote' => 'nullable',
            'sentenciaPredio.manzana' => 'nullable',
            'sentenciaPredio.ejido' => 'nullable',
            'sentenciaPredio.parcela' => 'nullable',
            'sentenciaPredio.solar' => 'nullable',
            'sentenciaPredio.poblado' => 'nullable',
            'sentenciaPredio.numero_exterior_2' => 'nullable',
            'sentenciaPredio.numero_adicional' => 'nullable',
            'sentenciaPredio.numero_adicional_2' => 'nullable',
            'sentenciaPredio.lote_fraccionador' => 'nullable',
            'sentenciaPredio.manzana_fraccionador' => 'nullable',
            'sentenciaPredio.etapa_fraccionador' => 'nullable',
            'sentenciaPredio.clave_edificio' => 'nullable',
            'tipo_documento' => 'required',
            'autoridad_cargo' => 'required',
            'autoridad_nombre' => 'required',
            'numero_documento' => 'nullable',
            'fecha_emision' => 'required',
            'procedencia' => 'nullable',
         ];
    }

    public function refresh(){

        $this->sentenciaPredio->load('actores.persona');

    }

    public function consultarArchivo(){

        if(!$this->sentencia->movimientoRegistral->documentoEntrada()){

            try {

                $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                    ->accept('application/json')
                                    ->asForm()
                                    ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                        'año' => $this->sentencia->movimientoRegistral->año,
                                                                                        'tramite' => $this->sentencia->movimientoRegistral->tramite,
                                                                                        'usuario' => $this->sentencia->movimientoRegistral->usuario,
                                                                                        'estado' => 'nuevo'
                                                                                    ]);

                $data = collect(json_decode($response, true));

                if($response->status() == 200){

                    $contents = file_get_contents($data['url']);

                    $filename = basename($data['url']);

                    Storage::disk('documento_entrada')->put($filename, $contents);

                    File::create([
                        'fileable_id' => $this->inscripcion->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $filename
                    ]);

                }

            } catch (ConnectionException $th) {

                Log::error("Error al cargar archivo en varios: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

                $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

            }

        }

    }

    public function finalizar(){

        $this->validate();

        if(!$this->sentencia->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        try {

            (new PredioService())->revisarPorcentajesFinal($this->sentenciaPredio->propietarios());

        } catch (PredioException $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);
            return;

        }

        $this->modalContraseña = true;

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->guardar();

                $this->sentencia->estado = 'activo';
                $this->sentencia->actualizado_por = auth()->id();
                $this->sentencia->fecha_inscripcion = now()->toDateString();

                $this->sentencia->movimientoRegistral->update(['estado' => 'elaborado']);

                $this->procesarPredio();

                $this->sentencia->save();

                $this->actualizarDocumentoEntrada($this->sentencia->movimientoRegistral);

                $this->sentencia->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->sentencia->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de sentencia']);

                (new SentenciasController())->caratula($this->sentencia);

            });

            return redirect()->route('sentencias');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                if($this->sentencia->movimientoRegistral->estado != 'correccion')
                    $this->sentencia->movimientoRegistral->estado = 'captura';

                $this->sentencia->movimientoRegistral->actualizado_por = auth()->id();
                $this->sentencia->movimientoRegistral->save();

                $this->sentenciaPredio->save();

                $this->sentencia->save();

                $this->actualizarDocumentoEntrada($this->sentencia->movimientoRegistral);

                $this->guardarColindancias($this->sentenciaPredio);

            });



            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function borrarActor(Actor $actor){

        $this->authorize('update', $this->sentencia->movimientoRegistral);

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function revisarProcentajes($id = null){

        $pp = 0;

        $pn = 0;

        $pu = 0;

        foreach($this->sentenciaPredio->propietarios() as $propietario){

            if($id == $propietario->id)
                continue;

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        $pp = $pp + (float)$this->porcentaje_propiedad;

        $pn = $pn + (float)$this->porcentaje_nuda + $pp;

        $pu = $pu + (float)$this->porcentaje_usufructo + $pp;

        if($pn > 100 || $pu > 100)
            return true;
        else
            return false;

    }

    public function cargarPredioInicial(){

        if(!$this->sentencia->predio_id){

            $predio = Predio::create([
                'status' => 'sentencia',
                'cp_localidad' => $this->sentencia->movimientoRegistral->folioReal->predio->cp_localidad,
                'cp_oficina' => $this->sentencia->movimientoRegistral->folioReal->predio->cp_oficina,
                'cp_tipo_predio' => $this->sentencia->movimientoRegistral->folioReal->predio->cp_tipo_predio,
                'cp_registro' => $this->sentencia->movimientoRegistral->folioReal->predio->cp_registro,
                'superficie_terreno' => $this->sentencia->movimientoRegistral->folioReal->predio->superficie_terreno,
                'unidad_area' => $this->sentencia->movimientoRegistral->folioReal->predio->unidad_area,
                'superficie_construccion' => $this->sentencia->movimientoRegistral->folioReal->predio->superficie_construccion,
                'monto_transaccion' => $this->sentencia->movimientoRegistral->folioReal->predio->monto_transaccion,
                'observaciones' => $this->sentencia->movimientoRegistral->folioReal->predio->observaciones,
                'superficie_judicial' => $this->sentencia->movimientoRegistral->folioReal->predio->superficie_judicial,
                'superficie_notarial' => $this->sentencia->movimientoRegistral->folioReal->predio->superficie_notarial,
                'area_comun_terreno' => $this->sentencia->movimientoRegistral->folioReal->predio->area_comun_terreno,
                'divisa' => $this->sentencia->movimientoRegistral->folioReal->predio->divisa,
                'area_comun_construccion' => $this->sentencia->movimientoRegistral->folioReal->predio->area_comun_construccion,
                'valor_terreno_comun' => $this->sentencia->movimientoRegistral->folioReal->predio->valor_terreno_comun,
                'valor_construccion_comun' => $this->sentencia->movimientoRegistral->folioReal->predio->valor_construccion_comun,
                'valor_total_terreno' => $this->sentencia->movimientoRegistral->folioReal->predio->valor_total_terreno,
                'valor_total_construccion' => $this->sentencia->movimientoRegistral->folioReal->predio->valor_total_construccion,
                'valor_catastral' => $this->sentencia->movimientoRegistral->folioReal->predio->valor_catastral,
                'codigo_postal' => $this->sentencia->movimientoRegistral->folioReal->predio->codigo_postal,
                'nombre_asentamiento' => $this->sentencia->movimientoRegistral->folioReal->predio->nombre_asentamiento,
                'municipio' => $this->sentencia->movimientoRegistral->folioReal->predio->municipio,
                'ciudad' => $this->sentencia->movimientoRegistral->folioReal->predio->ciudad,
                'tipo_asentamiento' => $this->sentencia->movimientoRegistral->folioReal->predio->tipo_asentamiento,
                'localidad' => $this->sentencia->movimientoRegistral->folioReal->predio->localidad,
                'tipo_vialidad' => $this->sentencia->movimientoRegistral->folioReal->predio->tipo_vialidad,
                'nombre_vialidad' => $this->sentencia->movimientoRegistral->folioReal->predio->nombre_vialidad,
                'numero_exterior' => $this->sentencia->movimientoRegistral->folioReal->predio->numero_exterior,
                'numero_interior' => $this->sentencia->movimientoRegistral->folioReal->predio->numero_interior,
                'nombre_edificio' => $this->sentencia->movimientoRegistral->folioReal->predio->nombre_edificio,
                'departamento_edificio' => $this->sentencia->movimientoRegistral->folioReal->predio->departamento_edificio,
                'departamento_edificio' => $this->sentencia->movimientoRegistral->folioReal->predio->departamento_edificio,
                'descripcion' => $this->sentencia->movimientoRegistral->folioReal->predio->descripcion,
                'lote' => $this->sentencia->movimientoRegistral->folioReal->predio->lote,
                'manzana' => $this->sentencia->movimientoRegistral->folioReal->predio->manzana,
                'ejido' => $this->sentencia->movimientoRegistral->folioReal->predio->ejido,
                'parcela' => $this->sentencia->movimientoRegistral->folioReal->predio->parcela,
                'solar' => $this->sentencia->movimientoRegistral->folioReal->predio->solar,
                'poblado' => $this->sentencia->movimientoRegistral->folioReal->predio->poblado,
                'numero_exterior_2' => $this->sentencia->movimientoRegistral->folioReal->predio->numero_exterior_2,
                'numero_adicional' => $this->sentencia->movimientoRegistral->folioReal->predio->numero_adicional,
                'numero_adicional_2' => $this->sentencia->movimientoRegistral->folioReal->predio->numero_adicional_2,
                'lote_fraccionador' => $this->sentencia->movimientoRegistral->folioReal->predio->lote_fraccionador,
                'manzana_fraccionador' => $this->sentencia->movimientoRegistral->folioReal->predio->manzana_fraccionador,
                'etapa_fraccionador' => $this->sentencia->movimientoRegistral->folioReal->predio->etapa_fraccionador,
                'clave_edificio' => $this->sentencia->movimientoRegistral->folioReal->predio->clave_edificio,
            ]);

            $this->sentencia->update(['predio_id' => $predio->id]);

            $this->copiarColindancias($this->sentencia->movimientoRegistral->folioReal->predio, $predio->id);

            foreach ($this->sentencia->movimientoRegistral->folioReal->predio->propietarios() as $actor) {

                $this->sentencia->predio->actores()->create([
                    'persona_id' => $actor->persona_id,
                    'tipo_actor' => $actor->tipo_actor,
                    'porcentaje_propiedad' => $actor->porcentaje_propiedad,
                    'porcentaje_nuda' => $actor->porcentaje_nuda,
                    'porcentaje_usufructo' => $actor->porcentaje_usufructo,
                ]);

            }

        }else{

            $this->cargarColindancias($this->sentencia->predio);

            $this->sentencia->predio->load('actores.persona');

        }

    }

    public function procesarPredio(){

        $this->sentencia->movimientoRegistral->folioReal->predio->cp_localidad = $this->sentenciaPredio->cp_localidad;
        $this->sentencia->movimientoRegistral->folioReal->predio->cp_oficina = $this->sentenciaPredio->cp_oficina;
        $this->sentencia->movimientoRegistral->folioReal->predio->cp_tipo_predio = $this->sentenciaPredio->cp_tipo_predio;
        $this->sentencia->movimientoRegistral->folioReal->predio->cp_registro = $this->sentenciaPredio->cp_registro;
        $this->sentencia->movimientoRegistral->folioReal->predio->superficie_terreno = $this->sentenciaPredio->superficie_terreno;
        $this->sentencia->movimientoRegistral->folioReal->predio->unidad_area = $this->sentenciaPredio->unidad_area;
        $this->sentencia->movimientoRegistral->folioReal->predio->superficie_construccion = $this->sentenciaPredio->superficie_construccion;
        $this->sentencia->movimientoRegistral->folioReal->predio->monto_transaccion = $this->sentenciaPredio->monto_transaccion;
        $this->sentencia->movimientoRegistral->folioReal->predio->observaciones = $this->sentenciaPredio->observaciones;
        $this->sentencia->movimientoRegistral->folioReal->predio->divisa = $this->sentenciaPredio->divisa;
        $this->sentencia->movimientoRegistral->folioReal->predio->superficie_judicial = $this->sentenciaPredio->superficie_judicial;
        $this->sentencia->movimientoRegistral->folioReal->predio->superficie_notarial = $this->sentenciaPredio->superficie_notarial;
        $this->sentencia->movimientoRegistral->folioReal->predio->area_comun_terreno = $this->sentenciaPredio->area_comun_terreno;
        $this->sentencia->movimientoRegistral->folioReal->predio->area_comun_construccion = $this->sentenciaPredio->area_comun_construccion;
        $this->sentencia->movimientoRegistral->folioReal->predio->valor_terreno_comun = $this->sentenciaPredio->valor_terreno_comun;
        $this->sentencia->movimientoRegistral->folioReal->predio->valor_construccion_comun = $this->sentenciaPredio->valor_construccion_comun;
        $this->sentencia->movimientoRegistral->folioReal->predio->valor_total_terreno = $this->sentenciaPredio->valor_total_terreno;
        $this->sentencia->movimientoRegistral->folioReal->predio->valor_total_construccion = $this->sentenciaPredio->valor_total_construccion;
        $this->sentencia->movimientoRegistral->folioReal->predio->valor_catastral = $this->sentenciaPredio->valor_catastral;
        $this->sentencia->movimientoRegistral->folioReal->predio->codigo_postal = $this->sentenciaPredio->codigo_postal;
        $this->sentencia->movimientoRegistral->folioReal->predio->nombre_asentamiento = $this->sentenciaPredio->nombre_asentamiento;
        $this->sentencia->movimientoRegistral->folioReal->predio->municipio = $this->sentenciaPredio->municipio;
        $this->sentencia->movimientoRegistral->folioReal->predio->ciudad = $this->sentenciaPredio->ciudad;
        $this->sentencia->movimientoRegistral->folioReal->predio->tipo_asentamiento = $this->sentenciaPredio->tipo_asentamiento;
        $this->sentencia->movimientoRegistral->folioReal->predio->localidad = $this->sentenciaPredio->localidad;
        $this->sentencia->movimientoRegistral->folioReal->predio->tipo_vialidad = $this->sentenciaPredio->tipo_vialidad;
        $this->sentencia->movimientoRegistral->folioReal->predio->nombre_vialidad = $this->sentenciaPredio->nombre_vialidad;
        $this->sentencia->movimientoRegistral->folioReal->predio->numero_exterior = $this->sentenciaPredio->numero_exterior;
        $this->sentencia->movimientoRegistral->folioReal->predio->numero_interior = $this->sentenciaPredio->numero_interior;
        $this->sentencia->movimientoRegistral->folioReal->predio->nombre_edificio = $this->sentenciaPredio->nombre_edificio;
        $this->sentencia->movimientoRegistral->folioReal->predio->departamento_edificio = $this->sentenciaPredio->departamento_edificio;
        $this->sentencia->movimientoRegistral->folioReal->predio->departamento_edificio = $this->sentenciaPredio->departamento_edificio;
        $this->sentencia->movimientoRegistral->folioReal->predio->descripcion = $this->sentenciaPredio->descripcion;
        $this->sentencia->movimientoRegistral->folioReal->predio->lote = $this->sentenciaPredio->lote;
        $this->sentencia->movimientoRegistral->folioReal->predio->manzana = $this->sentenciaPredio->manzana;
        $this->sentencia->movimientoRegistral->folioReal->predio->ejido = $this->sentenciaPredio->ejido;
        $this->sentencia->movimientoRegistral->folioReal->predio->parcela = $this->sentenciaPredio->parcela;
        $this->sentencia->movimientoRegistral->folioReal->predio->solar = $this->sentenciaPredio->solar;
        $this->sentencia->movimientoRegistral->folioReal->predio->poblado = $this->sentenciaPredio->poblado;
        $this->sentencia->movimientoRegistral->folioReal->predio->numero_exterior_2 = $this->sentenciaPredio->numero_exterior_2;
        $this->sentencia->movimientoRegistral->folioReal->predio->numero_adicional = $this->sentenciaPredio->numero_adicional;
        $this->sentencia->movimientoRegistral->folioReal->predio->numero_adicional_2 = $this->sentenciaPredio->numero_adicional_2;
        $this->sentencia->movimientoRegistral->folioReal->predio->lote_fraccionador = $this->sentenciaPredio->lote_fraccionador;
        $this->sentencia->movimientoRegistral->folioReal->predio->manzana_fraccionador = $this->sentenciaPredio->manzana_fraccionador;
        $this->sentencia->movimientoRegistral->folioReal->predio->etapa_fraccionador = $this->sentenciaPredio->etapa_fraccionador;
        $this->sentencia->movimientoRegistral->folioReal->predio->clave_edificio = $this->sentenciaPredio->clave_edificio;
        $this->sentencia->movimientoRegistral->folioReal->predio->partes_iguales = $this->sentenciaPredio->partes_iguales;

        $this->sentencia->movimientoRegistral->folioReal->predio->save();

        $this->sentencia->movimientoRegistral->folioReal->predio->colindancias()->delete();

        $this->guardarColindancias($this->sentencia->movimientoRegistral->folioReal->predio);

        foreach ($this->sentencia->movimientoRegistral->folioReal->predio->propietarios() as $propietario) {
            $propietario->delete();
        }

        foreach ($this->sentenciaPredio->propietarios() as $propietario) {
            $propietario->update(['actorable_id' => $this->sentencia->movimientoRegistral->folioReal->predio->id]);
        }

        $this->sentenciaPredio->colindancias()->delete();

        foreach ($this->sentenciaPredio->propietarios() as $propietario) {
            $propietario->delete();
        }

        $this->sentenciaPredio->delete();

        $this->sentencia->predio_id = null;

    }

    public function mount(){

        $this->consultarArchivo();

        $this->cargarPredioInicial();

        $this->sentenciaPredio = $this->sentencia->predio;

        $this->actos = Constantes::ACTOS_INSCRIPCION_SENTENCIAS;

        $this->areas = Constantes::UNIDADES;

        $this->divisas = Constantes::DIVISAS;

        $this->vientos = Constantes::VIENTOS;

        $this->tipos_vialidades = Constantes::TIPO_VIALIDADES;

        $this->tipos_asentamientos = Constantes::TIPO_ASENTAMIENTO;

        $this->cargarDocumentoEntrada($this->sentencia->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.sentencias.rectificatoria');
    }
}
