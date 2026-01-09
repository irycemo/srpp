<?php

namespace App\Livewire\PersonaMoral;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Http\Controllers\FolioPersonaMoralController\FolioPersonaMoralController;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;
use App\Traits\Inscripciones\InscripcionesIndex;
use App\Traits\Inscripciones\RechazarMovimientoTrait;

class PaseFolioPersonaMoral extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use InscripcionesIndex;
    use RechazarMovimientoTrait;

    public $supervisor;

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalFinalizar = true;

    }

    public function finalizar(){

        try {

            DB::transaction(function (){

                $this->modelo_editar->folioRealPersona->update([
                    'estado' => 'activo'
                ]);

                if($this->modelo_editar->reformaMoral->acto_contenido == 'INSCRIPCIÓN DE FOLIO REAL DE PERSONA MORAL'){

                    $this->modelo_editar->update(['estado' => 'concluido']);

                }

                $this->dispatch('mostrarMensaje', ['success', "El folio se finalizó con éxito."]);

                $this->modalFinalizar = false;

            });

        } catch (\Throwable $th) {

            Log::error("Error al finalizar folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function pasarCaptura(MovimientoRegistral $modelo){

        try {

            $modelo->folioRealPersona->update(['estado' => 'captura']);

        } catch (\Throwable $th) {

            Log::error("Error al pasar a captura el folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }
    }

    public function imprimir(MovimientoRegistral $modelo){

        try {

            $pdf = (new FolioPersonaMoralController())->reimprimir($modelo->firmaElectronica);

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'documento.pdf'
            );

        } catch (\Throwable $th) {
            Log::error("Error al reimiprimir caratula de inscripción por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->motivos_rechazo = Constantes::RECHAZO_MOTIVOS;

        $this->usuarios_regionales = Constantes::USUARIOS_REGIONALES;

        $this->usuarios = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->whereIn('name', ['Folio real moral']);
                                        })
                                        ->orderBy('name')
                                        ->get();

        $this->supervisor = in_array(auth()->user()->getRoleNames()->first(), ['Supervisor inscripciones', 'Supervisor certificaciones', 'Supervisor uruapan']);

    }

    public function render()
    {

        if(auth()->user()->hasRole(['Folio real moral'])){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioRealPersona')
                                                    ->has('reformaMoral')
                                                    ->where('folio', 1)
                                                    ->whereIn('estado', ['nuevo', 'correccion', 'elaborado', 'no recibido'])
                                                    ->where('usuario_asignado', auth()->user()->id)
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real_persona')
                                                            ->orWhereHas('folioRealPersona', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado']);
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor uruapan'])){

            $movimientos = MovimientoRegistral::with('actualizadoPor', 'folioRealPersona', 'asignadoA')
                                                    ->has('reformaMoral')
                                                    ->where('folio', 1)
                                                    ->where('usuario_supervisor', auth()->user()->id)
                                                    ->where(function($q){
                                                        $q->whereNull('folio_real_persona')
                                                            ->orWhereHas('folioRealPersona', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'no recibido']);
                                                            });
                                                    })
                                                    ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                        $q->where('distrito', 2);
                                                    })
                                                    ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                        $q->where('distrito', '!=', 2);
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico', 'Jefe de departamento inscripciones', 'Director'])){

            $movimientos = MovimientoRegistral::with('asignadoA', 'actualizadoPor', 'folioRealPersona')
                                                ->has('reformaMoral')
                                                ->where('folio', 1)
                                                ->where(function($q){
                                                    $q->whereNull('folio_real_persona')
                                                        ->orWhereHas('folioRealPersona', function($q){
                                                            $q->whereIn('estado', ['nuevo', 'captura', 'elaborado', 'no recibido']);
                                                        });
                                                })
                                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                    $q->where('distrito', 2);
                                                })
                                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                                    $q->where('distrito', '!=', 2);
                                                })
                                                ->orderBy($this->sort, $this->direction)
                                                ->paginate($this->pagination);

        }

        return view('livewire.persona-moral.pase-folio-persona-moral', compact('movimientos'))->extends('layouts.admin');

    }
}
