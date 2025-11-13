<?php

namespace App\Http\Services;

use App\Models\Old\AntecedenteOld;
use App\Models\Old\GravamenOld;

class OldBDService{

    public function tractoGravamenes(int $propiedadId){

        $array_antecedentes = AntecedenteOld::obtenerAntecedentes($propiedadId);

        array_push($array_antecedentes, $propiedadId);

        return GravamenOld::whereIn('idPropiedad', $array_antecedentes)->get();

    }

}