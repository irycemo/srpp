<?php

namespace App\Traits\Inscripciones\Propiedad;

use App\Models\File;
use App\Models\User;
use App\Models\Actor;
use App\Models\Predio;
use App\Models\Gravamen;
use App\Models\FolioReal;
use App\Models\Propiedad;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;
use App\Http\Controllers\Gravamen\GravamenController;


trait PropiedadTrait{

    public $modalTransmitente;
    public $modalContraseña;
    public $modalDocumento = false;
    public $documento;

    public $areas;
    public $divisas;
    public $tipos_asentamientos;

    public $estados;

    public $tipos_vialidades;
    public $vientos;

    public $propietario;

    public $descripcion;

    public $contraseña;

    public $actor;

    public $inscripcion;
    public $propiedad;
    public $predio;

    public function consultarArchivo(){

        if(!$this->inscripcion->movimientoRegistral->documentoEntrada()){

            try {

                $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                    ->accept('application/json')
                                    ->asForm()
                                    ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                        'año' => $this->inscripcion->movimientoRegistral->año,
                                                                                        'tramite' => $this->inscripcion->movimientoRegistral->tramite,
                                                                                        'usuario' => $this->inscripcion->movimientoRegistral->usuario,
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

                Log::error("Error al cargar archivo en inscripcion de propiedad: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

                $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

            }

        }

    }

    public function agregarTransmitente(){

        $this->modalTransmitente = true;
        $this->crear = true;

    }

    public function borrarActor(Actor $actor){

        $this->authorize('update', $this->inscripcion->movimientoRegistral);

        $tipo = $actor->tipo_actor;

        if($actor->representado_por){

            $this->dispatch('mostrarMensaje', ['error', "Debe borrar primero al representante."]);

            return;

        }

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->inscripcion->refresh();

            $this->inscripcion->load('actores.persona');

            if($tipo == 'transmitente'){

                $this->reset('transmitentes');

                foreach ($this->inscripcion->transmitentes() as $transmitente) {

                    $this->transmitentes[] = [
                        'id' => $transmitente['id'],
                        'nombre' => $transmitente->persona->nombre,
                        'ap_paterno' => $transmitente->persona->ap_paterno,
                        'ap_materno' => $transmitente->persona->ap_materno,
                        'razon_social' => $transmitente->persona->razon_social,
                        'porcentaje_propiedad' => $transmitente->porcentaje_propiedad,
                        'porcentaje_nuda' => $transmitente->porcentaje_nuda,
                        'porcentaje_usufructo' => $transmitente->porcentaje_usufructo,
                    ];
                }

            }

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizar(){

        $this->validate();

        if(!$this->inscripcion->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        if($this->validaciones()) return;

        $this->modalContraseña = true;

    }

    public function abrirModalDocumento(){

        $this->reset('documento');

        $this->modalDocumento = true;

    }

    public function guardarDocumento(){

        $this->validate(['documento' => 'nullable|mimes:pdf|max:153600']);

        try {

            DB::transaction(function (){

                if(app()->isProduction()){

                    $pdf = Str::random(40) . '.pdf';

                    $this->documento->store(config('services.ses.ruta_documento_entrada'), 's3');

                }else{

                    $pdf = $this->documento->store('/', 'documento_entrada');

                }

                File::create([
                    'fileable_id' => $this->inscripcion->movimientoRegistral->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento de entrada en inscripción de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function generarNuevoFolioReal(){

        $folioRealNuevo = FolioReal::create([
            'antecedente' => $this->inscripcion->movimientoRegistral->folio_real,
            'estado' => 'captura',
            'folio' => (FolioReal::max('folio') ?? 0) + 1,
            'distrito_antecedente' => $this->inscripcion->movimientoRegistral->getRawOriginal('distrito'),
            'seccion_antecedente' => 'Propiedad',
            'tipo_documento' => $this->inscripcion->movimientoRegistral->tipo_documento,
            'numero_documento' => $this->inscripcion->movimientoRegistral->numero_documento,
            'autoridad_cargo' => $this->inscripcion->movimientoRegistral->autoridad_cargo,
            'autoridad_nombre' => $this->inscripcion->movimientoRegistral->autoridad_nombre,
            'autoridad_numero' => $this->inscripcion->movimientoRegistral->autoridad_numero,
            'fecha_emision' => $this->inscripcion->movimientoRegistral->fecha_emision,
            'fecha_inscripcion' => $this->inscripcion->movimientoRegistral->fecha_inscripcion,
            'procedencia' => $this->inscripcion->movimientoRegistral->tipo_documento,
        ]);

        $documentoEntrada = File::where('fileable_type', 'App\Models\MovimientoRegistral')
                                    ->where('fileable_id', $this->inscripcion->movimientoRegistral->id)
                                    ->where('descripcion', 'documento_entrada')
                                    ->first();

        File::create([
            'fileable_id' => $folioRealNuevo->id,
            'fileable_type' => 'App\Models\FolioReal',
            'descripcion' => 'documento_entrada',
            'url' => $documentoEntrada->url
        ]);

        $movimiento = MovimientoRegistral::create([
            'estado' => 'nuevo',
            'folio' => 1,
            'folio_real' => $folioRealNuevo->id,
            'fecha_prelacion' => $this->inscripcion->movimientoRegistral->fecha_prelacion,
            'fecha_entrega' => $this->inscripcion->movimientoRegistral->fecha_entrega,
            'fecha_pago' => $this->inscripcion->movimientoRegistral->fecha_pago,
            'tipo_servicio' => $this->inscripcion->movimientoRegistral->tipo_servicio,
            'solicitante' => $this->inscripcion->movimientoRegistral->solicitante,
            'seccion' => $this->inscripcion->movimientoRegistral->seccion,
            'año' => $this->inscripcion->movimientoRegistral->año,
            'tramite' => $this->inscripcion->movimientoRegistral->tramite,
            'usuario' => $this->inscripcion->movimientoRegistral->usuario,
            'distrito' => $this->inscripcion->movimientoRegistral->getRawOriginal('distrito'),
            'tipo_documento' => $this->inscripcion->movimientoRegistral->tipo_documento,
            'numero_documento' => $this->inscripcion->movimientoRegistral->numero_documento,
            'autoridad_cargo' => $this->inscripcion->movimientoRegistral->autoridad_cargo,
            'autoridad_numero' => $this->inscripcion->movimientoRegistral->autoridad_numero,
            'fecha_emision' => $this->inscripcion->movimientoRegistral->fecha_emision,
            'fecha_inscripcion' => $this->inscripcion->movimientoRegistral->fecha_inscripcion,
            'procedencia' => $this->inscripcion->movimientoRegistral->procedencia,
            'numero_oficio' => $this->inscripcion->movimientoRegistral->numero_oficio,
            'monto' => $this->inscripcion->movimientoRegistral->monto,
            'usuario_asignado' => $this->usuarioAsignado(),
            'usuario_supervisor' => $this->inscripcion->movimientoRegistral->usuario_supervisor,
            'movimiento_padre' => $this->inscripcion->movimientoRegistral->id
        ]);

        Propiedad::create([
            'movimiento_registral_id' => $movimiento->id,
            'servicio' => $this->inscripcion->servicio,
            'descripcion_acto' => 'Movimiento registral que da origen al Folio Real'
        ]);

        $predioNuevo = Predio::create([
            'folio_real' => $folioRealNuevo->id,
            'status' => 'nuevo'
        ]);

        foreach($this->inscripcion->movimientoRegistral->folioReal->predio->getAttributes() as $attribute => $value){

            if(in_array($attribute, ['id', 'folio_real', 'escritura_id', 'superficie_terreno'])) continue;

            $predioNuevo->{$attribute} = $this->inscripcion->movimientoRegistral->folioReal->predio->{ $attribute};

        }

        $predioNuevo->save();

        return $folioRealNuevo->folio;

    }

    public function usuarioAsignado(){

        if(auth()->user()->hasRole(['Propiedad', 'Jefe de departamento inscripciones'])){


            if($this->inscripcion->movimientoRegistral->getRawOriginal('distrito') == 2){

                return User::where('status', 'activo')
                                ->where('ubicacion', 'Regional 4')
                                ->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Registrador Propiedad', 'Pase a folio']);
                                })
                                ->first()->id;

            }else{

                return User::where('status', 'activo')
                                ->where('ubicacion', '!=', 'Regional 4')
                                ->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Registrador Propiedad', 'Pase a folio']);
                                })
                                ->first()->id;

            }

        }else{

            return  auth()->id();

        }

    }

    public function generarGravamenReservaDominio(){

        $movimiento = $this->inscripcion->movimientoRegistral->replicate();
        $movimiento->folio = $movimiento->folio + 1;
        $movimiento->estado = 'carga_parcial';
        $movimiento->save();

        $url = $this->inscripcion->movimientoRegistral->archivos()->where('descripcion', 'documento_entrada')->first()->url;

        File::create([
            'fileable_id' => $movimiento->id,
            'fileable_type' => 'App\Models\MovimientoRegistral',
            'descripcion' => 'documento_entrada',
            'url' => $url
        ]);

        $gravamen = Gravamen::create([
            'movimiento_registral_id' => $movimiento->id,
            'acto_contenido' => 'RESERVA DE DOMINIO',
            'servicio' => 'D150',
            'estado' => 'activo'
        ]);

        foreach($this->inscripcion->transmitentes() as $transmitente){

            Actor::create([
                'actorable_type' => 'App\Models\Gravamen',
                'actorable_id' => $gravamen->id,
                'tipo_actor' => 'acreedor',
                'persona_id' => $transmitente->persona_id
            ]);

        }

        foreach($this->inscripcion->propietarios() as $propietario){

            Actor::create([
                'actorable_type' => 'App\Models\Gravamen',
                'actorable_id' => $gravamen->id,
                'tipo_actor' => 'deudor',
                'tipo_deudor' => 'DEUDOR',
                'persona_id' => $propietario->persona_id
            ]);

        }

        (new GravamenController())->caratula($gravamen);

    }

}
