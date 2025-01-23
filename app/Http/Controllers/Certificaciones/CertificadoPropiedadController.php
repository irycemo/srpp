<?php

namespace App\Http\Controllers\Certificaciones;

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FirmaElectronica;
use App\Models\MovimientoRegistral;
use App\Traits\NombreServicioTrait;
use PhpCfdi\Credentials\Credential;
use App\Http\Controllers\Controller;
use App\Traits\Inscripciones\FirmaElectronicaTrait;
use Illuminate\Support\Facades\Storage;
use Imagick;

class CertificadoPropiedadController extends Controller
{

    use NombreServicioTrait;
    use FirmaElectronicaTrait;

    public function certificadoNegativoPropiedad(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $director = User::where('status', 'activo', 'efirma')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Director');
                            })->first();

        $folioReal = (object)[];

        $folioReal->folio = $movimientoRegistral->folioReal->folio;
        $folioReal->seccion = $movimientoRegistral->folioReal->seccion_antecedente;
        $folioReal->distrito = $movimientoRegistral->folioReal->distrito;
        $folioReal->tomo = $movimientoRegistral->folioReal->tomo_antecedente;
        $folioReal->registro = $movimientoRegistral->folioReal->registro_antecedente;
        $folioReal->numero_propiedad = $movimientoRegistral->folioReal->numero_propiedad_antecedente;

        $datos_control = (object)[];

        $datos_control->numero_control = $movimientoRegistral->año . '-' . $movimientoRegistral->tramite . '-' . $movimientoRegistral->usuario;
        $datos_control->verificado_por = auth()->user()->name;
        $datos_control->elaborado_en = Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y');
        $datos_control->servicio = $this->nombreServicio($movimientoRegistral->año, $movimientoRegistral->tramite, $movimientoRegistral->usuario);
        $datos_control->solicitante = $movimientoRegistral->solicitante;
        $datos_control->monto = $movimientoRegistral->monto;
        $datos_control->tipo_servicio = $movimientoRegistral->tipo_servicio;
        $datos_control->movimiento_folio = $movimientoRegistral->folio;
        $datos_control->asigno_folio = $movimientoRegistral->folioReal->asignado_por;
        $datos_control->observaciones_certificado = $movimientoRegistral->certificacion->observaciones_certificado;

        $personas = collect();

        $movimientoRegistral->certificacion->load('personas.persona');

        if($movimientoRegistral->certificacion->personas->count()){

            foreach ($movimientoRegistral->certificacion->personas as $persona) {

                $item = (object)[];

                $item->nombre = $persona->persona->nombre;
                $item->ap_paterno = $persona->persona->ap_paterno;
                $item->ap_materno = $persona->persona->ap_materno;

                $personas->push($item);

            }

        }

        $object = (object)[];

        $object->predio = $this->predio($movimientoRegistral->folioReal->predio);
        $object->director = $director->name;
        $object->datos_control = $datos_control;
        $object->folioReal = $folioReal;
        $object->personas = $personas;

        $fielDirector = Credential::openFiles(
                                                Storage::disk('efirmas')->path($director->efirma->cer),
                                                Storage::disk('efirmas')->path($director->efirma->key),
                                                $director->efirma->contraseña
                                            );

        $firmaDirector = $fielDirector->sign(json_encode($object));

        FirmaElectronica::where('movimiento_registral_id', $movimientoRegistral->id)->first()?->delete();

        $firmaElectronica = FirmaElectronica::create([
            'movimiento_registral_id' => $movimientoRegistral->id,
            'cadena_original' => json_encode($object),
            'cadena_encriptada' => base64_encode($firmaDirector),
            'estado' => 'activo'
        ]);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('certificaciones.certificadoNegativoPropiedad', [
            'folioReal' => $object->folioReal,
            'predio' => $object->predio,
            'director' => $object->director,
            'personas' => $object->personas,
            'datos_control' => $object->datos_control,
            'firma_electronica' => false,
            'qr' => $qr,
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 745, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

        $objeto = json_decode($firmaElectronica->cadena_original);

        $pdfFirmado = Pdf::loadView('certificaciones.certificadoNegativoPropiedad', [
            'folioReal' => $objeto->folioReal,
            'predio' => $objeto->predio,
            'director' => $objeto->director,
            'personas' => $objeto->personas,
            'datos_control' => $objeto->datos_control,
            'firma_electronica' => base64_encode($firmaDirector),
            'qr' => $qr,
        ]);

        $this->pdfFirmado($pdfFirmado, $movimientoRegistral->id, $movimientoRegistral->folioReal->folio . '-' .$movimientoRegistral->folio);

        return $pdf->stream('documento.pdf');

    }

    public function certificadoPropiedad(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $director = User::where('status', 'activo', 'efirma')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Director');
                            })->first();

        $folioReal = (object)[];

        $folioReal->folio = $movimientoRegistral->folioReal->folio;
        $folioReal->seccion = $movimientoRegistral->folioReal->seccion_antecedente;
        $folioReal->distrito = $movimientoRegistral->folioReal->distrito;
        $folioReal->tomo = $movimientoRegistral->folioReal->tomo_antecedente;
        $folioReal->registro = $movimientoRegistral->folioReal->registro_antecedente;
        $folioReal->numero_propiedad = $movimientoRegistral->folioReal->numero_propiedad_antecedente;

        $datos_control = (object)[];

        $datos_control->numero_control = $movimientoRegistral->año . '-' . $movimientoRegistral->tramite . '-' . $movimientoRegistral->usuario;
        $datos_control->verificado_por = auth()->user()->name;
        $datos_control->elaborado_en = Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y');
        $datos_control->servicio = $this->nombreServicio($movimientoRegistral->año, $movimientoRegistral->tramite, $movimientoRegistral->usuario);
        $datos_control->solicitante = $movimientoRegistral->solicitante;
        $datos_control->monto = $movimientoRegistral->monto;
        $datos_control->tipo_servicio = $movimientoRegistral->tipo_servicio;
        $datos_control->movimiento_folio = $movimientoRegistral->folio;
        $datos_control->asigno_folio = $movimientoRegistral->folioReal->asignado_por;
        $datos_control->observaciones_certificado = $movimientoRegistral->certificacion->observaciones_certificado;

        $personas = collect();

        $movimientoRegistral->certificacion->load('personas.persona');

        if($movimientoRegistral->certificacion->personas->count()){

            foreach ($movimientoRegistral->certificacion->personas as $persona) {

                $item = (object)[];

                $item->nombre = $persona->persona->nombre;
                $item->ap_paterno = $persona->persona->ap_paterno;
                $item->ap_materno = $persona->persona->ap_materno;

                $personas->push($item);

            }

        }

        $object = (object)[];

        $object->predio = $this->predio($movimientoRegistral->folioReal->predio);
        $object->director = $director->name;
        $object->datos_control = $datos_control;
        $object->folioReal = $folioReal;
        $object->personas = $personas;

        $fielDirector = Credential::openFiles(
                                                Storage::disk('efirmas')->path($director->efirma->cer),
                                                Storage::disk('efirmas')->path($director->efirma->key),
                                                $director->efirma->contraseña
                                            );

        $firmaDirector = $fielDirector->sign(json_encode($object));

        FirmaElectronica::where('movimiento_registral_id', $movimientoRegistral->id)->first()?->delete();

        $firmaElectronica = FirmaElectronica::create([
            'movimiento_registral_id' => $movimientoRegistral->id,
            'cadena_original' => json_encode($object),
            'cadena_encriptada' => base64_encode($firmaDirector),
            'estado' => 'activo'
        ]);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('certificaciones.certificadoPropiedad', [
            'folioReal' => $object->folioReal,
            'predio' => $object->predio,
            'director' => $object->director,
            'personas' => $object->personas,
            'datos_control' => $object->datos_control,
            'firma_electronica' => false,
            'qr' => $qr,
        ]);


        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 745, 'I-' . $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

        $objeto = json_decode($firmaElectronica->cadena_original);

        $pdfFirmado = Pdf::loadView('certificaciones.certificadoPropiedad', [
            'folioReal' => $objeto->folioReal,
            'predio' => $objeto->predio,
            'director' => $objeto->director,
            'personas' => $objeto->personas,
            'datos_control' => $objeto->datos_control,
            'firma_electronica' => base64_encode($firmaDirector),
            'qr' => $qr,
        ]);

        $this->pdfFirmado($pdfFirmado, $movimientoRegistral->id, $movimientoRegistral->folioReal->folio . '-' .$movimientoRegistral->folio);

        return $pdf->stream('documento.pdf');

    }

    public function certificadoUnicoPropiedad(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $director = User::where('status', 'activo', 'efirma')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Director');
                            })->first();

        $folioReal = (object)[];

        $folioReal->folio = $movimientoRegistral->folioReal->folio;
        $folioReal->seccion = $movimientoRegistral->folioReal->seccion_antecedente;
        $folioReal->distrito = $movimientoRegistral->folioReal->distrito;
        $folioReal->tomo = $movimientoRegistral->folioReal->tomo_antecedente;
        $folioReal->registro = $movimientoRegistral->folioReal->registro_antecedente;
        $folioReal->numero_propiedad = $movimientoRegistral->folioReal->numero_propiedad_antecedente;

        $datos_control = (object)[];

        $datos_control->numero_control = $movimientoRegistral->año . '-' . $movimientoRegistral->tramite . '-' . $movimientoRegistral->usuario;
        $datos_control->verificado_por = auth()->user()->name;
        $datos_control->elaborado_en = Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y');
        $datos_control->servicio = $this->nombreServicio($movimientoRegistral->año, $movimientoRegistral->tramite, $movimientoRegistral->usuario);
        $datos_control->solicitante = $movimientoRegistral->solicitante;
        $datos_control->monto = $movimientoRegistral->monto;
        $datos_control->tipo_servicio = $movimientoRegistral->tipo_servicio;
        $datos_control->movimiento_folio = $movimientoRegistral->folio;
        $datos_control->asigno_folio = $movimientoRegistral->folioReal->asignado_por;
        $datos_control->observaciones_certificado = $movimientoRegistral->certificacion->observaciones_certificado;

        $personas = collect();

        $movimientoRegistral->certificacion->load('personas.persona');

        if($movimientoRegistral->certificacion->personas->count()){

            foreach ($movimientoRegistral->certificacion->personas as $persona) {

                $item = (object)[];

                $item->nombre = $persona->persona->nombre;
                $item->ap_paterno = $persona->persona->ap_paterno;
                $item->ap_materno = $persona->persona->ap_materno;

                $personas->push($item);

            }

        }

        $object = (object)[];

        $object->predio = $this->predio($movimientoRegistral->folioReal->predio);
        $object->director = $director->name;
        $object->datos_control = $datos_control;
        $object->folioReal = $folioReal;
        $object->personas = $personas;

        $fielDirector = Credential::openFiles(
                                                Storage::disk('efirmas')->path($director->efirma->cer),
                                                Storage::disk('efirmas')->path($director->efirma->key),
                                                $director->efirma->contraseña
                                            );

        $firmaDirector = $fielDirector->sign(json_encode($object));

        FirmaElectronica::where('movimiento_registral_id', $movimientoRegistral->id)->first()?->delete();

        $firmaElectronica = FirmaElectronica::create([
            'movimiento_registral_id' => $movimientoRegistral->id,
            'cadena_original' => json_encode($object),
            'cadena_encriptada' => base64_encode($firmaDirector),
            'estado' => 'activo'
        ]);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('certificaciones.certificadoUnicoPropiedad', [
            'folioReal' => $object->folioReal,
            'predio' => $object->predio,
            'director' => $object->director,
            'personas' => $object->personas,
            'datos_control' => $object->datos_control,
            'firma_electronica' => false,
            'qr' => $qr,
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 745, 'I-' . $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

        $objeto = json_decode($firmaElectronica->cadena_original);

        $pdfFirmado = Pdf::loadView('certificaciones.certificadoUnicoPropiedad', [
            'folioReal' => $objeto->folioReal,
            'predio' => $objeto->predio,
            'director' => $objeto->director,
            'personas' => $objeto->personas,
            'datos_control' => $objeto->datos_control,
            'firma_electronica' => base64_encode($firmaDirector),
            'qr' => $qr,
        ]);

        $this->pdfFirmado($pdfFirmado, $movimientoRegistral->id, $movimientoRegistral->folioReal->folio . '-' .$movimientoRegistral->folio);

        return $pdf->stream('documento.pdf');

    }

    public function certificadoPropiedadColindancias(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $director = User::where('status', 'activo', 'efirma')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Director');
                            })->first();

        $folioReal = (object)[];

        $folioReal->folio = $movimientoRegistral->folioReal->folio;
        $folioReal->seccion = $movimientoRegistral->folioReal->seccion_antecedente;
        $folioReal->distrito = $movimientoRegistral->folioReal->distrito;
        $folioReal->tomo = $movimientoRegistral->folioReal->tomo_antecedente;
        $folioReal->registro = $movimientoRegistral->folioReal->registro_antecedente;
        $folioReal->numero_propiedad = $movimientoRegistral->folioReal->numero_propiedad_antecedente;

        $datos_control = (object)[];

        $datos_control->numero_control = $movimientoRegistral->año . '-' . $movimientoRegistral->tramite . '-' . $movimientoRegistral->usuario;
        $datos_control->verificado_por = auth()->user()->name;
        $datos_control->elaborado_en = Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y');
        $datos_control->servicio = $this->nombreServicio($movimientoRegistral->año, $movimientoRegistral->tramite, $movimientoRegistral->usuario);
        $datos_control->solicitante = $movimientoRegistral->solicitante;
        $datos_control->monto = $movimientoRegistral->monto;
        $datos_control->tipo_servicio = $movimientoRegistral->tipo_servicio;
        $datos_control->movimiento_folio = $movimientoRegistral->folio;
        $datos_control->asigno_folio = $movimientoRegistral->folioReal->asignado_por;
        $datos_control->observaciones_certificado = $movimientoRegistral->certificacion->observaciones_certificado;

        $object = (object)[];

        $object->predio = $this->predio($movimientoRegistral->folioReal->predio);
        $object->director = $director->name;
        $object->datos_control = $datos_control;
        $object->folioReal = $folioReal;

        $fielDirector = Credential::openFiles(
                                                Storage::disk('efirmas')->path($director->efirma->cer),
                                                Storage::disk('efirmas')->path($director->efirma->key),
                                                $director->efirma->contraseña
                                            );

        $firmaDirector = $fielDirector->sign(json_encode($object));

        FirmaElectronica::where('movimiento_registral_id', $movimientoRegistral->id)->first()?->delete();

        $firmaElectronica = FirmaElectronica::create([
            'movimiento_registral_id' => $movimientoRegistral->id,
            'cadena_original' => json_encode($object),
            'cadena_encriptada' => base64_encode($firmaDirector),
            'estado' => 'activo'
        ]);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('certificaciones.certificadoPropiedadColindancias', [
            'folioReal' => $object->folioReal,
            'predio' => $object->predio,
            'director' => $object->director,
            'datos_control' => $object->datos_control,
            'firma_electronica' => false,
            'qr' => $qr,
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 745, 'I-' . $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

        $objeto = json_decode($firmaElectronica->cadena_original);

        $pdfFirmado = Pdf::loadView('certificaciones.certificadoPropiedadColindancias', [
            'folioReal' => $objeto->folioReal,
            'predio' => $objeto->predio,
            'director' => $objeto->director,
            'datos_control' => $objeto->datos_control,
            'firma_electronica' => base64_encode($firmaDirector),
            'qr' => $qr,
        ]);

        $this->pdfFirmado($pdfFirmado, $movimientoRegistral->id, $movimientoRegistral->folioReal->folio . '-' .$movimientoRegistral->folio);

        return $pdf->stream('documento.pdf');

    }

    public function certificadoNegativo(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $director = User::where('status', 'activo', 'efirma')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Director');
                            })->first();

        $datos_control = (object)[];

        $datos_control->numero_control = $movimientoRegistral->año . '-' . $movimientoRegistral->tramite . '-' . $movimientoRegistral->usuario;
        $datos_control->verificado_por = auth()->user()->name;
        $datos_control->elaborado_en = Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y');
        $datos_control->servicio = $this->nombreServicio($movimientoRegistral->año, $movimientoRegistral->tramite, $movimientoRegistral->usuario);
        $datos_control->solicitante = $movimientoRegistral->solicitante;
        $datos_control->monto = $movimientoRegistral->monto;
        $datos_control->tipo_servicio = $movimientoRegistral->tipo_servicio;
        $datos_control->movimiento_folio = $movimientoRegistral->folio;
        $datos_control->observaciones_certificado = $movimientoRegistral->certificacion->observaciones_certificado;

        $personas = collect();

        $movimientoRegistral->certificacion->load('personas.persona');

        if($movimientoRegistral->certificacion->personas->count()){

            foreach ($movimientoRegistral->certificacion->personas as $persona) {

                $item = (object)[];

                $item->nombre = $persona->persona->nombre;
                $item->ap_paterno = $persona->persona->ap_paterno;
                $item->ap_materno = $persona->persona->ap_materno;

                $personas->push($item);

            }

        }

        $object = (object)[];

        $object->director = $director->name;
        $object->datos_control = $datos_control;
        $object->personas = $personas;
        $object->distrito = $movimientoRegistral->distrito;
        $object->observaciones_certificado = $movimientoRegistral->certificacion->observaciones_certificado;

        $fielDirector = Credential::openFiles(
                                                Storage::disk('efirmas')->path($director->efirma->cer),
                                                Storage::disk('efirmas')->path($director->efirma->key),
                                                $director->efirma->contraseña
                                            );

        $firmaDirector = $fielDirector->sign(json_encode($object));

        FirmaElectronica::where('movimiento_registral_id', $movimientoRegistral->id)->first()?->delete();

        $firmaElectronica = FirmaElectronica::create([
            'movimiento_registral_id' => $movimientoRegistral->id,
            'cadena_original' => json_encode($object),
            'cadena_encriptada' => base64_encode($firmaDirector),
            'estado' => 'activo'
        ]);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('certificaciones.certificadoNegativo', [
            'distrito' => $object->distrito,
            'director' => $object->director,
            'personas' => $object->personas,
            'datos_control' => $object->datos_control,
            'observaciones_certificado' => $object->observaciones_certificado,
            'firma_electronica' => false,
            'qr' => $qr,
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

    public function pdfFirmado($pdf, $id, $folio){

        $this->resetCaratula($id);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(35, 745, $folio, null, 9, array(1, 1, 1));

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

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
            'fileable_id' => $id,
            'fileable_type' => 'App\Models\MovimientoRegistral',
            'descripcion' => 'caratula',
            'url' => $nombre
        ]);

        unlink('caratulas/' . $nombreFinal);

    }

    public function reimprimirFirmado(FirmaElectronica $firmaElectronica){

        $objeto = json_decode($firmaElectronica->cadena_original);

        $qr = $this->generadorQr($firmaElectronica->uuid);

        $pdf = Pdf::loadView('certificaciones.certificadoNegativo', [
            'distrito' => $objeto->distrito,
            'director' => $objeto->director,
            'personas' => $objeto->personas,
            'datos_control' => $objeto->datos_control,
            'observaciones_certificado' => $objeto->observaciones_certificado,
            'firma_electronica' => base64_encode($firmaElectronica->cadena_encriptada),
            'qr'=> $qr
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1,1,1));

        $canvas->page_text(35, 745, 'I-' . $firmaElectronica->movimientoRegistral->folioReal->folio . '-' .$firmaElectronica->movimientoRegistral->folio, null, 9, array(1, 1, 1));

        return $pdf;

    }

}
