<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresFutureTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    public function testMeEndpoint(): void
    {
      $response = static::createClient()->request('GET', '/api/v1/source1', [
        'headers' => [
          'API-KEY' => 'token1',
          'APP-NAME' => 'test',
          'accept' => 'application/json',
        ],
      ]);

      $this->assertResponseStatusCodeSame(404);
    }

}
