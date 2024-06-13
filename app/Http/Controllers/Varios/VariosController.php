<?php

namespace App\Http\Controllers\Varios;

use App\Models\User;
use App\Models\Vario;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class VariosController extends Controller
{

    public function acto(Vario $vario)
    {

        $this->authorize('update', $vario->movimientoRegistral);

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        if($vario->acto_contenido == 'PERSONAS MORALES'){

            $vario->load('movimientoRegistral.folioRealPersona.actores.persona');

        }

        $pdf = Pdf::loadView('varios.acto', [
            'vario' => $vario,
            'director' => $director,
            'jefe_departamento' => $jefe_departamento,
            'distrito' => $vario->movimientoRegistral->getRawOriginal('distrito'),
            'predio' => $vario->movimientoRegistral->folioReal->predio
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

}
