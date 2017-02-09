<?php

namespace Codescheme\Postcodes\Facades;

use Illuminate\Support\Facades\Facade;

class Postcode extends Facade{
    protected static function getFacadeAccessor() { return 'postcode'; }
}