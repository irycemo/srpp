<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FolioRealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tomo' => $this->tomo_antecedente,
            'registro' => $this->registro_antecedente,
            'numero_propiedad' => $this->numero_propiedad_antecedente,
            'distrito' => $this->distrito_antecedente,
            'seccion' => $this->seccion_antecedente,
            'folio' => $this->folio,
            'matriz' => $this->matriz
        ];
    }
}
