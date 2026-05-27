<?php

namespace App\Http\Services;

use App\Models\Old\AntecedenteOld;
use App\Models\Old\GravamenOld;
use App\Models\Old\PropiedadSentencia;
use App\Models\Old\PropietariosOld;
use App\Models\Old\SentenciaOld;
use App\Models\Propiedadold;

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

    public function obtenerPropietarios(Propiedadold $propiedad){

        $propietarios = PropietariosOld::where('idPropiedad', $propiedad->id)->get();

        $array = [];

        foreach ($propietarios as $propietario) {

            $nombre = trim($propietario->nombre1);

            if($propietario->nombre2 && strlen(trim($propietario->nombre2)) > 0){

                $nombre = $nombre . ' ' . $propietario->nombre2;

            }

            $array [] = [
                'nombre' => $nombre,
                'ap_paterno' => trim($propietario->paterno),
                'ap_materno' => trim($propietario->materno),
            ];

        }

        return $array;

    }

}