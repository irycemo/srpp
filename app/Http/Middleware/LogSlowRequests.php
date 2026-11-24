<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LogSlowRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $this->requestId($request->header('X-Request-ID'));
        $request->attributes->set('srpp_diagnostics', [
            'request_id' => $requestId,
            'query_count' => 0,
            'database_time_ms' => 0.0,
            'livewire_actions' => [],
            'active_livewire_action' => null,
        ]);

        $startedAt = hrtime(true);
        $response = null;
        $exception = null;

        try {
            $response = $next($request);

            return $response;
        } catch (Throwable $th) {
            $exception = $th;

            throw $th;
        } finally {
            $durationMs = (hrtime(true) - $startedAt) / 1_000_000;
            $status = $response?->getStatusCode() ?? ($exception ? 500 : 200);
            $diagnostics = $request->attributes->get('srpp_diagnostics', []);

            if ($exception && isset($diagnostics['active_livewire_action'])) {
                $activeAction = $diagnostics['active_livewire_action'];
                $actionDurationMs = (hrtime(true) - $activeAction['started_at']) / 1_000_000;
                $index = $activeAction['index'];
                $diagnostics['livewire_actions'][$index]['duration_ms'] = round($actionDurationMs, 2);
                $diagnostics['livewire_actions'][$index]['result'] = 'exception';
            }

            unset($diagnostics['active_livewire_action']);

            if ($response) {
                $response->headers->set('X-Request-ID', $requestId);
            }

            if (
                $durationMs >= config('diagnostics.slow_request_ms', 2000)
                || $status >= 500
            ) {
                $route = $request->route();

                Log::channel(config('diagnostics.channel', 'diagnostics'))->warning('slow_or_failed_request', [
                    'request_id' => $requestId,
                    'amzn_trace_id' => $this->safeHeader($request->header('X-Amzn-Trace-Id')),
                    'method' => $request->method(),
                    'path' => $route?->uri() ?? $request->path(),
                    'route_name' => $route?->getName(),
                    'route_action' => $route?->getActionName(),
                    'status' => $status,
                    'duration_ms' => round($durationMs, 2),
                    'query_count' => $diagnostics['query_count'] ?? 0,
                    'database_time_ms' => round($diagnostics['database_time_ms'] ?? 0, 2),
                    'memory_peak_bytes' => memory_get_peak_usage(true),
                    'user_id' => $request->user()?->getAuthIdentifier(),
                    'livewire_actions' => $diagnostics['livewire_actions'] ?? [],
                    'exception_class' => $exception ? $exception::class : null,
                ]);
            }
        }
    }

    private function requestId(?string $incoming): string
    {
        if ($incoming && preg_match('/^[A-Za-z0-9._-]{1,64}$/', $incoming)) {
            return $incoming;
        }

        return (string) Str::uuid();
    }

    private function safeHeader(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return preg_replace('/[^A-Za-z0-9=;:._-]/', '', substr($value, 0, 128));
    }
}
