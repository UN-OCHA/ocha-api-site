<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    public function testGetCollectionAsAdmin(): void
    {
        $response = static::createClientWithCredentials(
          [
            'API-KEY' => 'token1',
            'APP-NAME' => 'test',
            'accept' => 'application/json',
          ]
        )->request('GET', $this->addPrefix('key_figures'));

        $this->assertResponseStatusCodeSame(200);
        $this->assertGreaterThan(40, $this->getBody($response));
    }

    public function testGetCollectionAsUser1(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('key_figures'), [
            'headers' => [
                'API-KEY' => 'token2',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(30, $this->getBody($response));
    }

    public function testGetCollectionAsUser2(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('key_figures'), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(40, $this->getBody($response));
    }

}
