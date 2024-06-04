<?php

namespace App\Http\Controllers\Gravamen;

use App\Models\User;
use App\Models\Gravamen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class GravamenController extends Controller
{

    public function acto(Gravamen $gravamen)
    {

        $this->authorize('update', $gravamen->movimientoRegistral);

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        $pdf = Pdf::loadView('gravamenes.acto', [
            'gravamen' => $gravamen,
            'director' => $director,
            'jefe_departamento' => $jefe_departamento,
            'distrito' => $gravamen->movimientoRegistral->getRawOriginal('distrito'),
            'predio' => $gravamen->movimientoRegistral->folioReal->predio
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

}
