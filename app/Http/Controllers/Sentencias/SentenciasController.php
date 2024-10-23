<?php

namespace App\Http\Controllers\Sentencias;

use Imagick;
use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\Sentencia;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FirmaElectronica;
use App\Traits\NombreServicioTrait;
use PhpCfdi\Credentials\Credential;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Traits\Inscripciones\FirmaElectronicaTrait;

class SentenciasController extends Controller
{

    use NombreServicioTrait;
    use FirmaElectronicaTrait;

    public function caratula(Sentencia $sentencia)
    {

        $this->resetCaratula($sentencia->movimientoRegistral->id);

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first();

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento inscripciones');
        })->first()->name;

        $datos_control = (object)[];

        $datos_control->numero_control = $sentencia->movimientoRegistral->año . '-' . $sentencia->movimientoRegistral->tramite . '-' . $sentencia->movimientoRegistral->usuario;
        $datos_control->registrado_por = auth()->user()->name;
        $datos_control->fecha_asignacion = Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y');
        $datos_control->elaborado_en = Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y');
        $datos_control->jefe_departamento = $jefe_departamento;
        $datos_control->movimiento_folio = $sentencia->movimientoRegistral->folio;
        $datos_control->servicio = $this->nombreServicio($sentencia->servicio);
        $datos_control->solicitante = $sentencia->movimientoRegistral->solicitante;
        $datos_control->monto = $sentencia->movimientoRegistral->monto;
        $datos_control->tipo_servicio = $sentencia->movimientoRegistral->tipo_servicio;
        $datos_control->asigno_folio = $sentencia->movimientoRegistral->folioReal->asignado_por;

        $folioReal = (object)[];

        $folioReal->folio = $sentencia->movimientoRegistral->folioReal->folio;
        $folioReal->distrito = $sentencia->movimientoRegistral->folioReal->distrito;

        $object = (object)[];

        $object->folioReal = $folioReal;
        $object->director = $director->name;
        $object->predio = $this->predio($sentencia->movimientoRegistral->folioReal->predio);
        $object->datos_control = $datos_control;
        $object->sentencia = $this->sentencia($sentencia);

        $fielDirector = Credential::openFiles(Storage::disk('efirmas')->path($director->efirma->cer),
                                                Storage::disk('efirmas')->path($director->efirma->key),
                                                $director->efirma->contraseña
                                            );

        $firmaDirector = $fielDirector->sign(json_encode($object));

        $firmaElectronica = FirmaElectronica::create([
                                                    'movimiento_registral_id' => $sentencia->movimientoRegistral->id,
                                                    'cadena_original' => json_encode($object),
                                                    'cadena_encriptada' => base64_encode($firmaDirector),
                                                    'estado' => 'activo'
                                                    ]);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('sentencias.acto', [
            'folioReal' => $object->folioReal,
            'sentencia' => $object->sentencia,
            'director' => $object->director,
            'predio' => $object->predio,
            'firma_electronica' => base64_encode($firmaDirector),
            'datos_control' => $object->datos_control,
            'qr'=> $qr
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, $sentencia->movimientoRegistral->folioReal->folio  .'-' . $sentencia->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $w = $canvas->get_width();
            $h = $canvas->get_height();

            $canvas->image(public_path('storage/img/watermark.png'), 0, 0, $w, $h, $resolution = "normal");

        });

        $nombre = Str::random(40);

        $nombreFinal = $nombre . '.pdf';

        Storage::disk('caratulas')->put($nombre . '.pdf', $pdf->output());

        $pdfImagen = new \Spatie\PdfToImage\Pdf('caratulas/' . $nombre . '.pdf');

        $all = new Imagick();

        for ($i=1; $i <= $pdfImagen->pageCount(); $i++) {

            $nombre = $nombre . '_' . $i . '.jpg';

            $pdfImagen->selectPage($i)->save('caratulas/'. $nombre);

            $im = new Imagick(Storage::disk('caratulas')->path($nombre));

            $all->addImage($im);

            unlink('caratulas/' . $nombre);

        }

        $all->resetIterator();
        $combined = $all->appendImages(true);
        $combined->setImageFormat("jpg");

        file_put_contents("caratulas/" . $nombre, $combined);

        File::create([
            'fileable_id' => $sentencia->movimientoRegistral->id,
            'fileable_type' => 'App\Models\MovimientoRegistral',
            'descripcion' => 'caratula',
            'url' => $nombre
        ]);

        unlink('caratulas/' . $nombreFinal);

    }

    public function reimprimir(FirmaElectronica $firmaElectronica){

        $objeto = json_decode($firmaElectronica->cadena_original);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('sentencias.acto', [
            'folioReal' => $objeto->folioReal,
            'sentencia' => $objeto->sentencia,
            'director' => $objeto->director,
            'predio' => $objeto->predio,
            'firma_electronica' => false,
            'datos_control' => $objeto->datos_control,
            'qr'=> $qr
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1,1,1));

        $canvas->page_text(35, 745, $firmaElectronica->movimientoRegistral->folioReal->folio . '-' .$firmaElectronica->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        return $pdf;

    }


}
