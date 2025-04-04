<?php

namespace App\Console\Commands;

use App\Models\Certificacion;
use Illuminate\Console\Command;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

class ExpirarConsultas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expirar:consultas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proceso de para expirar consultas con mas de 30 dias';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {

            $certificaciones = Certificacion::withWhereHas('movimientoRegistral', function($q){
                                                                $q->whereIn('estado', ['nuevo', 'rechazado'])
                                                                    ->whereDate('fecha_pago', '<', $this->calcularDia());
                                                            })
                                                            ->where('servicio', 'DC93')
                                                            ->get();

            foreach ($certificaciones as $certificado) {

                $certificado->movimientoRegistral->update(['estado' => 'expirado']);

            }

            info('Proceso de para expirar consultas con mas de 30 dias completado');

        } catch (\Throwable $th) {
            Log::error("Error al concluir trámites de consulta en tarea programada. " . $th);
        }

    }

    public function calcularDia(){

        $actual = now();

            for ($i = 0; $i < 30; $i++) {

                $actual->subDay();

                while($actual->isWeekend()){

                    $actual->subDay();

                }

            }

            return $actual->format('Y-d-m');

    }

}
