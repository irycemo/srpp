<?php

return [
    'slow_request_ms' => (int) env('DIAGNOSTICS_SLOW_REQUEST_MS', 2000),
    'slow_query_ms' => (int) env('DIAGNOSTICS_SLOW_QUERY_MS', 500),
    'slow_livewire_action_ms' => (int) env('DIAGNOSTICS_SLOW_LIVEWIRE_ACTION_MS', 1000),
    'slow_job_ms' => (int) env('DIAGNOSTICS_SLOW_JOB_MS', 5000),
    'log_days' => (int) env('DIAGNOSTICS_LOG_DAYS', 30),
    'channel' => env('DIAGNOSTICS_LOG_CHANNEL', 'diagnostics'),
];
