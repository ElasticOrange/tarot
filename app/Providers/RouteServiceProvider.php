<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Guard;

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
        //
        parent::boot($router);

        $router->model('sites', 'App\Site');
        $router->model('infocosts', 'App\Infocost');
        $router->model('users', 'App\User');
        $router->model('templates', 'App\Template');
        $router->model('clients', 'App\Client');
        $router->model('emails', 'App\Email');
        $router->model('emailboxes', 'App\Emailbox');

        view()->composer(['menu'], function($view) {
            $view->with(['user' => \Auth::user()]);
        });

        view()->composer(['_siteselector', 'template.list'], function($view) {
            $view->with([
                'loggedUserSites' => \Auth::user()->sites,
                'currentSiteId' => \Auth::user()->currentSiteId()
            ]);
        });
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
