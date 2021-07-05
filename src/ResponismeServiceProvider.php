<?php

namespace Att\Responisme;

use Att\Responisme\Middleware\ValidateUser;
use Att\Responisme\Middleware\ValidateUserPermissions;
use Att\Responisme\Middleware\ValidateUserRoles;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ResponismeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {    
        $this->registerHelpers();

        $this->registerMiddleware();
    }

    protected function registerHelpers()
    {
        if (file_exists($file = __DIR__.'/Support/Helpers.php')) {
            require_once $file;
        }        
    }
    
    protected function registerMiddleware()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('permission.user', ValidateUserPermissions::class);
        $router->aliasMiddleware('role.user', ValidateUserRoles::class);
        $router->aliasMiddleware('with.user', ValidateUser::class);        
    }
}
