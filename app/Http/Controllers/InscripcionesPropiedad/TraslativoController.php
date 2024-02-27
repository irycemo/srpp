<?php

namespace App\Http\Controllers\InscripcionesPropiedad;

use App\Models\User;
use App\Models\Propiedad;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class TraslativoController extends Controller
{

    public function boleta_presentacion(Propiedad $propiedad)
    {

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        $pdf = Pdf::loadView('incripciones.propiedad.transmitivo', [
            'inscripcion' => $propiedad,
            'director' => $director,
            'jefe_departamento' => $jefe_departamento,
            'distrito' => $propiedad->movimientoRegistral->getRawOriginal('distrito'),
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

    public function acto(Propiedad $propiedad)
    {

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        $pdf = Pdf::loadView('incripciones.propiedad.acto', [
            'inscripcion' => $propiedad,
            'director' => $director,
            'jefe_departamento' => $jefe_departamento,
            'distrito' => $propiedad->movimientoRegistral->getRawOriginal('distrito'),
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

}
