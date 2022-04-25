<?php

namespace App\Http\Middleware;

use App\Traits\credientials;
use Closure;
use Illuminate\Http\Request;

class setData
{
    use credientials;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (\Auth::check())
        {
            $this->setCredientials();
          //  return $next($request);
        }
        return $next($request);

    }
}
