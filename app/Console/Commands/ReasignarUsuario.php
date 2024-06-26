<?php

namespace App\Console\Commands;

use App\Models\Certificacion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Services\AsignacionService;

class ReasignarUsuario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reasignar:usuario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarea programada para reasignar certificador a las certificaciones que han llegado a su fecha de elaboración sin atenderse';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {

            $asignacionService = new AsignacionService();

            $certificaciones = Certificacion::with('movimientoRegistral')
                                                ->whereIn('servicio', ['DL14', 'DL13'])
                                                ->whereHas('movimientoRegistral', function($q){
                                                    $q->where('estado', 'nuevo')
                                                        ->whereDate('fecha_entrega', '<', $this->calcularDia());
                                                })
                                                ->get();


            foreach($certificaciones as $certificacion){

                $nuevoUsuario = $asignacionService->obtenerCertificador(
                    $certificacion->movimientoRegistral->getRawOriginal('distrito'),
                    $certificacion->movimientoRegistral->solicitante,
                    $certificacion->movimientoRegistral->tipo_servicio,
                    false
                );

                while($nuevoUsuario == $certificacion->movimientoRegistral->usuario_asignado){

                    $nuevoUsuario = $asignacionService->obtenerCertificador(
                        $certificacion->movimientoRegistral->getRawOriginal('distrito'),
                        $certificacion->movimientoRegistral->solicitante,
                        $certificacion->movimientoRegistral->tipo_servicio,
                        true
                    );

                }

                if($nuevoUsuario != $certificacion->movimientoRegistral->usuario_asignado){

                    $certificacion->movimientoRegistral->update(['usuario_asignado' => $nuevoUsuario]);

                }

            }

            info('Proceso de para reasignar certificador a las certificaciones que han llegado a su fecha de elaboración sin atenderse completado');

        } catch (\Throwable $th) {
            Log::error("Error al reasignar trámites. " . $th);
        }

    }

    public function calcularDia(){

        $fecha = now()->subDays(2);

        while($fecha->isWeekend()){

            $fecha->subDay();

        }

        return $fecha->toDateString();

    }
}
