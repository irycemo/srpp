<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\User;
use Livewire\Component;
use App\Models\FolioReal;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\AsignacionService;
use App\Traits\Inscripciones\Varios\VariosTrait;
use App\Http\Controllers\Varios\VariosController;
use App\Traits\Inscripciones\ConsultarArchivoTrait;
use App\Traits\Inscripciones\DocumentoEntradaTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;
use Spatie\LivewireFilepond\WithFilePond;

class PrimerAvisoPreventivo extends Component
{

    use VariosTrait;
    use WithFilePond;
    use DocumentoEntradaTrait;
    use GuardarDocumentoEntradaTrait;
    use ConsultarArchivoTrait;

    protected function rules(){
        return [
            'vario.descripcion' => 'required',
            'vario.acto_contenido' => 'required',
            'documento_entrada_pdf' => 'nullable|mimes:pdf|max:100000',
            'tipo_documento' => 'required',
            'autoridad_cargo' => 'required',
            'autoridad_nombre' => 'required',
            'numero_documento' => 'nullable',
            'fecha_emision' => 'required',
            'procedencia' => 'nullable',
        ];
    }

    public function inscribir(){

        $this->validate();

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->vario->estado = 'activo';
                $this->vario->actualizado_por = auth()->id();
                $this->vario->fecha_inscripcion = $this->vario->movimientoRegistral->fecha_prelacion;
                $this->vario->save();

                $this->actualizarDocumentoEntrada($this->vario->movimientoRegistral);

                $this->crearCertificadoGravamen();

                $this->vario->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->vario->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de varios']);

                (new VariosController())->caratula($this->vario);

            });

            return redirect()->route('varios');

        } catch (Exception $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                if($this->vario->movimientoRegistral->estado != 'correccion')
                    $this->vario->movimientoRegistral->estado = 'captura';

                $this->vario->movimientoRegistral->actualizado_por = auth()->id();
                $this->vario->save();

                $this->actualizarDocumentoEntrada($this->vario->movimientoRegistral);

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar inscripción de varios por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function crearCertificadoGravamen(){

        $movimiento = MovimientoRegistral::create([
            'estado' => 'nuevo',
            'folio' => FolioReal::find($this->vario->movimientoRegistral->folio_real)->ultimoFolio() + 1,
            'folio_real' => $this->vario->movimientoRegistral->folio_real,
            'fecha_prelacion' => $this->vario->movimientoRegistral->fecha_prelacion,
            'fecha_entrega' => $this->vario->movimientoRegistral->fecha_entrega,
            'fecha_pago' => $this->vario->movimientoRegistral->fecha_pago,
            'tipo_servicio' => $this->vario->movimientoRegistral->tipo_servicio,
            'solicitante' => $this->vario->movimientoRegistral->solicitante,
            'seccion' => $this->vario->movimientoRegistral->seccion,
            'año' => $this->vario->movimientoRegistral->año,
            'tramite' => $this->vario->movimientoRegistral->tramite,
            'usuario' => $this->vario->movimientoRegistral->usuario,
            'tomo' => $this->vario->movimientoRegistral->tomo,
            'registro' => $this->vario->movimientoRegistral->registro,
            'distrito' => $this->vario->movimientoRegistral->getRawOriginal('distrito'),
            'tipo_documento' => $this->vario->movimientoRegistral->tipo_documento,
            'numero_documento' => $this->vario->movimientoRegistral->numero_documento,
            'numero_propiedad' => $this->vario->movimientoRegistral->numero_propiedad,
            'autoridad_cargo' => $this->vario->movimientoRegistral->autoridad_cargo,
            'autoridad_numero' => $this->vario->movimientoRegistral->autoridad_numero,
            'fecha_emision' => $this->vario->movimientoRegistral->fecha_emision,
            'fecha_inscripcion' => $this->vario->movimientoRegistral->fecha_inscripcion,
            'procedencia' => $this->vario->movimientoRegistral->procedencia,
            'numero_oficio' => $this->vario->movimientoRegistral->numero_oficio,
            'folio_real' => $this->vario->movimientoRegistral->folio_real,
            'monto' => $this->vario->movimientoRegistral->monto,
            'usuario_asignado' => (new AsignacionService())->obtenerUltimoUsuarioConAsignacion($this->obtenerUsuarios()),
            'usuario_supervisor' => $this->obtenerSupervisor(),
            'movimiento_padre' => $this->vario->movimientoRegistral->id,
            'servicio_nombre' => 'Certificado de gravamen'
        ]);

        $movimiento->certificacion()->create([
            'servicio' => 'DL07',
            'observaciones' => 'Trámite generado por inscripción de un primer aviso preventivo ' . $this->vario->movimientoRegistral->año . '-' . $this->vario->movimientoRegistral->tramite . '-' . $this->vario->movimientoRegistral->usuario
        ]);

    }

    public function obtenerSupervisor(){

        if($this->vario->movimientoRegistral->getRawOriginal('distrito') == 2){

            return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->where('ubicacion', 'Regional 4')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Supervisor uruapan');
                            })
                            ->first()->id;

        }else{

            return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->where('ubicacion', '!=', 'Regional 4')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Supervisor certificaciones');
                            })
                            ->first()->id;

        }

    }

    public function obtenerUsuarios(){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->vario->movimientoRegistral->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->vario->movimientoRegistral->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Certificador Gravamen');
                            })
                            ->get();
    }

    public function mount(){

        $this->movimientoRegistral = $this->vario->movimientoRegistral;

        $this->consultarArchivo($this->vario->movimientoRegistral);

        $this->cargarDocumentoEntrada($this->vario->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.varios.primer-aviso-preventivo');
    }
}
