<?php

namespace Database\Seeders;

use App\Models\Distrito;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DistritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Distrito::create([
            'nombre' => 'Morelia',
            'clave' => '1'
        ]);

        Distrito::create([
            'nombre' => 'Uruapan',
            'clave' => '2'
        ]);

        Distrito::create([
            'nombre' => 'Zamora',
            'clave' => '3'
        ]);

        Distrito::create([
            'nombre' => 'Apatzingan',
            'clave' => '4'
        ]);

        Distrito::create([
            'nombre' => 'Ciudad Hidalgo',
            'clave' => '5'
        ]);

        Distrito::create([
            'nombre' => 'Tacambaro',
            'clave' => '6'
        ]);

        Distrito::create([
            'nombre' => 'Patzcuaro',
            'clave' => '7'
        ]);

        Distrito::create([
            'nombre' => 'Zitacuaro',
            'clave' => '8'
        ]);

        Distrito::create([
            'nombre' => 'Jiquilpan',
            'clave' => '9'
        ]);

        Distrito::create([
            'nombre' => 'Zinapecuaro',
            'clave' => '10'
        ]);

        Distrito::create([
            'nombre' => 'Zacapu',
            'clave' => '11'
        ]);

        Distrito::create([
            'nombre' => 'La piedad',
            'clave' => '12'
        ]);

        Distrito::create([
            'nombre' => 'Huetamo',
            'clave' => '13'
        ]);

        Distrito::create([
            'nombre' => 'Maravatio',
            'clave' => '14'
        ]);

        Distrito::create([
            'nombre' => 'Salazar',
            'clave' => '15'
        ]);

        Distrito::create([
            'nombre' => 'Puruandiro',
            'clave' => '16'
        ]);

        Distrito::create([
            'nombre' => 'Coalcoman',
            'clave' => '17'
        ]);

        Distrito::create([
            'nombre' => 'Ario de Rosales',
            'clave' => '18'
        ]);

        Distrito::create([
            'nombre' => 'Tanhuato',
            'clave' => '19'
        ]);

    }

}
