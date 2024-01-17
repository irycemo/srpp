<?php

namespace App\Constantes;

class Constantes{

    /* PERMISOS */
    const AREAS = [
        'Roles',
        'Permisos',
        'Usuarios',
        'Auditoria',
        'Logs',
        'Distritos',
        'Municipios',
        'Tenencias',
        'Ranchos',
        'Certificaciones',
        'Inscripciones',
        'Pase a folio',
        'Consultas'
    ];

    /* USUARIOS */
    const AREAS_ADSCRIPCION = [
        'Dirección de Catastro',
        'Dirección General del Instituto Registral y Catastral',
        'Dirección del Registro Público de la Propiedad',
        'Delegación Administrativa',
        'Subdirección de Planeación Estratégica',
        'Subdirección Jurídica',
        'Subdirección de Tecnologías de la Información',
        'Departamento de Recepción Catastral y Registral',
        'Departamento de Registro de Inscripciones',
        'Departamento de Certificaciones',
        'Departamento de Archivo RPP',
        'Departamento de Anotaciones y Trámites Administrativos',
        'Departamento de lo Contencioso',
        'Departamento de Operación y Desarrollo de Sistemas',
        'Departamento de Soporte Técnico y Redes',
        'Departamento de Bases de Datos',
        'Departamento de Valuación',
        'Departamento de Gestión Catastral',
        'Departamento de Registro de Cartografía',
        'Departamento de Control Presupuestal y Recursos Financieros',
        'Departamento de Recursos Humanos, Materiales y Servicios Generales',
        'Departamento de Archivo Catastro',
        'Coordinación Regional 1 Lerma Chapala (Zamora)',
        'Coordinación Regional 2 Bajio (La Piedad)',
        'Coordinación Regional 3 Tepalcatepec (Apatzingan)',
        'Coordinación Regional 4 Purhépecha (Uruapan)',
        'Coordinación Regional 5 Tierra Caliente (Huetamo)',
        'Coordinación Regional 6 Sierra Costa (Lazaro Cardenas)',
        'Coordinación Regional 7 Oriente (Ciudad Hidalgo)',
    ];

    const UBICACIONES = [
        'Catastro',
        'RPP',
        'Regional 1',
        'Regional 2',
        'Regional 3',
        'Regional 4',
        'Regional 5',
        'Regional 6',
        'Regional 7'
    ];

    const DISTRITOS = [
        1 => '01 Morelia',
        2 => '02 Uruapan',
        3 => '03 Zamora',
        4 => '04 Apatzingán',
        5 => '05 Hidalgo',
        6 => '06 Tacámbaro',
        7 => '07 Pátzcuaro',
        8 => '08 Zitácuaro',
        9 => '09 Jiquilpan',
        10 => '10 Zinapécuaro',
        11 => '11 Zacapu',
        12 => '12 La Piedad',
        13 => '13 Huetamo',
        14 => '14 Maravatío',
        15 => '15 Salazar',
        16 => '16 Puruándiro',
        17 => '17 Coalcoman',
        18 => '18 Ario De Rosales',
        19 => '19 Tanhuato'
    ];

    const TIPO_PROPIETARIO = [
        'NUDO-PROPIETARIO',
        'USUFRUCTUARIO',
        'JUBILADO O PENSIONADO',
        'FIDUCIARIO',
        'FIDECOMITENTE',
        'PROPIETARIO',
        'POSEEDOR'
    ];

    const TIPO_VIALIDADES = [
        'AMPLIACIÓN',
        'ANDADOR',
        'ARROYO O CANAL',
        'AVENIDA',
        'BOULEVARD',
        'BRECHA',
        'CALLE',
        'CALLEJÓN',
        'CALZADA',
        'CAMINO',
        'CAMINO REAL',
        'CARRETERA',
        'CERRADA',
        'CIRCUITO',
        'CIRCUNVALACIÓN',
        'CONTINUACIÓN',
        'CORREDOR',
        'CUARTEL',
        'DIAGONAL',
        'EJE VIAL',
        'PASAJE',
        'PEATONAL',
        'PERIFÉRICO',
        'PLAZA',
        'PORTAL',
        'PRIVADA',
        'PROLONGACIÓN',
        'RETORNO',
        'SERVIDUMBRE DE PASO',
        'VIADUCTO'
    ];

    const TIPO_ASENTAMIENTO = [
        'AEROPUERTO',
        'AMPLIACIÓN',
        'BARRIO',
        'CANTON',
        'CIUDAD',
        'CIUDAD INSDUSTRIAL',
        'COLONIA',
        'CONDOMINIO',
        'CONJUNTO HABITACIONAL',
        'CORREDOR INDUSTRIAL',
        'COTO',
        'CUARTEL',
        'EJIDO',
        'EX-EJIDO',
        'EXHACIENDA',
        'FRACCIÓN',
        'FRACCIONAMIENTO',
        'GRANJA',
        'HACIENDA',
        'INGENIO',
        'MANZANA',
        'PARAJE',
        'PARQUE INDUSTRIAL',
        'POBLADO',
        'PRIVADA',
        'PROLONGACIÓN',
        'PUEBLO',
        'PUERTO',
        'RANCHERIA',
        'RANCHO',
        'REGIÓN',
        'RESIDENCIAL',
        'RINCONADA',
        'SECCIÓN',
        'SECTOR',
        'SUPERMANZANA',
        'TENENCIA',
        'UNIDAD',
        'UNIDAD HABITACIONAL',
        'VILLA',
        'ZONA FEDERAL',
        'ZONA INDUSTRIAL',
        'ZONA MILITAR',
        'ZONA NAVAL',
    ];

    const VIENTOS = [
        'ANEXO',
        'ESTE',
        'NORESTE',
        'NOROESTE',
        'NORORIENTE',
        'NORPONIENTE',
        'NORTE',
        'OESTE',
        'ORIENTE',
        'PONIENTE',
        'SUR',
        'SURESTE',
        'SUROESTE',
        'SURORIENTE',
        'SURPONIENTE',
    ];

    const ESTADOS = [
        'Aguascalientes',
        'Baja California',
        'Baja California Sur',
        'Campeche',
        'Chiapas',
        'Chihuahua',
        'Ciudad de México',
        'Coahuila',
        'Colima',
        'Durango',
        'Estado de México',
        'Guanajuato',
        'Guerrero',
        'Hidalgo',
        'Jalisco',
        'Michoacán',
        'Morelos',
        'Nayarit',
        'Nuevo León',
        'Oaxaca',
        'Puebla',
        'Querétaro',
        'Quintana Roo',
        'San Luis Potosí',
        'Sinaloa',
        'Sonora',
        'Tabasco',
        'Tamaulipas',
        'Tlaxcala',
        'Veracruz',
        'Yucatán',
        'Zacatecas',
    ];

    const UNIDADES = [
        'Metros cuadrados',
        'Hectareas'
    ];

    const DIVISAS = [
        'MXN',
        'USD',
        'EUR'
    ];

    const TIPO_DEUDOR = [
        'I-DEUDOR ÚNICO',
        'D-GARANTE(S) HIPOTECARIO(S)',
        'P-PARTE ALICUOTA',
        'G-GARANTES EN COOPROPIEDAD',
        'F-FIANZA'
    ];

    const AÑOS = [
        '2023' => '2023',
        '2024' => '2024'
    ];

}
