<?php

namespace Codescheme\Postcodes;

use Codescheme\Postcodes\Classes\Postcode;
use Illuminate\Support\ServiceProvider;

class PostcodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('postcode', function () {
            return new Postcode();
        });
    }
}
