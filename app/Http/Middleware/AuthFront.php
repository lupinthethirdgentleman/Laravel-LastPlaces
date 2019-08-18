<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;
Use Session;
Use URL;
class AuthFront 
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
		if (Auth::guest()){
            $url =   \Request::url();
            Session::put('backUrl', $url);
			return Redirect::to('login');	
		}
        
        return $next($request);
    }
}
