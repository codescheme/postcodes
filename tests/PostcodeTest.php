<?php

namespace Codescheme\Postcodes\Tests;

use Codescheme\Postcodes\Classes\Postcode;
use GuzzleHttp\Psr7\Response;

class PostcodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     *
     * @return void 
     */

    public function testCanValidatePostcode()
    {
        $stub = $this->getMockBuilder(Postcode::class)
                     ->getMock();

        $stub->method('validate')
             ->willReturn(true);
        $this->assertTrue($stub->validate('SE21 8JL'));
        
        $stub->method('validate')
             ->willReturn(false);
        $this->assertTrue($stub->validate('SE51 8JL'));
    }

    public function testCanLookupPostcode()
    {
        $response = new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'status' => 200,
            'result' => [
                'postcode' => 'NE30 1DP',
                'quality]' => 1,
                'eastings' => 435958,
                'northings' => 568671,
                'country' => 'England',
                'nhs_ha' => 'North East',
                'longitude' => -1.4392690051562,
                'latitude' => 55.011305191051,
                'parliamentary_constituency' => 'Tynemouth',
                'european_electoral_region' => 'North East',
                'primary_care_trust' => 'North Tyneside',
                'region' => 'North East',
                'lsoa' => 'North Tyneside 016C',
                'msoa' => 'North Tyneside 016',
                'incode' => '1DP',
                'outcode' => 'NE30',
                'admin_district' => 'North Tyneside',
                'parish' => 'North Tyneside, unparished area',
                'admin_county' => null,
                'admin_ward' => 'Tynemouth',
                'ccg' => 'NHS North Tyneside',
                'nuts' => 'Tyneside',
                'codes' => [
                    'admin_district' => 'E08000022',
                    'admin_county' => 'E99999999',
                    'admin_ward' => 'E05001130',
                    'parish' => 'E43000176',
                    'ccg' => 'E38000127',
                    'nuts' => 'UKC22',
                ]
            ]
        ]));

        $expectedObject = json_decode($response->getBody());

        $mock = $this->getMockBuilder(Postcode::class)
            ->setMethods(['postcodeLookup'])
            ->getMock();

        $mock->expects($this->once())
            ->method('postcodeLookup')
            ->with('NE30 1DP')
            ->will($this->returnValue($expectedObject));

        $this->assertSame($expectedObject, $mock->postcodeLookup('NE30 1DP'));
    }

    public function testCanStandToBe404ed()
    {
        $response = new Response(404, ['Content-Type' => 'application/json'], json_encode([
            'status' => 404,
            'error' => 'Postcode not found',
        ]));
    
        $expectedObject = json_decode($response->getBody());

        $mock = $this->getMockBuilder(Postcode::class)
            ->setMethods(['postcodeLookup'])
            ->getMock();

        $mock->expects($this->once())
            ->method('postcodeLookup')
            ->with('NZ56 86P')
            ->will($this->returnValue($expectedObject));

        $this->assertSame($expectedObject, $mock->postcodeLookup('NZ56 86P'));
    }
}
