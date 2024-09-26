<?php

namespace App\Http\Controllers\Varios;

use App\Models\User;
use App\Models\Vario;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Traits\NombreServicioTrait;

class VariosController extends Controller
{

    use NombreServicioTrait;

    public function acto(Vario $vario)
    {

        /* $this->authorize('update', $vario->movimientoRegistral); */

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        $servicio = $this->nombreServicio($vario->servicio);

        if($vario->acto_contenido == 'PERSONAS MORALES'){

            $vario->load('movimientoRegistral.folioRealPersona.actores.persona');

            $pdf = Pdf::loadView('varios.acto', [
                'vario' => $vario,
                'director' => $director,
                'jefe_departamento' => $jefe_departamento,
                'distrito' => $vario->movimientoRegistral->getRawOriginal('distrito'),
                'servicio' => $servicio
            ]);

        }else{

            $pdf = Pdf::loadView('varios.acto', [
                'vario' => $vario,
                'director' => $director,
                'jefe_departamento' => $jefe_departamento,
                'distrito' => $vario->movimientoRegistral->getRawOriginal('distrito'),
                'predio' => $vario->movimientoRegistral->folioReal->predio,
                'servicio' => $servicio
            ]);

        }

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        if($vario->acto_contenido == 'PERSONAS MORALES'){

            $canvas->page_text(35, 745, $vario->movimientoRegistral->folioRealPersona->folio  .'-' . $vario->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        }else{

            $canvas->page_text(35, 745, $vario->movimientoRegistral->folioReal->folio  .'-' . $vario->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        }

        return $pdf->stream('documento.pdf');

    }

}
