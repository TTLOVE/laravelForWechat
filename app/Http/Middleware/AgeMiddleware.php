<?php

namespace App\Http\Middleware;

use Closure;

class AgeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $age, $gender)
    {
        if ( $request->input('age')<$age || $request->input('gender')!=$gender) {
            return redirect()->route('AgeRefuce');
        }
        return $next($request);
    }
}
