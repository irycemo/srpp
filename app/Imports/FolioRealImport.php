<?php

namespace App\Imports;

use Exception;
use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\Gravamen;
use App\Models\FolioReal;
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
            'nombre_vialidad' => 'nullable|string',
            'nombre_asentamiento' => 'nullable|string',
            'numero_exterior' => 'nullable|string',
            'numero_exterior_2' => 'nullable|string',
            'numero_adicional' => 'nullable|string',
            'numero_adicional_2' => 'nullable|string',
            'numero_interior' => 'nullable|string',
            'lote' => 'nullable|string',
            'manzana_ubicacion' => 'nullable|string',
            'codigo_postal' => 'nullable|numeric',
            'lote_fraccionador' => 'nullable|string',
            'manzana_fraccionador' => 'nullable|string',
            'etapa_fraccionador' => 'nullable|string',
            'nombre_edificio' => 'nullable|string',
            'clave_edificio' => 'nullable|string',
            'departamento_edificio' => 'nullable|string',
            'municipio_ubicacion' => 'required|string',
            'ciudad' => 'nullable|string',
            'localidad_ubicacion' => 'nullable|string',
            'poblado' => 'nullable|string',
            'ejido' => 'nullable|string',
            'parcela' => 'nullable|string',
            'solar' => 'nullable|string',
            'zona_ubicacion' => 'nullable|string',
            'propietarios' => 'required',
            'transmitentes' => 'required',
            'tomo_gravamen' => 'required|numeric',
            'registro_gravamen' => 'required|numeric',
            'distrito_gravamen' => 'required|numeric|min:1|max:19',
            'tipo_documento_gravamen' => 'required|in:escritura,oficio,contrato,acta de embargo,convenio,fianza',
            'cargo_autoridad_gravamen' => 'required|string',
            'nombre_autoridad_gravamen' => 'required|string',
            'fecha_emision_gravamen' => 'required|date',
            'numero_documento_gravamen' => 'required|string',
            'procedencia_gravamen' => 'required|string',
            'acto_contenido_gravamen' => ['required', Rule::in(Constantes::ACTOS_INSCRIPCION_GRAVAMEN)],
            'tipo_gravamen' => 'required|string',
            'valor_gravamen' => 'required|numeric',
            'divisa_gravamen' => ['required', Rule::in(Constantes::DIVISAS)],
            'fecha_inscripcion_gravamen' => 'required|date',
            'comentario_gravamen' => 'required|date',
            'actores_gravamen' => 'required',
            'acreedores_gravamen' => 'required',
        ];

    }

    public function collection(Collection $rows){

        try {

            DB::transaction(function () use($rows){

                $gravamen = Gravamen::make([
                    'acto_contenido' => $rows['acto_contenido_gravamen'],
                    'servicio' => '----',
                    'estado' => 'activo',
                    'tipo' => $rows['tipo_gravamen'],
                    'valor_gravamen' => $rows['valor_gravamen'],
                    'divisa' => $rows['divisa_gravamen'],
                    'fecha_inscripcion' => $rows['fecha_inscripcion_gravamen'],
                    'observaciones' => $rows['descripcion_acto_gravamen'],
                ]);

                foreach ($rows as $key => $row)
                {

                    $key = $key + 3;

                    $colindancias = $this->procesarColindacias($row['colindancias'], $key);

                    $propietarios = $this->procesarPropietarios($row['propietarios'], $key);

                    $transmitentes = $this->procesarTransmitentes($row['transmitentes'], $key);

                    $acreedores = $this->procesarAcreedores($row['acreedores_gravamen'], $key);

                    $actores = $this->procesarActores($row['actores_gravamen'], $key);

                    $predio = $this->crearPredio($row);

                    $this->procesarRealacionesDePredio($predio->id, $colindancias, $propietarios, $transmitentes);

                }

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
                throw new Exception("Error en el campo viento de las colindancias en la linea " . $linea);

            if(!isset($campos[1]) || !isset($campos[2]))
                throw new Exception("Error en los campos de las colindancias en la linea " . $linea);

            if(isset($campos[3]))
                throw new Exception("Error en los campos de las colindancias en la lineass " . $linea);

            if($campos[1] == '' || $campos[2] == '')
                throw new Exception("Error en los campos de las colindancias en la lineass " . $linea);

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
                throw new Exception("Error en el campo tipo de persona de los propietarios en la linea " . $linea);

            if(isset($campos[1]) && !is_numeric((float)$campos[1]) || isset($campos[2]) && !is_numeric((float)$campos[2]) || isset($campos[3]) && !is_numeric((float)$campos[3]))
                throw new Exception("Error en los campos de los propietarios en la linea " . $linea);

            if($campos[0] === 'FISICA'){

                if(!isset($campos[4]) || !isset($campos[5]) || !isset($campos[6]))
                    throw new Exception("Error en los campos de los propietarios en la linea " . $linea);

                $persona = [
                    'tipo' => $campos[0],
                    'porcentaje_propiedad' => $campos[1],
                    'porcentaje_nuda' => $campos[2],
                    'porcentaje_usufructo' => $campos[3],
                    'nombre' => $campos[4],
                    'ap_paterno' => $campos[5],
                    'ap_materno' => $campos[6],
                ];

            }else{

                if(!isset($campos[7]))
                    throw new Exception("Error en los campos de los propietarios en la linea " . $linea);

                $persona = [
                    'tipo' => $campos[0],
                    'porcentaje_propiedad' => $campos[1],
                    'porcentaje_nuda' => $campos[2],
                    'porcentaje_usufructo' => $campos[3],
                    'razon_social' => $campos[7]
                ];

            }

            $propietariosArreglo [] = $persona;

        }


        return $propietariosArreglo;

    }

    public function procesarTransmitentes($transmitentes, $linea):array
    {

        $array = explode('|', $transmitentes);

        $transmitentesArreglo = [];

        foreach ($array as $transmitente) {

            $campos = explode(':', $transmitente);

            if(!in_array($campos[0], ['FISICA', 'MORAL']))
                throw new Exception("Error en el campo tipo de persona de los transmitentes en la linea " . $linea);

            if($campos[0] === 'FISICA'){

                if(!isset($campos[1]) || !isset($campos[2]) || !isset($campos[3]))
                    throw new Exception("Error en los campos de los transmitentes en la linea " . $linea);

                $persona = [
                    'tipo' => $campos[0],
                    'nombre' => $campos[1],
                    'ap_paterno' => $campos[2],
                    'ap_materno' => $campos[3],
                ];

            }else{

                if(!isset($campos[4]))
                    throw new Exception("Error en los campos de los transmitentes en la linea " . $linea);

                $persona = [
                    'tipo' => $campos[0],
                    'razon_social' => $campos[4]
                ];

            }

            $transmitentesArreglo [] = $persona;

        }


        return $transmitentesArreglo;

    }

    public function procesarAcreedores($acreedores, $linea):array
    {

        $array = explode('|', $acreedores);

        $acreedoresArreglo = [];

        foreach ($array as $acreedor) {

            $campos = explode(':', $acreedor);

            if(!in_array($campos[0], ['FISICA', 'MORAL']))
                throw new Exception("Error en el campo tipo de persona de los acreedores del gravamen en la linea " . $linea);

            if($campos[0] === 'FISICA'){

                if(!isset($campos[1]) || !isset($campos[2]) || !isset($campos[3]))
                    throw new Exception("Error en los campos de los acreedores del gravamen en la linea " . $linea);

                $acreedor = [
                    'tipo' => $campos[0],
                    'nombre' => $campos[1],
                    'ap_paterno' => $campos[2],
                    'ap_materno' => $campos[3],
                ];

            }else{

                if(!isset($campos[4]))
                    throw new Exception("Error en los campos de los acreedores del gravamen en la linea " . $linea);

                $acreedor = [
                    'tipo' => $campos[0],
                    'razon_social' => $campos[4]
                ];

            }

            $acreedoresArreglo [] = $acreedor;

        }


        return $acreedoresArreglo;

    }

    public function procesaractores($actores, $linea):array
    {

        $array = explode('|', $actores);

        $actoresArreglo = [];

        foreach ($array as $actor) {

            $campos = explode(':', $actor);

            if(!in_array($campos[0], Constantes::TIPO_DEUDOR))
                throw new Exception("Error en el campo tipo de persona de los actores del gravamen en la linea " . $linea);

            if($campos[0] === 'FISICA'){

                if(!isset($campos[1]) || !isset($campos[2]) || !isset($campos[3]))
                    throw new Exception("Error en los campos de los actores del gravamen en la linea " . $linea);

                $actor = [
                    'tipo' => $campos[0],
                    'nombre' => $campos[1],
                    'ap_paterno' => $campos[2],
                    'ap_materno' => $campos[3],
                ];

            }else{

                if(!isset($campos[4]))
                    throw new Exception("Error en los campos de los actores del gravamen en la linea " . $linea);

                $actor = [
                    'tipo' => $campos[0],
                    'razon_social' => $campos[4]
                ];

            }

            $actoresArreglo [] = $actor;

        }


        return $actoresArreglo;

    }

    public function crearPredio($linea):Predio
    {

        return Predio::create([
            'localidad' => $linea['localidad'],
            'oficina' => $linea['oficina'],
            'tipo' => $linea['tipo'],
            'registro' => $linea['registro'],
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
            'manzana_ubicacion' => $linea['manzana_ubicacion'],
            'codigo_postal' => $linea['codigo_postal'],
            'lote_fraccionador' => $linea['lote_fraccionador'],
            'manzana_fraccionador' => $linea['manzana_fraccionador'],
            'etapa_fraccionador' => $linea['etapa_fraccionador'],
            'nombre_edificio' => $linea['nombre_edificio'],
            'clave_edificio' => $linea['clave_edificio'],
            'departamento_edificio' => $linea['departamento_edificio'],
            'municipio_ubicacion' => $linea['municipio_ubicacion'],
            'ciudad' => $linea['ciudad'],
            'localidad_ubicacion' => $linea['localidad_ubicacion'],
            'poblado' => $linea['poblado'],
            'ejido' => $linea['ejido'],
            'parcela' => $linea['parcela'],
            'solar' => $linea['solar'],
            'zona_ubicacion' => $linea['zona_ubicacion'],
        ]);

    }

    public function procesarRealacionesDePredio($predioId, $colindancias, $propietarios, $transmitentes):void
    {

        foreach ($colindancias as $colindancia) {

            Colindancia::create([
                'predio_id' => $predioId,
                'viento' => $colindancia[0],
                'longitud' => $colindancia[1],
                'descripcion' => $colindancia[2],
            ]);

        }

        foreach ($propietarios as $propietario) {

            Actor::create([
                'actorable_type' => 'App\Models\Predio',
                'actorable_id' => $predioId,
                'persona_id' => $this->persona($propietario),
                'tipo_actor' => 'propietario',
                'porcentaje_propiedad' => $propietario[1],
                'porcentaje_nuda' => $propietario[2],
                'porcentaje_usufructo' => $propietario[3],
            ]);

        }

        foreach ($transmitentes as $transmitente) {

            Actor::create([
                'actorable_type' => 'App\Models\Predio',
                'actorable_id' => $predioId,
                'persona_id' => $this->persona($transmitente),
                'tipo_actor' => 'transmitente'
            ]);

        }

    }

    public function persona($array):int
    {

        $persona = Persona::when($array[0] == 'FISICA', function($q) use($array){
                                $q->where('nombre', $array[4])
                                    ->where('ap_paterno', $array[5])
                                    ->where('ap_materno', $array[6]);
                            })
                            ->when($array[0] == 'MORAL', function($q) use($array){
                                $q->where('razon_social', $array[7]);
                            })
                            ->first();

        if(!$persona){

            $persona = Persona::create([
                        'tipo' => $array[0],
                        'nombre' => $array[4],
                        'ap_paterno' => $array[5],
                        'ap_materno' => $array[6],
                        'razon_social' => $array[7],
                        ]);

            return $persona->id;

        }else{

            return $persona->id;

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

}
