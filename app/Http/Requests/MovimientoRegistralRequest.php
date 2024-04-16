<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimientoRegistralRequest extends FormRequest
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
            /* Predio */
            'folio_real' => 'nullable',
            'tomo' => 'nullable',
            'tomo_bis' => 'nullable',
            'registro' => 'nullable',
            'registro_bis' => 'nullable',
            'distrito' => 'required',
            'seccion' => 'required',

            /* Tramite */
            'año' => 'required',
            'tramite' => 'required',
            'tipo_servicio' => 'required',
            'monto' => 'required',

            'solicitante' => 'required',
            'nombre_solicitante' => 'required',

            'fecha_prelacion' => 'required',
            'fecha_entrega' => 'required',
            'fecha_pago' => 'nullable',

            'numero_oficio' => 'nullable',
            'numero_paginas' => 'nullable',
            'numero_inmuebles' => 'nullable',
            'numero_propiedad' => 'nullable',
            'numero_escritura' => 'nullable',
            'numero_notaria' => 'nullable',
            'numero_documento' => 'nullable',

            'valor_propiedad' => 'nullable',

            'movimiento_registral' => 'nullable',

            'observaciones' => 'nullable',

            /* Categoria - MR */
            'categoria_servicio' => 'required',
            'servicio' => 'required',

            /* Documento entrada */
            'tipo_documento' => 'nullable',
            'autoridad_cargo' => 'nullable',
            'autoridad_nombre' => 'nullable',
            'fecha_emision' => 'nullable',
            'procedencia' => 'nullable',
        ];
    }
}
