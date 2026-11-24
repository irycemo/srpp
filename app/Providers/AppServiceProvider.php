<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Model::shouldBeStrict();

        DB::listen(function (QueryExecuted $query): void {
            $request = app(Request::class);
            $diagnostics = $request->attributes->get('srpp_diagnostics');

            if (! is_array($diagnostics)) {
                return;
            }

            $diagnostics['query_count']++;
            $diagnostics['database_time_ms'] += $query->time;
            $request->attributes->set('srpp_diagnostics', $diagnostics);

            if ($query->time < config('diagnostics.slow_query_ms', 500)) {
                return;
            }

            Log::channel(config('diagnostics.channel', 'diagnostics'))->warning('slow_database_query', [
                'request_id' => $diagnostics['request_id'],
                'route_name' => $request->route()?->getName(),
                'route_action' => $request->route()?->getActionName(),
                'livewire_action' => isset($diagnostics['active_livewire_action'])
                    ? [
                        'component' => $diagnostics['active_livewire_action']['component'],
                        'method' => $diagnostics['active_livewire_action']['method'],
                    ]
                    : null,
                'connection' => $query->connectionName,
                'duration_ms' => $query->time,
                'sql' => mb_substr($query->sql, 0, 2000),
            ]);
        });

        Livewire::listen('call', function ($component, string $method, array $params, $context, $returnEarly) {
            $request = app(Request::class);
            $diagnostics = $request->attributes->get('srpp_diagnostics');

            if (! is_array($diagnostics)) {
                return;
            }

            $index = count($diagnostics['livewire_actions']);
            $startedAt = hrtime(true);
            $action = [
                'component' => $component->getName(),
                'method' => $method,
            ];

            $diagnostics['livewire_actions'][] = $action;
            $diagnostics['active_livewire_action'] = $action + [
                'index' => $index,
                'started_at' => $startedAt,
            ];
            $request->attributes->set('srpp_diagnostics', $diagnostics);

            return function ($result) use ($request, $index, $action, $startedAt) {
                $durationMs = (hrtime(true) - $startedAt) / 1_000_000;
                $diagnostics = $request->attributes->get('srpp_diagnostics', []);
                $diagnostics['livewire_actions'][$index]['duration_ms'] = round($durationMs, 2);
                $diagnostics['livewire_actions'][$index]['result'] = 'completed';
                $diagnostics['active_livewire_action'] = null;
                $request->attributes->set('srpp_diagnostics', $diagnostics);

                if ($durationMs >= config('diagnostics.slow_livewire_action_ms', 1000)) {
                    Log::channel(config('diagnostics.channel', 'diagnostics'))->warning('slow_livewire_action', [
                        'request_id' => $diagnostics['request_id'],
                        'component' => $action['component'],
                        'method' => $action['method'],
                        'duration_ms' => round($durationMs, 2),
                    ]);
                }

                return $result;
            };
        });

        $jobStartedAt = [];
        $jobKey = fn ($event): string => (string) ($event->job->uuid() ?: $event->job->getJobId());

        Queue::before(function (JobProcessing $event) use (&$jobStartedAt, $jobKey): void {
            $jobStartedAt[$jobKey($event)] = hrtime(true);
        });

        Queue::after(function (JobProcessed $event) use (&$jobStartedAt, $jobKey): void {
            $key = $jobKey($event);
            $startedAt = $jobStartedAt[$key] ?? null;
            unset($jobStartedAt[$key]);

            if ($startedAt === null) {
                return;
            }

            $durationMs = (hrtime(true) - $startedAt) / 1_000_000;

            if ($durationMs >= config('diagnostics.slow_job_ms', 5000)) {
                Log::channel(config('diagnostics.channel', 'diagnostics'))->warning('slow_queue_job', [
                    'job' => $event->job->resolveName(),
                    'job_id' => $event->job->getJobId(),
                    'connection' => $event->connectionName,
                    'duration_ms' => round($durationMs, 2),
                    'attempts' => $event->job->attempts(),
                ]);
            }
        });

        Queue::exceptionOccurred(function (JobExceptionOccurred $event) use (&$jobStartedAt, $jobKey): void {
            $key = $jobKey($event);
            $startedAt = $jobStartedAt[$key] ?? null;
            unset($jobStartedAt[$key]);

            Log::channel(config('diagnostics.channel', 'diagnostics'))->warning('queue_job_exception', [
                'job' => $event->job->resolveName(),
                'job_id' => $event->job->getJobId(),
                'connection' => $event->connectionName,
                'duration_ms' => $startedAt === null
                    ? null
                    : round((hrtime(true) - $startedAt) / 1_000_000, 2),
                'attempts' => $event->job->attempts(),
                'exception_class' => $event->exception::class,
            ]);
        });

        if(app()->isProduction()){

            URL::forceScheme('https');

            Livewire::setScriptRoute(function ($handle) {
                return Route::get('/srpp/public/vendor/livewire/livewire.js', $handle);
            });

            Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/srpp/livewire/update', $handle);
            });

        }elseif(app()->environment('staging')){

            Livewire::setScriptRoute(function ($handle) {
                return Route::get('/srpp/public/vendor/livewire/livewire.js', $handle);
            });

            Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/srpp/livewire/update', $handle);
            });

        }

    }
}
