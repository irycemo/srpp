<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SetPasswordController extends Controller
{

    public function create(Request $request){

        $email = $request->email;

        return view('auth.setpassword', compact('email'));
    }

    public function store(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|string|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user)
            return redirect()->route('login')->with('mensaje', 'El correo proporcionado no se encuentra registrado.');

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        auth()->login($user);

        return redirect()->route('dashboard');

    }

}
