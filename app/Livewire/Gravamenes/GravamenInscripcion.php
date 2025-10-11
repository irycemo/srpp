<?php

namespace App\Livewire\Gravamenes;

use App\Models\File;
use App\Models\User;
use App\Models\Actor;
use Livewire\Component;
use App\Models\Gravamen;
use App\Models\FolioReal;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\LivewireFilepond\WithFilePond;
use Illuminate\Http\Client\ConnectionException;
use App\Http\Controllers\Gravamen\GravamenController;

class GravamenInscripcion extends Component
{

    use WithFileUploads;
    use WithFilePond;

    public $distritos;
    public $actos;
    public $divisas;
    public $tipo_deudores;
    public $tipo_deudor;

    public Gravamen $gravamen;

    public $propiedad;

    public $documento;
    public $contraseña;

    public $modalDocumento = false;
    public $modalContraseña;

    protected $listeners = ['refresh'];

    protected function rules(){
        return [
            'gravamen.tipo' => 'required',
            'gravamen.acto_contenido' => 'required',
            'gravamen.valor_gravamen' => 'required|numeric',
            'gravamen.divisa' => ['required', Rule::in($this->divisas)],
            'gravamen.estado' => 'required',
            'gravamen.expediente' => 'nullable',
            'gravamen.observaciones' => 'required',
         ];
    }

    public function updated($property, $value){

        if($value === ''){

            $this->$property = null;

        }

    }

    public function eliminarActor(Actor $actor){

        try {

            $actor->delete();

            $this->dispatch('mostrarMensaje', ['success', "La información se eliminó con éxito."]);

            $this->refresh();

        } catch (\Throwable $th) {

            Log::error("Error al eliminar actor en gravamen de pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function finalizar(){

        $this->validate();

        if($this->gravamen->acto_contenido === 'DIVISIÓN DE HIPOTECA' && count($this->folios_reales) == 0){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar los folios reales de la división."]);

            return;

        }

        if(!$this->gravamen->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        if(!$this->gravamen->acreedores()->count()){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar los acreedores."]);

            return;

        }

        if(!$this->gravamen->deudores()->count()){

            $this->dispatch('mostrarMensaje', ['error', "Debe ingresar los deudores."]);

            return;

        }

        $this->modalContraseña = true;

    }

    public function inscribir(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                $this->gravamen->estado = 'activo';
                $this->gravamen->fecha_inscripcion = now()->toDateString();
                $this->gravamen->actualizado_por = auth()->id();
                $this->gravamen->save();

                $this->gravamen->movimientoRegistral->update(['estado' => 'elaborado', 'actualizado_por' => auth()->id()]);

                $this->gravamen->movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Elaboró inscripción de gravamen']);

                (new GravamenController())->caratula($this->gravamen);

            });

            return redirect()->route('gravamen');

        } catch (\Throwable $th) {
            Log::error("Error al finalizar inscripcion de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function guardar(){

        try {

            DB::transaction(function () {

                if($this->gravamen->movimientoRegistral->estado != 'correccion')
                    $this->gravamen->movimientoRegistral->estado = 'captura';

                $this->gravamen->movimientoRegistral->actualizado_por = auth()->id();
                $this->gravamen->movimientoRegistral->save();

                $this->gravamen->save();

            });

            $this->dispatch('mostrarMensaje', ['success', "La información se guardó con éxito."]);

        } catch (\Throwable $th) {
            Log::error("Error al guardar gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function obtenerSupervisor(){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->gravamen->movimientoRegistral->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->gravamen->movimientoRegistral->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Supervisor gravamen');
                            })
                            ->first()->id;
    }

    public function obtenerUsuarios(){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->gravamen->movimientoRegistral->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->gravamen->movimientoRegistral->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Gravamen');
                            })
                            ->get();
    }

    public function abrirModalFinalizar(){

        $this->reset('documento');

        $this->dispatch('removeFiles');

        $this->modalDocumento = true;

    }

    public function guardarDocumento(){

        $this->validate(['documento' => 'required']);

        try {

            DB::transaction(function (){

                if(app()->isProduction()){

                    $pdf = $this->documento->store(config('services.ses.ruta_documento_entrada'), 's3');

                }else{

                    $pdf = $this->documento->store('/', 'documento_entrada');

                }

                File::create([
                    'fileable_id' => $this->gravamen->movimientoRegistral->id,
                    'fileable_type' => 'App\Models\MovimientoRegistral',
                    'descripcion' => 'documento_entrada',
                    'url' => $pdf
                ]);

                $this->modalDocumento = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de inscripción de gravamen por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function refresh(){

        $this->gravamen->load('actores.persona', 'acreedores.persona');

    }

    public function mount(){

        if(!$this->gravamen->movimientoRegistral->documentoEntrada()){

            try {

                $response = Http::withToken(env('SISTEMA_TRAMITES_TOKEN'))
                                    ->accept('application/json')
                                    ->asForm()
                                    ->post(env('SISTEMA_TRAMITES_CONSULTAR_ARCHIVO'), [
                                                                                        'año' => $this->gravamen->movimientoRegistral->año,
                                                                                        'tramite' => $this->gravamen->movimientoRegistral->tramite,
                                                                                        'usuario' => $this->gravamen->movimientoRegistral->usuario,
                                                                                        'estado' => 'nuevo'
                                                                                    ]);

                $data = collect(json_decode($response, true));

                if($response->status() == 200){

                    $contents = file_get_contents($data['url']);

                    $filename = basename($data['url']);

                    Storage::disk('documento_entrada')->put($filename, $contents);

                    File::create([
                        'fileable_id' => $this->gravamen->movimientoRegistral->id,
                        'fileable_type' => 'App\Models\MovimientoRegistral',
                        'descripcion' => 'documento_entrada',
                        'url' => $filename
                    ]);

                }

            } catch (ConnectionException $th) {

                Log::error("Error al cargar archivo en gravamen: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

                $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

            }

        }

        $this->distritos = Constantes::DISTRITOS;

        $this->actos = Constantes::ACTOS_INSCRIPCION_GRAVAMEN;

        $this->divisas = Constantes::DIVISAS;

        $this->tipo_deudores = Constantes::ACTORES_GRAVAMEN;

        $this->propiedad = $this->gravamen->movimientoRegistral->folioReal->predio;

        $this->gravamen->estado = 'nuevo';

        $this->gravamen->load('actores.persona');

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first();

        if(!$director) abort(500, message:"Es necesario registrar al director.");

        $jefe_departamento = User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Jefe de departamento inscripciones');
        })->first();

        if(!$jefe_departamento) abort(500, message:"Es necesario registrar al jefe de Departamento de Registro de Inscripciones.");

    }

    public function render()
    {

        $this->authorize('view', $this->gravamen->movimientoRegistral);

        return view('livewire.gravamenes.gravamen-inscripcion')->extends('layouts.admin');
    }

}
