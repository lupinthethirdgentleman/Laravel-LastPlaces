<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;

class AuthAdmin 
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		if (Auth::guest())
		{
			return Redirect::to('/admin/login');
		}
        return $next($request);
    }
}
