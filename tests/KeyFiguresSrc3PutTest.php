<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresSrc3PutTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $data = [
        'iso3' => 'afg',
        'country' => 'Afghanistan',
        'year' => '2022',
        'name' => 'Indicator',
        'value' => '777',
    ];

    public function testOnSource3AsAdmin(): void
    {
        $response = static::createClient()->request('PUT', $this->addPrefix('source-3') . '/1', [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertEquals('1', $body->id);
        $this->assertEquals('afg', $body->iso3);
        $this->assertEquals('Afghanistan', $body->country);
        $this->assertEquals('2022', $body->year);
        $this->assertEquals('Indicator', $body->name);
        $this->assertEquals('777.00', $body->value);
        $this->assertEquals([], $body->tags);
        $this->assertEquals('src3', $body->provider);
    }

    public function testOnSource3AsUser1(): void
    {
        $response = static::createClient()->request('PUT', $this->addPrefix('source-3') . '/2', [
            'headers' => [
                'API-KEY' => 'token2',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        // User 1 does not have access to source 3.
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testOnSource3AsUser3(): void
    {
        $response = static::createClient()->request('PUT', $this->addPrefix('source-3') . '/3', [
            'headers' => [
                'API-KEY' => 'token4',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertEquals('3', $body->id);
        $this->assertEquals('afg', $body->iso3);
        $this->assertEquals('Afghanistan', $body->country);
        $this->assertEquals('2022', $body->year);
        $this->assertEquals('Indicator', $body->name);
        $this->assertEquals('777.00', $body->value);
        $this->assertEquals([], $body->tags);
        $this->assertEquals('src3', $body->provider);
    }

}
