<?php

namespace App\Traits\Certificaciones;

use App\Models\Persona;
use App\Models\CertificadoPersona;

trait CertificadoPropiedadTrait{

    public $certificacion;

    public $nombre;
    public $ap_paterno;
    public $ap_materno;

    public $observaciones;

    public $modalRechazar = false;
    public $flagGenerar = false;

    public $temporalidad;

    public $predios = [];
    public $prediosOld = [];

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

    public function procesarPersonas($personas){

        $this->certificacion->personas()->delete();

        foreach ($personas as $persona) {

            $persona = Persona::firstOrCreate(
                [
                    'tipo' => 'FISICA',
                    'nombre' => $persona['nombre'],
                    'ap_paterno' => $persona['ap_paterno'],
                    'ap_materno' => $persona['ap_materno'],
                ],
                [
                    'tipo' => 'FISICA',
                    'nombre' => $persona['nombre'],
                    'ap_paterno' => $persona['ap_paterno'],
                    'ap_materno' => $persona['ap_materno']
                ]
            );

            CertificadoPersona::create(['certificacion_id' => $this->certificacion->id, 'persona_id' => $persona->id]);

        }

    }

    public function calcularDiaElaboracion($modelo){

        $diaElaboracion = $modelo->movimientoRegistral->fecha_pago;

        for ($i=0; $i < 2; $i++) {

            $diaElaboracion->addDays(1);

            while($diaElaboracion->isWeekend()){

                $diaElaboracion->addDay();

            }

        }

        return $diaElaboracion;

    }

}
