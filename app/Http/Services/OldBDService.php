<?php

namespace App\Http\Services;

use App\Models\Old\AntecedenteOld;
use App\Models\Old\GravamenOld;
use App\Models\Old\PropiedadSentencia;
use App\Models\Old\SentenciaOld;

class OldBDService{

    public function tractoGravamenes(int $propiedadId){

        $array_antecedentes = AntecedenteOld::obtenerAntecedentes($propiedadId);

        array_push($array_antecedentes, $propiedadId);

        return GravamenOld::whereIn('idPropiedad', $array_antecedentes)->get();

    }

    public function sentencias(int $propiedadId){

        $sentencias_ids = PropiedadSentencia::where('idPropiedad', $propiedadId)->pluck('idSentencia');

        return SentenciaOld::find($sentencias_ids);

    }

}