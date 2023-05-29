<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresSrc3PutExtraTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $data = [
        'iso3' => 'afg',
        'country' => 'Afghanistan',
        'year' => '2022',
        'name' => 'Indicator',
        'value' => '777',
        'dummy' => 'test',
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
        $this->assertEquals('test', $body->dummy);
    }

}
