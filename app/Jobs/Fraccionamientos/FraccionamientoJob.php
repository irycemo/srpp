<?php

namespace App\Jobs\Fraccionamientos;

use App\Models\Actor;
use App\Models\Colindancia;
use App\Models\Escritura;
use App\Models\File;
use App\Models\FolioReal;
use App\Models\Gravamen;
use App\Models\Import;
use App\Models\MovimientoRegistral;
use App\Models\Persona;
use App\Models\Predio;
use App\Models\Propiedad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FraccionamientoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 20;
    public $import;

    public MovimientoRegistral $movimiento_registral;

    public function __construct(public int $import_id, public array $row, public int $movimiento_id, public int $user_id)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {

            $this->movimiento_registral = MovimientoRegistral::find($this->movimiento_id);

            $this->import = Import::find($this->import_id);

            DB::transaction(function () {

                if(isset($row['colindancias'])){

                    $colindancias = $this->procesarColindacias($this->row['colindancias']);

                }else{

                    $colindancias = [];

                }

                $propietarios = $this->procesarPropietarios($this->row['propietarios']);

                $folioReal = $this->generarFolioReal();

                $predio = $this->crearPredio($folioReal->id, $this->row);

                $this->procesarRealacionesDePredio($predio->id, $colindancias, $propietarios);

                if(in_array($this->movimiento_registral->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])){

                    $this->procesarEscritura($predio);

                }

                if($this->row['acto_contenido_gravamen']){

                    $gravamen = [
                        'acto_contenido' => $this->row['acto_contenido_gravamen'],
                        'servicio' => 'D127',
                        'estado' => 'activo',
                        'tipo' => $this->row['tipo_gravamen'],
                        'valor_gravamen' => $this->row['valor_gravamen'],
                        'divisa' => $this->row['divisa_gravamen'],
                        'fecha_inscripcion' => $this->row['fecha_inscripcion_gravamen'],
                        'observaciones' => $this->row['descripcion_acto_gravamen'],
                    ];

                    $acreedores = $this->procesarAcreedores($this->row['acreedores_gravamen']);

                    $actores = $this->procesarActores($this->row['actores_gravamen']);

                    $this->generarGravamen($folioReal->id, $gravamen, $acreedores, $actores);

                }

                $this->import->update([
                    'status' => 'processed',
                    'folio_real' => $folioReal->id . '| Folio real: ' . $folioReal->folio
                ]);

            });

        } catch (\Throwable $th) {

            Log::error("Error en job fraccionamiento row number: " . $this->import->row_number . " row: " . json_encode($this->row) . " " . $th);

            $this->import->update([
                'status' => 'error',
                'errores' => json_encode([$th->getMessage()]),
            ]);

            throw $th;

        }

    }

    public function procesarColindacias($colindancias):array
    {

        $array = explode('|', $colindancias);

        $colindanciasArreglo = [];

        foreach($array as $colindancia){

            $campos = explode(':', $colindancia);

            $colindanciasArreglo [] = [
                'viento' => $campos[0],
                'longitud' => $campos[1],
                'descripcion' => $campos[2],
            ];

        }

        return $colindanciasArreglo;

    }

    public function procesarPropietarios($propietarios):array
    {

        $array = explode('|', $propietarios);

        $propietariosArreglo = [];

        foreach ($array as $propietario) {

            $campos = explode(':', $propietario);

            if(in_array($campos[0], ['FISICA', 'FÍSICA'])){

                $persona = [
                    'tipo' => $campos[0],
                    'nombre' => $campos[1],
                    'ap_paterno' => $campos[2],
                    'ap_materno' => $campos[3],
                ];

            }else{

                $persona = [
                    'tipo' => $campos[0],
                    'razon_social' => $campos[1]
                ];

            }

            $propietariosArreglo [] = $persona;

        }


        return $propietariosArreglo;

    }

    public function procesarAcreedores($acreedores):array
    {

        $array = explode('|', $acreedores);

        $acreedoresArreglo = [];

        foreach ($array as $acreedor) {

            $campos = explode(':', $acreedor);

            if(in_array($campos[0], ['FISICA', 'FÍSICA'])){

                $acreedor = [
                    'tipo' => $campos[0],
                    'nombre' => $campos[1],
                    'ap_paterno' => $campos[2],
                    'ap_materno' => $campos[3],
                ];

            }else{

                $acreedor = [
                    'tipo' => $campos[0],
                    'razon_social' => $campos[1]
                ];

            }

            $acreedoresArreglo [] = $acreedor;

        }


        return $acreedoresArreglo;

    }

    public function procesarActores($actores):array
    {

        $array = explode('|', $actores);

        $actoresArreglo = [];

        foreach ($array as $actor) {

            $campos = explode(':', $actor);

            if(in_array($campos[0], ['FISICA', 'FÍSICA'])){

                $actor = [
                    'tipo' => $campos[0],
                    'nombre' => $campos[1],
                    'ap_paterno' => $campos[2],
                    'ap_materno' => $campos[3],
                ];

            }else{

                $actor = [
                    'tipo' => $campos[0],
                    'razon_social' => $campos[1]
                ];

            }

            $actoresArreglo [] = $actor;

        }

        return $actoresArreglo;

    }

    public function crearPredio($folioRealId, $linea):Predio
    {

        return Predio::create([
            'status' => 'nuevo',
            'folio_real' => $folioRealId,
            'cp_localidad' => $linea['localidad'],
            'cp_oficina' => $linea['oficina'],
            'cp_tipo_predio' => $linea['tipo'],
            'cp_registro' => $linea['registro'],
            'superficie_terreno' => (float)$linea['superficie_terreno'],
            'superficie_construccion' => (float)$linea['superficie_construccion'],
            'superficie_judicial' => (float)$linea['superficie_judicial'],
            'superficie_notarial' => (float)$linea['superficie_notarial'],
            'area_comun_terreno' => (float)$linea['area_comun_terreno'],
            'area_comun_construccion' => (float)$linea['area_comun_construccion'],
            'valor_terreno_comun' => (float)$linea['valor_terreno_comun'],
            'valor_construccion_comun' => (float)$linea['valor_construccion_comun'],
            'valor_total_terreno' => (float)$linea['valor_total_terreno'],
            'valor_total_construccion' => (float)$linea['valor_total_construccion'],
            'valor_catastral' => (float)$linea['valor_catastral'],
            'monto_transaccion' => (float)$linea['monto_transaccion'],
            'divisa' => $linea['divisa'],
            'unidad_area' => $linea['unidad_area'],
            'tipo_vialidad' => $linea['tipo_vialidad'],
            'tipo_asentamiento' => $linea['tipo_asentamiento'],
            'nombre_vialidad' => $linea['nombre_vialidad'],
            'nombre_asentamiento' => $linea['nombre_asentamiento'],
            'numero_exterior' => $linea['numero_exterior'],
            'numero_exterior_2' => $linea['numero_exterior_2'],
            'numero_adicional' => $linea['numero_adicional'],
            'numero_adicional_2' => $linea['numero_adicional_2'],
            'numero_interior' => $linea['numero_interior'],
            'lote' => $linea['lote'],
            'manzana' => $linea['manzana_ubicacion'],
            'codigo_postal' => $linea['codigo_postal'],
            'lote_fraccionador' => $linea['lote_fraccionador'],
            'manzana_fraccionador' => $linea['manzana_fraccionador'],
            'etapa_fraccionador' => $linea['etapa_fraccionador'],
            'nombre_edificio' => $linea['nombre_edificio'],
            'clave_edificio' => $linea['clave_edificio'],
            'departamento_edificio' => $linea['departamento_edificio'],
            'municipio' => $linea['municipio_ubicacion'],
            'ciudad' => $linea['ciudad'],
            'localidad' => $linea['localidad_ubicacion'],
            'poblado' => $linea['poblado'],
            'ejido' => $linea['ejido'],
            'parcela' => $linea['parcela'],
            'solar' => $linea['solar'],
            'descripcion' => $linea['descripcion'],
            'zona_ubicacion' => $linea['zona_ubicacion'],
            'creado_por' => $this->user_id,
        ]);

    }

    public function procesarRealacionesDePredio($predioId, $colindancias, $propietarios):void
    {

        foreach ($colindancias as $colindancia) {

            Colindancia::create([
                'predio_id' => $predioId,
                'viento' => $colindancia['viento'],
                'longitud' => $colindancia['longitud'],
                'descripcion' => $colindancia['descripcion'],
            ]);

        }

        foreach ($propietarios as $propietario) {

            Actor::create([
                'actorable_type' => 'App\Models\Predio',
                'actorable_id' => $predioId,
                'persona_id' => $this->persona($propietario),
                'tipo_actor' => 'propietario',
                'porcentaje_propiedad' => 100 / count($propietarios),
                'porcentaje_nuda' => 0,
                'porcentaje_usufructo' => 0,
            ]);

        }

        foreach ($propietarios as $transmitente) {

            Actor::create([
                'actorable_type' => 'App\Models\Predio',
                'actorable_id' => $predioId,
                'persona_id' => $this->persona($transmitente),
                'tipo_actor' => 'transmitente'
            ]);

        }

    }

    public function procesarEscritura(Predio $predio):void
    {

        $escritura = Escritura::create([
            'fecha_inscripcion' => now()->toDateString(),
            'notaria' => $this->movimiento_registral->autoridad_numero,
            'nombre_notario' => $this->movimiento_registral->autoridad_nombre,
            'numero' => $this->movimiento_registral->numero_documento,
            'estado_notario' => 'MICHOACAN',
            'acto_contenido_antecedente' => 'PROTOCOLIZACIÓN Y ELEVACIÓN DE LA AUTORIZACIÓN DE FRACCIONAMIENTO',
            'creado_por' => $this->user_id,
        ]);

        $predio->update(['escritura_id' => $escritura->id]);

    }

    public function persona($array):int
    {

        $persona = Persona::when(in_array($array['tipo'], ['FISICA', 'FÍSICA']), function($q) use($array){
                                $q->where('nombre', $array['nombre'])
                                    ->where('ap_paterno', $array['ap_paterno'])
                                    ->where('ap_materno', $array['ap_materno']);
                            })
                            ->when($array['tipo'] == 'MORAL', function($q) use($array){
                                $q->where('razon_social', $array['razon_social']);
                            })
                            ->first();

        if(!$persona){

            if(in_array($array['tipo'], ['FISICA', 'FÍSICA'])){

                $persona = Persona::create([
                    'tipo' => $array['tipo'],
                    'nombre' => $array['nombre'],
                    'ap_paterno' => $array['ap_paterno'],
                    'ap_materno' => $array['ap_materno'],
                    ]);

            }else{

                $persona = Persona::create([
                    'tipo' => $array['tipo'],
                    'razon_social' => $array['razon_social'],
                    ]);

            }

            return $persona->id;

        }else{

            return $persona->id;

        }

    }

    public function generarFolioReal():FolioReal
    {

        $folioRealNuevo = FolioReal::create([
            'antecedente' => $this->movimiento_registral->folio_real,
            'estado' => 'captura',
            'folio' => (FolioReal::max('folio') ?? 0) + 1,
            'distrito_antecedente' => $this->movimiento_registral->getRawOriginal('distrito'),
            'seccion_antecedente' => $this->movimiento_registral->seccion,
            'tipo_documento' => $this->movimiento_registral->tipo_documento,
            'numero_documento' => $this->movimiento_registral->numero_documento,
            'autoridad_cargo' => $this->movimiento_registral->autoridad_cargo,
            'autoridad_nombre' => $this->movimiento_registral->autoridad_nombre,
            'autoridad_numero' => $this->movimiento_registral->autoridad_numero,
            'fecha_emision' => $this->movimiento_registral->fecha_emision,
            'fecha_inscripcion' => now()->toDateString(),
            'acto_contenido_antecedente' => 'PROTOCOLIZACIÓN Y ELEVACIÓN DE LA AUTORIZACIÓN DE FRACCIONAMIENTO',
            'procedencia' => $this->movimiento_registral->procedencia,
            'creado_por' => $this->user_id,
        ]);

        $documentoEntrada = File::where('fileable_type', 'App\Models\MovimientoRegistral')
                                    ->where('fileable_id', $this->movimiento_registral->id)
                                    ->where('descripcion', 'documento_entrada')
                                    ->first();

        File::create([
            'fileable_id' => $folioRealNuevo->id,
            'fileable_type' => 'App\Models\FolioReal',
            'descripcion' => 'documento_entrada',
            'url' => $documentoEntrada->url
        ]);

        $movimientoRegistralPropiedad = $this->movimiento_registral->replicate();

        $movimientoRegistralPropiedad->movimiento_padre = $this->movimiento_registral->id;
        $movimientoRegistralPropiedad->folio = 1;
        $movimientoRegistralPropiedad->pase_a_folio = 1;
        $movimientoRegistralPropiedad->estado = 'nuevo';
        $movimientoRegistralPropiedad->folio_real = $folioRealNuevo->id;
        $movimientoRegistralPropiedad->save();

        Propiedad::create([
            'acto_contenido' => 'PROTOCOLIZACIÓN Y ELEVACIÓN DE LA AUTORIZACIÓN DE FRACCIONAMIENTO',
            'movimiento_registral_id' => $movimientoRegistralPropiedad->id,
            'servicio' => $this->movimiento_registral->inscripcionPropiedad->servicio,
            'descripcion_acto' => 'Movimiento registral que da origen al Folio Real',
            'estado' => 'michoacan'
        ]);

        return $folioRealNuevo;

    }

    public function generarGravamen($folioRealId, $gravamen, $acreedores, $actores):void
    {

        $movimientoRegistralGravamen = $this->movimiento_registral->replicate();

        $movimientoRegistralGravamen->folio_real = $folioRealId;
        $movimientoRegistralGravamen->folio = 2;
        $movimientoRegistralGravamen->estado = 'pase_folio';
        $movimientoRegistralGravamen->save();

        $gravamen = Gravamen::create(['movimiento_registral_id' => $movimientoRegistralGravamen->id,] + $gravamen);

        foreach ($acreedores as $acreedor) {

            Actor::create([
                'actorable_type' => 'App\Models\Gravamen',
                'actorable_id' => $gravamen->id,
                'persona_id' => $this->persona($acreedor),
                'tipo_actor' => 'acreedor',
                'creado_por' => $this->user_id,
            ]);

        }

        foreach ($actores as $actor) {

            Actor::create([
                'actorable_type' => 'App\Models\Gravamen',
                'actorable_id' => $gravamen->id,
                'persona_id' => $this->persona($actor),
                'tipo_actor' => 'deudor',
                'tipo_deudor' => 'I-DEUDOR ÚNICO',
                'creado_por' => $this->user_id,
            ]);

        }

    }

}
