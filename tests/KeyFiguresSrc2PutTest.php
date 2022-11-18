<?php 

namespace App\Tests;

use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class KeyFiguresSrc2PutTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $data = [
        'iso3' => 'afg',
        'country' => 'Afghanistan',
        'year' => '2022',
        'name' => 'Indicator',
        'value' => '777',
    ];

    public function testGetOnSource2AsAdmin(): void
    {
        /** @var \Symfony\Component\HttpClient\Response\CurlResponse $response */
        $response = $this->http->request('PUT', $this->addPrefix('source-2') . '/1', [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertEquals('1', $body->id);
        $this->assertEquals('afg', $body->iso3);
        $this->assertEquals('Afghanistan', $body->country);
        $this->assertEquals('2022', $body->year);
        $this->assertEquals('Indicator', $body->name);
        $this->assertEquals('777.00', $body->value);
        $this->assertEquals([], $body->tags);
        $this->assertEquals('src2', $body->provider);
    }

    public function testGetOnSource2AsUser1(): void
    {
        /** @var \Symfony\Component\HttpClient\Response\CurlResponse $response */
        $response = $this->http->request('PUT', $this->addPrefix('source-2') . '/2', [
            'headers' => [
                'API-KEY' => 'token2',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        // User 1 does not have access to source 2.
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testGetOnSource2AsUser2(): void
    {
        /** @var \Symfony\Component\HttpClient\Response\CurlResponse $response */
        $response = $this->http->request('PUT', $this->addPrefix('source-2') . '/3', [
            'headers' => [
                'API-KEY' => 'token3',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertEquals('3', $body->id);
        $this->assertEquals('afg', $body->iso3);
        $this->assertEquals('Afghanistan', $body->country);
        $this->assertEquals('2022', $body->year);
        $this->assertEquals('Indicator', $body->name);
        $this->assertEquals('777.00', $body->value);
        $this->assertEquals([], $body->tags);
        $this->assertEquals('src2', $body->provider);
    }

}