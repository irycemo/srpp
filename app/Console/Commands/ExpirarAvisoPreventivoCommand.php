<?php

namespace App\Console\Commands;

use App\Models\Vario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpirarAvisoPreventivoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expirar:avisos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proceso para exiprar avisos preventivos cuya fecha de inscripción ha superado 30 dias para primer aviso y 90 dias para segundo aviso.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {

            $primerAvisos = Vario::where('acto_contenido', 'PRIMER AVISO PREVENTIVO')
                                    ->where('estado', 'activo')
                                    ->whereDate('fecha_inscripcion', '<', now()->subDays(30)->format('Y-m-d'))
                                    ->get();

            foreach ($primerAvisos as $aviso) {

                $aviso->update(['estado' => 'expirado']);

            }

            $sgundoAvisos = Vario::where('acto_contenido', 'SEGUNDO AVISO PREVENTIVO')
                                    ->where('estado', 'activo')
                                    ->whereDate('fecha_inscripcion', '<', now()->subDays(90)->format('Y-m-d'))
                                    ->get();

            foreach ($sgundoAvisos as $aviso) {

                $aviso->update(['estado' => 'expirado']);

            }

            info('Proceso de para expirar avisos preventivos completado');

        } catch (\Throwable $th) {
            Log::error("Error al concluir avisos preventivos en tarea programada. " . $th);
        }

    }
}
