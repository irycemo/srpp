<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Vario;
use App\Models\Predio;
use App\Models\FolioReal;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CertificacionServiceException;
use App\Models\Propiedad;

class VariosService{

    public function store(array $request){

        try {

            if($request['servicio_nombre'] == 'Segundo aviso preventivo'){

                $acto = 'SEGUNDO AVISO PREVENTIVO';

            }elseif($request['servicio_nombre'] == 'Donación / Venta de usufructo'){

                $acto = 'DONACIÓN / VENTA DE USUFRUCTO';

            }elseif($request['servicio_nombre'] == 'Aclaraciones administrativas de inscripciones'){

                $acto = 'ACLARACIÓN ADMINISTRATIVA';

            }elseif($request['servicio'] == 'DN83'){

                $acto = 'PRIMER AVISO PREVENTIVO';

            }elseif($request['servicio_nombre'] == 'Cancelación de primer aviso preventivo'){

                $acto = 'CANCELACIÓN DE PRIMER AVISO PREVENTIVO';

                $this->avisoAclaratorioCancelar($request);

            }elseif($request['servicio_nombre'] == 'Cancelación de segundo aviso preventivo'){

                $acto = 'CANCELACIÓN DE SEGUNDO AVISO PREVENTIVO';

                $this->avisoAclaratorioCancelar($request);

            }else{

                $acto = null;
            }

            $vario = Vario::create([
                'acto_contenido' => $acto,
                'servicio' => $request['servicio'],
                'movimiento_registral_id' => $request['movimiento_registral'],
            ]);

            if(in_array($vario->acto_contenido, ['SEGUNDO AVISO PREVENTIVO', 'PRIMER AVISO PREVENTIVO'])){

                $this->revisarFolioMatriz($vario->movimientoRegistral);

                $vario->movimientoRegistral->update(['usuario_asignado' => $this->obtenerUsuarioRolAvisos($vario->movimientoRegistral->getRawOriginal('distrito'))]);

                if($vario->movimientoRegistral->folioReal?->avisoPreventivo()){

                    $aviso = $vario->movimientoRegistral->folioReal->avisoPreventivo();

                    $aviso->update(['estado' => 'inactivo']);

                }

            }

            if($vario->acto_contenido == 'ACLARACIÓN ADMINISTRATIVA'){

                $vario->movimientoRegistral->update(['usuario_asignado' => $this->obtenerUsuarioRolAclaraciones($vario->movimientoRegistral->getRawOriginal('distrito'))]);

            }

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar inscripción de varios con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

    }

    public function obtenerUsuarioRolAvisos($distrito){

        $usuario = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function ($q){
                                $q->where('name', 'Avisos preventivos');
                            })
                            ->first();

        if(!$usuario) throw new CertificacionServiceException('No hay usuario con rol de Avisos preventivos.');

        return $usuario->id;

    }

    public function obtenerUsuarioRolAclaraciones($distrito){

        $usuario = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function ($q){
                                $q->where('name', 'Aclaraciones administrativas');
                            })
                            ->first();

        if(!$usuario) throw new CertificacionServiceException('No hay usuario con rol de Aclaraciones administrativas.');

        return $usuario->id;

    }

    public function avisoAclaratorioCancelar($request){

        $movimiento = MovimientoRegistral::find($request['movimiento_registral']);

        $movimientoAviso = $movimiento->folioReal->movimientosRegistrales()->where('folio', $request['asiento_registral'])->first();

        $movimientoAviso->update(['movimiento_padre' => $request['movimiento_registral']]);

    }

    public function revisarFolioMatriz(MovimientoRegistral $movimiento)
    {

        if($movimiento->folioReal?->matriz){

            $folioReal = FolioReal::create([
                'estado' => 'captura',
                'folio' => (FolioReal::max('folio') ?? 0) + 1,
                'antecedente' => $movimiento->folioReal->id,
                'distrito_antecedente' => $movimiento->getRawOriginal('distrito'),
                'seccion_antecedente' => $movimiento->seccion,
                'autoridad_cargo' => $movimiento->autoridad_cargo,
                'autoridad_nombre' => $movimiento->autoridad_nombre,
                'autoridad_numero' => $movimiento->autoridad_numero,
                'numero_documento' => $movimiento->numero_documento,
                'fecha_emision' => $movimiento->fecha_emision,
                'fecha_inscripcion' => $movimiento->fecha_inscripcion,
                'procedencia' => $movimiento->procedencia,
                'tipo_documento' => $movimiento->tipo_documento,
            ]);

            Predio::create(['folio_real' => $folioReal->id, 'status' => 'nuevo']);

            $nuevoMovimientoRegistral = $movimiento->replicate();
            $nuevoMovimientoRegistral->tomo = null;
            $nuevoMovimientoRegistral->registro = null;
            $nuevoMovimientoRegistral->numero_propiedad = null;
            $nuevoMovimientoRegistral->estado = 'concluido';
            $nuevoMovimientoRegistral->servicio_nombre = 'Genera nuevo folio real';
            $nuevoMovimientoRegistral->folio = $movimiento->folioReal->ultimoFolio();
            $nuevoMovimientoRegistral->save();

            Propiedad::create([
                'servicio' => 'D114',
                'acto_contenido' => 'CREA NUEVO FOLIO',
                'descripcion_acto' => 'ESTE MOVIMIENTO REGISTRAL CREA EL FOLIO REAL: ' . $folioReal->folio . '.',
                'movimiento_registral_id' => $nuevoMovimientoRegistral->id
            ]);

            $movimiento->update(['folio_real' => $folioReal->id, 'folio' => 1]);

        }

    }

}
