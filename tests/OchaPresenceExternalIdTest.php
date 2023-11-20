<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class OchaPresenceExternalIdTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $token = 'token-ocha-presence';
    protected $user_token = 'token2';

    protected $data = [
        'ocha_presence' => 'roap',
        'provider' => 'src2',
        'year' => '2000',
        'external_ids' => [
            'fts_1116',
            'fts_1117',
        ],
    ];

    public function testCreateJson(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('ocha_presence_external_ids'), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreateLdJson(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('ocha_presence_external_ids'), [
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

        $this->assertEquals($body['ocha_presence']['id'], $this->data['ocha_presence']);
        $this->assertEquals($body['year'], $this->data['year']);
    }

    public function testCreateAsUser1(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('ocha_presence_external_ids'), [
            'headers' => [
                'API-KEY' => $this->user_token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

}
