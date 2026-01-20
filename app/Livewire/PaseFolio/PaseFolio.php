<?php

namespace App\Livewire\PaseFolio;

use Exception;
use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\Antecedente;
use App\Models\Propiedadold;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\AsignacionService;
use App\Http\Services\SistemaTramitesService;
use App\Http\Controllers\PaseFolio\PaseFolioController;
use App\Traits\Inscripciones\FinalizarInscripcionTrait;
use App\Traits\Inscripciones\ReasignarmeMovimientoTrait;
use App\Traits\Inscripciones\ReasignarUsuarioTrait;
use App\Traits\Inscripciones\RechazarMovimientoTrait;
use App\Traits\Inscripciones\RecibirDocumentoTrait;

class PaseFolio extends Component
{

    use ComponentesTrait;
    use WithFileUploads;
    use WithPagination;
    use RechazarMovimientoTrait;
    use RecibirDocumentoTrait;
    use FinalizarInscripcionTrait;
    use ReasignarUsuarioTrait;
    use ReasignarmeMovimientoTrait;

    public $observaciones;
    public $modal = false;
    public $modalNuevoFolio = false;
    public $supervisor = false;

    public $distritos;
    public $distrito;
    public $tomo;
    public $registro;
    public $numero_propiedad;
    public $años;

    public $filters = [
        'año' => '',
        'tramite' => '',
        'usuario' => '',
        'folio_real' => '',
        'folio' => '',
        'estado' => ''
    ];

    protected function rules(){
        return [
            'modelo_editar.usuario_asignado' => 'required',
         ];
    }

    public MovimientoRegistral $modelo_editar;

    public function updatedFilters() { $this->resetPage(); }

    public function crearModeloVacio(){
        $this->modelo_editar = MovimientoRegistral::make();
    }

    public function abrirModalNuevoFolio(){

        $this->modalNuevoFolio = true;

    }

    public function buscarAntecedente(){

        $this->validate([
            'tomo' => 'required',
            'registro' => 'required',
            'distrito' => 'required',
            'numero_propiedad' => 'required',
        ]);

        $folioReal = FolioReal::where('tomo_antecedente', $this->tomo)
                                ->where('registro_antecedente', $this->registro)
                                ->where('distrito_antecedente', $this->distrito)
                                ->where('numero_propiedad_antecedente', $this->numero_propiedad)
                                ->first();

        if($folioReal){

            $this->dispatch('mostrarMensaje', ['warning', "Ya existe un folio con ese antecedente."]);

            return;

        }

        $antecedente = Antecedente::where('tomo_antecedente', $this->tomo)
                                    ->where('registro_antecedente', $this->registro)
                                    ->where('distrito_antecedente', $this->distrito)
                                    ->where('numero_propiedad_antecedente', $this->numero_propiedad)
                                    ->first();

        if($antecedente){

            $this->dispatch('mostrarMensaje', ['warning', "El antecedente ya esta ligado a un folio."]);

            return;

        }

        $movimientoRegistral = MovimientoRegistral::where('tomo', $this->tomo)
                                    ->where('registro', $this->registro)
                                    ->where('distrito', $this->distrito)
                                    ->where('numero_propiedad', $this->numero_propiedad)
                                    ->first();

        if($movimientoRegistral){

            $this->dispatch('mostrarMensaje', ['warning', "Ya existe un movimiento con la información ingresada."]);

            return;

        }

        $propiedad = Propiedadold::where('distrito', $this->distrito)
                                    ->where('tomo', $this->tomo)
                                    ->where('registro', $this->registro)
                                    ->where('noprop', $this->numero_propiedad)
                                    ->first();

        if($propiedad?->status == 'V'){

            $this->dispatch('mostrarMensaje', ['warning', "La propiedad ya esta vendida."]);

            return;

        }

        $movimiento = $this->crearNuevoMovimientoRegistral();

        return redirect()->route('elaboracion_folio', $movimiento);

    }

    public function finalizar(){

        try {

            DB::transaction(function (){

                if(auth()->user()->hasRole('Pase a folio')){

                    $this->seleccionarRoleUsuarios(true);

                    $this->modelo_editar->update([
                        'usuario_asignado' => $this->usuarios->random()->id
                    ]);

                }

                /* Revisar si su antecedente es un folio matriz */
                if($this->modelo_editar->folioReal?->folioRealAntecedente?->matriz){

                    if($this->modelo_editar->inscripcionPropiedad){

                        $this->modelo_editar->update(['estado' => 'concluido']);

                    }

                }

                $this->modelo_editar->folioReal->update([
                    'estado' => 'activo',
                ]);

                if($this->modelo_editar->inscripcionPropiedad) $this->revisarInscripcionPropiedad();

                if($this->modelo_editar->cancelacion) $this->revisarCancelaciones();

                $this->revisarFolioCero();

                $this->revisarMovimientosPrecalificacion();

            });

            $this->dispatch('mostrarMensaje', ['success', "El folio se finalizó con éxito."]);

            $this->modal_finalizar = false;

        } catch (Exception $ex) {

            Log::error("Error al finalizar folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $ex);

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        }catch (\Throwable $th) {

            Log::error("Error al finalizar folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function pasarCaptura(MovimientoRegistral $modelo){

        try {

            $modelo->folioReal->update(['estado' => 'captura']);

            $modelo->update(['estado' => 'nuevo']);

        } catch (\Throwable $th) {

            Log::error("Error al pasar a captura el folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }
    }

    public function revisarMovimientosPrecalificacion(){

        $mRegsitrales = MovimientoRegistral::where('tomo', $this->modelo_editar->tomo)
                                            ->where('registro', $this->modelo_editar->registro)
                                            ->where('numero_propiedad', $this->modelo_editar->numero_propiedad)
                                            ->where('distrito', $this->modelo_editar->getRawOriginal('distrito'))
                                            ->whereNull('folio_real')
                                            ->where('estado', 'precalificacion')
                                            ->get();

        foreach ($mRegsitrales as $movimiento) {

            $movimiento->update([
                'estado' => 'nuevo',
                'folio_real' => $this->modelo_editar->folio_real,
                'folio' => $this->modelo_editar->folioReal->ultimoFolio() == $movimiento->folio ? $movimiento->folio : $this->modelo_editar->folioReal->ultimoFolio() + 1
            ]);

        }

        $mRegsitrales = MovimientoRegistral::where('folio_real', $this->modelo_editar->folio_real)
                                            ->where('estado', 'precalificacion')
                                            ->get();

        foreach ($mRegsitrales as $movimiento) {

            $movimiento->update([
                'estado' => 'nuevo',
                'folio' => $this->modelo_editar->folioReal->ultimoFolio() == $movimiento->folio ? $movimiento->folio : $this->modelo_editar->folioReal->ultimoFolio() + 1
            ]);

        }

    }

    public function revisarInscripcionPropiedad(){

        /* Inscripciones de propiedad sin antecedente para RAN */
        if(
            (
                in_array($this->modelo_editar->inscripcionPropiedad->servicio, ['D114', 'D113', 'D116', 'D115']) &&
                $this->modelo_editar->tomo == null &&
                $this->modelo_editar->registro == null &&
                $this->modelo_editar->numero_propiedad == null
            )
            ||
            (
                 /* Fusion */
                $this->modelo_editar->inscripcionPropiedad->servicio == 'D157'
            )
            ||
            (
                /* Movimientos provenientes de una subdivisión */
                $this->modelo_editar->inscripcionPropiedad->servicio == 'D127' && $this->modelo_editar->movimiento_padre
            )
            ||
            (
                /* Captura especial de folio real */
                $this->modelo_editar->inscripcionPropiedad->servicio == 'D118' && $this->modelo_editar->monto <= 3
            )
        ){

            $this->modelo_editar->update(['estado' => 'concluido']);

            if($this->modelo_editar->año && $this->modelo_editar->tramite &&  $this->modelo_editar->usuario)
                (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'concluido');

        }

    }

    public function revisarCancelaciones(){

        $movimientoGravamen = $this->modelo_editar->folioReal->movimientosRegistrales->where('tomo_gravamen', $this->modelo_editar->tomo_gravamen)
                                                                                        ->where('registro_gravamen', $this->modelo_editar->registro_gravamen)
                                                                                        ->first();

        if(!$movimientoGravamen){

            (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, 'Se rechaza en pase a folio debido a que el folio real no tiene gravamenes con la información ingresada.');

            $this->modelo_editar->update(['estado' => 'rechazado']);

        }

    }

    public function crearNuevoMovimientoRegistral(){

        try {

            $supervisor = (new AsignacionService())->obtenerSupervisorInscripciones($this->distrito);

            $movimiento = null;

            DB::transaction(function () use ($supervisor, &$movimiento){

                $movimiento = MovimientoRegistral::create([
                    'estado' => 'nuevo',
                    'usuario_asignado' => auth()->id(),
                    'usuario_supervisor' => $supervisor,
                    'monto' => 0,
                    'tipo_servicio' => 'extra_urgente',
                    'tomo' => $this->tomo,
                    'registro' => $this->registro,
                    'distrito' => $this->distrito,
                    'numero_propiedad' => $this->numero_propiedad,
                    'seccion' => 'Propiedad',
                    'folio' => 1,
                    'pase_a_folio' => true
                ]);

                Propiedad::create([
                    'movimiento_registral_id' => $movimiento->id,
                    'servicio' => 'D118',
                    'acto_contenido' => 'CAPTURA ESPECIAL DE FOLIO REAL',
                    'descripcion_acto' => 'ESTE MOVIMIENTO REGISTRAL CREA EL FOLIO REAL POR CAPTURA ESPECIAL.'
                ]);

            });

            return $movimiento;

        } catch (\Throwable $th) {

            Log::error("Error al generar nuevo movimiento registral para asignacion de folio real inmobiliario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function revisarFolioCero(){

        if($this->modelo_editar->folioReal->movimientosRegistrales()->where('folio', 0)->first()){

            $folio = $this->modelo_editar->folioReal->ultimoFolio() + 1;

            $this->modelo_editar->update(['folio' => $folio]);

        }

    }

    public function imprimir(MovimientoRegistral $movimientoRegistral){

        try {

            $pdf = (new PaseFolioController())->reimprimirSinFirma($movimientoRegistral->folioReal->firmaElectronica);

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {
            Log::error("Error al reimiprimir caratula de folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function enviarFolioSimlificado(MovimientoRegistral $movimientoRegistral){

        try {

            $movimientoRegistral->update([
                'pase_a_folio' => false,
                'actualizado_por' => auth()->id()
            ]);

        } catch (\Throwable $th) {
            Log::error("Error al envia a pase simplificado por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->distritos = Constantes::DISTRITOS;

        if(auth()->user()->ubicacion == 'Regional 4'){

            $this->distritos = [2 => '02 Uruapan'];

        }else{

            unset($this->distritos[2]);

        }

        $this->años = Constantes::AÑOS;

        $this->motivos_rechazo = Constantes::RECHAZO_MOTIVOS;

        $this->supervisor = in_array(auth()->user()->getRoleNames()->first(), ['Supervisor inscripciones', 'Supervisor certificaciones', 'Supervisor uruapan']);

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Administrador', 'Jefe de departamento inscripciones'])){

            $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad')
                                                    ->with('actualizadoPor:id,name', 'asignadoA:id,name', 'folioReal:id,folio,estado', 'supervisor:id,name')
                                                    ->where('pase_a_folio', true)
                                                    ->whereIn('estado', ['nuevo', 'correccion', 'no recibido'])
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado', 'pendiente']);
                                                            });
                                                    })
                                                    ->when($this->filters['año'] && $this->filters['año'] != '', fn($q) => $q->where('año', $this->filters['año']))
                                                    ->when($this->filters['tramite'] && $this->filters['tramite'] != '', fn($q) => $q->where('tramite', $this->filters['tramite']))
                                                    ->when($this->filters['usuario'] && $this->filters['usuario'] != '', fn($q) => $q->where('usuario', $this->filters['usuario']))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->select('id', 'folio')
                                                                ->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'] && $this->filters['folio'] != '', fn($q) => $q->where('folio', $this->filters['folio']))
                                                    ->when($this->filters['estado'] && $this->filters['estado'] != '', fn($q) => $q->where('estado', $this->filters['estado']))
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor certificaciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad')
                                                    ->with('actualizadoPor:id,name', 'asignadoA:id,name', 'folioReal:id,folio,estado', 'supervisor:id,name')
                                                    ->where('pase_a_folio', true)
                                                    ->whereIn('estado', ['nuevo', 'correccion', 'no recibido'])
                                                    ->where(function($q){
                                                        $q->select('id', 'folio', 'estado')
                                                            ->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado', 'pendiente']);
                                                            });
                                                    })
                                                    ->when($this->filters['año'] && $this->filters['año'] != '', fn($q) => $q->where('año', $this->filters['año']))
                                                    ->when($this->filters['tramite'] && $this->filters['tramite'] != '', fn($q) => $q->where('tramite', $this->filters['tramite']))
                                                    ->when($this->filters['usuario'] && $this->filters['usuario'] != '', fn($q) => $q->where('usuario', $this->filters['usuario']))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->select('id', 'folio')
                                                                ->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'] && $this->filters['folio'] != '', fn($q) => $q->where('folio', $this->filters['folio']))
                                                    ->when($this->filters['estado'] && $this->filters['estado'] != '', fn($q) => $q->where('estado', $this->filters['estado']))
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones'])){

            $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad')
                                                    ->with('actualizadoPor:id,name', 'asignadoA:id,name', 'folioReal:id,folio,estado', 'supervisor:id,name')
                                                    ->where('pase_a_folio', true)
                                                    ->whereIn('estado', ['nuevo', 'correccion', 'no recibido'])
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->select('id', 'estado')
                                                                    ->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado', 'pendiente']);
                                                            });
                                                    })
                                                    ->when($this->filters['año'] && $this->filters['año'] != '', fn($q) => $q->where('año', $this->filters['año']))
                                                    ->when($this->filters['tramite'] && $this->filters['tramite'] != '', fn($q) => $q->where('tramite', $this->filters['tramite']))
                                                    ->when($this->filters['usuario'] && $this->filters['usuario'] != '', fn($q) => $q->where('usuario', $this->filters['usuario']))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->select('id', 'folio')
                                                                ->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'] && $this->filters['folio'] != '', fn($q) => $q->where('folio', $this->filters['folio']))
                                                    ->when($this->filters['estado'] && $this->filters['estado'] != '', fn($q) => $q->where('estado', $this->filters['estado']))
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);
        }else{

            $movimientos = MovimientoRegistral::select('id', 'folio', 'folio_real', 'año', 'tramite', 'usuario', 'actualizado_por', 'usuario_asignado', 'usuario_supervisor', 'estado', 'distrito', 'created_at', 'updated_at', 'tomo', 'registro', 'numero_propiedad')
                                                    ->with('actualizadoPor:id,name', 'asignadoA:id,name', 'folioReal:id,folio,estado', 'supervisor:id,name')
                                                    ->where('pase_a_folio', true)
                                                    ->whereIn('estado', ['nuevo', 'correccion', 'no recibido'])
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'pendiente']);
                                                            });
                                                    })
                                                    ->when($this->filters['año'] && $this->filters['año'] != '', fn($q) => $q->where('año', $this->filters['año']))
                                                    ->when($this->filters['tramite'] && $this->filters['tramite'] != '', fn($q) => $q->where('tramite', $this->filters['tramite']))
                                                    ->when($this->filters['usuario'] && $this->filters['usuario'] != '', fn($q) => $q->where('usuario', $this->filters['usuario']))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'] && $this->filters['folio'] != '', fn($q) => $q->where('folio', $this->filters['folio']))
                                                    ->when($this->filters['estado'] && $this->filters['estado'] != '', fn($q) => $q->where('estado', $this->filters['estado']))
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->where('usuario_asignado', auth()->user()->id)
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }

        return view('livewire.pase-folio.pase-folio', compact('movimientos'))->extends('layouts.admin');
    }

}
