<?php

namespace InsitesConsulting\DecipherApi\Factories;

use GuzzleHttp\Client as HttpClient;
use InsitesConsulting\DecipherApi\Exceptions\MissingApiKeyException;
use InsitesConsulting\DecipherApi\Exceptions\MissingUriException;

class Client
{
    private $timeout = 0;

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
        return $this->client->get($endpoint, ['connect_timeout' => $this->timeout]);
    }

    public function post($endpoint, $options)
    {
        $options = $this->addTimeoutOption($options);
        return $this->client->post($endpoint, $options);
    }

    public function put($endpoint, $options)
    {
        $options = $this->addTimeoutOption($options);
        return $this->client->put($endpoint, $options);
    }

    public function patch($endpoint, $options)
    {
        $options = $this->addTimeoutOption($options);
        return $this->client->patch($endpoint, $options);
    }

    /**
     * @param float $timeout
     * The time in seconds before the client will time out a request.
     */
    public function setTimeout(float $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }

    private function addTimeoutOption($options)
    {
        if (!array_key_exists('connect_timeout', $options)) {
            $options['connect_timeout'] = $this->timeout;
        }

        return $options;
    }
}
