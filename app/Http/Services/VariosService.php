<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Vario;
use App\Models\MovimientoRegistral;
use App\Exceptions\GeneralException;
use App\Http\Services\MovimientoServiceInterface;
use App\Traits\Inscripciones\RevisarFolioMatrizTrait;

class VariosService implements MovimientoServiceInterface{

    use RevisarFolioMatrizTrait;

    public function crear(array $request):void
    {

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
            'movimiento_registral_id' => $request['movimiento_registral_id'],
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

    }

    public function obtenerUsuarioAsignado(array $request):int | null
    {
        return (new AsignacionService())->obtenerUsuarioVarios(isset($request['folio_real']), $request['distrito'], $request['estado']);
    }

    public function obtenerSupervisorAsignado(array $request):int
    {
        return (new AsignacionService())->obtenerSupervisorInscripciones($request['distrito']);
    }

    public function corregir(MovimientoRegistral $movimientoRegistral):void
    {}

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

        if(!$usuario) throw new GeneralException('No hay usuario con rol de Avisos preventivos.');

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

        if(!$usuario) throw new GeneralException('No hay usuario con rol de Aclaraciones administrativas.');

        return $usuario->id;

    }

    public function avisoAclaratorioCancelar($request){

        $movimiento = MovimientoRegistral::find($request['movimiento_registral']);

        $movimientoAviso = $movimiento->folioReal->movimientosRegistrales()->where('folio', $request['asiento_registral'])->first();

        $movimientoAviso->update(['movimiento_padre' => $request['movimiento_registral']]);

    }

}
