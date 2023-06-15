<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresSrc3ArchiveTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $data = [
        'data' => [
            [
                'iso3' => 'afg',
                'country' => 'Afghanistan',
                'year' => '2022',
                'name' => 'Indicator',
                'value' => '777',
                'dummy' => 'test',
            ],
            [
                'iso3' => 'afg',
                'country' => 'Afghanistan',
                'year' => '2021',
                'name' => 'Indicator',
                'value' => '666',
            ],
        ],
    ];

    public function testOnSource3AsAdmin(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('source-3') . '/batch', [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertCount(2, $body['successful']);
        $this->assertCount(0, $body['failed']);
        $this->assertArrayHasKey(strtolower('src3_afg_2021_Indicator'), $body['successful']);
        $this->assertArrayHasKey(strtolower('src3_afg_2022_Indicator'), $body['successful']);

        $response = $client->request('POST', $this->addPrefix('source-3') . '/archive', [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => [
                'iso3' => 'afg',
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertCount(2, $body['successful']);
        $this->assertCount(0, $body['failed']);
        $this->assertArrayHasKey(strtolower('src3_afg_2021_Indicator'), $body['successful']);
        $this->assertArrayHasKey(strtolower('src3_afg_2022_Indicator'), $body['successful']);

        $response = $client->request('GET', $this->addPrefix('source-3') . '/src3_afg_2021_indicator', [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals(true, $body['archived']);

    }

    public function testOnSource3AsUser1(): void
    {
        $response = static::createClient()->request('POST', $this->addPrefix('source-3') . '/batch', [
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
        $response = static::createClient()->request('POST', $this->addPrefix('source-3') . '/batch', [
            'headers' => [
                'API-KEY' => 'token4',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertCount(2, $body['successful']);
        $this->assertCount(0, $body['failed']);
        $this->assertArrayHasKey(strtolower('src3_afg_2021_Indicator'), $body['successful']);
        $this->assertArrayHasKey(strtolower('src3_afg_2022_Indicator'), $body['successful']);
   }

}
