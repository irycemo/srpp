<?php

namespace App\Policies;

use App\Models\MovimientoRegistral;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MovimientoRegistralPolicy
{

    public function view(User $user, MovimientoRegistral $movimientoRegistral): Response
    {
        if ($user->hasRole('Administrador')) {
            return Response::allow();
        }

        return $user->id === $movimientoRegistral->usuario_asignado
                ? Response::allow()
                : Response::deny('No tienes asignado el movimineto registral.');
    }

    public function update(User $user, MovimientoRegistral $movimientoRegistral): Response
    {

        if ($user->hasRole('Administrador')) {
            return Response::allow();
        }

        return $user->id === $movimientoRegistral->usuario_asignado
                ? Response::allow()
                : Response::deny('No tienes asignado el movimineto registral.');

    }

}
