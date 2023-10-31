<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstaActivoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(auth()->user()->status == 'inactivo'){

            if(auth()->user()){

                auth()->logout();

            }

            return redirect()->route('login')->with('mensaje', 'Tu cuenta esta bloqueada, contacta al administrador.');

        }

        return $next($request);

    }
}
