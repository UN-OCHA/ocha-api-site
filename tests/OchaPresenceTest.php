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
    protected $user_token = 'token2';

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

    public function testCreateAsUser1(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('ocha_presences'), [
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

    public function testPutLdJson(): void
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

        $response = $client->request('PUT', $this->addPrefix('ocha_presences/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);

        $this->assertEquals($body['id'], $this->data['id']);
        $this->assertEquals($body['countries'][0]['id'], $this->data['countries'][0]);

        $new_data = $this->data;
        $new_data['countries'][] = 'zaf';
        $response = $client->request('PUT', $this->addPrefix('ocha_presences/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $new_data,
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);

        $this->assertEquals($body['id'], $new_data['id']);
        $this->assertEquals($body['countries'][0]['id'], $new_data['countries'][0]);
        $this->assertEquals($body['countries'][2]['id'], $new_data['countries'][2]);
    }

    public function testPatchLdJson(): void
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

        $response = $client->request('PATCH', $this->addPrefix('ocha_presences/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'name' => 'new name',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);

        $this->assertEquals($body['id'], $this->data['id']);
        $this->assertEquals($body['countries'][0]['id'], $this->data['countries'][0]);

        // PATCH on arrays will do a replace not a merge!
        $response = $client->request('PATCH', $this->addPrefix('ocha_presences/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'countries' => ['zaf'],
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);

        $this->assertEquals($body['id'], $this->data['id']);
        $this->assertEquals($body['countries'][0]['id'], 'zaf');
    }

}
