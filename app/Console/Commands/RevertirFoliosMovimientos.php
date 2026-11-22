<?php

namespace App\Console\Commands;

use App\Models\Audit;
use App\Models\MovimientoRegistral;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RevertirFoliosMovimientos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movimientos:revertir-folios
                            {--from=2026-10-29 00:00:00 : Fecha/hora inicial del incidente}
                            {--to=2026-10-29 23:59:59 : Fecha/hora final del incidente}
                            {--user=1 : ID del usuario que ejecuto el cambio masivo}
                            {--fingerprint : Solo movimientos con la huella del reacomodo masivo (folio 2 -> 1 + estado no recibido)}
                            {--revert-estado : Restaurar tambien el estado}
                            {--force : Revertir aunque el valor actual ya no coincida con el ultimo valor del incidente}
                            {--chunk=500 : Registros por transaccion}
                            {--apply : Ejecutar los cambios (sin esta bandera solo simula)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revierte el folio (y opcionalmente el estado) de los movimientos registrales a partir de los audits';

    public function handle(): int
    {
        $from = $this->option('from');
        $to = $this->option('to');
        $userId = $this->option('user');
        $apply = (bool) $this->option('apply');
        $fingerprint = (bool) $this->option('fingerprint');
        $revertEstado = (bool) $this->option('revert-estado');
        $force = (bool) $this->option('force');
        $chunkSize = max(1, (int) $this->option('chunk'));

        $this->info($apply ? 'MODO APLICAR' : 'MODO SIMULACION (use --apply para escribir)');
        $this->line("Ventana: {$from} a {$to} | user_id: " . ($userId ?: 'todos'));

        $audits = Audit::query()
                        ->where('auditable_type', MovimientoRegistral::class)
                        ->whereBetween('created_at', [$from, $to])
                        ->when($userId, fn ($q, $id) => $q->where('user_id', $id))
                        ->orderBy('id')
                        ->get();

        if ($audits->isEmpty()) {

            $this->warn('No se encontraron audits en la ventana indicada.');

            return self::SUCCESS;

        }

        $this->line("Audits encontrados: {$audits->count()}");

        $grupos = $audits->groupBy('auditable_id');

        $backupPath = storage_path(
            'app/folio_revert_' . now()->format('Ymd_His') . ($apply ? '' : '_simulacion') . '.csv'
        );

        $backup = fopen($backupPath, 'w');

        fputcsv($backup, [
            'movimiento_id',
            'folio_actual',
            'folio_original',
            'folio_ultimo_audit',
            'estado_actual',
            'estado_original',
            'accion',
        ]);

        $cambios = [];
        $omitidos = 0;
        $sinCambio = 0;
        $procesados = 0;

        foreach ($grupos as $movimientoId => $auditsMovimiento) {

            $procesados++;

            $movimiento = MovimientoRegistral::find($movimientoId);

            if (! $movimiento) {

                $omitidos++;

                continue;

            }

            $oldValues = $auditsMovimiento->map(fn ($audit) => $this->decodificar($audit->old_values));
            $newValues = $auditsMovimiento->map(fn ($audit) => $this->decodificar($audit->new_values));

            if ($fingerprint && ! $this->tieneHuellaMasiva($oldValues, $newValues)) {

                $omitidos++;

                continue;

            }

            $primerFolio = $oldValues->first(fn ($values) => array_key_exists('folio', $values));

            if (! $primerFolio || ! is_numeric($primerFolio['folio'])) {

                $omitidos++;

                continue;

            }

            $folioOriginal = (int) $primerFolio['folio'];

            $primerEstado = $oldValues->first(fn ($values) => array_key_exists('estado', $values));

            $estadoOriginal = $primerEstado['estado'] ?? null;

            $ultimoFolio = $newValues->last(fn ($values) => array_key_exists('folio', $values))['folio'] ?? null;

            if (! $force && $ultimoFolio !== null && (int) $ultimoFolio !== (int) $movimiento->folio) {

                $this->line(
                    "OMITIDO movimiento {$movimientoId}: folio actual {$movimiento->folio} no coincide con el ultimo valor del incidente {$ultimoFolio}"
                );

                $omitidos++;

                fputcsv($backup, [
                    $movimientoId,
                    $movimiento->folio,
                    $folioOriginal,
                    $ultimoFolio,
                    $movimiento->estado,
                    $estadoOriginal,
                    'omitido: cambio posterior al incidente',
                ]);

                continue;

            }

            $datos = ['folio' => $folioOriginal];

            if ($revertEstado && $estadoOriginal !== null) {

                $datos['estado'] = $estadoOriginal;

            }

            $cambioFolio = (int) $movimiento->folio !== $folioOriginal;
            $cambioEstado = isset($datos['estado']) && $movimiento->estado !== $datos['estado'];

            if (! $cambioFolio && ! $cambioEstado) {

                $sinCambio++;

                continue;

            }

            $cambios[] = [
                'id' => $movimientoId,
                'datos' => $datos,
            ];

            fputcsv($backup, [
                $movimientoId,
                $movimiento->folio,
                $folioOriginal,
                $ultimoFolio,
                $movimiento->estado,
                $estadoOriginal,
                $apply ? 'revertido' : 'simulado',
            ]);

        }

        fclose($backup);

        $this->newLine();
        $this->table(
            ['Movimientos revisados', 'A revertir', 'Omitidos', 'Sin cambio', 'Backup'],
            [[$procesados, count($cambios), $omitidos, $sinCambio, $backupPath]]
        );

        if (! $apply) {

            $this->warn('Simulacion terminada. Revisa el CSV y vuelve a ejecutar con --apply.');

            return self::SUCCESS;

        }

        if (empty($cambios)) {

            $this->info('No hay cambios por aplicar.');

            return self::SUCCESS;

        }

        $aplicados = 0;

        foreach (array_chunk($cambios, $chunkSize) as $chunk) {

            DB::transaction(function () use ($chunk, &$aplicados) {

                foreach ($chunk as $cambio) {

                    MovimientoRegistral::whereKey($cambio['id'])->update($cambio['datos']);

                    $aplicados++;

                }

            });

        }

        Log::warning('Reversion de folios de movimientos registrales ejecutada.', [
            'from' => $from,
            'to' => $to,
            'user_id' => $userId,
            'aplicados' => $aplicados,
            'backup' => $backupPath,
        ]);

        $this->info("Movimientos revertidos: {$aplicados}");
        $this->line("Backup: {$backupPath}");

        return self::SUCCESS;

    }

    private function decodificar($valor): array
    {
        if (is_array($valor)) {

            return $valor;

        }

        if (! $valor) {

            return [];

        }

        $decodificado = json_decode($valor, true);

        return is_array($decodificado) ? $decodificado : [];

    }

    private function tieneHuellaMasiva($oldValues, $newValues): bool
    {
        foreach ($oldValues as $indice => $old) {

            $new = $newValues[$indice] ?? [];

            if (($old['folio'] ?? null) == 2 && ($new['estado'] ?? null) === 'no recibido') {

                return true;

            }

        }

        return false;

    }
}
