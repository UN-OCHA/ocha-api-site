<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\Client;
use ApiPlatform\Symfony\Bundle\Test\Response;

trait TestTrait {
    private $prefix = '/api/v1/';

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function addPrefix(string $url) : string {
        return rtrim($this->prefix, '/') . '/' . ltrim($url, '/');
    }

    protected function getBody(Response $response) {
        return json_decode($response->getContent());
    }

    protected function createClientWithCredentials($headers): Client
    {
        return static::createClient([], ['headers' => $headers]);
    }
}
