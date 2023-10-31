<?php

namespace App\Console\Commands;

use App\Models\Certificacion;
use Illuminate\Console\Command;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

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

        $asignacionService = new AsignacionService();

        try {

            $tramites = [];

            $ids = Certificacion::whereHas('movimientoRegistral', function($q){
                                                                                $q->where('estado', 'nuevo')
                                                                                    ->where('fecha_entrega', '<=', now()->toDateString());
                                                                            })
                                                                            ->pluck('movimiento_registral_id');


            foreach($ids as $id){

                $movimientoRegistral = MovimientoRegistral::findOrFail($id);

                $nuevoUsuario = (new MovimientoRegistralController($asignacionService))->obtenerUsuarioAsignado(
                    $movimientoRegistral->certificacion->servicio,
                    $movimientoRegistral->getRawOriginal('distrito'),
                    $movimientoRegistral->solicitante,
                    $movimientoRegistral->tipo_servicio,
                    false
                );

                while($nuevoUsuario == $movimientoRegistral->usuario_asignado){

                    $nuevoUsuario = (new MovimientoRegistralController($asignacionService))->obtenerUsuarioAsignado(
                                                                                                $movimientoRegistral->certificacion->servicio,
                                                                                                $movimientoRegistral->getRawOriginal('distrito'),
                                                                                                $movimientoRegistral->solicitante,
                                                                                                $movimientoRegistral->tipo_servicio,
                                                                                                true
                                                                                            );

                }

                if($nuevoUsuario != $movimientoRegistral->usuario_asignado){

                    $movimientoRegistral->update(['usuario_asignado' => $nuevoUsuario]);

                    array_push($tramites, $movimientoRegistral->tramite);

                }

                info('Tramites reasignados: ' . $tramites);

            }

        } catch (\Throwable $th) {
            Log::error("Error al reasignar trámites. " . $th);
        }

    }
}
