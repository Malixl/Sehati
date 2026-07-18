<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Models\Device;
use Illuminate\Support\Facades\Cookie;

class DeviceTracker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('device_token');

        if (!$token) {
            $token = Str::uuid()->toString();
            Device::create([
                'token' => $token,
                'last_used_at' => now(),
            ]);
            Cookie::queue('device_token', $token, 60 * 24 * 365); // 1 year
        } else {
            $device = Device::where('token', $token)->first();
            if ($device) {
                $device->update(['last_used_at' => now()]);
            } else {
                Device::create([
                    'token' => $token,
                    'last_used_at' => now(),
                ]);
            }
        }

        $request->merge(['device_token' => $token]);

        return $next($request);
    }
}
