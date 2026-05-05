<?php

namespace App\Jobs\Fraccionamientos;

use App\Jobs\Fraccionamientos\FraccionamientoJob;
use App\Models\Import;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class DispatchFraccionamientoChain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $batchId, public int $movimiento_id)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $jobs = [];

        Import::where('batch_id', $this->batchId)
            ->where('status', 'pending')
            ->orderBy('row_number')
            ->chunk(500, function ($rows) use (&$jobs) {

                foreach ($rows as $row) {

                    $data = json_decode($row->data, true);

                    $jobs[] = new FraccionamientoJob(
                        $row->id,
                        $data,
                        $this->movimiento_id
                    );

                }
            });

        if (!empty($jobs)) {

            Bus::chain($jobs)->dispatch();

        }

    }
}
