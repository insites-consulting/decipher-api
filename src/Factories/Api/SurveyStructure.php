<?php

namespace InsitesConsulting\DecipherApi\Factories\Api;

use InsitesConsulting\DecipherApi\Exceptions\InvalidReturnFormatException;
use InsitesConsulting\DecipherApi\Factories\Client;

class SurveyStructure
{
    private const RETURN_FORMATS = [
        'json',
        'html',
        'sss',
        'text',
        'tab'
    ];

    private $client;
    private $survey_id;
    private $server_directory;

    /**
     * Inject HTTP Client
     */
    public function __construct(Client $client, $server_directory, $survey_id)
    {
        $this->client = $client;
        $this->survey_id = $survey_id;
        $this->server_directory = $server_directory;
    }

    public function fetch(string $return_format)
    {
        $this->validateReturnFormat($return_format);

        $response = $this->client->get($this->buildEndpoint($return_format));

        return (string) $response->getBody();
    }

    protected function buildEndpoint($format)
    {
        return "surveys/$this->server_directory/$this->survey_id/datamap?format=$format";
    }

    private function validateReturnFormat($format)
    {
        if (!in_array($format, self::RETURN_FORMATS)) {
            $message = "Only the following return types are currently supported: implode(', ', self::RETURN_FORMATS)";
            throw new InvalidReturnFormatException($message);
        }
    }

}
