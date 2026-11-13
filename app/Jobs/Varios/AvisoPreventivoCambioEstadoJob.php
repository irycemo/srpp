<?php

namespace App\Jobs\Varios;

use Illuminate\Bus\Queueable;
use App\Http\Services\SistemaTramitesLineaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AvisoPreventivoCambioEstadoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $movimiento_registral_id)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        (new SistemaTramitesLineaService())->actualizarAvisoPreventivo($this->movimiento_registral_id);

    }
}
