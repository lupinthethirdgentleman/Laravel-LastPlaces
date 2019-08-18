<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Auth;
use Redirect;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
		Route::filter('AuthAdmin', function()
		{
			if (Auth::guest())
			{
				return Redirect::to('/admin/login');
			}
		});

		Route::filter('AuthFront', function()
		{

			
		});

		Route::filter('GuestFront', function()
		{

			
		});

		Route::filter('GuestAdmin', function()
		{
			
		});

		Route::filter('csrf', function()
		{
			if (Session::token() !== Input::get('_token'))
			{
				throw new Illuminate\Session\TokenMismatchException;
			}
		});

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
