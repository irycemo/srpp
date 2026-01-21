<?php

namespace App\Livewire\Varios;

use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Spatie\LivewireFilepond\WithFilePond;
use App\Traits\Inscripciones\Varios\VariosTrait;
use App\Http\Controllers\Varios\VariosController;
use App\Traits\Inscripciones\DocumentoEntradaTrait;
use App\Traits\Inscripciones\GuardarDocumentoEntradaTrait;

class CancelacionAvisoPreventivo extends Component
{

    use VariosTrait;
    use WithFilePond;
    use DocumentoEntradaTrait;
    use GuardarDocumentoEntradaTrait;

    public $avisoCancelar;

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

                $this->avisoCancelar->update([
                    'estado' => 'inactivo',
                    'descripcion' => $this->avisoCancelar->descripcion . ' AVISO CANCELADO MEDIANTE MOVIMIENTO REGISTRAL ' . $this->vario->movimientoRegistral->folio
                ]);

                $this->actualizarDocumentoEntrada($this->vario->movimientoRegistral);

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

    public function mount(){

        $this->avisoCancelar = $this->vario->movimientoRegistral->movimientosHijos->first()->vario;

        $this->vario->acto_contenido = 'CANCELACIÓN DE PRIMER AVISO PREVENTIVO';

        $this->cargarDocumentoEntrada($this->vario->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.varios.cancelacion-aviso-preventivo');
    }
}
