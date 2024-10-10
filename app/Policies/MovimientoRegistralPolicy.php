<?php

namespace App\Policies;

use App\Models\MovimientoRegistral;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MovimientoRegistralPolicy
{

    public function view(User $user, MovimientoRegistral $movimientoRegistral): Response
    {

        if ($user->hasRole(['Administrador', 'Jefe de departamento inscripciones', 'Jefe de departamento certificaciones'])) {
            return Response::allow();
        }

        return in_array($user->id, [$movimientoRegistral->usuario_asignado, $movimientoRegistral->usuario_supervisor])
                ? Response::allow()
                : Response::deny('No tienes asignado el movimineto registral.');
    }

    public function update(User $user, MovimientoRegistral $movimientoRegistral): Response
    {

        if(!in_array($movimientoRegistral->estado, ['nuevo', 'captura', 'correccion'])){

            return Response::deny('El movimiento registral no puede ser modificado.');

        }

        if ($user->hasRole('Administrador', 'Jefe de departamento inscripciones', 'Jefe de departamento certificaciones')) {
            return Response::allow();
        }

        return in_array($user->id, [$movimientoRegistral->usuario_asignado, $movimientoRegistral->usuario_supervisor])
                ? Response::allow()
                : Response::deny('No tienes asignado el movimineto registral.');

    }

}
