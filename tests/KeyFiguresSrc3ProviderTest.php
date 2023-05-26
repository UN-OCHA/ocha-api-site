<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\KeyFigures;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresSrc3ProviderTest extends ApiTestCase
{

  use RefreshDatabaseTrait;
  use TestTrait;

  protected $json = [
    'data' => [
        [
          'id' => 'src3_afg_2022_indicator',
          'iso3' => 'afg',
          'country' => 'Afghanistan',
          'year' => '2022',
          'name' => 'Indicator',
          'value' => '777',
        ],
        [
          'id' => 'src3_afg_2021_indicator',
          'iso3' => 'afg',
          'country' => 'Afghanistan',
          'year' => '2021',
          'name' => 'Indicator',
          'value' => '666',
        ],
        [
          'id' => 'src3_afg_2021_indicator',
          'iso3' => 'afg',
          'country' => 'Afghanistan',
          'year' => '2021',
          'name' => 'Indicator',
          'value' => '333',
      ],
    ],
  ];

  protected $data = [
    'id' => 'src3_afg_2021_indicator',
    'iso3' => 'afg',
    'country' => 'Afghanistan',
    'year' => '2021',
    'name' => 'Indicator',
    'value' => '444',
    'provider' => 'xyzzy',
  ];

  public function testKey(): void
  {
      $client = static::createClient();
      $client->disableReboot();

      // Create key figure.
      $response = $client->request('POST', $this->addPrefix('source-3') . '/batch', [
        'headers' => [
            'API-KEY' => 'token1',
            'APP-NAME' => 'test',
            'accept' => 'application/json',
        ],
        'json' => $this->json,
      ]);

      $this->assertEquals(201, $response->getStatusCode());
      $body = json_decode($response->getContent(), TRUE);
      $this->assertEquals('Created', $body['successful']['src3_afg_2022_indicator']);
      $this->assertEquals('Updated', $body['successful']['src3_afg_2021_indicator']);

      $id = 'src3_afg_2021_indicator';
      $iri = $this->findIriBy(KeyFigures::class, ['id' => $id]);
      $this->assertEquals('/api/v1/key_figures/' . $id, $iri);

      $response = $client->request('GET', $iri, [
        'headers' => [
            'API-KEY' => 'token1',
            'APP-NAME' => 'test',
            'accept' => 'application/json',
        ],
      ]);

      $this->assertEquals(200, $response->getStatusCode());

      $body = json_decode($response->getContent());
      $this->assertEqualsIgnoringCase($id, $body->id);
      $this->assertEquals($body->provider, 'src3');

      $response = static::createClient()->request('PUT', $this->addPrefix('source-3') . '/src3_afg_2021_indicator', [
        'headers' => [
            'API-KEY' => 'token1',
            'APP-NAME' => 'test',
            'accept' => 'application/json',
        ],
        'json' => $this->data,
      ]);

      $this->assertEquals(200, $response->getStatusCode());
      $body = json_decode($response->getContent());
      $this->assertEqualsIgnoringCase($id, $body->id);
      $this->assertEquals($body->provider, 'src3');

      $response = static::createClient()->request('PUT', $this->addPrefix('source-3') . '/src3_afg_2021_indicator', [
        'headers' => [
            'API-KEY' => 'token1',
            'APP-NAME' => 'test',
            'accept' => 'application/json',
        ],
        'json' => $this->data,
      ]);

      $this->assertEquals(201, $response->getStatusCode());
      $body = json_decode($response->getContent());
      $this->assertEqualsIgnoringCase($id, $body->id);
      $this->assertEquals($body->provider, 'src3');
  }

}
