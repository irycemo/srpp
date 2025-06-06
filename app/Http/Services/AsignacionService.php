<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Asignacion;
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

    public function obtenerSiguienteUsuario($arrayUsuarios, $idActual){

        if($idActual == null) return $arrayUsuarios[0];

        $key = array_search($idActual, $arrayUsuarios->toArray());

        if(($key + 1) == count($arrayUsuarios)){

            return $arrayUsuarios[0];

        }else{

            return $arrayUsuarios[$key + 1];

        }

    }

    public function obtenerUsuarioPaseAFolio($distrito):int
    {

        if($distrito == 2){

            $idActual = Asignacion::first()?->pase_a_folio_uruapan;

        }else{

            $idActual = Asignacion::first()?->pase_a_folio;

        }

        $usuarios = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q) {
                                    $q->where('name', 'Pase a folio');
                            })
                            ->pluck('id');

        if(count($usuarios) == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de propiedad para asignar al movimiento registral.');

        }else if(count($usuarios) == 1){

            return $usuarios[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($usuarios, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['pase_a_folio_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['pase_a_folio' => $actual]);

            }

            return $actual;

        }

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

    public function obtenerCopiador($distrito, $solicitante, $tipo_servicio, $random):int
    {

        if($distrito == 2){

            $idActual = Asignacion::first()?->copiador_uruapan;

        }else{

            $idActual = Asignacion::first()?->copiador;

        }

        $copiadores = User::where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Copiador');
                                })
                                ->pluck('id');

        if(count($copiadores) == 0){

            Log::error('No se encontraron usuario copiador para asignar la certificación.');

            throw new AsignacionServiceException('No se encontraron copiadores para asignar al movimiento registral.');

        }else if($random){

            return array_rand($copiadores, 1);

        }else if(count($copiadores) == 1){

            return $copiadores[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($copiadores, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['copiador_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['copiador' => $actual]);

            }

            return $actual;

        }

    }

    public function obtenerCertificador($distrito, $solicitante, $tipo_servicio, $random):int
    {

        if($distrito != 2 && $solicitante == 'Oficialia de partes'){

            $idActual = Asignacion::first()?->certificador_uruapan;

            if($tipo_servicio == 'ordinario')

                $certificadores = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Oficialia');
                                        })
                                        ->pluck('id');
            else

                $certificadores = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Juridico');
                                        })
                                        ->pluck('id');

        }else{

            $idActual = Asignacion::first()?->certificador;

            $certificadores = User::where('status', 'activo')
                                    ->when($distrito == 2, function($q){
                                        $q->where('ubicacion', 'Regional 4');
                                    })
                                    ->when($distrito != 2, function($q){
                                        $q->where('ubicacion', '!=', 'Regional 4');
                                    })
                                    ->whereHas('roles', function($q){
                                        $q->where('name', 'Certificador');
                                    })
                                    ->pluck('id');

        }

        if(count($certificadores) == 0){

            Log::error('No se encontraron usuario para asignar la certificación.');

            throw new AsignacionServiceException('No se encontraron certificadores para asignar al movimiento registral.');

        }else if($random){

            return array_rand($certificadores, 1);

        }else if(count($certificadores) == 1){

            return $certificadores[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($certificadores, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['certificador_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['certificador' => $actual]);

            }

            return $actual;

        }

    }

    public function obtenerCertificadorGravamen($distrito, $solicitante, $tipo_servicio, $random, $folioReal):int
    {

        if(!$folioReal){

            return $this->obtenerUsuarioPaseAFolio($distrito);

        }

        if($distrito != 2 && $solicitante == 'Oficialia de partes'){

            $idActual = Asignacion::first()?->certificado_gravamen_uruapan;

            if($tipo_servicio == 'ordinario')

                $certificadores = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Oficialia');
                                        })
                                        ->pluck('id');
            else

                $certificadores = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Juridico');
                                        })
                                        ->pluck('id');

        }else{

            $idActual = Asignacion::first()?->certificado_gravamen;

            $certificadores = User::where('status', 'activo')
                                    ->when($distrito == 2, function($q){
                                        $q->where('ubicacion', 'Regional 4');
                                    })
                                    ->when($distrito != 2, function($q){
                                        $q->where('ubicacion', '!=', 'Regional 4');
                                    })
                                    ->whereHas('roles', function($q) {
                                        $q->where('name', 'Certificador Gravamen');
                                    })
                                    ->pluck('id');

        }

        if(count($certificadores) == 0){

            Log::error('No se encontraron usuario para asignar la certificación.');

            throw new AsignacionServiceException('No se encontraron certificadores para asignar al movimiento registral.');

        }else if($random){

            return array_rand($certificadores, 1);

        }else if(count($certificadores) == 1){

            return $certificadores[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($certificadores, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['certificado_gravamen_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['certificado_gravamen' => $actual]);

            }

            return $actual;

        }

    }

    public function obtenerCertificadorPropiedad($distrito, $solicitante, $tipo_servicio, $random, $folioReal):int
    {

        if($distrito == 2){

            $idActual = Asignacion::first()?->certificado_propiedad_uruapan;

        }else{

            $idActual = Asignacion::first()?->certificado_propiedad;

        }

        $certificadores = User::where('status', 'activo')
                                    ->when($distrito == 2, function($q){
                                        $q->where('ubicacion', 'Regional 4');
                                    })
                                    ->when($distrito != 2, function($q){
                                        $q->where('ubicacion', '!=', 'Regional 4');
                                    })
                                    ->whereHas('roles', function($q) {
                                        $q->where('name', 'Certificador Propiedad');
                                    })
                                    ->pluck('id');

        if(count($certificadores) == 0){

            Log::error('No se encontraron usuario para asignar la certificación.');

            throw new AsignacionServiceException('No se encontraron certificadores para asignar al movimiento registral.');

        }else if($random){

            return array_rand($certificadores, 1);

        }else if(count($certificadores) == 1){

            return $certificadores[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($certificadores, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['certificado_propiedad_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['certificado_propiedad' => $actual]);

            }

            return $actual;

        }

    }

    public function obtenerSupervisorCertificaciones($distrito):int
    {

        if($distrito == 2){

            $supervisor = User::inRandomOrder()
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor uruapan');
                                })
                                ->first();

            return $supervisor->id;

        }

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
    public function obtenerUsuarioPropiedad($folioReal, $distrito, $estado):int
    {

        if($distrito == 2){

            $roles = ['Pase a folio', 'Registrador Propiedad'];

            $idActual = Asignacion::first()?->propiedad_uruapan;

        }else{

            $roles = ['Registrador Propiedad'];

            $idActual = Asignacion::first()?->propiedad;

        }

        $usuarios = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->when(($folioReal === true) || ($folioReal === false && $estado == 'precalificacion'), function($q){
                                $q->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Propiedad', 'Registrador Propiedad']);
                                });
                            })
                            ->when($folioReal === false && $estado != 'precalificacion', function($q) use($roles){
                                $q->whereHas('roles', function($q) use($roles){
                                    $q->whereIn('name', $roles);
                                });
                            })
                            ->pluck('id');

        if(count($usuarios) == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de propiedad para asignar al movimiento registral.');

        }else if(count($usuarios) == 1){

            return $usuarios[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($usuarios, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['propiedad_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['propiedad' => $actual]);

            }

            return $actual;

        }

    }

    /* Inscripciones : Subdivisiones */
    public function obtenerUsuarioSubdivisiones($distrito):int
    {

        if($distrito == 2){

            $idActual = Asignacion::first()?->fraccionador_uruapan;

        }else{

            $idActual = Asignacion::first()?->fraccionador;

        }

        $usuarios = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Registrador fraccionamientos');
                            })
                            ->pluck('id');

        if(count($usuarios) == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de propiedad para asignar al movimiento registral.');

        }else if(count($usuarios) == 1){

            return $usuarios[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($usuarios, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['fraccionador_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['fraccionador' => $actual]);

            }

            return $actual;

        }

    }

    /* Inscripciones : Gravamen */
    public function obtenerUsuarioGravamen($folioReal, $distrito, $estado):int
    {

        if($distrito == 2){

            $roles = ['Pase a folio', 'Registrador Gravamen'];

            $idActual = Asignacion::first()?->gravamen_uruapan;

        }else{

            $roles = ['Registrador Gravamen'];

            $idActual = Asignacion::first()?->gravamen;

        }

        $usuarios = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->when(($folioReal === true) || ($folioReal === false && $estado == 'precalificacion'), function($q){
                                $q->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Gravamen', 'Registrador Gravamen']);
                                });
                            })
                            ->when($folioReal === false && $estado != 'precalificacion', function($q) use($roles){
                                $q->whereHas('roles', function($q) use($roles){
                                    $q->whereIn('name', $roles);
                                });
                            })
                            ->pluck('id');

        if(count($usuarios) == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de gravamen para asignar al movimiento registral.');

        }else if(count($usuarios) == 1){

            return $usuarios[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($usuarios, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['gravamen_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['gravamen' => $actual]);

            }

            return $actual;

        }

    }

    /* Inscripciones : Cancelacion */
    public function obtenerUsuarioCancelacion($folioReal, $distrito, $estado):int
    {

        if($distrito == 2){

            $idActual = Asignacion::first()?->cancelacion_uruapan;

        }else{

            $idActual = Asignacion::first()?->cancelacion;

        }

        $usuarios = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->when(($folioReal === true) || ($folioReal === false && $estado == 'precalificacion'), function($q){
                                $q->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Cancelación', 'Registrador Cancelación']);
                                });
                            })
                            ->when($folioReal === false && $estado != 'precalificacion', function($q){
                                $q->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Registrador Cancelación']);
                                });
                            })
                            ->pluck('id');

        if(count($usuarios) == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de cancelación para asignar al movimiento registral.');

        }else if(count($usuarios) == 1){

            return $usuarios[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($usuarios, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['cancelacion_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['cancelacion' => $actual]);

            }

            return $actual;

        }

    }

    /* Inscripciones : Varios */
    public function obtenerUsuarioVarios($folioReal, $distrito, $estado):int
    {

        if($distrito == 2){

            $roles = ['Pase a folio', 'Registrador Varios'];

            $idActual = Asignacion::first()?->varios_uruapan;

        }else{

            $roles = ['Registrador Varios'];

            $idActual = Asignacion::first()?->varios;

        }

        $usuarios = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->when(($folioReal === true) || ($folioReal === false && $estado == 'precalificacion'), function($q){
                                $q->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Varios', 'Registrador Varios']);
                                });
                            })
                            ->when($folioReal === false && $estado != 'precalificacion', function($q) use($roles){
                                $q->whereHas('roles', function($q) use($roles){
                                    $q->whereIn('name', $roles);
                                });
                            })
                            ->pluck('id');

        if(count($usuarios) == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de varios para asignar al movimiento registral.');

        }else if(count($usuarios) == 1){

            return $usuarios[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($usuarios, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['varios_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['varios' => $actual]);

            }

            return $actual;

        }

    }

    /* Inscripciones : Sentencias */
    public function obtenerUsuarioSentencias($folioReal, $distrito, $estado):int
    {

        if($distrito == 2){

            $roles = ['Pase a folio', 'Registrador Sentencias'];

            $idActual = Asignacion::first()?->sentencia_uruapan;

        }else{

            $roles = ['Registrador Sentencias'];

            $idActual = Asignacion::first()?->sentencia;

        }


        $usuarios = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->when(($folioReal === true) || ($folioReal === false && $estado == 'precalificacion'), function($q){
                                $q->whereHas('roles', function($q){
                                    $q->whereIn('name', ['Sentencias', 'Registrador Sentencias']);
                                });
                            })
                            ->when($folioReal === false && $estado != 'precalificacion', function($q) use($roles){
                                $q->whereHas('roles', function($q) use($roles){
                                    $q->whereIn('name', $roles);
                                });
                            })
                            ->pluck('id');

        if(count($usuarios) == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de sentencias para asignar al movimiento registral.');

        }else if(count($usuarios) == 1){

            return $usuarios[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($usuarios, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['sentencia_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['sentencia' => $actual]);

            }

            return $actual;

        }

    }

    public function obtenerSupervisorInscripciones($distrito):int
    {

        if($distrito == 2){

            $supervisor = User::inRandomOrder()
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor uruapan');
                                })
                                ->first();

            return $supervisor->id;

        }

        $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor inscripciones');
                                })
                                ->first();

        if(!$supervisor){

            throw new AsignacionServiceException('No se encontraron supervisores de inscripciones para asignar al movimiento registral.');

        }

        return $supervisor->id;

    }

    public function obtenerUsuarioFolioRealMoral($distrito):int
    {

        if($distrito == 2){

            $idActual = Asignacion::first()?->folio_real_moral_uruapan;

        }else{

            $idActual = Asignacion::first()?->folio_real_moral;

        }


        $usuarios = User::where('status', 'activo')
                            ->when($distrito == 2, function($q){
                                $q->where('ubicacion', 'Regional 4');
                            })
                            ->when($distrito != 2, function($q){
                                $q->where('ubicacion', '!=', 'Regional 4');
                            })
                            ->whereHas('roles', function($q){
                                    $q->where('name', 'Folio real moral');
                            })
                            ->pluck('id');

        if(count($usuarios) == 0){

            throw new AsignacionServiceException('No se encontraron usuarios de folio real de persona moral para asignar al movimiento registral.');

        }else if(count($usuarios) == 1){

            return $usuarios[0];

        }else{

            $actual = $this->obtenerSiguienteUsuario($usuarios, $idActual);

            if($distrito == 2){

                Asignacion::first()->update(['folio_real_moral_uruapan' => $actual]);

            }else{

                Asignacion::first()->update(['folio_real_moral' => $actual]);

            }

            return $actual;

        }

    }

}
