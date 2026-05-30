<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGPSRadius
{
    private const EARTH_RADIUS = 6371000;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has(['latitude', 'longitude', 'target_lat', 'target_lng'])) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $request->target_lat,
                $request->target_lng
            );

            $maxRadius = config('patrol.radius_meters', 30);
            $request->merge(['gps_distance' => round($distance, 2)]);

            if ($distance > $maxRadius) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda berada di luar radius checkpoint. Jarak: " . round($distance, 1) . "m (maks: {$maxRadius}m)",
                    'distance' => round($distance, 2),
                ], 422);
            }
        }

        return $next($request);
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2): float
    {
        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));

        return $angle * self::EARTH_RADIUS;
    }
}
