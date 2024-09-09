<?php

namespace App\Http\Controllers\Sentencias;

use App\Models\User;
use App\Models\Sentencia;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MovimientoRegistral;
use App\Http\Controllers\Controller;
use App\Traits\NombreServicioTrait;

class SentenciasController extends Controller
{

    use NombreServicioTrait;

    public function acto(Sentencia $sentencia)
    {

        /* $this->authorize('update', $sentencia->movimientoRegistral); */

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name;

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento')->where('area', 'Departamento de Registro de Inscripciones');
        })->first()->name;

        if($sentencia->acto_contenido == 'CANCELACIÓN DE INSCRIPCIÓN'){

            $movimientoCancelado = MovimientoRegistral::where('movimiento_padre', $sentencia->movimientoRegistral->id)->first();

        }else{

            $movimientoCancelado = null;

        }

        $servicio = $this->nombreServicio($sentencia->servicio);

        $pdf = Pdf::loadView('sentencias.acto', [
            'sentencia' => $sentencia,
            'director' => $director,
            'jefe_departamento' => $jefe_departamento,
            'distrito' => $sentencia->movimientoRegistral->getRawOriginal('distrito'),
            'predio' => $sentencia->movimientoRegistral->folioReal->predio,
            'movimientoCancelado' => $movimientoCancelado,
            'servicio' => $servicio
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, $sentencia->movimientoRegistral->folioReal->folio  .'-' . $sentencia->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

}
