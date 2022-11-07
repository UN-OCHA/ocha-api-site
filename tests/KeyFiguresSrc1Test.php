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
                'accept' => 'application/json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(40, $response->toArray());
    }

}