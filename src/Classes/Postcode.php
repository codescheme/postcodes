<?php

namespace Codescheme\Postcodes\Classes;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Log;

class Postcode
{
    /**
     * The endpoint base uri
     */
    protected string $base_uri = 'https://api.postcodes.io';

    /**
     * The default HTTP Headers
     * @var array<string, string>
     */
    protected array $headers;

    /**
     * The Guzzle client instance
     */
    protected Client $client;

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
     * @return bool
     */
    public function validate(string $postcode): bool
    {
        $url = '/postcodes/' . rawurlencode($postcode) . '/validate';
        $response = $this->httpTransport(request: new Request(method: 'GET', uri: $url));

        return $response?->status === 200 && $response->result;
    }

    /**
     * @param string $postcode Find nearest postcodes to given postcode
     * @throws RequestException
     * @return object | null on Exception
     */
    public function nearest(string $postcode): ?object
    {
        $url = '/postcodes/' . rawurlencode($postcode) . '/nearest';

        return $this->httpTransport(request: new Request(method: 'GET', uri: $url));
    }

    /**
     * Get postcode from coordinates
     * @throws RequestException
     * @return object | null on Exception
     */

    public function reverseGeocode(float $lon, float $lat): ?object
    {
        $url = "/postcodes?lon={$lon}&lat={$lat}";

        return $this->httpTransport(request: new Request(method: 'GET', uri: $url));
    }

    /**
     * Autocomplete a postcode,
     *
     * partial postcode, especially outcode
     * @throws RequestException
     * @return object | null on Exception
     */
    public function autocomplete(string $postcode): ?object
    {
        $url = '/postcodes/' . rawurlencode($postcode) . '/autocomplete';

        return $this->httpTransport(request: new Request(method: 'GET', uri: $url));
    }

    /**
     * Look up a postcode,
     * @throws RequestException
     * @return object | null on Exception
     */
    public function postcodeLookup(string $postcode): ?object
    {
        $url = '/postcodes/' . rawurlencode($postcode);

        return $this->httpTransport(request: new Request(method: 'GET', uri: $url));
    }

    /**
     * Look up an outcode,
     * @throws RequestException
     * @return object | null on Exception
     */
    public function outcodeLookup(string $outcode): ?object
    {
        $url = '/outcodes/' . rawurlencode($outcode);

        return $this->httpTransport(request: new Request(method: 'GET', uri: $url));
    }

    /**
     * Bulk information lookup for multiple postcodes
     * @param array<string> $postcodes Array of postcodes
     * @throws RequestException
     * @return object | null on RequestException
     */
    public function postcodeLookupBulk(array $postcodes): ?object
    {
        $headers = ['Content-Type' => 'application/json'];

        return $this->httpTransport(request: new Request('POST', '/postcodes', $headers, json_encode(['postcodes' => $postcodes])));
    }

    /**
     * Bulk lookup of postcodes matching multiple lon/lat coordinates.
     *
     * @param array<array<string,float>> $geolocations Array of geolocation arrays with 'longitude' and 'latitude' keys.
     * @throws RequestException
     * @return object | null on RequestException 
     */
    public function reverseGeocodeBulk(array $geolocations): ?object
    {
        $headers = ['Content-Type' => 'application/json'];

        return $this->httpTransport(request: new Request('POST', '/postcodes', $headers, json_encode(['geolocations' => $geolocations])));
    }

    /**
     * Does the http
     *
     * @param \GuzzleHttp\Psr7\Request $request
     * @throws RequestException
     * @return object | null on RequestException
     */
    protected function httpTransport(Request $request): ?object
    {
        try {
            $response = $this->client->send($request);
            return json_decode(json: $response->getBody());
        } catch (RequestException $e) {
            Log::error(Message::toString($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error(Message::toString($e->getResponse()));
            }
        }

        return null;
    }
}
