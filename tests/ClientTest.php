<?php

/**
 * Test cases for MrDth\DecipherApi\Factories\Client
 *
 * @tags
 * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */

namespace InsitesConsulting\DecipherApi\Test;

use InsitesConsulting\DecipherApi\Exceptions\MissingApiKeyException;
use InsitesConsulting\DecipherApi\Exceptions\MissingUriException;
use InsitesConsulting\DecipherApi\Factories\Client;

class ClientTest extends TestCase
{

    /** @test */
    public function uri_cannot_be_null()
    {
        $this->expectException(MissingUriException::class);
        new Client(null, 'fakeapikey');
    }

    /** @test */
    public function uri_cannot_be_empty_string()
    {
        $this->expectException(MissingUriException::class);
        new Client('', 'fakeapikey');
    }

    /** @test */
    public function api_key_cannot_be_null()
    {
        $this->expectException(MissingApiKeyException::class);
        new Client('https://api.cintworks.net', null);
    }

    /** @test */
    public function api_key_cannot_be_empty_string()
    {
        $this->expectException(MissingApiKeyException::class);
        new Client('https://api.cintworks.net', '');
    }

    /** @test */
    public function it_can_be_instantiated_with_valid_details()
    {
        $this->assertInstanceOf(
            Client::class,
            new Client('https://api.cintworks.net', 'thisisafakekey')
        );
    }

    /** @test */
    public function it_allows_a_connection_timeout_to_be_set()
    {
        $client = new Client('https://api.cintworks.net', 'thisisafakekey');
        $this->assertEquals(0, $client->getTimeout());

        $client->setTimeout(60);
        $this->assertEquals(60, $client->getTimeout());
    }
}
