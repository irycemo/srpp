<?php

namespace App\Http\Controllers\Cancelaciones;

use App\Models\User;
use App\Models\Cancelacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MovimientoRegistral;
use App\Http\Controllers\Controller;

class CancelacionController extends Controller
{

    public function acto(Cancelacion $cancelacion)
    {

        /* $this->authorize('view', $cancelacion->movimientoRegistral); */

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        $movimientoGravamen = MovimientoRegistral::where('movimiento_padre', $cancelacion->movimientoRegistral->id)->first();

        $pdf = Pdf::loadView('cancelaciones.acto', [
            'cancelacion' => $cancelacion,
            'director' => $director,
            'jefe_departamento' => $jefe_departamento,
            'distrito' => $cancelacion->movimientoRegistral->getRawOriginal('distrito'),
            'predio' => $cancelacion->movimientoRegistral->folioReal->predio,
            'movimientoGravamen' => $movimientoGravamen
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 794, $cancelacion->movimientoRegistral->folioReal->folio  .'-' . $cancelacion->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

}
