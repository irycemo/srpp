<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AntecedenteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'numero_propiedad' => $this->noprop,
            'ubicacion' => $this->ubicacion
        ];

    }
}
