<?php

namespace App\Http\Middleware;

use Closure;

class Check_secret
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
        if($request->header('secret_key') != 'O3CHoxPt2I1r6Z8ksi0RqJlGDu4N9m'){
            return response()->json(['result' => "999", 'message' => "unauthorised request"]);
        }
        return $next($request);
    }
}
