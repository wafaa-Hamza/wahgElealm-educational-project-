<?php

namespace App\Http\Middleware;

use App\Models\UserIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckIPCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // dd('ff');
        $user = Auth::user();

        $ipAddress = $request->ip();

        if ($user) {
            $userIps = UserIp::where('user_id', $user->id)->pluck('ip_address')->toArray();
            if (!in_array($ipAddress, $userIps)) {
                if (count($userIps) >= 3) {
                    $response = response()->json(['error' => 'You have exceeded the maximum number of allowed IP addresses.'], 403);
                    Log::info('Exceeded IP limit', ['response' => $response]);
                    return $next($request);
                }

                // إضافة عنوان الـ IP الجديد للمستخدم
                UserIp::create([
                    'user_id' => $user->id,
                    'ip_address' => $ipAddress,
                ]);
            }
        }

        $response = $next($request);
        Log::info('Passing through middleware', ['response' => $next($request)]);

        return $next($request);
    }

}
