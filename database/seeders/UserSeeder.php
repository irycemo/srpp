<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Enrique',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'enrique_j_@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Departamento de Operación y Desarrollode Sistemas',
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Jesus Manriquez Vargas',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'subdirti.irycem@correo.michoacan.gob.mx',
            'password' => Hash::make('sistema'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Omar Alejandro Morales Arellano',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'alex@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Director');

        User::create([
            'name' => 'Supervisor Copias',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'supervisor@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Supervisor Copias');

        User::create([
            'name' => 'Certificador 1',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'certificador1@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Certificador');

        User::create([
            'name' => 'Certificador 2',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'certificador2@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Certificador');

        User::create([
            'name' => 'Certificador 3',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'certificador3@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Certificador');

        User::create([
            'name' => 'Certificador 4',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'email' => 'certificador4@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
        ])->assignRole('Certificador');

        User::create([
            'name' => 'Certificador Juridico',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'certificador6@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Certificador Juridico');

        User::create([
            'name' => 'Certificador Oficialia',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'certificador5@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Certificador Oficialia');

        User::create([
            'name' => 'Propiedad 1',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'propiedad1@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Propiedad 2',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'propiedad2@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Propiedad 3',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'propiedad3@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Propiedad 4',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'email' => 'propiedad4@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Propiedad 5',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'email' => 'propiedad5@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Supervisor Copias',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'email' => 'supervisor2@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
        ])->assignRole('Supervisor Copias');

        User::create([
            'name' => 'Supervisor propiedad',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'email' => 'supervisor3@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
        ])->assignRole('Supervisor propiedad');

        User::create([
            'name' => 'Supervisor propiedad 2',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'supervisor4@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Supervisor propiedad');

        User::create([
            'name' => 'Consulta',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'email' => 'consulta@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
        ])->assignRole('Consulta');

        User::create([
            'name' => 'Consulta',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'consulta2@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Consulta');

        User::create([
            'name' => 'Pase a folio',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'email' => 'pasefolio@hotmail.com',
            'password' => Hash::make('sistema'),
            'area' => 'Dirección del Registro Público de la Propiedad',
        ])->assignRole('Pase a folio');

    }
}
