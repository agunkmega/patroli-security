<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $module = $request->segment(2) ?? 'general';

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => $request->method() . ' ' . $request->path(),
                'description' => $request->method() . ' on ' . $request->path(),
                'module' => $module,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'old_data' => $request->method() === 'PUT' || $request->method() === 'PATCH' ? json_encode($request->all()) : null,
                'new_data' => $request->method() === 'POST' ? json_encode($request->all()) : null,
            ]);
        }

        return $response;
    }
}
