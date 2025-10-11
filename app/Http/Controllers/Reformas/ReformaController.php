<?php

namespace App\Http\Controllers\Reformas;

use Imagick;
use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\ReformaMoral;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FirmaElectronica;
use App\Traits\NombreServicioTrait;
use PhpCfdi\Credentials\Credential;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Traits\Inscripciones\FirmaElectronicaTrait;
use App\Traits\Inscripciones\RevisarUsuarioRegionalTrait;

class ReformaController extends Controller
{

    use NombreServicioTrait;
    use FirmaElectronicaTrait;
    use RevisarUsuarioRegionalTrait;

    public function caratula(ReformaMoral $reforma)
    {

        $this->resetCaratula($reforma->movimientoRegistral->id);

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first();

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento inscripciones');
        })->first()->name;

        $datos_control = (object)[];

        $datos_control->numero_control = $reforma->movimientoRegistral->año . '-' . $reforma->movimientoRegistral->tramite . '-' . $reforma->movimientoRegistral->usuario;
        $datos_control->registrado_por = auth()->user()->name;
        $datos_control->elaborado_en = Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y');
        $datos_control->jefe_departamento = $jefe_departamento;
        $datos_control->folioReal = $reforma->movimientoRegistral->folioRealPersona->folio;
        $datos_control->distrito = $reforma->movimientoRegistral->folioRealPersona->distrito;
        $datos_control->director = $director->name;
        $datos_control->movimiento_folio = $reforma->movimientoRegistral->folio;
        $datos_control->servicio = $this->nombreServicio($reforma->movimientoRegistral->año, $reforma->movimientoRegistral->tramite, $reforma->movimientoRegistral->usuario);
        $datos_control->solicitante = $reforma->movimientoRegistral->solicitante;
        $datos_control->monto = $reforma->movimientoRegistral->monto;
        $datos_control->tipo_servicio = $reforma->movimientoRegistral->tipo_servicio;
        $datos_control->asigno_folio = $reforma->movimientoRegistral->folioRealPersona->asignado_por;

        $regional = $this->revisarUsuarioRegional($reforma->movimientoRegistral->usuario);

        if($regional){

            $datos_control->nombre_regional = $regional->nombre;
            $datos_control->titular_regional = $regional->titular;
            $datos_control->ciudad_regional = $regional->ciudad;

        }

        $object = (object)[];

        $object->datos_control = $datos_control;
        $object->reforma = $this->reforma($reforma);

        $fielDirector = Credential::openFiles(Storage::disk('efirmas')->path($director->efirma->cer),
                                                Storage::disk('efirmas')->path($director->efirma->key),
                                                $director->efirma->contraseña
                                            );

        $firmaDirector = $fielDirector->sign(json_encode($object));

        $firmaElectronica = FirmaElectronica::create([
                                                    'movimiento_registral_id' => $reforma->movimientoRegistral->id,
                                                    'cadena_original' => json_encode($object),
                                                    'cadena_encriptada' => base64_encode($firmaDirector),
                                                    'estado' => 'activo'
                                                    ]);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('reformas.acto', [
            'reforma' => $object->reforma,
            'folioReal' => $object->reforma->folioReal,
            'firma_electronica' => base64_encode($firmaDirector),
            'datos_control' => $object->datos_control,
            'qr'=> $qr
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, 'PM-' . $reforma->movimientoRegistral->folioRealPersona->folio  .'-' . $reforma->movimientoRegistral->folio, null, 9, array(1, 1, 1));

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

            $nombre_img = $nombre . '_' . $i . '.jpg';

            $pdfImagen->selectPage($i)->save('caratulas/'. $nombre_img);

            $im = new Imagick(Storage::disk('caratulas')->path($nombre_img));

            $all->addImage($im);

            unlink('caratulas/' . $nombre_img);

        }

        $all->resetIterator();
        $combined = $all->appendImages(true);
        $combined->setImageFormat("jpg");

        if(app()->isProduction()){

            Storage::disk('s3')->put(config('services.ses.ruta_caratulas') . $nombre . '.jpg', $combined);

        }else{

            file_put_contents("caratulas/" . $nombre . '.jpg', $combined);

        }

        File::create([
            'fileable_id' => $reforma->movimientoRegistral->id,
            'fileable_type' => 'App\Models\MovimientoRegistral',
            'descripcion' => 'caratula',
            'url' => $nombre . '.jpg'
        ]);

        unlink('caratulas/' . $nombreFinal);

    }

    public function reimprimir(FirmaElectronica $firmaElectronica){

        $objeto = json_decode($firmaElectronica->cadena_original);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('reformas.acto', [
            'reforma' => $objeto->reforma,
            'folioReal' => $objeto->reforma->folioReal,
            'firma_electronica' => false,
            'datos_control' => $objeto->datos_control,
            'qr'=> $qr
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1,1,1));

        $canvas->page_text(35, 745, 'PM-' . $firmaElectronica->movimientoRegistral->folioRealPersona->folio . '-' .$firmaElectronica->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        return $pdf;

    }

    public function reimprimirFirmado(FirmaElectronica $firmaElectronica){

        $objeto = json_decode($firmaElectronica->cadena_original);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('reformas.acto', [
            'reforma' => $objeto->reforma,
            'folioReal' => $objeto->reforma->folioReal,
            'firma_electronica' => base64_encode($firmaElectronica->cadena_encriptada),
            'datos_control' => $objeto->datos_control,
            'qr'=> $qr
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1,1,1));

        $canvas->page_text(35, 745, 'PM-' . $firmaElectronica->movimientoRegistral->folioRealPersona->folio . '-' .$firmaElectronica->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        return $pdf;

    }

}
