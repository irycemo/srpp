<?php

namespace App\Traits\Certificaciones;

use App\Models\Persona;
use App\Models\CertificadoPersona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

trait CertificadoPropiedadTrait{

    public $nombre;
    public $ap_paterno;
    public $ap_materno;

    public $observaciones;

    public $modalRechazar = false;

    public function procesarPersona($nombre, $ap_paterno, $ap_materno){

        $this->certificacion->personas()->delete();

        $persona = Persona::firstOrCreate(
            [
                'tipo' => 'FISICA',
                'nombre' => $nombre,
                'ap_paterno' => $ap_paterno,
                'ap_materno' => $ap_materno
            ],
            [
                'tipo' => 'FISICA',
                'nombre' => $nombre,
                'ap_paterno' => $ap_paterno,
                'ap_materno' => $ap_materno
            ]
        );

        CertificadoPersona::create(['certificacion_id' => $this->certificacion->id, 'persona_id' => $persona->id]);

    }

}
