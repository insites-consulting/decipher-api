<?php

namespace InsitesConsulting\DecipherApi\Factories\Api;

use InsitesConsulting\DecipherApi\Factories\Client;

class SurveyFile {

    private $client;
    private $survey_id;
    private $server_directory;

    public function __construct(Client $client, $server_directory, $survey_id)
    {
        $this->client = $client;
        $this->server_directory = $server_directory;
        $this->survey_id = $survey_id;
    }

    public function fetch(string $filename)
    {
        $response = $this->client->get($this->buildEndpoint($filename));

        return (string) $response->getBody();
    }

    private function buildEndpoint(string $filename): string
    {
        return "surveys/$this->server_directory/$this->survey_id/files/$filename";
    }
}
