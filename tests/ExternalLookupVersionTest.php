<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ExternalLookupVersionTest extends ApiTestCase
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

    public function testVersionAsUser2(): void
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

        $old_name = $this->data['name'];
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

        $response = $client->request('GET', $this->addPrefix('external_lookups/' . $this->data['id'] . '/versions'), [
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

        $this->assertEquals($body[0]['id'], $this->data['id']);
        $this->assertEquals($body[0]['name'], $this->data['name']);
        $this->assertEquals($body[1]['id'], $this->data['id']);
        $this->assertEquals($body[1]['name'], $old_name);

        // We saved an identical one, which doesn't trigger a revision.
        $this->assertCount(2, $body);
    }

}
