<?php

namespace Codescheme\Postcodes\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostcodeTest extends TestCase
{
    
		protected function getPostcodes()
    {
        return $this->getMockBuilder('Codescheme\Postcodes\Classes\Postcode')
                    ->setMethods(array('validate'))
                    ->disableOriginalConstructor()
                    ->getMock();
    }
		
		/**
     * An http test
     *
     * @return void
     */
    public function testBasicHttp()
    {
				$response = $this->get('/postcode');
        $response->assertStatus(200);
    }
		

}
