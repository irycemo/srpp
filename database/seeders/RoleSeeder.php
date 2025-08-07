<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role1 = Role::create(['name' => 'Administrador']);
        /* Certificaciones */
        $role2 = Role::create(['name' => 'Supervisor certificaciones']);
        $role3 = Role::create(['name' => 'Certificador']);
        $role3 = Role::create(['name' => 'Certificador Gravamen']);
        $role3 = Role::create(['name' => 'Certificador Propiedad']);

        $role5 = Role::create(['name' => 'Director']);
        $role6 = Role::create(['name' => 'Consulta']);

        $role3 = Role::create(['name' => 'Certificador Juridico']);
        $role3 = Role::create(['name' => 'Certificador Oficialia']);
        /* Inscripciones */
        $role7 = Role::create(['name' => 'Propiedad']);
        $role7 = Role::create(['name' => 'Gravamen']);
        $role7 = Role::create(['name' => 'Cancelacación']);
        $role7 = Role::create(['name' => 'Sentencias']);
        $role7 = Role::create(['name' => 'Varios']);
        $role8 = Role::create(['name' => 'Supervisor inscripciones']);
        /* Pase a folio */
        $role9 = Role::create(['name' => 'Pase a folio']);
        /* Registrador */
        $role9 = Role::create(['name' => 'Registrador Propiedad']);
        $role9 = Role::create(['name' => 'Registrador Gravamen']);
        $role9 = Role::create(['name' => 'Registrador Cancelaciones']);
        $role9 = Role::create(['name' => 'Registrador Varios']);
        $role9 = Role::create(['name' => 'Registrador Sentencias']);
        $role9 = Role::create(['name' => 'Registrador fraccionamientos']);

        $role9 = Role::create(['name' => 'Folio real moral']);
        $role9 = Role::create(['name' => 'Avisos preventivos']);
        $role9 = Role::create(['name' => 'Aclaraciones administrativas']);

        $role10 = Role::create(['name' => 'Jefe de departamento certificaciones']);
        $role12 = Role::create(['name' => 'Jefe de departamento inscripciones']);
        $role13 = Role::create(['name' => 'Jefe de departamento jurídico']);
        $role14 = Role::create(['name' => 'Operador']);
        $role11 = Role::create(['name' => 'Sistemas']);
        $role11 = Role::create(['name' => 'Regional']);


        Permission::create(['name' => 'Lista de roles', 'area' => 'Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear rol', 'area' => 'Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar rol', 'area' => 'Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar rol', 'area' => 'Roles'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de permisos', 'area' => 'Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear permiso', 'area' => 'Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar permiso', 'area' => 'Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar permiso', 'area' => 'Permisos'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de usuarios', 'area' => 'Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear usuario', 'area' => 'Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar usuario', 'area' => 'Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar usuario', 'area' => 'Usuarios'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de efirmas', 'area' => 'Efirmas'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear efirma', 'area' => 'efirmas'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar efirma', 'area' => 'efirmas'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar efirma', 'area' => 'efirmas'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de folios reales', 'area' => 'Folios reales'])->syncRoles([$role1]);
        Permission::create(['name' => 'Envia a captura', 'area' => 'Folios reales'])->syncRoles([$role1]);
        Permission::create(['name' => 'Convertir en matriz', 'area' => 'Folios reales'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de movimientos registrales', 'area' => 'Movimientos Registrales'])->syncRoles([$role1]);
        Permission::create(['name' => 'Reasignar movimiento', 'area' => 'Movimientos Registrales'])->syncRoles([$role1]);

        Permission::create(['name' => 'Centinela', 'area' => 'Centinela'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de distritos', 'area' => 'Distritos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear distrito', 'area' => 'Distritos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar distrito', 'area' => 'Distritos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar distrito', 'area' => 'Distritos'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de municipios', 'area' => 'Municipios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear municipio', 'area' => 'Municipios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar municipio', 'area' => 'Municipios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar municipio', 'area' => 'Municipios'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de tenencias', 'area' => 'Tenencias'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear tenencia', 'area' => 'Tenencias'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar tenencia', 'area' => 'Tenencias'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar tenencia', 'area' => 'Tenencias'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de ranchos', 'area' => 'Ranchos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear rancho', 'area' => 'Ranchos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar rancho', 'area' => 'Ranchos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar rancho', 'area' => 'Ranchos'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de personas', 'area' => 'Personas'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar persona', 'area' => 'Personas'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de propiedades', 'area' => 'Propiedades'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de regionales', 'area' => 'Regionales'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear regional', 'area' => 'Regionales'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar regional', 'area' => 'Regionales'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar regional', 'area' => 'Regionales'])->syncRoles([$role1]);

        Permission::create(['name' => 'Auditoria', 'area' => 'Auditoria'])->syncRoles([$role1]);

        Permission::create(['name' => 'Logs', 'area' => 'Logs'])->syncRoles([$role1]);

        Permission::create(['name' => 'Área Certificaciones', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2, $role6]);
        Permission::create(['name' => 'Elaborar copias', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Copiador', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Copias Simples', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Copias Certificadas', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Reimprimir documento', 'area' => 'Certificaciones'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'Finalizar copias simples', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Consultas certificaciones', 'area' => 'Certificaciones'])->syncRoles([$role1, $role5]);
        Permission::create(['name' => 'Rechazar copias certificadas', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'Finalizar copias certificadas', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Reasignar copias', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Finalizar consulta', 'area' => 'Certificaciones'])->syncRoles([$role1, $role6]);
        Permission::create(['name' => 'Reactivar trámite', 'area' => 'Certificaciones'])->syncRoles([$role1, $role6]);
        Permission::create(['name' => 'Certificado gravamen', 'area' => 'Certificaciones'])->syncRoles([$role1, $role6]);
        Permission::create(['name' => 'Certificado propiedad', 'area' => 'Certificaciones'])->syncRoles([$role1, $role6]);

        Permission::create(['name' => 'Pase a folio', 'area' => 'Pase a folio'])->syncRoles([$role1, $role7, $role9]);

        Permission::create(['name' => 'Área Inscripciones', 'area' => 'Inscripciones'])->syncRoles([$role1, $role7, $role9, $role8]);
        Permission::create(['name' => 'Consultas inscripción', 'area' => 'Inscripciones'])->syncRoles([$role1, $role7, $role9, $role8]);

        Permission::create(['name' => 'Propiedad', 'area' => 'Inscripciones'])->syncRoles([$role1, $role7, $role9, $role8]);
        Permission::create(['name' => 'Propiedad inscripción', 'area' => 'Inscripciones'])->syncRoles([$role1, $role7, $role9, $role8]);
        Permission::create(['name' => 'Subdivisiones', 'area' => 'Inscripciones'])->syncRoles([$role1, $role7, $role9, $role8]);
        Permission::create(['name' => 'Fraccionamientos', 'area' => 'Inscripciones'])->syncRoles([$role1, $role7, $role9, $role8]);
        Permission::create(['name' => 'Fideicomisos', 'area' => 'Inscripciones'])->syncRoles([$role1, $role7, $role9, $role8]);

        Permission::create(['name' => 'Gravamen', 'area' => 'Inscripciones'])->syncRoles([$role1]);
        Permission::create(['name' => 'Gravamen inscripción', 'area' => 'Inscripciones'])->syncRoles([$role1]);

        Permission::create(['name' => 'Sentencias', 'area' => 'Inscripciones'])->syncRoles([$role1]);
        Permission::create(['name' => 'Sentencias inscripción', 'area' => 'Inscripciones'])->syncRoles([$role1]);

        Permission::create(['name' => 'Cancelación', 'area' => 'Inscripciones'])->syncRoles([$role1]);
        Permission::create(['name' => 'Cancelación inscripción', 'area' => 'Inscripciones'])->syncRoles([$role1]);

        Permission::create(['name' => 'Varios', 'area' => 'Inscripciones'])->syncRoles([$role1]);
        Permission::create(['name' => 'Varios inscripción', 'area' => 'Inscripciones'])->syncRoles([$role1]);

        Permission::create(['name' => 'Área Personas morales', 'area' => 'Personas morales'])->syncRoles([$role1, $role7, $role9, $role8]);
        Permission::create(['name' => 'Pase a folio persona moral', 'area' => 'Personas morales'])->syncRoles([$role1]);
        Permission::create(['name' => 'Reformas', 'area' => 'Personas morales'])->syncRoles([$role1]);
        Permission::create(['name' => 'Reformas inscripción', 'area' => 'Personas morales'])->syncRoles([$role1]);
        Permission::create(['name' => 'Asignación', 'area' => 'Personas morales'])->syncRoles([$role1]);

        Permission::create(['name' => 'Cancelaciones', 'area' => 'Inscripciones'])->syncRoles([$role1]);

        Permission::create(['name' => 'Consultas', 'area' => 'Consultas'])->syncRoles([$role1, $role7]);

    }

}
