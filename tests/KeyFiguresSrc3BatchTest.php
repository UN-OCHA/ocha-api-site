<?php 

namespace App\Tests;

use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class KeyFiguresSrc3BatchTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    protected $data = [
        'data' => [
            [
                'iso3' => 'afg',
                'country' => 'Afghanistan',
                'year' => '2022',
                'name' => 'Indicator',
                'value' => '777',
            ],
            [
                'iso3' => 'afg',
                'country' => 'Afghanistan',
                'year' => '2021',
                'name' => 'Indicator',
                'value' => '666',
            ],
        ],
    ];

    public function testOnSource3AsAdmin(): void
    {
        /** @var \Symfony\Component\HttpClient\Response\CurlResponse $response */
        $response = $this->http->request('POST', $this->addPrefix('source-3') . '/batch', [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertCount(2, $body->successful);
        $this->assertCount(0, $body->failed);
        $this->assertContains('src3_afg_2021_Indicator', $body->successful);
        $this->assertContains('src3_afg_2022_Indicator', $body->successful);
    }

    public function testOnSource3AsUser1(): void
    {
        /** @var \Symfony\Component\HttpClient\Response\CurlResponse $response */
        $response = $this->http->request('POST', $this->addPrefix('source-3') . '/batch', [
            'headers' => [
                'API-KEY' => 'token2',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        // User 1 does not have access to source 2.
        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertCount(0, $body->successful);
        $this->assertCount(2, $body->failed);
        $this->assertContains('src3_afg_2021_Indicator', $body->failed);
        $this->assertContains('src3_afg_2022_Indicator', $body->failed);
    }

    public function testOnSource3AsUser3(): void
    {
        /** @var \Symfony\Component\HttpClient\Response\CurlResponse $response */
        $response = $this->http->request('POST', $this->addPrefix('source-3') . '/batch', [
            'headers' => [
                'API-KEY' => 'token4',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent());
        $this->assertCount(2, $body->successful);
        $this->assertCount(0, $body->failed);
        $this->assertContains('src3_afg_2021_Indicator', $body->successful);
        $this->assertContains('src3_afg_2022_Indicator', $body->successful);
   }

}