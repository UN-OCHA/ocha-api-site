<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class UserTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    public function testMeEndpoint(): void
    {
      $response = static::createClient()->request('GET', $this->addPrefix('me'), [
        'headers' => [
          'API-KEY' => 'token1',
          'APP-NAME' => 'test',
          'accept' => 'application/json',
        ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

      $this->assertJsonContains([
        'id' => 1,
        'email' => 'admin@example',
        'username' => 'admin',
        'FullName' => 'admin',
        'token' => 'token1',
        'can_read' => [],
        'can_write' => [],
      ]);
    }

    public function testNonExistingMeEndpoint(): void
    {
      $response = static::createClient()->request('GET', $this->addPrefix('me'), [
        'headers' => [
          'API-KEY' => 'not',
          'APP-NAME' => 'test',
          'accept' => 'application/json',
        ],
      ]);

      $this->assertEquals($response->getStatusCode(), 401);
    }

    public function testRegister(): void
    {
      $response = static::createClient()->request('POST', $this->addPrefix('register'), [
        'headers' => [
          'APP-NAME' => 'test',
          'accept' => 'application/json',
        ],
        'json' => [
          'email' => 'test@example.com',
          'username' => 'username',
          'FullName' => 'fullname',
        ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

      $this->assertJsonContains([
        'email' => 'test@example.com',
        'username' => 'username',
        'FullName' => 'fullname',
        'can_read' => [],
        'can_write' => [],
      ]);
    }

}
