<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificadoListaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>  $this->id,
            'folio_real' => $this->folioReal?->folio,
            'año' => $this->año,
            'tramite' =>  $this->tramite,
            'usuario' =>  $this->usuario,
            'distrito' =>  $this->distrito,
            'tramite' =>  $this->tramite,
            'estado' =>  $this->estado,
            'servicio_nombre' => $this->servicio_nombre
        ];
    }
}
