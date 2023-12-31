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
            'email' => 'correo@correo.com',
            'password' => Hash::make('12345678'),
            'area' => 'Departamento de Operación y Desarrollode Sistemas',
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Jesus Manriquez Vargas',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'subdirti.irycem@correo.michoacan.gob.mx',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Omar Alejandro Morales Arellano',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'alex@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Director');

        User::create([
            'name' => 'Supervisor Copias',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'supervisor@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Supervisor Copias');

        User::create([
            'name' => 'Certificador 1',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'certificador1@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Certificador');

        User::create([
            'name' => 'Certificador 2',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'certificador2@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Certificador');

        User::create([
            'name' => 'Certificador 3',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'certificador3@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Certificador');

        User::create([
            'name' => 'Certificador 4',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'certificador4@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Certificador');

        User::create([
            'name' => 'Propiedad 1',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'propiedad1@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Propiedad 2',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'propiedad2@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Propiedad 3',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'propiedad3@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Propiedad 4',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'email' => 'propiedad4@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Propiedad 5',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'email' => 'propiedad5@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
        ])->assignRole('Propiedad');

        User::create([
            'name' => 'Supervisor Copias',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'email' => 'supervisor2@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
        ])->assignRole('Supervisor Copias');

    }
}
