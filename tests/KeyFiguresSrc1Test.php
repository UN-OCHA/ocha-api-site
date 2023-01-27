<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresSrc1Test extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    public function testGetOnSource1AsAdmin(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('source1'), [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(30, $this->getBody($response));
    }

    public function testGetOnSource1AsUser1(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('source1'), [
            'headers' => [
                'API-KEY' => 'token2',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(30, $this->getBody($response));
    }

    public function testGetOnSource1AsUser2(): void
    {
        $response = static::createClient()->request('GET', $this->addPrefix('source1'), [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(30, $this->getBody($response));
    }

}
