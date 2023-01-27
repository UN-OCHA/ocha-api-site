<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresSrc2Test extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    public function testGetOnSource2AsAdmin(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('source-2'), [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(10, $this->getBody($response));
    }

    public function testGetOnSource2AsUser1(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('source-2'), [
            'headers' => [
                'API-KEY' => 'token2',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ]
        ]);

        // User 1 does not have access to source 2.
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testGetOnSource2AsUser2(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('source-2'), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(10, $this->getBody($response));
    }

}
