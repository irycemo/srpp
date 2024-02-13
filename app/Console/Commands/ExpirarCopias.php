<?php

namespace App\Console\Commands;

use App\Models\Certificacion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpirarCopias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expirar-copias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {

            $certificaciones = Certificacion::whereHas('movimientoRegistral', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'rechazado'])
                                                                    ->whereDate('fecha_pago', '>', now()->subMonth()->format('Y-m-d'));
                                                            })
                                                            ->whereIn('servicio', ['DL14', 'DL13'])
                                                            ->get();

            foreach ($certificaciones as $certificado) {

                $certificado->movimientoRegistral->update(['estado' => 'expirado']);

            }

        } catch (\Throwable $th) {
            Log::error("Error al concluir trámites de consulta en tarea programada. " . $th);
        }

    }

}
