<?php

namespace App\Imports;

use Exception;
use App\Models\File;
use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\Gravamen;
use App\Models\Escritura;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\Colindancia;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FolioRealImport implements ToCollection, WithHeadingRow, WithValidation, WithMultipleSheets, SkipsEmptyRows
{

    public $data;
    public $foliosReales = [];

    public function __construct(public MovimientoRegistral $movimientoRegistral){}

    public function rules():array
    {

        return [
            'localidad' => 'nullable|numeric',
            'oficina' => 'nullable|numeric',
            'tipo' => 'nullable|numeric',
            'registro' => 'nullable|numeric',
            'superficie_terreno' => 'required|numeric',
            'superficie_construccion' => 'nullable|numeric',
            'superficie_judicial' => 'nullable|numeric',
            'superficie_notarial' => 'nullable|numeric',
            'area_comun_terreno' => 'nullable|numeric',
            'area_comun_construccion' => 'nullable|numeric',
            'valor_terreno_comun' => 'nullable|numeric',
            'valor_construccion_comun' => 'nullable|numeric',
            'valor_total_terreno' => 'nullable|numeric',
            'valor_total_construccion' => 'nullable|numeric',
            'valor_catastral' => 'nullable|numeric',
            'monto_transaccion' => 'nullable|numeric',
            'divisa' => ['required', Rule::in(Constantes::DIVISAS)],
            'unidad_area' => ['required', Rule::in(Constantes::UNIDADES)],
            'colindancias' => 'required',
            'tipo_vialidad' => ['required', Rule::in(Constantes::TIPO_VIALIDADES)],
            'tipo_asentamiento' => ['required', Rule::in(Constantes::TIPO_ASENTAMIENTO)],
            'nombre_vialidad' => 'nullable',
            'nombre_asentamiento' => 'nullable',
            'numero_exterior' => 'nullable',
            'numero_exterior_2' => 'nullable',
            'numero_adicional' => 'nullable',
            'numero_adicional_2' => 'nullable',
            'numero_interior' => 'nullable',
            'lote' => 'nullable',
            'manzana_ubicacion' => 'nullable',
            'codigo_postal' => 'nullable|numeric',
            'lote_fraccionador' => 'nullable',
            'manzana_fraccionador' => 'nullable',
            'etapa_fraccionador' => 'nullable',
            'nombre_edificio' => 'nullable',
            'clave_edificio' => 'nullable',
            'departamento_edificio' => 'nullable',
            'municipio_ubicacion' => 'required|string',
            'ciudad' => 'nullable|string',
            'localidad_ubicacion' => 'nullable|string',
            'poblado' => 'nullable|string',
            'ejido' => 'nullable|string',
            'parcela' => 'nullable|string',
            'solar' => 'nullable|string',
            'zona_ubicacion' => 'nullable|string',
            'propietarios' => 'required',
            'acto_contenido_gravamen' => ['nullable', Rule::in(Constantes::ACTOS_INSCRIPCION_GRAVAMEN)],
            'tipo_gravamen' => ['required_unless:acto_contenido_gravamen,null', 'nullable', 'string'],
            'valor_gravamen' => [ 'numeric', 'required_unless:acto_contenido_gravamen,null', 'nullable'],
            'divisa_gravamen' => [ Rule::in(Constantes::DIVISAS), 'nullable'],
            'fecha_inscripcion_gravamen' => [ 'date_format:Y-m-d', 'required_unless:acto_contenido_gravamen,null', 'nullable'],
            'descripcion_acto_gravamen' => [ 'required_unless:acto_contenido_gravamen,null', 'nullable'],
            'actores_gravamen' => [ 'required_unless:acto_contenido_gravamen,null', 'nullable'],
            'acreedores_gravamen' => [ 'required_unless:acto_contenido_gravamen,null', 'nullable'],
        ];

    }

    public function collection(Collection $rows){

        if($this->movimientoRegistral->inscripcionPropiedad->numero_inmuebles != count($rows)){

            throw new Exception("El número de propiedades del trámite (" . $this->movimientoRegistral->inscripcionPropiedad->numero_inmuebles . ") no corresponde con el numero de regsitros en el archivo");

        }

        try {

            DB::transaction(function () use($rows){

                $gravamen = [
                    'acto_contenido' => $rows[0]['acto_contenido_gravamen'],
                    'servicio' => 'D127',
                    'estado' => 'activo',
                    'tipo' => $rows[0]['tipo_gravamen'],
                    'valor_gravamen' => $rows[0]['valor_gravamen'],
                    'divisa' => $rows[0]['divisa_gravamen'],
                    'fecha_inscripcion' => $rows[0]['fecha_inscripcion_gravamen'],
                    'observaciones' => $rows[0]['descripcion_acto_gravamen'],
                ];

                foreach ($rows as $key => $row)
                {

                    $key = $key + 3;

                    $colindancias = $this->procesarColindacias($row['colindancias'], $key);

                    $propietarios = $this->procesarPropietarios($row['propietarios'], $key);

                    $folioReal = $this->generarFolioReal();

                    $predio = $this->crearPredio($folioReal->id, $row);

                    $this->procesarRealacionesDePredio($predio->id, $colindancias, $propietarios);

                    if(in_array($this->movimientoRegistral->tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA'])){

                        $this->procesarEscritura($predio);

                    }

                    if($row['acto_contenido_gravamen']){

                        $acreedores = $this->procesarAcreedores($row['acreedores_gravamen'], $key);

                        $actores = $this->procesarActores($row['actores_gravamen'], $key);

                        $this->generarGravamen($folioReal->id, $gravamen, $acreedores, $actores);

                    }

                    $this->foliosReales [] = $folioReal->load('folioRealAntecedente');

                }

                $this->movimientoRegistral->update(['estado' => 'concluido']);

                $this->movimientoRegistral->FolioReal->update(['estado' => 'inactivo']);

                $this->data = $this->foliosReales;

            });

        } catch (ValidationException $th) {

            throw $th;

        } catch (Exception $ex) {

            throw $ex;

        } catch (\Throwable $th) {

            throw $th;

        }

    }

    public function procesarColindacias($colindancias, $linea):array
    {

        $array = explode('|', $colindancias);

        $colindanciasArreglo = [];

        foreach($array as $colindancia){

            $campos = explode(':', $colindancia);

            if(!in_array($campos[0], Constantes::VIENTOS))
                throw new Exception("Error en el campo viento de las colindancias en la línea " . $linea);

            if(!isset($campos[1]) || !isset($campos[2]))
                throw new Exception("Error en los campos de las colindancias en la línea " . $linea);

            if(isset($campos[3]))
                throw new Exception("Error en los campos de las colindancias en la líneass " . $linea);

            if($campos[1] == '' || $campos[2] == '')
                throw new Exception("Error en los campos de las colindancias en la líneass " . $linea);

            $colindanciasArreglo [] = [
                'viento' => $campos[0],
                'longitud' => $campos[1],
                'descripcion' => $campos[2],
            ];

        }

        return $colindanciasArreglo;

    }

    public function procesarPropietarios($propietarios, $linea):array
    {

        $array = explode('|', $propietarios);

        $propietariosArreglo = [];

        foreach ($array as $propietario) {

            $campos = explode(':', $propietario);

            if(!in_array($campos[0], ['FISICA', 'MORAL']))
                throw new Exception("Error en el campo tipo de persona de los propietarios en la línea " . $linea);

            if($campos[0] === 'FISICA'){

                if(!isset($campos[1]) || !isset($campos[2]) || !isset($campos[3]))
                    throw new Exception("Error en los campos de los propietarios en la línea " . $linea);

                $persona = [
                    'tipo' => $campos[0],
                    'nombre' => $campos[1],
                    'ap_paterno' => $campos[2],
                    'ap_materno' => $campos[3],
                ];

            }else{

                if(!isset($campos[1]))
                    throw new Exception("Error en los campos de los propietarios en la línea " . $linea);

                $persona = [
                    'tipo' => $campos[0],
                    'razon_social' => $campos[1]
                ];

            }

            $propietariosArreglo [] = $persona;

        }


        return $propietariosArreglo;

    }

    public function procesarAcreedores($acreedores, $linea):array
    {

        $array = explode('|', $acreedores);

        $acreedoresArreglo = [];

        foreach ($array as $acreedor) {

            $campos = explode(':', $acreedor);

            if(!in_array($campos[0], ['FISICA', 'MORAL']))
                throw new Exception("Error en el campo tipo de persona de los acreedores del gravamen en la línea " . $linea);

            if($campos[0] === 'FISICA'){

                if(!isset($campos[1]) || !isset($campos[2]) || !isset($campos[3]))
                    throw new Exception("Error en los campos de los acreedores del gravamen en la línea " . $linea);

                $acreedor = [
                    'tipo' => $campos[0],
                    'nombre' => $campos[1],
                    'ap_paterno' => $campos[2],
                    'ap_materno' => $campos[3],
                ];

            }else{

                if(!isset($campos[1]))
                    throw new Exception("Error en los campos de los acreedores del gravamen en la línea " . $linea);

                $acreedor = [
                    'tipo' => $campos[0],
                    'razon_social' => $campos[1]
                ];

            }

            $acreedoresArreglo [] = $acreedor;

        }


        return $acreedoresArreglo;

    }

    public function procesarActores($actores, $linea):array
    {

        $array = explode('|', $actores);

        $actoresArreglo = [];

        foreach ($array as $actor) {

            $campos = explode(':', $actor);

            if(!in_array($campos[0], ['FISICA', 'MORAL']))
                throw new Exception("Error en el campo tipo de persona de los actores del gravamen en la línea " . $linea);

            if($campos[0] === 'FISICA'){

                if(!isset($campos[1]) || !isset($campos[2]) || !isset($campos[3]))
                    throw new Exception("Error en los campos de los actores del gravamen en la línea " . $linea);

                $actor = [
                    'tipo' => $campos[0],
                    'nombre' => $campos[1],
                    'ap_paterno' => $campos[2],
                    'ap_materno' => $campos[3],
                ];

            }else{

                if(!isset($campos[1]))
                    throw new Exception("Error en los campos de los actores del gravamen en la línea " . $linea);

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
            'superficie_terreno' => $linea['superficie_terreno'],
            'superficie_construccion' => $linea['superficie_construccion'],
            'superficie_judicial' => $linea['superficie_judicial'],
            'superficie_notarial' => $linea['superficie_notarial'],
            'area_comun_terreno' => $linea['area_comun_terreno'],
            'area_comun_construccion' => $linea['area_comun_construccion'],
            'valor_terreno_comun' => $linea['valor_terreno_comun'],
            'valor_construccion_comun' => $linea['valor_construccion_comun'],
            'valor_total_terreno' => $linea['valor_total_terreno'],
            'valor_total_construccion' => $linea['valor_total_construccion'],
            'valor_catastral' => $linea['valor_catastral'],
            'monto_transaccion' => $linea['monto_transaccion'],
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
            'zona_ubicacion' => $linea['zona_ubicacion'],
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
            'fecha_inscripcion' => $this->movimientoRegistral->fecha_emision,
            'notaria' => $this->movimientoRegistral->autoridad_numero,
            'nombre_notario' => $this->movimientoRegistral->autoridad_nombre,
            'numero' => $this->movimientoRegistral->numero_documento,
        ]);

        $predio->update(['escritura_id' => $escritura->id]);

    }

    public function persona($array):int
    {

        $persona = Persona::when($array['tipo'] == 'FISICA', function($q) use($array){
                                $q->where('nombre', $array['nombre'])
                                    ->where('ap_paterno', $array['ap_paterno'])
                                    ->where('ap_materno', $array['ap_materno']);
                            })
                            ->when($array['tipo'] == 'MORAL', function($q) use($array){
                                $q->where('razon_social', $array['razon_social']);
                            })
                            ->first();

        if(!$persona){

            if($array['tipo'] == 'FISICA'){

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
            'antecedente' => $this->movimientoRegistral->folio_real,
            'estado' => 'captura',
            'folio' => (FolioReal::max('folio') ?? 0) + 1,
            'distrito_antecedente' => $this->movimientoRegistral->getRawOriginal('distrito'),
            'seccion_antecedente' => $this->movimientoRegistral->seccion,
            'tipo_documento' => $this->movimientoRegistral->tipo_documento,
            'numero_documento' => $this->movimientoRegistral->numero_documento,
            'autoridad_cargo' => $this->movimientoRegistral->autoridad_cargo,
            'autoridad_nombre' => $this->movimientoRegistral->autoridad_nombre,
            'autoridad_numero' => $this->movimientoRegistral->autoridad_numero,
            'fecha_emision' => $this->movimientoRegistral->fecha_emision,
            'fecha_inscripcion' => $this->movimientoRegistral->fecha_inscripcion,
            'procedencia' => $this->movimientoRegistral->tipo_documento,
        ]);

        $documentoEntrada = File::where('fileable_type', 'App\Models\MovimientoRegistral')
                                    ->where('fileable_id', $this->movimientoRegistral->id)
                                    ->where('descripcion', 'documento_entrada')
                                    ->first();

        File::create([
            'fileable_id' => $folioRealNuevo->id,
            'fileable_type' => 'App\Models\FolioReal',
            'descripcion' => 'documento_entrada',
            'url' => $documentoEntrada->url
        ]);

        $movimientoRegistralPropiedad = $this->movimientoRegistral->replicate();

        $movimientoRegistralPropiedad->movimiento_padre = $this->movimientoRegistral->id;
        $movimientoRegistralPropiedad->folio = 1;
        $movimientoRegistralPropiedad->estado = 'nuevo';
        $movimientoRegistralPropiedad->folio_real = $folioRealNuevo->id;
        $movimientoRegistralPropiedad->save();

        Propiedad::create([
            'movimiento_registral_id' => $movimientoRegistralPropiedad->id,
            'servicio' => $this->movimientoRegistral->inscripcionPropiedad->servicio,
            'descripcion_acto' => 'Movimiento registral que da origen al Folio Real'
        ]);

        return $folioRealNuevo;

    }

    public function generarGravamen($folioRealId, $gravamen, $acreedores, $actores):void
    {

        $movimientoRegistralGravamen = $this->movimientoRegistral->replicate();

        $movimientoRegistralGravamen->folio_real = $folioRealId;
        $movimientoRegistralGravamen->folio = 2;
        $movimientoRegistralGravamen->estado = 'concluido';
        $movimientoRegistralGravamen->save();

        $gravamen = Gravamen::create(['movimiento_registral_id' => $movimientoRegistralGravamen->id,] + $gravamen);

        foreach ($acreedores as $acreedor) {

            Actor::create([
                'actorable_type' => 'App\Models\Gravamen',
                'actorable_id' => $gravamen->id,
                'persona_id' => $this->persona($acreedor),
                'tipo_actor' => 'acreedor'
            ]);

        }

        foreach ($actores as $actor) {

            Actor::create([
                'actorable_type' => 'App\Models\Gravamen',
                'actorable_id' => $gravamen->id,
                'persona_id' => $this->persona($actor),
                'tipo_actor' => 'deudor',
                'tipo_deudor' => 'I-DEUDOR ÚNICO'
            ]);

        }

    }

    public function headingRow(): int
    {
        return 2;
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function customValidationMessages()
    {
        return [
            'tipo_gravamen.required_unless' => 'El campo tipo_gravamen es requerido si el acto de gravamen tiene valor.',
            'valor_gravamen.required_unless' => 'El campo valor_gravamen es requerido si el acto de gravamen tiene valor.',
            'divisa_gravamen.required_unless' => 'El campo divisa_gravamen es requerido si el acto de gravamen tiene valor.',
            'fecha_inscripcion_gravamen.required_unless' => 'El campo fecha_inscripcion_gravamen es requerido si el acto de gravamen tiene valor.',
            'descripcion_acto_gravamen.required_unless' => 'El campo descripcion_acto_gravamen es requerido si el acto de gravamen tiene valor.',
            'actores_gravamen.required_unless' => 'El campo actores_gravamen es requerido si el acto de gravamen tiene valor.',
            'acreedores_gravamen.required_unless' => 'El campo acreedores_gravamen es requerido si el acto de gravamen tiene valor.',
        ];
    }

}
