<?php

namespace App\Policies;

use App\Models\MovimientoRegistral;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MovimientoRegistralPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MovimientoRegistral $movimientoRegistral): Response
    {
        if ($user->hasRole('Administrador')) {
            return Response::allow();
        }

        return $user->id === $movimientoRegistral->usuario_asignado
                ? Response::allow()
                : Response::deny('No tienes asignado el movimineto registral.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MovimientoRegistral $movimientoRegistral): Response
    {

        if ($user->hasRole('Administrador')) {
            return Response::allow();
        }

        return $user->id === $movimientoRegistral->usuario_asignado
                ? Response::allow()
                : Response::deny('No tienes asignado el movimineto registral.');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MovimientoRegistral $movimientoRegistral): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MovimientoRegistral $movimientoRegistral): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MovimientoRegistral $movimientoRegistral): bool
    {
        //
    }
}
