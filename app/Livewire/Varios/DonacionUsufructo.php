<?php

namespace App\Livewire\Varios;

use Exception;
use App\Models\Actor;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Exceptions\PredioException;
use Illuminate\Support\Facades\Log;
use App\Http\Services\PredioService;
use Illuminate\Support\Facades\Hash;
use Spatie\LivewireFilepond\WithFilePond;
use App\Traits\Inscripciones\Varios\VariosTrait;
use App\Http\Controllers\Varios\VariosController;
use App\Traits\Inscripciones\DocumentoEntradaTrait;

class DonacionUsufructo extends Component
{

    use VariosTrait;
    use WithFilePond;
    use DocumentoEntradaTrait;

    protected $listeners = ['refresh'];

    protected function rules(){
        return [
            'vario.acto_contenido' => 'required',
            'vario.descripcion' => 'required',
            'documento' => 'nullable|mimes:pdf|max:100000'
         ];
    }

    public function finalizar(){

        $this->validate();

        if(!$this->vario->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        try {

            (new PredioService())->revisarPorcentajesFinal($this->vario->propietarios());

        } catch (PredioException $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);
            return;

        }

        $this->modalContraseña = true;

    }

    public function refresh(){

        $this->vario->load('actores.persona');

    }

    public function borrarActor(Actor $actor){

        try {

            $actor->delete();

            $this->vario->movimientoRegistral->folioReal->predio->actores()->where('persona_id', $actor->persona_id)->first()->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->vario->load('actores.persona');

        } catch (\Throwable $th) {

            Log::error("Error al borrar actor en donación de usufructo por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->vario->estado = 'activo';
                $this->vario->actualizado_por = auth()->id();
                $this->vario->fecha_inscripcion = now()->toDateString();
                $this->vario->save();

                $this->actualizarDocumentoEntrada($this->vario->movimientoRegistral);

                $this->vario->movimientoRegistral->update(['estado' => 'elaborado']);

                $this->procesarPropietarios();

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

    public function procesarPropietarios(){

        foreach($this->vario->actores as $propietario){

            $actor = $this->vario->movimientoRegistral->folioReal->predio->actores()->where('persona_id', $propietario->persona_id)->first();

            if($actor){

                $actor->update([
                    'porcentaje_propiedad' => $propietario->porcentaje_propiedad,
                    'porcentaje_nuda' => $propietario->porcentaje_nuda,
                    'porcentaje_usufructo' => $propietario->porcentaje_usufructo,
                ]);

            }else{

                $this->vario->movimientoRegistral->folioReal->predio->actores()->create([
                    'persona_id' => $propietario->persona->id,
                    'tipo_actor' => 'propietario',
                    'porcentaje_propiedad' => $propietario->porcentaje_propiedad,
                    'porcentaje_nuda' => $propietario->porcentaje_nuda,
                    'porcentaje_usufructo' => $propietario->porcentaje_usufructo,
                    'creado_por' => auth()->id()
                ]);

            }

        }

    }

    public function guardar(){

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

        $this->vario->acto_contenido = 'DONACIÓN / VENTA DE USUFRUCTO';

        if($this->vario->actores()->count() == 0){

            foreach ($this->vario->movimientoRegistral->folioReal->predio->propietarios() as $propietario) {

                $this->vario->actores()->create([
                    'persona_id' => $propietario->persona_id,
                    'tipo_actor' => 'propietario',
                    'porcentaje_propiedad' => $propietario->porcentaje_propiedad,
                    'porcentaje_nuda' => $propietario->porcentaje_nuda,
                    'porcentaje_usufructo' => $propietario->porcentaje_usufructo,
                ]);

            }

        }

        $this->vario->load('actores.persona');

        $this->cargarDocumentoEntrada($this->vario->movimientoRegistral);

    }

    public function render()
    {
        return view('livewire.varios.donacion-usufructo');
    }
}
