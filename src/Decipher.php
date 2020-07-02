<?php

namespace MrDth\DecipherApi;

use MrDth\DecipherApi\Exceptions\ServerDirectoryNotSetException;
use MrDth\DecipherApi\Exceptions\SurveyIdNotSetException;
use MrDth\DecipherApi\Factories\Api\SurveyData;
use MrDth\DecipherApi\Factories\Api\SurveyFile;
use MrDth\DecipherApi\Factories\Api\SurveyList;
use MrDth\DecipherApi\Factories\Api\SurveyStructure;
use MrDth\DecipherApi\Factories\Client;

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

    public function getSurveyData(array $fields = ['all'], string $return_format = 'json')
    {
        $this->checkRequiredPropertiesExist();

        $client = new SurveyData($this->client, $this->server_directory, $this->survey_id);

        if(isset($this->condition)) {
            $client->setCondition($this->condition);
        }

        return $client->get($this->prepareFields($fields), $return_format);
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
