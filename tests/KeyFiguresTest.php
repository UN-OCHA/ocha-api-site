<?php 

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;

class KeyFiguresTest extends ApiTestCase
{
    use TestTrait;

    public function testGetCollectionAsAdmin(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('key_figures'), [
            'headers' => [
                'API-KEY' => 'token1',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(40, $response->toArray());
    }

    public function testGetCollectionAsUser1(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('key_figures'), [
            'headers' => [
                'API-KEY' => 'token2',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(20, $response->toArray());
    }

    public function testGetCollectionAsUser2(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('key_figures'), [
            'headers' => [
                'API-KEY' => 'token3',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(40, $response->toArray());
    }

}