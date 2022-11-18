<?php 

namespace App\Tests;

use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class KeyFiguresSrc1Test extends WebTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    public function testGetOnSource1AsAdmin(): void
    {
        /** @var \Symfony\Component\HttpClient\Response\CurlResponse $response */
        $response = $this->http->request('GET', $this->addPrefix('source1'), [
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
        /** @var \Symfony\Component\HttpClient\Response\CurlResponse $response */
        $response = $this->http->request('GET', $this->addPrefix('source1'), [
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
        /** @var \Symfony\Component\HttpClient\Response\CurlResponse $response */
        $response = $this->http->request('GET', $this->addPrefix('source1'), [
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