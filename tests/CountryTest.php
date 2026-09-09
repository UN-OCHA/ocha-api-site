<?php

namespace App\Tests;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class CountryTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $token = 'token-ocha-presence';
    protected $user_token = 'token2';

    protected $data = [
        'id' => 'zzz',
        'name' => 'Test Country ZZZ',
        'iso2' => 'zz',
        'iso3' => 'zzz',
        'code' => '999',
    ];

    private function assertStatus($response, int $expected, string $note = ''): void
    {
        $this->assertSame(
            $expected,
            $response->getStatusCode(),
            trim($note.' '.$response->getContent(false)),
        );
    }

    public function testGetCollectionJson(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('GET', $this->addPrefix('countries'), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
        ]);

        $this->assertStatus($response, 200, 'GET /countries');

        $body = json_decode($response->getContent(), TRUE);
        $this->assertIsArray($body);
        $ids = array_column($body, 'id');
        $this->assertContains('afg', $ids);
        $this->assertContains('zaf', $ids);
    }

    public function testGetItemJson(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        // zaf has no OchaPresence links in fixtures (unlike afg/yem).
        $response = $client->request('GET', $this->addPrefix('countries/zaf'), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
        ]);

        $this->assertStatus($response, 200, 'GET /countries/zaf');

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals('zaf', $body['id']);
        $this->assertEquals('South Africa', $body['name']);

        // afg is linked to an OchaPresence; item GET must still succeed.
        $response = $client->request('GET', $this->addPrefix('countries/afg'), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
        ]);

        $this->assertStatus($response, 200, 'GET /countries/afg');
        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals('afg', $body['id']);
        $this->assertEquals('Afghanistan', $body['name']);
    }

    public function testPutCreateAndUpdate(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('PUT', $this->addPrefix('countries/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertTrue(
            in_array($response->getStatusCode(), [200, 201], true),
            $response->getContent(false),
        );

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($this->data['id'], $body['id']);
        $this->assertEquals($this->data['name'], $body['name']);
        $this->assertEquals($this->data['iso2'], $body['iso2']);
        $this->assertEquals($this->data['iso3'], $body['iso3']);
        $this->assertEquals($this->data['code'], $body['code']);

        $response = $client->request('GET', $this->addPrefix('countries/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
        ]);

        $this->assertStatus($response, 200, 'GET after PUT create');
        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals($this->data['name'], $body['name']);

        $updated = $this->data;
        $updated['name'] = 'Test Country Updated';
        $response = $client->request('PUT', $this->addPrefix('countries/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $updated,
        ]);

        $this->assertStatus($response, 200, 'PUT update');

        $body = json_decode($response->getContent(), TRUE);
        $this->assertEquals('Test Country Updated', $body['name']);

        static::getContainer()->get('doctrine')->getManager()->clear();

        $response = $client->request('GET', $this->addPrefix('countries'), [
            'headers' => [
                'API-KEY' => $this->token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
        ]);

        $this->assertStatus($response, 200, 'GET /countries after writes');
        $ids = array_column(json_decode($response->getContent(), TRUE), 'id');
        $this->assertContains('afg', $ids);
        $this->assertContains('zzz', $ids);
    }

    public function testPutUnauthorized(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $response = $client->request('PUT', $this->addPrefix('countries/' . $this->data['id']), [
            'headers' => [
                'API-KEY' => $this->user_token,
                'APP-NAME' => 'test',
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertStatus($response, 403, 'PUT unauthorized');
    }
}
