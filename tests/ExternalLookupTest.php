<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ExternalLookupTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $admin_token = 'token1';

    protected $data = [
        'id' => 'fts_2226',
        'provider' => 'src2',
        'year' => '2023',
        'iso3' => 'afg',
        'external_id' => '2226',
        'name' => 'Plan 2226',
    ];

    public function testCreateJson(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('external_lookups'), [
            'headers' => [
                'API-KEY' => $this->admin_token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($body['id'], $this->data['id']);
    }

    public function testCreateLdJson(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('external_lookups'), [
            'headers' => [
                'API-KEY' => $this->admin_token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($body['id'], $this->data['id']);
    }

    public function testCreateAsUser1(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('external_lookups'), [
            'headers' => [
                'API-KEY' => 'token2',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testCreateAsUser2(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('external_lookups'), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($body['id'], $this->data['id']);
    }

    public function testPutAsUser2(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('external_lookups'), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($body['id'], $this->data['id']);

        $response = $client->request('PUT', $this->addPrefix('external_lookups/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($body['id'], $this->data['id']);

        $this->data['name'] = $this->data['name'] . ' - updated';
        $response = $client->request('PUT', $this->addPrefix('external_lookups/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($body['id'], $this->data['id']);
        $this->assertEquals($body['name'], $this->data['name']);
    }

    public function testPatchAsUser2(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('POST', $this->addPrefix('external_lookups'), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($body['id'], $this->data['id']);

        $response = $client->request('PATCH', $this->addPrefix('external_lookups/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'name' => $this->data['name'],
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($body['id'], $this->data['id']);

        $this->data['name'] = $this->data['name'] . ' - updated';
        $response = $client->request('PATCH', $this->addPrefix('external_lookups/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'name' => $this->data['name'],
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($body['id'], $this->data['id']);
        $this->assertEquals($body['name'], $this->data['name']);
    }

}
