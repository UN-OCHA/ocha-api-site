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
        'full_name' => 'admin',
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
          'full_name' => 'full_name',
        ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

      $this->assertJsonContains([
        'email' => 'test@example.com',
        'username' => 'username',
        'full_name' => 'full_name',
        'can_read' => [],
        'can_write' => [],
      ]);
    }

    public function testMeEndpointPatch(): void
    {
      $response = static::createClient()->request('PATCH', $this->addPrefix('me'), [
        'headers' => [
          'API-KEY' => 'token1',
          'APP-NAME' => 'test',
          'accept' => 'application/json',
          'content-type' => 'application/merge-patch+json',
        ],
        'json' => [
          'email' => 'patchy@example',
          'webhook' => 'https://api.example/api/v42/webhook'
        ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

      $this->assertJsonContains([
        'email' => 'patchy@example',
        'username' => 'admin',
        'full_name' => 'admin',
        'token' => 'token1',
        'can_read' => [],
        'can_write' => [],
        'webhook' => 'https://api.example/api/v42/webhook',
      ]);
    }

}
