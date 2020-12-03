<?php

namespace InsitesConsulting\DecipherApi\Factories;

use GuzzleHttp\Client as HttpClient;
use InsitesConsulting\DecipherApi\Exceptions\MissingApiKeyException;
use InsitesConsulting\DecipherApi\Exceptions\MissingUriException;

class Client
{
    public function __construct($base_uri, $api_key)
    {
        $this->checkCredentials($base_uri, $api_key);

        $this->client = new HttpClient([
            'base_uri' => $base_uri,
            'headers' => [
                'x-apikey' => $api_key
            ],
        ]);
    }

    private function checkCredentials($uri, $key)
    {
        if ($uri === null || $uri === '') {
            throw new MissingUriException();
        }

        if ($key === null || $key === '') {
            throw new MissingApiKeyException();
        }
    }

    public function get($endpoint)
    {
        return $this->client->get($endpoint);
    }

    public function post($endpoint, $options)
    {
        return $this->client->post($endpoint, $options);
    }

    public function put($endpoint, $options)
    {
        return $this->client->put($endpoint, $options);
    }

    public function patch($endpoint, $options)
    {
        return $this->client->patch($endpoint, $options);
    }
}
