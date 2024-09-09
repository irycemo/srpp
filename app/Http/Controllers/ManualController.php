<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualController extends Controller
{
    public function __invoke()
    {
        return view('manual');
    }
}
