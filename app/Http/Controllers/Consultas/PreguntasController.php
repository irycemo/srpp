<?php

namespace App\Http\Controllers\Consultas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PreguntasController extends Controller
{

    public function storeImage(Request $request)
    {

        if ($request->hasFile('upload')) {

            try {

                $fileName = $request->file('upload')->store('/', 'preguntas');

                $url = Storage::disk('preguntas')->url($fileName);

                return response()->json(['uploaded' => true, 'url' => $url]);

            }catch (\Exception $e) {
                return response()->json(
                    [
                        'uploaded' => false,
                        'error'    => [
                            'message' => $e->getMessage()
                        ]
                    ]
                );
            }

        }

    }

}
