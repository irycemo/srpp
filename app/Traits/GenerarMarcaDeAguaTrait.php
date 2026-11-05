<?php

namespace App\Traits;

use Illuminate\Support\Str;
use App\Models\File;
use Imagick;
use Illuminate\Support\Facades\Storage;

trait GenerarMarcaDeAguaTrait{

    public function crearImagenConMarcaDeAgua($movimiento_registral, $pdf){

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, 'I-' . $movimiento_registral->folioReal->folio . '-' .$movimiento_registral->folio, null, 9, array(1, 1, 1));

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $w = $canvas->get_width();
            $h = $canvas->get_height();

            $canvas->image(public_path('storage/img/watermark.png'), 0, 0, $w, $h, $resolution = "normal");

        });

        $nombre = Str::random(40);

        $nombreFinal = $nombre . '.pdf';

        Storage::disk('caratulas')->put($nombreFinal, $pdf->output());

        $pdfImagen = new \Spatie\PdfToImage\Pdf('/var/www/html/srpp/storage/app/caratulas/' . $nombreFinal);

        $all = new Imagick();

        for ($i=1; $i <= $pdfImagen->pageCount(); $i++) {

            $nombre_img = $nombre . '_' . $i . '.jpg';

            $pdfImagen->selectPage($i)->save('/var/www/html/srpp/storage/app/caratulas/'. $nombre_img);

            $im = new Imagick(Storage::disk('caratula')->path($nombre_img));

            $all->addImage($im);

            unlink('/var/www/html/srpp/storage/app/caratulas/' . $nombre_img);

        }

        $all->resetIterator();
        $combined = $all->appendImages(true);
        $combined->setImageFormat("jpg");

        if(app()->isProduction()){

            Storage::disk('s3')->put(config('services.ses.ruta_caratulas') . $nombre . '.jpg', $combined);

        }else{

            file_put_contents("/var/www/html/srpp/storage/app/caratulas/" . $nombre . '.jpg', $combined);

        }

        File::create([
            'fileable_id' => $movimiento_registral->id,
            'fileable_type' => 'App\Models\MovimientoRegistral',
            'descripcion' => 'caratula',
            'url' => $nombre . '.jpg'
        ]);

        unlink('/var/www/html/srpp/storage/app/caratulas/' . $nombreFinal);

    }

}
