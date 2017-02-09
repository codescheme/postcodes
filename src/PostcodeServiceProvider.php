<?php

namespace Codescheme\Postcodes;

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
     * @return object
     */
    public function register()
    {
         \App::bind('postcode', function()
        {
            return new Classes\Postcode;
        });
    }
}
