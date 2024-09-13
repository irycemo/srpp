<?php

namespace App\Livewire\Sentencias;

use App\Models\File;
use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Colindancia;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Sentencia as ModelsSentencia;
use Illuminate\Http\Client\ConnectionException;
use Livewire\WithFileUploads;

class Sentencia extends Component
{

    use WithFileUploads;

    public $actos;
    public $areas;
    public $divisas;
    public $vientos;
    public $tipos_vialidades;
    public $tipos_asentamientos;
    public $modalContraseña = false;
    public $modalPropietario = false;
    public $modalDocumento = false;
    public $crear = false;
    public $editar = false;
    public $documento;
    public $contraseña;

    public $medidas = [];

    public ModelsSentencia $sentencia;

    public $predio;
    public $sentenciaPredio;

    public $actor;

    public $tipo_propietario;
    public $porcentaje_propiedad = 0.00;
    public $porcentaje_nuda = 0.00;
    public $porcentaje_usufructo = 0.00;
    public $tipo_persona;
    public $nombre;
    public $ap_paterno;
    public $ap_materno;
    public $curp;
    public $rfc;
    public $razon_social;
    public $fecha_nacimiento;
    public $nacionalidad;
    public $estado_civil;
    public $calle;
    public $numero_exterior_propietario;
    public $numero_interior_propietario;
    public $colonia;
    public $cp;
    public $entidad;
    public $ciudad;
    public $municipio_propietario;

    public $folio_real;
    public $folio_movimiento;
    public $movimientoCancelar;

    public $observaciones;
    public $acto_contenido;
    public $tipo;
    public $valor_gravamen;
    public $fecha_inscripcion;


    protected function rules(){
        return [
            'sentencia.acto_contenido' => 'required',
            'sentencia.descripcion' => 'required',
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

    public function consultarArchivo(){

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

                $this->dispatch('ver_documento', ['url' => $data['url']]);

            }else{

                $this->dispatch('mostrarMensaje', ['error', "No se encontro el documento."]);

            }

        } catch (ConnectionException $th) {

            Log::error("Error al cargar archivo en varios: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizar(){

        $this->validate();

        if(!$this->sentencia->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

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

                $this->sentencia->estado = 'activo';
                $this->sentencia->actualizado_por = auth()->id();
                $this->sentencia->fecha_inscripcion = now()->toDateString();
                $this->sentencia->save();

                $this->sentencia->movimientoRegistral->update(['estado' => 'elaborado']);

                if($this->sentencia->acto_contenido == 'CANCELACIÓN DE INSCRIPCIÓN'){

                    if($this->movimientoCancelar->inscripcionPropiedad){

                        $this->movimientoCancelar->inscripcionPropiedad->update([
                            'observaciones' => $this->movimientoCancelar->inscripcionPropiedad->observaciones . ' ' . 'Cancelado mediante movimiento registral: ' . $this->sentencia->movimientoRegistral->folioReal->folio . '-' . $this->sentencia->movimientoRegistral->folio,
                            'estado' => 'cancelado',
                            'actualizado_por' => auth()->id()
                        ]);

                        $this->movimientoCancelar->update(['movimiento_padre' => $this->sentencia->movimientoRegistral->id]);

                    }elseif($this->movimientoCancelar->cancelacion){

                        $this->movimientoCancelar->cancelacion->update([
                            'observaciones' => $this->movimientoCancelar->cancelacion->observaciones . ' ' . 'Cancelado mediante movimiento registral: ' . $this->sentencia->movimientoRegistral->folioReal->folio . '-' . $this->sentencia->movimientoRegistral->folio,
                            'estado' => 'cancelado',
                            'actualizado_por' => auth()->id()
                        ]);

                        $this->movimientoCancelar->update(['movimiento_padre' => $this->sentencia->movimientoRegistral->id]);

                    }elseif($this->movimientoCancelar->gravamen){

                        $this->movimientoCancelar->gravamen->update([
                            'observaciones' => $this->movimientoCancelar->gravamen->observaciones . ' ' . 'Cancelado mediante movimiento registral: ' . $this->sentencia->movimientoRegistral->folioReal->folio . '-' . $this->sentencia->movimientoRegistral->folio,
                            'estado' => 'cancelado',
                            'actualizado_por' => auth()->id()
                        ]);

                        $this->movimientoCancelar->update(['movimiento_padre' => $this->sentencia->movimientoRegistral->id]);

                    }elseif($this->movimientoCancelar->sentencia){

                        $this->movimientoCancelar->sentencia->update([
                            'descripcion' => $this->movimientoCancelar->sentencia->descripcion . ' ' . 'Cancelado mediante movimiento registral: ' . $this->sentencia->movimientoRegistral->folioReal->folio . '-' . $this->sentencia->movimientoRegistral->folio,
                            'estado' => 'cancelado',
                            'actualizado_por' => auth()->id()
                        ]);

                        $this->movimientoCancelar->update(['movimiento_padre' => $this->sentencia->movimientoRegistral->id]);

                    }elseif($this->movimientoCancelar->vario){

                        $this->movimientoCancelar->vario->update([
                            'descripcion' => $this->movimientoCancelar->vario->descripcion . ' ' . 'Cancelado mediante movimiento registral: ' . $this->sentencia->movimientoRegistral->folioReal->folio . '-' . $this->sentencia->movimientoRegistral->folio,
                            'estado' => 'cancelado',
                            'actualizado_por' => auth()->id()
                        ]);

                        $this->movimientoCancelar->update(['movimiento_padre' => $this->sentencia->movimientoRegistral->id]);

                    }

                }

                foreach ($this->medidas as $key =>$medida) {

                    if($medida['id'] == null){

                        $aux = $this->sentenciaPredio->colindancias()->create([
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

                $this->propcesarPredio();

            });

            return redirect()->route('sentencias');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                $this->sentencia->movimientoRegistral->update(['estado' => 'captura']);

                $this->sentencia->save();

                $this->sentenciaPredio->save();

                foreach ($this->medidas as $key =>$medida) {

                    if($medida['id'] == null){

                        $aux = $this->sentenciaPredio->colindancias()->create([
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

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function agregarColindancia(){

        $this->medidas[] = ['viento' => null, 'longitud' => null, 'descripcion' => null, 'id' => null];

    }

    public function borrarColindancia($index){

        $this->authorize('update',  $this->sentencia->movimientoRegistral);

        try {

            $this->sentenciaPredio->colindancias()->where('id', $this->medidas[$index]['id'])->delete();

        } catch (\Throwable $th) {
            Log::error("Error al borrar colindancia en sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
        }

        unset($this->medidas[$index]);

        $this->medidas = array_values($this->medidas);

    }

    public function agregarPropietario(){

        $this->modalPropietario = true;
        $this->crear = true;

    }

    public function guardarPropietario(){

        $this->authorize('update', $this->sentencia->movimientoRegistral);

        $this->validate([
            'porcentaje_propiedad' => 'nullable|numeric|min:0|max:100',
            'porcentaje_nuda' => 'nullable|numeric|min:0|max:100',
            'porcentaje_usufructo' => 'nullable|numeric|min:0|max:100',
            'tipo_persona' => 'required',
            'nombre' => [
                Rule::requiredIf($this->tipo_persona === 'FISICA')
            ],
            'ap_paterno' => Rule::requiredIf($this->tipo_persona === 'FISICA'),
            'ap_materno' => Rule::requiredIf($this->tipo_persona === 'FISICA'),
            'curp' => [
                'nullable',
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => [Rule::requiredIf($this->tipo_persona === 'MORAL')],
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable',
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior_propietario' => 'nullable',
            'numero_interior_propietario' => 'nullable',
            'colonia' => 'nullable',
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio_propietario' => 'nullable',
        ]);

        if($this->porcentaje_propiedad === 0 && $this->porcentaje_nuda === 0 && $this->porcentaje_usufructo === 0){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede ser 0."]);

            return;

        }

        if($this->revisarProcentajes()){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede exceder el 100%."]);

            return;

        }

        $persona = Persona::query()
                    ->where(function($q){
                        $q->when($this->nombre, fn($q) => $q->where('nombre', $this->nombre))
                            ->when($this->ap_paterno, fn($q) => $q->where('ap_paterno', $this->ap_paterno))
                            ->when($this->ap_materno, fn($q) => $q->where('ap_materno', $this->ap_materno));
                    })
                    ->when($this->razon_social, fn($q) => $q->orWhere('razon_social', $this->razon_social))
                    ->when($this->rfc, fn($q) => $q->orWhere('rfc', $this->rfc))
                    ->when($this->curp, fn($q) => $q->orWhere('curp', $this->curp))
                    ->first();

        if($persona){

            foreach ($this->predio->propietarios() as $propietario) {

                if($persona->id == $propietario->persona_id){

                    $this->dispatch('mostrarMensaje', ['error', "La persona ya es un propietario."]);

                    return;

                }

            }

        }

        try {

            DB::transaction(function () use ($persona){

                if($persona != null){

                    $persona->update([
                        'estado_civil' => $this->estado_civil,
                        'calle' => $this->calle,
                        'numero_exterior' => $this->numero_exterior_propietario,
                        'numero_interior' => $this->numero_interior_propietario,
                        'colonia' => $this->colonia,
                        'cp' => $this->cp,
                        'entidad' => $this->entidad,
                        'municipio' => $this->municipio_propietario,
                        'actualizado_por' => auth()->id()
                    ]);

                }else{

                    $persona = Persona::create([
                        'tipo' => $this->tipo_persona,
                        'nombre' => $this->nombre,
                        'ap_paterno' => $this->ap_paterno,
                        'ap_materno' => $this->ap_materno,
                        'curp' => $this->curp,
                        'rfc' => $this->rfc,
                        'razon_social' => $this->razon_social,
                        'fecha_nacimiento' => $this->fecha_nacimiento,
                        'nacionalidad' => $this->nacionalidad,
                        'estado_civil' => $this->estado_civil,
                        'calle' => $this->calle,
                        'numero_exterior' => $this->numero_exterior_propietario,
                        'numero_interior' => $this->numero_interior_propietario,
                        'colonia' => $this->colonia,
                        'cp' => $this->cp,
                        'entidad' => $this->entidad,
                        'municipio' => $this->municipio_propietario,
                        'creado_por' => auth()->id()
                    ]);

                }

                $this->sentenciaPredio->actores()->create([
                    'persona_id' => $persona->id,
                    'tipo_actor' => 'propietario',
                    'porcentaje_propiedad' => $this->porcentaje_propiedad,
                    'porcentaje_nuda' => $this->porcentaje_nuda,
                    'porcentaje_usufructo' => $this->porcentaje_usufructo,
                    'creado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "El propietario se guardó con éxito."]);

                $this->resetear();

                $this->sentenciaPredio->refresh();

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar propietario en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function editarActor(Actor $actor, $tipo){

        $this->resetear();

        $this->actor = $actor;

        $this->tipo_propietario = $actor->tipo_actor;
        $this->porcentaje_propiedad = $actor->porcentaje_propiedad;
        $this->porcentaje_nuda = $actor->porcentaje_nuda;
        $this->porcentaje_usufructo = $actor->porcentaje_usufructo;
        $this->tipo_persona = $actor->persona->tipo;
        $this->nombre = $actor->persona->nombre;
        $this->ap_paterno = $actor->persona->ap_paterno;
        $this->ap_materno = $actor->persona->ap_materno;
        $this->curp = $actor->persona->curp;
        $this->rfc = $actor->persona->rfc;
        $this->razon_social = $actor->persona->razon_social;
        $this->fecha_nacimiento = $actor->persona->fecha_nacimiento;
        $this->nacionalidad = $actor->persona->nacionalidad;
        $this->estado_civil = $actor->persona->estado_civil;
        $this->calle = $actor->persona->calle;
        $this->numero_exterior_propietario = $actor->persona->numero_exterior;
        $this->numero_interior_propietario = $actor->persona->numero_interior;
        $this->colonia = $actor->persona->colonia;
        $this->cp = $actor->persona->cp;
        $this->entidad = $actor->persona->entidad;
        $this->municipio_propietario = $actor->persona->municipio;

        $this->modalPropietario = true;

        $this->crear = false;

        $this->editar = true;

    }

    public function actualizarActor(){

        $this->validate([
            'porcentaje_propiedad' => 'nullable|numeric|min:0|max:100',
            'porcentaje_nuda' => 'nullable|numeric|min:0|max:100',
            'porcentaje_usufructo' => 'nullable|numeric|min:0|max:100',
            'tipo_persona' => 'required',
            'nombre' => [
                Rule::requiredIf($this->tipo_persona === 'FISICA')
            ],
            'ap_paterno' => Rule::requiredIf($this->tipo_persona === 'FISICA'),
            'ap_materno' => Rule::requiredIf($this->tipo_persona === 'FISICA'),
            'curp' => [
                'nullable',
                'regex:/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i'
            ],
            'rfc' => [
                'nullable',
                'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'
            ],
            'razon_social' => [Rule::requiredIf($this->tipo_persona === 'MORAL')],
            'fecha_nacimiento' => 'nullable',
            'nacionalidad' => 'nullable',
            'estado_civil' => 'nullable',
            'calle' => 'nullable',
            'numero_exterior_propietario' => 'nullable',
            'numero_interior_propietario' => 'nullable',
            'colonia' => 'nullable',
            'cp' => 'nullable|numeric',
            'ciudad' => 'nullable',
            'entidad' => 'nullable',
            'municipio_propietario' => 'nullable',
        ]);

        if($this->porcentaje_propiedad == 0 && $this->porcentaje_nuda == 0 && $this->porcentaje_usufructo == 0){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede ser 0."]);

            return;

        }

        if($this->revisarProcentajes($this->actor->id)){

            $this->dispatch('mostrarMensaje', ['error', "La suma de los porcentajes no puede exceder el 100%."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->actor->persona->update([
                    'tipo' => $this->tipo_persona,
                    'nombre' => $this->nombre,
                    'ap_paterno' => $this->ap_paterno,
                    'ap_materno' => $this->ap_materno,
                    'curp' => $this->curp,
                    'rfc' => $this->rfc,
                    'razon_social' => $this->razon_social,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'nacionalidad' => $this->nacionalidad,
                    'estado_civil' => $this->estado_civil,
                    'calle' => $this->calle,
                    'numero_exterior' => $this->numero_exterior_propietario,
                    'numero_interior' => $this->numero_interior_propietario,
                    'colonia' => $this->colonia,
                    'cp' => $this->cp,
                    'entidad' => $this->entidad,
                    'municipio' => $this->municipio_propietario,
                    'creado_por' => auth()->id()
                ]);

                $this->actor->update([
                    'tipo_propietario' => $this->tipo_propietario,
                    'porcentaje_propiedad' => $this->porcentaje_propiedad,
                    'porcentaje_nuda' => $this->porcentaje_nuda,
                    'porcentaje_usufructo' => $this->porcentaje_usufructo,
                    'actualizado_por' => auth()->id()
                ]);

                $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito."]);

                $this->resetear();

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar actor en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function borrarActor(Actor $actor){

        $this->authorize('update', $this->sentencia->movimientoRegistral);

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->resetear();

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function resetear(){

        $this->reset([
            'tipo_propietario',
            'porcentaje_propiedad',
            'porcentaje_nuda',
            'porcentaje_usufructo',
            'tipo_persona',
            'nombre',
            'ap_paterno',
            'ap_materno',
            'curp',
            'rfc',
            'razon_social',
            'fecha_nacimiento',
            'nacionalidad',
            'estado_civil',
            'calle',
            'numero_exterior_propietario',
            'numero_interior_propietario',
            'colonia',
            'cp',
            'entidad',
            'municipio_propietario',
            'modalPropietario'
        ]);
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

    public function buscarMovimiento(){

        $this->movimientoCancelar = MovimientoRegistral::where('folio', $this->folio_movimiento)
                                                            ->whereHas('folioReal', function($q){
                                                                $q->where('folio', $this->folio_real);
                                                            })
                                                            ->where('estado', 'concluido')
                                                            ->first();

        if(!$this->movimientoCancelar){

            $this->dispatch('mostrarMensaje', ['warning', 'No se encontró el movimiento registral.']);

            return;

        }

        if($this->movimientoCancelar->inscripcionPropiedad){

            if($this->movimientoCancelar->inscripcionPropiedad->estado != 'activo'){

                $this->dispatch('mostrarMensaje', ['warning', 'El movimiento registral no se encuentra activo.']);

                $this->movimientoCancelar = null;

                return;

            }

            $this->acto_contenido = $this->movimientoCancelar->inscripcionPropiedad->acto_contenido;

            $this->observaciones = $this->movimientoCancelar->inscripcionPropiedad->observaciones;

        }elseif($this->movimientoCancelar->cancelacion){

            if($this->movimientoCancelar->cancelacion->estado != 'activo'){

                $this->dispatch('mostrarMensaje', ['warning', 'El movimiento registral no se encuentra activo.']);

                $this->movimientoCancelar = null;

                return;

            }

            $this->acto_contenido = $this->movimientoCancelar->cancelacion->acto_contenido;

            $this->tipo = $this->movimientoCancelar->cancelacion->tipo;

            $this->observaciones = $this->movimientoCancelar->cancelacion->observaciones;

        }elseif($this->movimientoCancelar->gravamen){

            if($this->movimientoCancelar->gravamen->estado != 'activo'){

                $this->dispatch('mostrarMensaje', ['warning', 'El movimiento registral no se encuentra activo.']);

                $this->movimientoCancelar = null;

                return;

            }

            $this->acto_contenido = $this->movimientoCancelar->gravamen->acto_contenido;

            $this->tipo = $this->movimientoCancelar->gravamen->tipo;

            $this->observaciones = $this->movimientoCancelar->gravamen->observaciones;

            $this->valor_gravamen = $this->movimientoCancelar->gravamen->valor_gravamen;

        }elseif($this->movimientoCancelar->sentencia){

            if($this->movimientoCancelar->sentencia->estado != 'activo'){

                $this->dispatch('mostrarMensaje', ['warning', 'El movimiento registral no se encuentra activo.']);

                $this->movimientoCancelar = null;

                return;

            }

            $this->acto_contenido = $this->movimientoCancelar->gravamen->acto_contenido;

            $this->observaciones = $this->movimientoCancelar->gravamen->descripcion;

        }elseif($this->movimientoCancelar->vario){

            if($this->movimientoCancelar->vario->estado != 'activo'){

                $this->dispatch('mostrarMensaje', ['warning', 'El movimiento registral no se encuentra activo.']);

                $this->movimientoCancelar = null;

                return;

            }

            $this->acto_contenido = $this->movimientoCancelar->gravamen->acto_contenido;

            $this->observaciones = $this->movimientoCancelar->gravamen->descripcion;

        }

    }

    public function cargarPredioInicial(){

        if(!$this->sentencia->predio_id){

            $predio = Predio::create([
                'status' => 'sentencia',
                'cp_localidad' => $this->predio->cp_localidad,
                'cp_oficina' => $this->predio->cp_oficina,
                'cp_tipo_predio' => $this->predio->cp_tipo_predio,
                'cp_registro' => $this->predio->cp_registro,
                'superficie_terreno' => $this->predio->superficie_terreno,
                'unidad_area' => $this->predio->unidad_area,
                'superficie_construccion' => $this->predio->superficie_construccion,
                'monto_transaccion' => $this->predio->monto_transaccion,
                'observaciones' => $this->predio->observaciones,
                'superficie_judicial' => $this->predio->superficie_judicial,
                'superficie_notarial' => $this->predio->superficie_notarial,
                'area_comun_terreno' => $this->predio->area_comun_terreno,
                'area_comun_construccion' => $this->predio->area_comun_construccion,
                'valor_terreno_comun' => $this->predio->valor_terreno_comun,
                'valor_construccion_comun' => $this->predio->valor_construccion_comun,
                'valor_total_terreno' => $this->predio->valor_total_terreno,
                'valor_total_construccion' => $this->predio->valor_total_construccion,
                'valor_catastral' => $this->predio->valor_catastral,
                'codigo_postal' => $this->predio->codigo_postal,
                'nombre_asentamiento' => $this->predio->nombre_asentamiento,
                'municipio' => $this->predio->municipio,
                'ciudad' => $this->predio->ciudad,
                'tipo_asentamiento' => $this->predio->tipo_asentamiento,
                'localidad' => $this->predio->localidad,
                'tipo_vialidad' => $this->predio->tipo_vialidad,
                'nombre_vialidad' => $this->predio->nombre_vialidad,
                'numero_exterior' => $this->predio->numero_exterior,
                'numero_interior' => $this->predio->numero_interior,
                'nombre_edificio' => $this->predio->nombre_edificio,
                'departamento_edificio' => $this->predio->departamento_edificio,
                'departamento_edificio' => $this->predio->departamento_edificio,
                'descripcion' => $this->predio->descripcion,
                'lote' => $this->predio->lote,
                'manzana' => $this->predio->manzana,
                'ejido' => $this->predio->ejido,
                'parcela' => $this->predio->parcela,
                'solar' => $this->predio->solar,
                'poblado' => $this->predio->poblado,
                'numero_exterior_2' => $this->predio->numero_exterior_2,
                'numero_adicional' => $this->predio->numero_adicional,
                'numero_adicional_2' => $this->predio->numero_adicional_2,
                'lote_fraccionador' => $this->predio->lote_fraccionador,
                'manzana_fraccionador' => $this->predio->manzana_fraccionador,
                'etapa_fraccionador' => $this->predio->etapa_fraccionador,
                'clave_edificio' => $this->predio->clave_edificio,
            ]);

            $this->sentencia->update(['predio_id' => $predio->id]);

            foreach ($this->predio->colindancias as $colindancia) {

                $this->sentencia->predio->colindancias()->create([
                    'viento' => $colindancia->viento,
                    'longitud' => $colindancia->longitud,
                    'descripcion' => $colindancia->descripcion,
                ]);

            }

            foreach ($this->predio->propietarios() as $actor) {

                $this->sentencia->predio->actores()->create([
                    'persona_id' => $actor->persona_id,
                    'tipo_actor' => $actor->tipo_actor,
                    'porcentaje_propiedad' => $actor->porcentaje_propiedad,
                    'porcentaje_nuda' => $actor->porcentaje_nuda,
                    'porcentaje_usufructo' => $actor->porcentaje_usufructo,
                ]);

            }

        }

    }

    public function propcesarPredio(){

        $this->predio->cp_localidad = $this->sentenciaPredio->cp_localidad;
        $this->predio->cp_oficina = $this->sentenciaPredio->cp_oficina;
        $this->predio->cp_tipo_predio = $this->sentenciaPredio->cp_tipo_predio;
        $this->predio->cp_registro = $this->sentenciaPredio->cp_registro;
        $this->predio->superficie_terreno = $this->sentenciaPredio->superficie_terreno;
        $this->predio->unidad_area = $this->sentenciaPredio->unidad_area;
        $this->predio->superficie_construccion = $this->sentenciaPredio->superficie_construccion;
        $this->predio->monto_transaccion = $this->sentenciaPredio->monto_transaccion;
        $this->predio->observaciones = $this->sentenciaPredio->observaciones;
        $this->predio->superficie_judicial = $this->sentenciaPredio->superficie_judicial;
        $this->predio->superficie_notarial = $this->sentenciaPredio->superficie_notarial;
        $this->predio->area_comun_terreno = $this->sentenciaPredio->area_comun_terreno;
        $this->predio->area_comun_construccion = $this->sentenciaPredio->area_comun_construccion;
        $this->predio->valor_terreno_comun = $this->sentenciaPredio->valor_terreno_comun;
        $this->predio->valor_construccion_comun = $this->sentenciaPredio->valor_construccion_comun;
        $this->predio->valor_total_terreno = $this->sentenciaPredio->valor_total_terreno;
        $this->predio->valor_total_construccion = $this->sentenciaPredio->valor_total_construccion;
        $this->predio->valor_catastral = $this->sentenciaPredio->valor_catastral;
        $this->predio->codigo_postal = $this->sentenciaPredio->codigo_postal;
        $this->predio->nombre_asentamiento = $this->sentenciaPredio->nombre_asentamiento;
        $this->predio->municipio = $this->sentenciaPredio->municipio;
        $this->predio->ciudad = $this->sentenciaPredio->ciudad;
        $this->predio->tipo_asentamiento = $this->sentenciaPredio->tipo_asentamiento;
        $this->predio->localidad = $this->sentenciaPredio->localidad;
        $this->predio->tipo_vialidad = $this->sentenciaPredio->tipo_vialidad;
        $this->predio->nombre_vialidad = $this->sentenciaPredio->nombre_vialidad;
        $this->predio->numero_exterior = $this->sentenciaPredio->numero_exterior;
        $this->predio->numero_interior = $this->sentenciaPredio->numero_interior;
        $this->predio->nombre_edificio = $this->sentenciaPredio->nombre_edificio;
        $this->predio->departamento_edificio = $this->sentenciaPredio->departamento_edificio;
        $this->predio->departamento_edificio = $this->sentenciaPredio->departamento_edificio;
        $this->predio->descripcion = $this->sentenciaPredio->descripcion;
        $this->predio->lote = $this->sentenciaPredio->lote;
        $this->predio->manzana = $this->sentenciaPredio->manzana;
        $this->predio->ejido = $this->sentenciaPredio->ejido;
        $this->predio->parcela = $this->sentenciaPredio->parcela;
        $this->predio->solar = $this->sentenciaPredio->solar;
        $this->predio->poblado = $this->sentenciaPredio->poblado;
        $this->predio->numero_exterior_2 = $this->sentenciaPredio->numero_exterior_2;
        $this->predio->numero_adicional = $this->sentenciaPredio->numero_adicional;
        $this->predio->numero_adicional_2 = $this->sentenciaPredio->numero_adicional_2;
        $this->predio->lote_fraccionador = $this->sentenciaPredio->lote_fraccionador;
        $this->predio->manzana_fraccionador = $this->sentenciaPredio->manzana_fraccionador;
        $this->predio->etapa_fraccionador = $this->sentenciaPredio->etapa_fraccionador;
        $this->predio->clave_edificio = $this->sentenciaPredio->clave_edificio;

        $this->predio->save();

        $this->predio->colindancias()->delete();

        foreach ($this->sentenciaPredio->colindancias as $colindancia) {

            $colindancia->update(['predio_id' => $this->predio->id]);

        }

        foreach ($this->predio->propietarios() as $propietario) {
            $propietario->delete();
        }

        foreach ($this->sentenciaPredio->propietarios() as $propietario) {
            $propietario->update(['actorable_id' => $this->predio->id]);
        }

        $this->sentenciaPredio->colindancias()->delete();

        foreach ($this->sentenciaPredio->propietarios() as $propietario) {
            $propietario->delete();
        }

        $this->sentenciaPredio->delete();

    }

    public function abrirModalFinalizar(){

        $this->reset('documento');

        $this->dispatch('removeFiles');

        $this->modalDocumento = true;

    }

    public function guardarDocumento(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                if(env('LOCAL') == "0"){

                    $pdf = $this->documento->store('srpp/documento_entrada', 's3');

                    File::create([
                        'fileable_id' => $this->sentencia->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada_s3',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "1"){

                    $pdf = $this->documento->store('/', 'documento_entrada');

                    File::create([
                        'fileable_id' => $this->sentencia->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $pdf
                    ]);

                }elseif(env('LOCAL') == "2"){

                    $pdf = $this->documento->store('srpp/documento_entrada', 's3');

                    File::create([
                        'fileable_id' => $this->sentencia->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada_s3',
                        'url' => $pdf
                    ]);

                    $pdf = $this->documento->store('/', 'documento_entrada');

                    File::create([
                        'fileable_id' => $this->sentencia->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $pdf
                    ]);

                }

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en inscripción de sentencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

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
                        'fileable_id' => $this->sentencia->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $filename
                    ]);

                }

            } catch (ConnectionException $th) {

                Log::error("Error al cargar archivo en cancelación: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

                $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

            }

        }

        $this->predio = $this->sentencia->movimientoRegistral->folioReal->predio;

        $this->cargarPredioInicial();

        $this->sentenciaPredio = $this->sentencia->predio;

        foreach ($this->sentencia->predio->colindancias as $colindancia) {

            $this->medidas[] = [
                'id' => $colindancia->id,
                'viento' => $colindancia->viento,
                'longitud' => $colindancia->longitud,
                'descripcion' => $colindancia->descripcion,
            ];

        }

        $this->actos = Constantes::ACTOS_INSCRIPCION_SENTENCIAS;

        $this->areas = Constantes::UNIDADES;

        $this->divisas = Constantes::DIVISAS;

        $this->vientos = Constantes::VIENTOS;

        $this->tipos_vialidades = Constantes::TIPO_VIALIDADES;

        $this->tipos_asentamientos = Constantes::TIPO_ASENTAMIENTO;

    }

    public function render()
    {
        return view('livewire.sentencias.sentencia')->extends('layouts.admin');
    }

}
