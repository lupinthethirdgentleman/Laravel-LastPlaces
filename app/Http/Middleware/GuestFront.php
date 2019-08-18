<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;

class GuestFront 
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
        if(Auth::check()){

            if(Auth::user()->user_role_id==1){

                return Redirect::route('admin_dashboard');
            }
        }
        return $next($request);
    }
}
