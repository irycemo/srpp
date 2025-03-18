<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __invoke()
    {

        if(auth()->user()->hasRole('Administrador')){



        }if(auth()->user()->hasRole('Jefe de departamento certificaciones')){

        }if(auth()->user()->hasRole('Jefe de departamento inscripciones')){

        }if(auth()->user()->hasRole('Jefe de departamento inscripciones')){
        }

        return view('dashboard');
    }
}
