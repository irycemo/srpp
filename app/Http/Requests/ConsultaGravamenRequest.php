<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultaGravamenRequest extends FormRequest
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
            'folio_real' => 'nullable',
            'folio' => 'nullable',
            'tomo_gravamen' => 'nullable',
            'registro_gravamen' => 'nullable',
            'distrito' => 'required',
            'seccion' => 'required'
        ];
    }
}
