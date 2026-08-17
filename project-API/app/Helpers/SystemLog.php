<?php

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('system_log')) {
    function system_log(
        string $action,
        ?string $model = null,
        ?int $modelId = null,
        ?string $description = null,
        ?array $data = null,
    ) {
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}