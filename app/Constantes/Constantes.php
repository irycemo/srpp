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
        'Consultas',
        'Propiedades',
        'Folios reales',
        'Movimientos Registrales',
        'Personas morales'
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

    const ESTADO_CIVIL = [
        'CASADO',
        'SOLTERO',
        'VIUDO',
        'DIVORCIADO'
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

    const ACTOS_INSCRIPCION_PROPIEDAD = [
        'COMPRAVENTA',
        'DONACIÓN',
        'JUICIO SUCESORIO TESTAMENTARIO',
        'JUICIO SUCESORIO INTESTAMENTARIO',
        'ADJUDICACION JUDICIAL POR REMATE',
        'DACIÓN EN PAGO',
        'VARIACIÓN CATASTRAL',
        'PRESCRIPCIÓN POSITIVA',
        'SOCIEDADES',
        'PERMUTA',
        'FIDEICOMISO',
        'EXPEDICIÓN DE TÍTULO DE PROPIEDAD'
    ];

    const ACTOS_INSCRIPCION_GRAVAMEN = [
        'CREDITO HIPOTECARIO',
        'HIPOTECA',
        'FIANZA JUDICIAL',
        'FIANZA ADMINISTRATIVA',
        'FIANZA COMERCIAL',
        'EMBARGO',
        'RESERVA DE DOMINIO',
        'CONVENIO',
        'DIVISIÓN DE HIPOTECA',
        'ADHESIÓN DE HIPOTECA',
        'POR ANTECEDENTE'
    ];

    const ACTOS_INSCRIPCION_VARIOS = [
        /* 'PRIMER AVISO PREVENTIVO', */
        'SEGUNDO AVISO PREVENTIVO',
        'CAPITULACIÓN',
        'CAPITULACIÓN MATRIMONIAL',
        'CESIÓN DE DERECHOS DE HERENCIA',
        'CESIÓN DE PODER',
        'CONSTITUCIÓN DE SOCIEDAD CONYUGAL',
        'NOMBRAMIENTO',
        'PERSONAS MORALES',
        /* 'CONSOLIDACIÓN DEL USUFRUCTO',
        'ACLARACIÓN ADMINISTRATIVA' */
    ];

    const ACTOS_INSCRIPCION_SENTENCIAS = [
        'SENTENCIA RECTIFICACTORIA',
        'CANCELACIÓN DE INSCRIPCIÓN',
        'RESOLUCIÓN',
        'ADJUDICACIÓN JUDICIAL',
        'DEMANDA',
        'INMATRICULACIÓN JUDICIAL',
        'PRESCRIPCIÓN ADQUISITIVA DE DOMINIO'
    ];

    const ACTOS_INSCRIPCION_CANCELACIONES = [
        'CANCELACIÓN TOTAL DE GRAVAMEN',
        'CANCELACIÓN PARCIAL DE GRAVAMEN',
    ];

    const DOCUMENTOS_DE_ENTRADA = [
        'ESCRITURA PÚBLICA',
        'ESCRITURA PRIVADA',
        'OFICIO',
        'TÍTULO DE PROPIEDAD',
    ];

    const RECHAZO_MOTIVOS = [
        'Presenta Gravamen (HIPOTECA).  Art. 1107, 1198, 1199 Frac. I, Código Civil del Estado de Michoacán; en relación con el diverso 27, de La 	Ley de la Función Registral y Catastral del Estado de Michoacán.',
        'No Presenta Traslado de Dominio Autorizado.  Art. 86 de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Aparece como titular una persona distinta de la que figure en la inscripción precedente. Art. 6, fracción IV, 38 Frac. I. del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'No se está mencionando la naturaleza o denominación del acto o contrato, Art. 38, fracción II del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Se omitió la ubicación, linderos o colindancias de los inmuebles objeto de la operación, Art. 38, fracción III del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Anotaciones de Gravamen o Resoluciones Judiciales. Falta de la extensión, condiciones y cargas del derecho que se constituya, transmite, modifica o extinga, Art. 38, fracción IV del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Se omite el valor de los bienes o derechos, Art. 38, fracción V del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Falta de Inserción de Poderes y/o Autorizaciones Diversas. El acto de que se trata no contiene las condiciones esenciales propias de su naturaleza jurídica, Art. 38, fracción VI del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Falta de nombre, nacionalidad, edad, estado civil, domicilio, profesión u ocupación de las personas que celebran el acto, Art. 38, fracción VII del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Es incompatible el texto del instrumento y el contenido en los archivos y en el acervo del Registro, Art. 38, fracción VIII del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Falta de tracto sucesivo, Art. 38, fracción IX del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Falta una inscripción pendiente del acto que le da tracto a la operación o testimonio, Art. 38, fracción X del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'La omisión de fecha de celebración del acto o contrato, Art. Art. 38 fracción XI del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'La inscripción proviene de un tribunal o autoridad judicial, administrativa o fiscal que no sea competente en el Distrito Judicial de la Oficina registradora a la que se le ordene, Art 38, fracción XIII del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'No se encuentran los documentos redactados en español o traducidos al idioma por persona competente según el Código Civil, Art. 38, fracción XIV del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo y artículo 57 párrafo primero de la Ley del Notariado del Estado de Michoacán.',
        'Faltan sellos, rúbricas y cotejos que son parte de la formalidad del documento, Art. 38, fracción XV del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Es un acto o documento no inscribible, Art. 38, fracción XVI del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Faltan antecedentes registrales, Art. 38, fracción XVIII del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Existe discrepancia del titular de la unidad registral, Art. 38, fracción XIX del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Falta de tracto sucesivo o inexistencia del derecho que se pretende modificar o extinguir, Art. 38, fracción XXI del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'No se están presentando los documentos auténticos en que estén consignados los derechos, actos, contratos, diligencias y resoluciones señalados en los ordenamientos legales, Art. 45, párrafo primero, fracción I del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'No contiene la orden expresa de la autoridad correspondiente, Art. 45, fracción II del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Falta Pago de Derechos Fiscales del Testimonio presentado para su registro que se solicita, Art. 45, párrafo primero, fracción III del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'No presenta copia certificada del plano o croquis en que se funda el documento, Art. 45, párrafo primero, fracción IV del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'No menciona el antecedente registral, Art. 45, párrafo primero, fracción V del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'El documento privado que pretende inscribirse no se trata de documento, contrato o diligencia relevante para la manifestación sobre un derecho de posesión o propiedad, Art. 46, fracción I del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'El documento privado que pretende inscribirse no contiene la constancia de que un Notario Público o autoridad judicial competente, se cercioraron de la autenticidad de las firmas y voluntad de las partes, Art. 46, fracción II del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'El documento privado que pretende inscribirse no contiene el sello de la Notaría ni firma legible del Notario Público que ratificó dicho documento Privado, Art. 46, fracción III del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'La firma en el documento privado no son legibles, Art. 46, fracción IV del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'El documento privado que se presente inscribir se celebró por medio de representante legal pero no exhibió el documento en original o copia certificada en que consta dicha representación legal, Art. 46, fracción V del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'El documento que pretende inscribirse no contiene la fecha y hora de presentación, Art. 42 del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Falta de pago, Art. 43 y 44 del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'La aclaración administrativa no es solicitada por la persona que tiene interés legítimo, fedatario público, autoridad que hubiere intervenido en el acto jurídico o convenio registrado o por orden judicial, Art. 70 del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Para la cancelación del gravamen es necesario presentar el Testimonio Publico, si el gravamen se otorgó en Instrumento Público; el otorgado en Instrumento Privado, en Instrumento privado, ratificado ante notario público de los comparecientes, Art. 64, fracción I del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'No presenta oficio y/o auto que ordena la cancelación del gravamen, Art. 64, fracción II del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Para la cancelación de la reserva de dominio es necesario mencionar los antecedentes registrales, tomo y registro de propiedad y anexar oficio de cancelación con firmas y sellos auténticos anexando la escritura original, y previamente autorizado por la Oficina Castral y/o Receptoría de Rentas que corresponda.  Art. 7 Fracción VII, y 64, fracción IV del Reglamento de la Ley de la Función Registral y Catastral del Estado de Michoacán de Ocampo.',
        'Falta de la Inscripción de la División de Hipoteca. De conformidad a lo establecido en los artículos 2045 y 2046, del Código Civil del Estado de Michoacán.',
        'El Acto Registral debe Constar en Instrumento Público. (documentos con cuantía superior a 50 cincuenta mil pesos. De conformidad a lo establecido en el artículo 2050, del Código Civil del Estado de Michoacán.',
        'Congruencia de en la Identificación inmueble (HIPOTECA). No hay identidad entre el inmueble descrito en el acto contractual y/o ordenamiento Jurisdiccional, con el descrito en el antecedente registral. De conformidad a lo establecido en el artículo 2030, del Código Civil del Estado de Michoacán.',
        'Usufructo Vitalicio. Previo a la Inscripción del acto traslativo de dominio; Consolidar el Usufructo Vitalicio. De conformidad a lo establecido en el artículo 2035, del Código Civil del Estado de Michoacán.',
        'De la copropiedad. No comparece copropietario (s) al acto contractual (HIPOTECA). De conformidad a lo establecido en el artículo 2035, del Código Civil del Estado de Michoacán.',
        'El Inmueble materia del Acto Contractual, constituye una Fracción del Fracción. Previo a su inscripción, presentar para su registro Autorización para subdividir, lotificar y/o fraccionar o subdividir. De conformidad a lo establecido en  artículo 13 fracción XVII,  48, 104, 146, 339 del Código de Desarrollo Urbano del Estado de Michoacán.',
        'Previo a la Inscripción de la lotificación, Fusión, Lotificación y/Fraccionamiento. Es necesario Inscribir previamente, los actos contractuales de los inmuebles destinados a vías públicas y destinados al H. Ayuntamiento y/o Gobierno del Estado. Artículo 159, 331 del Código de Desarrollo Urbano del Estado de Michoacán.'
    ];

}
