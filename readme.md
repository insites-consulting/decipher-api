
## Decipher API

This package provides a PHP wrapper for the FocusVision Decipher API, along with binding for using it in Laravel applications.

#### Installation
Install with composer: 
```bash 
composer require mrdth/decipher-api
```

#### Usage In Laravel

Add two new env vars:

- DECIPHER_API_URI_BASE - base URI for the decipher api (E.g. https://v2.decipherinc.com/api/v1/)
- DECIPHER_API_KEY - API key for the application

The package will be auto-registered, and can be accessed via it's facade.

All calls to the API via the wrapper should be carried out inside a try - catch block;
The wrapper does not deal with any Exceptions thrown by the underlying Guzzle client.
```php
$directory = 'selfserve/99d';
$survey_id = '12345';

\Decipher::setServerDirectory($directory)->setSurveyId($survey_id);

try {
    $survey_structure = \Decipher::getSurveyStructure('json');
  
    // Do whatever with $survey object
} catch (\GuzzleHttp\Exception\RequestException $e) {
    echo 'Error making request, server responded: ' . $e->getCode();
}

```

#

#### Usage as a standalone PHP Package

```php
use InsitesConsulting\DecipherApi\Decipher;use InsitesConsulting\DecipherApi\Factories\Client;

$api_uri = 'https://v2.decipherinc.com/api/v1/';
$api_key = 'OBVIOUSLYFAKEAPIKEY';
$directory = 'selfserve/99d';
$survey_id = '12345';

$client = new Client($api_uri, $api_key);
$decipher = new Decipher($client, $api_uri, $api_key);


```


All calls to the API via the wrapper should be carried out inside a try - catch block;
The wrapper does not deal with any Exceptions thrown by the underlying Guzzle client.

```php
$decipher->setServerDirectory($directory)->setSurveyId($survey_id);

try {
    $survey_structure = $decipher->getSurveyStructure('json');
  
    // Do whatever with $survey object
} catch (\GuzzleHttp\Exception\RequestException $e) {
    echo 'Error making request, server responded: ' . $e->getCode();
}
```

#### Methods exposed via the wrapper

- getSurveyList() - Get a list of all surveys available with the current API key.
- setServerDirectory(string $directory) - Directory where your projects are stored on the decipher server
- setSurveyId(int $id) - Set the ID of the current survey to work with.
- setCondition(string $condition) - Condition required to retrieve the participant. This is a Python condition as if you would enter in survey logic or crosstabs. For example, “qualified and q3.r2” retrieves only participants that were qualified and answered q3 as r2.
- getSurveyStructure(string $format) - Retrieve the structure of available questions for a survey, returning htem in the specified format.  Valid formats are: html, json, text, tab
- getSurveyData(array $fields = ['all'], string $return_format = 'json', bool $raw = false, $start = null, $end = null) - Retrieve the responses to the question IDs passed in $fields from Decipher.  Note: uuid & status fields will always be returned.<br>
  Setting $raw to true will result in the raw Guzzle response object being returned, rather than $response->getBody().<br>
  $start & $end can be set to ISO 8601 formatted datestrings (using Carbons toIso8601ZuluString() method, for example) to limit date range for responses returned.<br>
  Any condition set using ::setCondition() will be applied
- getSurveyFile(string $filename) - Retrieve the survey file called $filename as a string. 
