<?php

namespace AshAllenDesign\LaravelExchangeRates\Classes;

use GuzzleHttp\Client;

class RequestBuilder
{
    /**
     * The base URL for the Exchange Rates API.
     *
     * @var string
     */
    private $baseUrl = 'http://api.exchangeratesapi.io';

    /**
     * @var Client
     */
    private $client;

    /**
     * RequestBuilder constructor.
     *
     * @param  Client|null  $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client();
    }

    /**
     * Make an API request to the ExchangeRatesAPI.
     *
     * @param  string  $path
     * @param  string[]  $queryParams
     *
     * @return mixed
     */
    public function makeRequest(string $path, array $queryParams = [])
    {
        $url = $this->baseUrl.$path.'?';
        
        $token = null;

        if(! empty(config('exchangerates.secrets'))) {
		$token = config('exchangerates.secrets')[array_rand(config('exchangerates.secrets'))];
        }

        if (!empty(env('EXCHANGE_RATES_TOKEN'))) {
		$url .= 'access_key=' . ($token ?? env('EXCHANGE_RATES_TOKEN'));
        }

        foreach ($queryParams as $param => $value) {
            $url .= '&'.urlencode($param).'='.urlencode($value);
        }

        return json_decode($this->client->get($url)->getBody()->getContents(), true);
    }
}
