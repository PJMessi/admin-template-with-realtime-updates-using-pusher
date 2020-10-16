<?php

namespace App\Http\Middleware\API;

use Closure;

class APILogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge([
            'grant_type' => 'password',
            'client_id' => env("CLIENT_ID"),
            'client_secret' => env('CLIENT_SECRET'),
            'scope' => ''
        ]);

        return $next($request);
    }
}
