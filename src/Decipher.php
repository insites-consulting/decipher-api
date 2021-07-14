<?php

namespace InsitesConsulting\DecipherApi;

use InsitesConsulting\DecipherApi\Exceptions\ServerDirectoryNotSetException;
use InsitesConsulting\DecipherApi\Exceptions\SurveyIdNotSetException;
use InsitesConsulting\DecipherApi\Factories\Api\SurveyData;
use InsitesConsulting\DecipherApi\Factories\Api\SurveyFile;
use InsitesConsulting\DecipherApi\Factories\Api\SurveyList;
use InsitesConsulting\DecipherApi\Factories\Api\SurveyStructure;
use InsitesConsulting\DecipherApi\Factories\Client;

class Decipher
{
    protected $client;
    protected $default_fields;
    protected $server_directory;
    protected $survey_id;
    protected $condition;


    public function setServerDirectory(string $server_directory)
    {
        $this->server_directory = $server_directory;
        return $this;
    }

    public function setSurveyId(string $survey_id)
    {
        $this->survey_id = $survey_id;
        return $this;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
        return $this;
    }

    public function __construct(Client $client)
    {
        $this->client = $client;

        // Set the default fields to be fetched with every survey data request.
        $this->default_fields = [
            'uuid',
            'status'
        ];
    }

    public function getSurveyList()
    {
        return (new SurveyList($this->client))->fetch();
    }

    public function getSurveyData(array $fields = ['all'], string $return_format = 'json', bool $raw = false, $start = null, $end = null)
    {
        $this->checkRequiredPropertiesExist();

        $client = new SurveyData($this->client, $this->server_directory, $this->survey_id);

        if (isset($this->condition)) {
            $client->setCondition($this->condition);
        }

        if ($start) {
            $client->setStartTime($start);
        }

        if ($end) {
            $client->setEndTime($end);
        }

        return $client->get($this->prepareFields($fields), $return_format, $raw);
    }

    public function getSurveyStructure($format = 'json')
    {
        $this->checkRequiredPropertiesExist();

        $client = new SurveyStructure($this->client, $this->server_directory, $this->survey_id);

        return $client->fetch($format);
    }

    public function getSurveyFile($filename)
    {
        $this->checkRequiredPropertiesExist();

        $client = new SurveyFile($this->client, $this->server_directory, $this->survey_id);

        return $client->fetch($filename);
    }

    protected function prepareFields(array $fields)
    {
        if ($fields === ['all']) {
            $fields = [];
        }

        if (count($fields)) {
            $fields = array_unique(array_merge($this->default_fields, $fields));
        }

        return $fields;
    }

    protected function checkRequiredPropertiesExist()
    {
        if (!$this->survey_id) {
            throw new SurveyIdNotSetException();
        }

        if (!$this->server_directory) {
            throw new ServerDirectoryNotSetException();
        }
    }
}
