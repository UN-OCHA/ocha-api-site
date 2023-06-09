<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class OchaPresenceTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $token = 'token-ocha-presence';
    protected $data = [
        'id' => 'afg',
        'name' => 'Indicator',
        'office_type' => 'Afghanistan',
        'countries' => [
            'afg',
            'yem',
        ],
        'ocha_presence_external_ids' => [],
    ];

    public function testCreateJson(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('ocha_presences'), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testCreateLdJson(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('ocha_presences'), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);

        $this->assertEquals($body['id'], $this->data['id']);
        $this->assertEquals($body['countries'][0]['id'], $this->data['countries'][0]);
    }

}
