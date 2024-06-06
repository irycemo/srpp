<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Exceptions\AsignacionServiceException;

class AsignacionService{

    public function obtenerUltimoUsuarioConAsignacion($usuarios):int
    {

        $movimientos = [];

        foreach ($usuarios as $usuario) {

            if(!$usuario->ultimoMovimientoRegistralAsignado)
                return $usuario->id;

            array_push($movimientos, $usuario->ultimoMovimientoRegistralAsignado);

        }

        return collect($movimientos)->sortBy('created_at')->first()->usuario_asignado;

        /* return MovimientoRegistral::whereIn('id', $ids)->orderBy('created_at')->first()->usuario_asignado; */

    }

    /* Certificaciones */
    public function obtenerUsuarioConsulta($distrito):int
    {

        $usuarios = User::with('ultimoMovimientoRegistralAsignado')
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Consulta');
                                })
                                ->get();

        if($usuarios->count() == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de consulta para asignar al movimiento registral.');

        }else if($usuarios->count() == 1){

            return $usuarios->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($usuarios);

        }

    }

    public function obtenerCertificador($distrito, $solicitante, $tipo_servicio, $random):int
    {

        if($distrito != 2 && $solicitante == 'Oficialia de partes'){

            if($tipo_servicio == 'ordinario')

                $certificadores = User::with('ultimoMovimientoRegistralAsignado')
                                        ->where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Oficialia');
                                        })
                                        ->get();
            else

                $certificadores = User::with('ultimoMovimientoRegistralAsignado')
                                        ->where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Juridico');
                                        })
                                        ->get();

        }else{

            $certificadores = User::with('ultimoMovimientoRegistralAsignado')
                                        ->where('status', 'activo')
                                        ->when($distrito == 2, function($q){
                                            $q->where('ubicacion', 'Regional 4');
                                        })
                                        ->when($distrito != 2, function($q){
                                            $q->where('ubicacion', '!=', 'Regional 4');
                                        })
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador');
                                        })
                                        ->get();

        }

        if($certificadores->count() == 0){

            Log::error('No se encontraron usuario para asignar la certificación.');

            throw new AsignacionServiceException('No se encontraron certificadores para asignar al movimiento registral.');

        }else if($random){

            $certificador = $certificadores->shuffle()->first();

            return $certificador->id;

        }else if($certificadores->count() == 1){

            return $certificadores->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($certificadores);

        }

    }

    public function obtenerCertificadorGravamen($distrito, $solicitante, $tipo_servicio, $random):int
    {

        if($distrito != 2 && $solicitante == 'Oficialia de partes'){

            if($tipo_servicio == 'ordinario')

                $certificadores = User::with('ultimoMovimientoRegistralAsignado')
                                        ->where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Oficialia');
                                        })
                                        ->get();
            else

                $certificadores = User::with('ultimoMovimientoRegistralAsignado')
                                        ->where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Juridico');
                                        })
                                        ->get();

        }else{

            $certificadores = User::with('ultimoMovimientoRegistralAsignado')
                                        ->where('status', 'activo')
                                        ->when($distrito == 2, function($q){
                                            $q->where('ubicacion', 'Regional 4');
                                        })
                                        ->when($distrito != 2, function($q){
                                            $q->where('ubicacion', '!=', 'Regional 4');
                                        })
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Gravamen');
                                        })
                                        ->get();

        }

        if($certificadores->count() == 0){

            Log::error('No se encontraron usuario para asignar la certificación.');

            throw new AsignacionServiceException('No se encontraron certificadores para asignar al movimiento registral.');

        }else if($random){

            $certificador = $certificadores->shuffle()->first();

            return $certificador->id;

        }else if($certificadores->count() == 1){

            return $certificadores->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($certificadores);

        }

    }

    public function obtenerSupervisorCertificaciones($distrito):int
    {

        $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor certificaciones');
                                })
                                ->first();

        if(!$supervisor){

            throw new AsignacionServiceException('No se encontraron supervisores de certificaciones para asignar al movimiento registral.');

        }

        return $supervisor->id;

    }

    /* Inscripciones : Propiedad */
    public function obtenerUsuarioPropiedad($folioReal, $distrito):int
    {

        $usuarios = User::with('ultimoMovimientoRegistralAsignado')
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->when($folioReal != null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Propiedad', 'Registrador Propiedad']);
                                    });
                                })
                                ->when($folioReal === null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Pase a folio', 'Registrador Propiedad']);
                                    });
                                })
                                ->get();

        if($usuarios->count() == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de propiedad para asignar al movimiento registral.');

        }else if($usuarios->count() == 1){

            return $usuarios->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($usuarios);

        }

    }

    public function obtenerSupervisorPropiedad($distrito):int
    {

        $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor propiedad');
                                })
                                ->first();

        if(!$supervisor){

            throw new AsignacionServiceException('No se encontraron supervisores de propiedad para asignar al movimiento registral.');

        }

        return $supervisor->id;

    }

    /* Inscripciones : Propiedad */
    public function obtenerUsuarioGravamen($folioReal, $distrito):int
    {

        $usuarios = User::with('ultimoMovimientoRegistralAsignado')
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->when($folioReal != null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Gravamen', 'Registrador Gravamen']);
                                    });
                                })
                                ->when($folioReal === null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Pase a folio', 'Registrador Gravamen']);
                                    });
                                })
                                ->get();

        if($usuarios->count() == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de gravamen para asignar al movimiento registral.');

        }else if($usuarios->count() == 1){

            return $usuarios->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($usuarios);

        }

    }

    public function obtenerSupervisorGravamen($distrito):int
    {

        $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor gravamen');
                                })
                                ->first();

        if(!$supervisor){

            throw new AsignacionServiceException('No se encontraron supervisores de gravamen para asignar al movimiento registral.');

        }

        return $supervisor->id;

    }

    /* Inscripciones : Cancelacion */
    public function obtenerUsuarioCancelacion($folioReal, $distrito):int
    {

        $usuarios = User::with('ultimoMovimientoRegistralAsignado')
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->when($folioReal != null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Cancelación', 'Registrador Cancelación']);
                                    });
                                })
                                ->when($folioReal === null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Pase a folio', 'Registrador Cancelación']);
                                    });
                                })
                                ->get();

        if($usuarios->count() == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de cancelación para asignar al movimiento registral.');

        }else if($usuarios->count() == 1){

            return $usuarios->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($usuarios);

        }

    }

    public function obtenerSupervisorCancelacion($distrito):int
    {

        $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor cancelación');
                                })
                                ->first();

        if(!$supervisor){

            throw new AsignacionServiceException('No se encontraron supervisores de cancelación para asignar al movimiento registral.');

        }

        return $supervisor->id;

    }

    /* Inscripciones : Cancelacion */
    public function obtenerUsuarioVarios($folioReal, $distrito):int
    {

        $usuarios = User::with('ultimoMovimientoRegistralAsignado')
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->when($folioReal != null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Varios', 'Registrador Varios']);
                                    });
                                })
                                ->when($folioReal === null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Pase a folio', 'Registrador Varios']);
                                    });
                                })
                                ->get();

        if($usuarios->count() == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de varios para asignar al movimiento registral.');

        }else if($usuarios->count() == 1){

            return $usuarios->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($usuarios);

        }

    }

    public function obtenerSupervisorVarios($distrito):int
    {

        $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor varios');
                                })
                                ->first();

        if(!$supervisor){

            throw new AsignacionServiceException('No se encontraron supervisores de varios para asignar al movimiento registral.');

        }

        return $supervisor->id;

    }

    /* Inscripciones : Sentencias */
    public function obtenerUsuarioSentencias($folioReal, $distrito):int
    {

        $usuarios = User::with('ultimoMovimientoRegistralAsignado')
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->when($folioReal != null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Sentencias', 'Registrador Sentencias']);
                                    });
                                })
                                ->when($folioReal === null, function($q){
                                    $q->whereHas('roles', function($q){
                                        $q->whereIn('name', ['Pase a folio', 'Registrador Sentencias']);
                                    });
                                })
                                ->get();

        if($usuarios->count() == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de varios para asignar al movimiento registral.');

        }else if($usuarios->count() == 1){

            return $usuarios->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($usuarios);

        }

    }

    public function obtenerSupervisorSentencias($distrito):int
    {

        $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor sentencias');
                                })
                                ->first();

        if(!$supervisor){

            throw new AsignacionServiceException('No se encontraron supervisores de sentencias para asignar al movimiento registral.');

        }

        return $supervisor->id;

    }

}
