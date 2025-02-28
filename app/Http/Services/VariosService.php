<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Vario;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CertificacionServiceException;

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

            }else{

                $acto = null;
            }

            $vario = Vario::create([
                'acto_contenido' => $acto,
                'servicio' => $request['servicio'],
                'movimiento_registral_id' => $request['movimiento_registral'],
            ]);

            if(in_array($vario->acto_contenido, ['SEGUNDO AVISO PREVENTIVO', 'PRIMER AVISO PREVENTIVO'])){

                $vario->movimientoRegistral->update(['usuario_asignado' => $this->obtenerUsuarioRolAvisos($vario->movimientoRegistral->getRawOriginal('distrito'))]);

            }

            if($vario->acto_contenido == 'ACLARACIÓN ADMINISTRATIVA'){

                $vario->movimientoRegistral->update(['usuario_asignado' => $this->obtenerUsuarioRolAclaraciones($vario->movimientoRegistral->getRawOriginal('distrito'))]);

            }

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

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

}
