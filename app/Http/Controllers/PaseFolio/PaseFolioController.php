<?php

namespace App\Http\Controllers\PaseFolio;

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\FolioReal;
use Illuminate\Support\Str;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FirmaElectronica;
use PhpCfdi\Credentials\Credential;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;
use App\Traits\Inscripciones\FirmaElectronicaTrait;
use Imagick;

class PaseFolioController extends Controller
{

    use FirmaElectronicaTrait;

    public function caratula(FolioReal $folioReal){

        $movimiento1 = $folioReal->movimientosRegistrales->where('folio', 1)->first();

        $numero_control = $movimiento1->año . '-' . $movimiento1->tramite . '-'.  $movimiento1->usuario;

        $formatter = new NumeroALetras();

        $director = User::where('status', 'activo')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Director');
                            })
                            ->first();

        $datos_control = (object)[];

        $datos_control->numero_control = $numero_control;
        $datos_control->registrador = auth()->user()->name;
        $datos_control->fecha_asignacion = Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y');

        $object = (object)[];

        $object->folioReal = $this->folioReal($folioReal);
        $object->distrito = Constantes::DISTRITOS[$folioReal->distrito_antecedente];
        $object->registro_letras = $formatter->toWords($folioReal->registro_antecedente);
        $object->tomo_letras = $formatter->toWords($folioReal->tomo_antecedente);
        $object->director = $director->name;
        $object->predio = $this->predio($folioReal->predio);
        $object->datos_control = $datos_control;

        $fielDirector = Credential::openFiles(Storage::disk('efirmas')->path($director->efirma->cer),
                                                Storage::disk('efirmas')->path($director->efirma->key),
                                                $director->efirma->contraseña
                                            );

        $firmaDirector = $fielDirector->sign(json_encode($object));

        FirmaElectronica::where('folio_real', $folioReal->id)->first()?->delete();

        $firmaElectronica = FirmaElectronica::create([
            'folio_real' => $folioReal->id,
            'cadena_original' => json_encode($object),
            'cadena_encriptada' => base64_encode($firmaDirector),
            'estado' => 'activo'
        ]);

        $director = $director->name;

        $firma_electronica = false;

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('pasefolio.caratula', [
            'folioReal' => $object->folioReal,
            'distrito' => $object->distrito,
            'registro_letras' => $object->registro_letras,
            'tomo_letras' => $object->tomo_letras,
            'director' => $object->director,
            'predio' => $object->predio,
            'datos_control' => $object->datos_control,
            'firma_electronica' => $firma_electronica,
            'qr' => $qr
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, "Folio real: " . $folioReal->folio , null, 10, array(1, 1, 1));


        $objeto = json_decode($firmaElectronica->cadena_original);

        $pdfFirmado = Pdf::loadView('pasefolio.caratula', [
            'folioReal' => $objeto->folioReal,
            'distrito' => $objeto->distrito,
            'registro_letras' => $objeto->registro_letras,
            'tomo_letras' => $objeto->tomo_letras,
            'director' => $objeto->director,
            'predio' => $objeto->predio,
            'datos_control' => $objeto->datos_control,
            'firma_electronica' => base64_encode($firmaDirector),
            'qr'=> $qr
        ]);

        $this->pdfFirmado($pdfFirmado, $folioReal->id, $folioReal->folio);

        return $pdf->stream('documento.pdf');

    }

    public function pdfFirmado($pdf, $id, $folio){

        $this->resetCaratula($id);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, "Folio real: " . $folio , null, 10, array(1, 1, 1));

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $w = $canvas->get_width();
            $h = $canvas->get_height();

            $canvas->image(public_path('storage/img/watermark.png'), 0, 0, $w, $h, $resolution = "normal");

        });

        $nombre = Str::random(40);

        $nombreFinal = $nombre . 'pdf';

        Storage::disk('caratulas')->put($nombre .'pdf', $pdf->output());

        $pdfImagen = new \Spatie\PdfToImage\Pdf('caratulas/' . $nombre . 'pdf');

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
            'fileable_id' => $id,
            'fileable_type' => 'App\Models\FolioReal',
            'descripcion' => 'caratula',
            'url' => $nombre
        ]);

        unlink('caratulas/' . $nombreFinal);

    }

    public function resetCaratula($id){

        $folioReal = FolioReal::with('archivos')->find($id);

        foreach($folioReal->archivos as $archivo){

            if($archivo->descripcion == 'caratula'){

                Storage::disk('caratulas')->delete($archivo->url);

            }

        }

        $folioReal->archivos()->delete();

    }

}
