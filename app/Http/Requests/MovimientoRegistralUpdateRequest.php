<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimientoRegistralUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           /* Antecedente */
           'folio_real' => 'nullable',
           'tomo' => 'nullable',
           'tomo_bis' => 'nullable',
           'registro' => 'nullable',
           'registro_bis' => 'nullable',
           'distrito' => 'nullable',
           'seccion' => 'nullable',
           'numero_propiedad' => 'nullable',

           /* Tramite */
           'tipo_servicio' => 'nullable',
           'categoria_servicio' => 'nullable',

           /* Solicitante */
           'solicitante' => 'nullable',
           'nombre_solicitante' => 'nullable',

           /* Fechas */
           'fecha_entrega' => 'nullable',

           /* Gravamen */
           'tomo_gravamen' => 'nullable',
           'registro_gravamen' => 'nullable',

           'numero_oficio' => 'nullable',
           'numero_paginas' => 'nullable',
           'numero_inmuebles' => 'nullable',
           'numero_escritura' => 'nullable',
           'numero_notaria' => 'nullable',

           'valor_propiedad' => 'nullable',

           'movimiento_registral' => 'required',
           'asiento_registral' => 'nullable',

           'observaciones' => 'nullable',

           /* Documento entrada */
           'tipo_documento' => 'nullable',
           'autoridad_cargo' => 'nullable',
           'autoridad_nombre' => 'nullable',
           'fecha_emision' => 'nullable',
           'numero_documento' => 'nullable',
           'procedencia' => 'nullable',
        ];
    }
}
