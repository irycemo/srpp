<?php

namespace App\Imports;

use App\Constantes\Constantes;
use App\Models\Import;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class FolioRealImport implements OnEachRow, WithHeadingRow, WithValidation, WithMultipleSheets, SkipsEmptyRows
{

    protected bool $hasErrors = false;

    public function __construct(public string $batchId)
    {}

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
            'monto_transaccion' => 'required|numeric',
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
            'descripcion' => 'nullable',
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

    public function validarRelaciones(Row $row):array
    {

        $errores = [];

        /* COLINDANCIAS */
        $colindancias = $row['colindancias'];

        $array_colinadancias = explode('|', $colindancias);

        foreach($array_colinadancias as $colindancia){

            $campos = explode(':', $colindancia);

            if(! in_array(trim($campos[0]), Constantes::VIENTOS))
                $errores[] = "Error en el campo viento de las colindancias en la linea " . ($row->getIndex());

            if(!isset($campos[1]) || !isset($campos[2]))
                $errores[] = "Error en los campos de las colindancias en la linea " . ($row->getIndex());

            if(isset($campos[3]))
                $errores[] = "Error en los campos de las colindancias en la lineas " . ($row->getIndex());

            if($campos[1] == '' || $campos[2] == '')
                $errores[] = "Error en los campos de las colindancias en la lineas " . ($row->getIndex());

        }

        /* PROPIETARIOS */
        $propietarios = $row['propietarios'];

        $array_propietarios = explode('|', $propietarios);

        foreach ($array_propietarios as $propietario) {

            $campos = explode(':', $propietario);

            if(!in_array($campos[0], ['FISICA', 'MORAL']))
                $errores[] = "Error en el campo tipo de persona de los propietarios en la linea " . ($row->getIndex());

            if(in_array($campos[0], ['FISICA', 'FÍSICA'])){

                if(!isset($campos[1]) || !isset($campos[2]) || !isset($campos[3]))
                    $errores[] = "Error en los campos de los propietarios en la linea " . ($row->getIndex());

            }else{

                if(!isset($campos[1]))
                    $errores[] = "Error en los campos de los propietarios en la linea " . ($row->getIndex());

            }

        }

        if($row['acto_contenido_gravamen']){

            /* ACREEDORES */
            $acreedores = $row['acreedores_gravamen'];

            $array_acreedores = explode('|', $acreedores);

            foreach ($array_acreedores as $acreedor) {

                $campos = explode(':', $acreedor);

                if(!in_array($campos[0], ['FISICA', 'MORAL']))
                    $errores[] = "Error en el campo tipo de persona de los acreedores del gravamen en la linea " . ($row->getIndex());

                if(in_array($campos[0], ['FISICA', 'FÍSICA'])){

                    if(!isset($campos[1]) || !isset($campos[2]) || !isset($campos[3]))
                        $errores[] = "Error en los campos de los acreedores del gravamen en la linea " . ($row->getIndex());

                }else{

                    if(!isset($campos[1]))
                        $errores[] = "Error en los campos de los acreedores del gravamen en la linea " . ($row->getIndex());

                }

            }

            /* ACTORES GRAVAMEN */
            $actores_gravamen = $row['actores_gravamen'];

            $array_actores_gravamen = explode('|', $actores_gravamen);

            foreach ($array_actores_gravamen as $actor) {

                $campos = explode(':', $actor);

                if(!in_array($campos[0], ['FISICA', 'MORAL']))
                    $errores[] = "Error en el campo tipo de persona de los actores del gravamen en la linea " . ($row->getIndex());

                if(in_array($campos[0], ['FISICA', 'FÍSICA'])){

                    if(!isset($campos[1]) || !isset($campos[2]) || !isset($campos[3]))
                        $errores[] = "Error en los campos de los actores del gravamen en la linea " . ($row->getIndex());

                }else{

                    if(!isset($campos[1]))
                        $errores[] = "Error en los campos de los actores del gravamen en la linea " . ($row->getIndex());

                }

            }

        }

        if (!empty($errores)) {
            $this->hasErrors = true;
        }

        return $errores;

    }

    public function onRow(Row $row){

        $data = $row->toArray();

        $errores =  $this->validarRelaciones($row);

        Import::create([
            'batch_id'   => $this->batchId,
            'row_number' => $row->getIndex(),
            'data'       => json_encode($data),
            'errores'    => $errores ? json_encode($errores) : null,
            'status'     => $errores ? 'error' : 'pending'
        ]);

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

    public function customValidationMessages():array
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

    public function chunkSize(): int
    {
        return 50;
    }

    public function getBatchId(): string
    {
        return $this->batchId;
    }

    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }

}
