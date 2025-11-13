<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntecedenteOld extends Model
{

    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'antecedentesprop';

    public static function obtenerAntecedentes(int $idPropiedad, array &$visitados = []): array
    {
        // Buscar antecedentes directos
        $antecedentes = self::where('idPropiedad', $idPropiedad)->pluck('idAntecedente');

        foreach ($antecedentes as $idAntecedente) {

            if ($idAntecedente && !in_array($idAntecedente, $visitados, true)) {

                $visitados[] = $idAntecedente;

                // Llamada recursiva
                self::obtenerAntecedentes($idAntecedente, $visitados);

            }

        }

        return $visitados;
    }

}
