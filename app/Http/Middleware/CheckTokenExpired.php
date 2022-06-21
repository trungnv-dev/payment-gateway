<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckTokenExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty($request->user())) {
            return response()->json([
                'error' => true,
                'msg'   => 'Token time has expired. Please log in again.'
            ]);
        } else {
            $isExpired = Carbon::now()->greaterThan($request->user()->token()->expires_at);
            if ($isExpired) {
                return response()->json([
                    'error' => true,
                    'msg'   => 'Token time has expired. Please log in again.'
                ]);
            }
        }
        return $next($request);
    }
}
