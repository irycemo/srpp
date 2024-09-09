<?php

namespace App\Http\Controllers\Gravamen;

use App\Models\User;
use App\Models\Gravamen;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\MovimientoRegistral;
use App\Traits\NombreServicioTrait;

class GravamenController extends Controller
{

    use NombreServicioTrait;

    public function acto(Gravamen $gravamen)
    {

        /* $this->authorize('update', $gravamen->movimientoRegistral); */

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        $movimientos = null;

        if($gravamen->acto_contenido === 'DIVISIÓN DE HIPOTECA'){

            $movimientos = MovimientoRegistral::with('folioReal.predio.colindancias', 'gravamen')
                                                ->where('movimiento_padre', $gravamen->movimientoRegistral->id)
                                                ->get();

        }

        $servicio = $this->nombreServicio($gravamen->servicio);

        $pdf = Pdf::loadView('gravamenes.acto', [
            'gravamen' => $gravamen,
            'director' => $director,
            'jefe_departamento' => $jefe_departamento,
            'distrito' => $gravamen->movimientoRegistral->getRawOriginal('distrito'),
            'predio' => $gravamen->movimientoRegistral->folioReal->predio,
            'movimientos' => $movimientos,
            'servicio' => $servicio
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, $gravamen->movimientoRegistral->folioReal->folio  .'-' . $gravamen->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

}
