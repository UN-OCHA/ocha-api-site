<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresSrc3BatchTest extends ApiTestCase
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
                'dummy' => 'test',
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
        $response = static::createClient()->request('POST', $this->addPrefix('source-3') . '/batch', [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertCount(2, $body['successful']);
        $this->assertCount(0, $body['failed']);
        $this->assertArrayHasKey(strtolower('src3_afg_2021_Indicator'), $body['successful']);
        $this->assertArrayHasKey(strtolower('src3_afg_2022_Indicator'), $body['successful']);
    }

    public function testOnSource3AsAdminLdJson(): void
    {
        $response = static::createClient()->request('POST', $this->addPrefix('source-3') . '/batch', [
            'headers' => [
                'API-KEY' => 'token1',
                'APP-NAME' => 'test',
                'accept' => 'application/ld+json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertCount(2, $body['successful']);
        $this->assertCount(0, $body['failed']);
        $this->assertArrayHasKey(strtolower('src3_afg_2021_Indicator'), $body['successful']);
        $this->assertArrayHasKey(strtolower('src3_afg_2022_Indicator'), $body['successful']);
    }

    public function testOnSource3AsUser1(): void
    {
        $response = static::createClient()->request('POST', $this->addPrefix('source-3') . '/batch', [
            'headers' => [
                'API-KEY' => 'token2',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        // User 1 does not have access to source 3.
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testOnSource3AsUser3(): void
    {
        $response = static::createClient()->request('POST', $this->addPrefix('source-3') . '/batch', [
            'headers' => [
                'API-KEY' => 'token4',
                'APP-NAME' => 'test',
                'accept' => 'application/json',
            ],
            'json' => $this->data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = json_decode($response->getContent(), TRUE);
        $this->assertCount(2, $body['successful']);
        $this->assertCount(0, $body['failed']);
        $this->assertArrayHasKey(strtolower('src3_afg_2021_Indicator'), $body['successful']);
        $this->assertArrayHasKey(strtolower('src3_afg_2022_Indicator'), $body['successful']);
   }

   public function testOnSource3WithoutValueAsUser3(): void
   {
        $record = [
            'plan_id' => 1159,
            'external_id' => 1159,
            'updated' => '2023-11-28 14:52:57',
            'name' => 'Top sectors',
            'year' => '2024',
            'code' => 'HCMR24',
            'iso3' => 'cmr',
            'country' => 'Cameroon',
            'source' => 'FTS',
            'url' => 'https://fts.unocha.org/appeals/1159/summary',
            'description' => 'Cameroon Humanitarian Response Plan 2024 [1159]',
            'total_funding' => 0,
            'value_type' => 'list',
            'archived' => 0,
            'sectors' => [
            ]
        ];

        $response = static::createClient()->request('POST', $this->addPrefix('source-3') . '/batch', [
           'headers' => [
               'API-KEY' => 'token4',
               'APP-NAME' => 'test',
               'accept' => 'application/json',
           ],
           'json' => [
                'data' => [
                    $record,
                ],
            ],
       ]);

       $this->assertEquals(201, $response->getStatusCode());

       $body = json_decode($response->getContent(), TRUE);
       $this->assertCount(1, $body['successful']);
       $this->assertCount(0, $body['failed']);
       $this->assertArrayHasKey(strtolower('src3_cmr_2024_1159_top-sectors'), $body['successful']);
  }

}
