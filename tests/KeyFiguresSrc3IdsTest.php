<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresSrc3IdsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $data = [
        'id' => 'src3_AFG_2022_Test',
        'iso3' => 'afg',
        'country' => 'Afghanistan',
        'year' => '2022',
        'name' => 'Test',
        'value' => '777.00',
        'tags' => [],
    ];

    public function testKey(): void
    {
        $client = static::createClient();

        // Create key figure.
        $response = $client->request('PUT', $this->addPrefix('source-3') . '/' . $this->data['id'], [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertEquals($this->data['id'], $body->id);
        $this->assertEquals($this->data['iso3'], $body->iso3);
        $this->assertEquals($this->data['country'], $body->country);
        $this->assertEquals($this->data['year'], $body->year);
        $this->assertEquals($this->data['name'], $body->name);
        $this->assertEquals($this->data['value'], $body->value);
        $this->assertEquals($this->data['tags'], $body->tags);
        $this->assertEquals('src3', $body->provider);

        // Update key figure using lower cased id.
        $this->data['value'] = 666.00;
        $response = $client->request('PUT', $this->addPrefix('source-3') . '/' . strtolower($this->data['id']), [
          'headers' => [
              'API-KEY' => 'token1',
              'APP-NAME' => 'test',
              'accept' => 'application/json',
          ],
          'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertEqualsIgnoringCase($this->data['id'], $body->id);
        $this->assertEquals($this->data['iso3'], $body->iso3);
        $this->assertEquals($this->data['country'], $body->country);
        $this->assertEquals($this->data['year'], $body->year);
        $this->assertEquals($this->data['name'], $body->name);
        $this->assertEquals($this->data['value'], $body->value);
        $this->assertEquals($this->data['tags'], $body->tags);
        $this->assertEquals('src3', $body->provider);

        // Get key figure.
        $response = $client->request('GET', $this->addPrefix('source-3') . '/' . $this->data['id'], [
          'headers' => [
              'API-KEY' => 'token1',
              'APP-NAME' => 'test',
              'accept' => 'application/json',
          ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertEqualsIgnoringCase($this->data['id'], $body->id);
        $this->assertEquals($this->data['iso3'], $body->iso3);
        $this->assertEquals($this->data['country'], $body->country);
        $this->assertEquals($this->data['year'], $body->year);
        $this->assertEquals($this->data['name'], $body->name);
        $this->assertEquals($this->data['value'], $body->value);
        $this->assertEquals($this->data['tags'], $body->tags);
        $this->assertEquals('src3', $body->provider);

        // Get key figure using lower cased id.
        $response = $client->request('GET', $this->addPrefix('source-3') . '/' . strtolower($this->data['id']), [
          'headers' => [
              'API-KEY' => 'token1',
              'APP-NAME' => 'test',
              'accept' => 'application/json',
          ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertEqualsIgnoringCase($this->data['id'], $body->id);
        $this->assertEquals($this->data['iso3'], $body->iso3);
        $this->assertEquals($this->data['country'], $body->country);
        $this->assertEquals($this->data['year'], $body->year);
        $this->assertEquals($this->data['name'], $body->name);
        $this->assertEquals($this->data['value'], $body->value);
        $this->assertEquals($this->data['tags'], $body->tags);
        $this->assertEquals('src3', $body->provider);
    }

}
