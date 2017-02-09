<?php

namespace Codescheme\Postcodes\Classes;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Exception\Handler;

class Postcode {

	private $base_url = 'https://api.postcodes.io';
	public $format = 'object';
		
	  /**
   * Validates a postcode
   *
   * @param string $postcode, to be validated
   * @return boolean
   */
  public function validate($postcode)
  {
		$url = $this->base_url . '/postcodes/' . rawurlencode($postcode) . '/validate';
		
		$response = $this->_http_request($url);
		
		return ($response && (200 === $response->status) && $response->result); 
	}
	
	  /**
   * Find nearest postcodes to given
   *
   * @param string $postcode 
	 * @param string $format 
   * @return by $this->format object|string|array  null on Exception 
   */	
	public function nearest($postcode, $format='object')
  {
		$this->format = $format;
		$url = $this->base_url . '/postcodes/' . rawurlencode($postcode) . '/nearest';

		return $this->_http_request($url);
	}
		
	  /**
   * Get postcode from coordinates
   *
   * @params string $lon, $lat, the coordinates
	 * @param string $format, 
   * @return by $this->format object|string|array  null on Exception 
   */	
	public function reverseGeocode($lon, $lat, $format='object')
  {
		$this->format = $format;
		$url = $this->base_url . '/postcodes?lon=' . (float) $lon . '&lat=' . (float) $lat ;

		return $this->_http_request($url);
	}
		
	  /**
   * Autocomplete a postcode, especially 
   *
   * @param string $postcode, partial, especially outcode 
	 * @param string $format, 
   * @return by $this->format object|string|array  null on Exception 
   */	
	public function autocomplete($postcode, $format='object')
  {
		$this->format = $format;
		$url = $this->base_url . '/postcodes/' . rawurlencode($postcode) . '/autocomplete';

		return $this->_http_request($url);
	}
		
	/**
   * Look up a postcode, 
   *
   * @param string $postcode,  
	 * @param string $format, 
   * @return by $this->format object|string|array  null on Exception 
   */	
	public function postcodeLookup($postcode, $format='object')
  {
		$this->format = $format;
		$url = $this->base_url . '/postcodes/' . rawurlencode($postcode);

		return $this->_http_request($url);
	}
	
	/**
   * Look up a outcode, 
   *
   * @param string $outcode,  
	 * @param string $format, 
   * @return by $this->format object|string|array  null on Exception 
   */	
	public function outcodeLookup($outcode, $format='object')
  {
		$this->format = $format;
		$url = $this->base_url . '/outcodes/' . rawurlencode($outcode);

		return $this->_http_request($url);
	}
		
	 /**
   * Does the http GET
   *
   * @param string $url, the endpoint
   * @return by $this->format object|string|array  null on Exception 
   */		
	private function _http_request($url)
	{

		try {
    	$response = file_get_contents($url);

    	if ($response === false) {
      	throw new Exception("Classes\Postcode file_get_contents error"); 
    	}
		
			switch ($this->format)
			{
				default :
				case 'object' : $response = json_decode($response);
				break;
				case 'json'   :
				break;
				case 'array'  : $response = json_decode($response, true);
				break;
			}
			return $response;
			
		} catch (Exception $e) {
    	// Handle exception by Laravel logger
			Log::error($e);
			return null;
		} 
	}
 
} //eoclass