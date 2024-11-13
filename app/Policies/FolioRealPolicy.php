<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FolioReal;
use Illuminate\Auth\Access\Response;

class FolioRealPolicy
{

    public function view(User $user, FolioReal $folioReal): Response
    {

        if ($folioReal->estado == 'activo') {

            return Response::deny('El folio real no puede ser modificado.');

        }else{

            return Response::allow();
        }

    }

}
