<?php

namespace InsitesConsulting\DecipherApi\Factories\Api;

use InsitesConsulting\DecipherApi\Factories\Client;

class SurveyList
{
    /**
     * Client object
     *
     * @var Client
     */
    protected $client;

    /**
     * Inject API Client
     *
     * @param $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetch()
    {
        $response = $this->client->get('rh/companies/all/surveys');

        return (string) $response->getBody();
    }
}
