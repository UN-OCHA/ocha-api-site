<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresFutureTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    public function testSrc1Endpoint(): void
    {
      $response = static::createClient()->request('GET', $this->addPrefix('source1'), [
        'headers' => [
          'API-KEY' => 'token1',
          'APP-NAME' => 'test',
          'accept' => 'application/json',
        ],
      ]);

      $this->assertResponseStatusCodeSame(404);
    }

}
