<?php 

namespace App\Tests;

use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class KeyFiguresTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    public function testGetCollectionAsAdmin(): void
    {
        $response = $this->http->request('GET', $this->addPrefix('key_figures'), [
            'headers' => [
                'API-KEY' => 'token1',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(40, $this->getBody($response));
    }

    public function testGetCollectionAsUser1(): void
    {
        $response = $this->http->request('GET', $this->addPrefix('key_figures'), [
            'headers' => [
                'API-KEY' => 'token2',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(20, $this->getBody($response));
    }

    public function testGetCollectionAsUser2(): void
    {
        $response = $this->http->request('GET', $this->addPrefix('key_figures'), [
            'headers' => [
                'API-KEY' => 'token3',
                'accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(40, $this->getBody($response));
    }

}