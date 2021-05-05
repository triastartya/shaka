<?php

namespace Att\Responisme;

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
    }

    protected function registerHelpers()
    {
        if (file_exists($file = __DIR__.'/Support/Helpers.php')) {
            require_once $file;
        }
    }
}
