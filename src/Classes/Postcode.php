<?php

namespace Codescheme\Postcodes\Classes;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class Postcode
{
    /**
     * The endpoint base uri
     */
    protected $base_uri = 'https://api.postcodes.io';

    /**
     * The default HTTP Headers
     */
    protected $headers;

    /**
     * The Guzzle client instance
     */
    protected $client;

    /**
     * Construct a Guzzle client
     *
     * @return void
     */
    public function __construct()
    {
        $this->headers = [
            'User-Agent' => 'CodeschemeLaravelPostcodes/1.3 https://github.com/codescheme/postcodes',
            'Accept'     => 'application/json',
        ];

        $this->client = new Client([
            'base_uri' => $this->base_uri,
            'headers' => $this->headers,
            'http_errors' => false,
            'verify' => __DIR__ . '/../cacert.pem'
        ]);
    }

    /**
     * Validates a postcode
     *
     * @param string $postcode to be validated
     * @return boolean
     */
    public function validate($postcode)
    {
        $url = '/postcodes/' . rawurlencode($postcode) . '/validate';
        $request = new Request('GET', $url);
        $response = $this->httpTransport($request);

        return ($response && (200 === $response->status) && $response->result);
    }

    /**
     * Find nearest postcodes to given
     *
     * @param string         $postcode
     * @return object | null on Exception
     */
    public function nearest($postcode)
    {
        $url = '/postcodes/' . rawurlencode($postcode) . '/nearest';
        $request = new Request('GET', $url);

        return $this->httpTransport($request);
    }

    /**
     * Get postcode from coordinates
     *
     * @params string        $lon, $lat    the coordinates
     * @return object | null on Exception
     */
    public function reverseGeocode($lon, $lat)
    {
        $url = '/postcodes?lon=' . (float) $lon . '&lat=' . (float) $lat;
        $request = new Request('GET', $url);

        return $this->httpTransport($request);
    }

    /**
     * Autocomplete a postcode,
     *
     * @param string             $postcode, partial, especially outcode
     * @return object | null on Exception
     */
    public function autocomplete($postcode)
    {
        $url = '/postcodes/' . rawurlencode($postcode) . '/autocomplete';
        $request = new Request('GET', $url);

        return $this->httpTransport($request);
    }

    /**
     * Look up a postcode,
     *
     * @param string $postcode,
     * @return object | null on Exception
     */
    public function postcodeLookup($postcode)
    {
        $url = '/postcodes/' . rawurlencode($postcode);
        $request = new Request('GET', $url);

        return $this->httpTransport($request);
    }

    /**
     * Look up a outcode,
     *
     * @param string $outcode,
     * @return object | null on Exception
     */
    public function outcodeLookup($outcode)
    {
        $url = '/outcodes/' . rawurlencode($outcode);
        $request = new Request('GET', $url);

        return $this->httpTransport($request);
    }

    /**
     * Bulk information lookup for multiple postcodes
     *
     * @param array $postcodes
     * @return object | null on RequestException
     */
    public function postcodeLookupBulk($postcodes)
    {
        $headers = ['Content-Type' => 'application/json'];
        $body = ['postcodes' => $postcodes];

        $request = new Request('POST', '/postcodes', $headers, json_encode($body));

        return $this->httpTransport($request);
    }

    /**
     * Bulk lookup of postcodes matching multiple lon/lat coordinates
     *
     * @param array(array(longitude,latitude)) $geolocations   
     * @return object | null on RequestException 
     */
    public function reverseGeocodeBulk($geolocations)
    {
        $headers = ['Content-Type' => 'application/json'];
        $body = ['geolocations' => $geolocations];

        $request = new Request('POST', '/postcodes', $headers, json_encode($body));

        return $this->httpTransport($request);
    }

    /**
     * Does the http
     *
     * @param GuzzleHttp\Psr7\Request $request
     * @throws RequestException
     * @return object | null on RequestException
     */
    protected function httpTransport($request)
    {
        try {
            $response = $this->client->send($request);
            $results = json_decode($response->getBody());
            return $results;
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error(Psr7\str($e->getResponse()));
            }
        }
    }
}
