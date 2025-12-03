<?php

namespace App\Livewire\PaseFolio;

use Exception;
use App\Models\User;
use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\Antecedente;
use App\Models\Propiedadold;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\AsignacionService;
use App\Http\Services\SistemaTramitesService;
use App\Http\Controllers\PaseFolio\PaseFolioController;

class PaseFolio extends Component
{

    use ComponentesTrait;
    use WithFileUploads;
    use WithPagination;

    public $observaciones;
    public $modal = false;
    public $modalFinalizar = false;
    public $modalRechazar = false;
    public $modalNuevoFolio = false;
    public $modalReasignarUsuario = false;
    public $modalRecibirDocumentacion = false;
    public $modalBuscarTramite = false;
    public $motivos;
    public $motivo;
    public $supervisor = false;
    public $contraseña;

    public $año;
    public $tramite;
    public $usuario;

    public $distritos;
    public $distrito;
    public $tomo;
    public $registro;
    public $numero_propiedad;
    public $usuarios = [];

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

    public function rechazar(){

        $this->authorize('update', $this->modelo_editar);

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->año, $this->modelo_editar->tramite, $this->modelo_editar->usuario, $this->motivo . ' ' . $observaciones);

                $this->modelo_editar->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

                $this->modelo_editar->folioReal?->update(['estado' => 'rechazado', 'actualizado_por' => auth()->user()->id]);

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

            $pdf = Pdf::loadView('rechazos.rechazo', [
                'movimientoRegistral' => $this->modelo_editar,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones
            ])->output();

            $this->reset(['modalRechazar', 'observaciones']);

            return response()->streamDownload(
                fn () => print($pdf),
                'rechazo.pdf'
            );

        } catch (\Throwable $th) {

            Log::error("Error al rechazar pase a folio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->reset(['modal', 'observaciones']);
        }

    }

    public function abrirModalNuevoFolio(){

        $this->modalNuevoFolio = true;

    }

    public function abrirModalRechazar(MovimientoRegistral $movimientoRegistral){

        $this->reset(['observaciones', 'motivo']);

        if($this->modelo_editar->isNot($movimientoRegistral))
            $this->modelo_editar = $movimientoRegistral;

        $this->modalRechazar = true;

    }

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function abrirModalRecibirDocumentacion(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalRecibirDocumentacion = true;

    }

    public function recibirDocumentacion(){

        if(!Hash::check($this->contraseña, auth()->user()->password)){

            $this->dispatch('mostrarMensaje', ['error', "Contraseña incorrecta."]);

            return;

        }

        try {

            DB::transaction(function () {

                if(auth()->user()->hasRole('Jefe de departamento inscripciones')){

                    $this->modelo_editar->usuario_asignado = auth()->id();

                }

                $this->modelo_editar->estado = 'nuevo';

                $this->modelo_editar->actualizado_por = auth()->id();

                $this->modelo_editar->save();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Recibió documentación']);

            });

            $this->modalRecibirDocumentacion = false;

            $this->dispatch('mostrarMensaje', ['success', "La información se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al recibir documentación de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function cargarUsuarios($roles){

        $this->usuarios = User::whereHas('roles', function($q) use($roles){
                                    $q->whereIn('name', $roles);
                                })
                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->orderBy('name')
                                ->get();

        if(!$this->usuarios->count()){

            throw new Exception("No hay usuarios activos para el rol " . $roles[0]);

        }

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

            $this->modalFinalizar = false;

        } catch (Exception $ex) {

            Log::error("Error al finalizar folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $ex);

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        }catch (\Throwable $th) {

            Log::error("Error al finalizar folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function seleccionarRoleUsuarios($no_pase_a_folio = false){

        if($this->modelo_editar->inscripcionPropiedad){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Propiedad', 'Registrador Propiedad']);

            }else{

                $this->cargarUsuarios(['Propiedad', 'Registrador Propiedad', 'Pase a folio']);

            }

        }

        if($this->modelo_editar->gravamen){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Gravamen', 'Registrador Gravamen']);

            }else{

                $this->cargarUsuarios(['Gravamen', 'Registrador Gravamen', 'Pase a folio']);

            }

        }

        if($this->modelo_editar->vario){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Varios', 'Registrador Varios', 'Aclaraciones administrativas', 'Avisos preventivos']);

            }else{

                $this->cargarUsuarios(['Varios', 'Registrador Varios', 'Pase a folio', 'Aclaraciones administrativas', 'Avisos preventivos']);

            }

        }

        if($this->modelo_editar->cancelacion){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Cancelación', 'Registrador cancelación']);

            }else{

                $this->cargarUsuarios(['Cancelación', 'Registrador cancelación', 'Pase a folio']);

            }

        }

        if($this->modelo_editar->sentencia){

            if($no_pase_a_folio){

                $this->cargarUsuarios(['Sentencias', 'Registrador sentencias']);

            }else{

                $this->cargarUsuarios(['Sentencias', 'Registrador sentencias', 'Pase a folio']);

            }

        }

        if($this->modelo_editar->reformaMoral){

            $this->cargarUsuarios(['Folio real moral']);

        }

        if($this->modelo_editar->certificacion){

            if($this->modelo_editar->certificacion->servicio == 'DL07'){

                if($no_pase_a_folio){

                    $this->cargarUsuarios(['Certificador Gravamen']);

                }else{

                    $this->cargarUsuarios(['Certificador Gravamen', 'Pase a folio']);

                }

            }elseif(in_array($this->modelo_editar->certificacion->servicio, ['DL11', 'DL10'])){

                if($no_pase_a_folio){

                    $this->cargarUsuarios(['Certificador Propiedad']);

                }else{

                    $this->cargarUsuarios(['Certificador Propiedad']);

                }

            }

        }

    }

    public function abrirModalReasignarUsuario(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->seleccionarRoleUsuarios();

        $this->modalReasignarUsuario = true;

    }

    public function reasignarUsuario(){

        $this->validate();

        try {

            $this->modelo_editar->actualizado_por = auth()->id();
            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function reasignarUsuarioAleatoriamente(){

        try {

            $this->modelo_editar->usuario_asignado = $this->usuarios->random()->id;
            $this->modelo_editar->actualizado_por = auth()->id();
            $this->modelo_editar->save();

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            $this->dispatch('mostrarMensaje', ['success', "El usuario se reasignó con éxito."]);

            $this->modalReasignarUsuario = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al reasignar usuario a movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

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

    public function seleccionarMotivo($key){

        $this->motivo = $this->motivos[$key];

    }

    public function obtenerUsuarios($role){

        return User::with('ultimoMovimientoRegistralAsignado')
                            ->where('status', 'activo')
                            ->when($this->modelo_editar->getRawOriginal('distrito') == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($this->modelo_editar->getRawOriginal('distrito') != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q) use ($role){
                                $q->whereIn('name', $role);
                            })
                            ->get();
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
                    'folio' => 1
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

            $pdf = (new PaseFolioController())->reimprimir($movimientoRegistral->folioReal->firmaElectronica);

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {
            Log::error("Error al reimiprimir caratula de folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function asignarmeTramite(){

        try {

            $movimientoRegistral = MovimientoRegistral::where('año', $this->año)
                                                        ->where('tramite', $this->tramite)
                                                        ->where('usuario', $this->usuario)
                                                        ->where('folio', 1)
                                                        ->whereIn('estado', ['nuevo', 'no recibido'])
                                                        ->first();

            if(!$movimientoRegistral){

                $this->dispatch('mostrarMensaje', ['warning', "No se encontro el movimiento registral."]);

                return;

            }

            DB::transaction(function () use($movimientoRegistral) {

                $movimientoRegistral->update([
                    'usuario_asignado' => auth()->id(),
                    'actualizado_por' => auth()->id()
                ]);

                $movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            });

            $this->dispatch('mostrarMensaje', ['success', "Se reasigno correctamente."]);

            $this->reset(['tramite', 'usuario', 'modalBuscarTramite']);

        } catch (\Throwable $th) {

            Log::error("Error al reasignar movimiento registral para asignacion de folio real inmobiliario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

        $this->año = now()->format('Y');

        $this->motivos = Constantes::RECHAZO_MOTIVOS;

        $this->supervisor = in_array(auth()->user()->getRoleNames()->first(), ['Supervisor inscripciones', 'Supervisor certificaciones', 'Supervisor uruapan']);

    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'asignadoA', 'folioReal:id,folio,estado', 'supervisor')
                                                    ->doesnthave('reformaMoral')
                                                    ->whereIn('folio', [0, 1])
                                                    ->whereIn('estado', ['nuevo', 'correccion', 'no recibido'])
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado', 'pendiente']);
                                                            });
                                                    })
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->whereDoesntHave('certificacion', function($q){
                                                        $q->whereNotIn('servicio', ['DL07', 'DL10']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor certificaciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal:id,folio,estado', 'asignadoA')
                                                    ->doesnthave('reformaMoral')
                                                    ->whereIn('folio', [0, 1])
                                                    ->whereIn('estado', ['nuevo', 'correccion', 'no recibido'])
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado', 'pendiente']);
                                                            });
                                                    })
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones', 'Jefe de departamento inscripciones'])){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal:id,folio,estado', 'asignadoA')
                                                    ->doesnthave('reformaMoral')
                                                    ->whereIn('folio', [0, 1])
                                                    ->whereIn('estado', ['nuevo', 'correccion', 'no recibido'])
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'rechazado', 'pendiente']);
                                                            });
                                                    })
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->whereDoesntHave('certificacion', function($q){
                                                        $q->whereNotIn('servicio', ['DL07', 'DL10']);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);
        }else{

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioReal:id,folio,estado', 'asignadoA')
                                                    ->doesnthave('reformaMoral')
                                                    ->whereIn('folio', [0, 1])
                                                    ->whereIn('estado', ['nuevo', 'correccion', 'no recibido'])
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real')
                                                            ->orWhereHas('folioReal', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'pendiente']);
                                                            });
                                                    })
                                                    ->when($this->filters['año'], fn($q, $año) => $q->where('año', $año))
                                                    ->when($this->filters['tramite'], fn($q, $tramite) => $q->where('tramite', $tramite))
                                                    ->when($this->filters['usuario'], fn($q, $usuario) => $q->where('usuario', $usuario))
                                                    ->when($this->filters['folio_real'], function($q){
                                                        $q->whereHas('folioreal', function ($q){
                                                            $q->where('folio', $this->filters['folio_real']);
                                                        });
                                                    })
                                                    ->when($this->filters['folio'], fn($q, $folio) => $q->where('folio', $folio))
                                                    ->when($this->filters['estado'], fn($q, $estado) => $q->where('estado', $estado))
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
