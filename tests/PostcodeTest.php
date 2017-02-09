<?php

namespace Codescheme\Postcodes\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostcodeTest extends TestCase
{
    /**
     * Todo...
     *
     * @return void
     */
		protected function getPostcodes()
    {
        return $this->getMockBuilder('Codescheme\Postcodes\Classes\Postcode')
                    ->setMethods(array('validate'))
                    ->disableOriginalConstructor()
                    ->getMock();
    }
		
	

}
