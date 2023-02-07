<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class KeyFiguresN8nTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestTrait;

    public function testN8nEndpoints(): void
    {
      $endpoints = [
        'n8n/templates/categories/1',
        'n8n/templates/categories',
        'n8n/templates/collections/1',
        'n8n/templates/collections',
        'n8n/templates/workflows/1',
        'n8n/workflows/templates/1',
        'n8n/templates/workflows',
      ];
      foreach ($endpoints as $endpoint) {
        $response = static::createClient()->request('GET', $this->addPrefix($endpoint), [
            'headers' => [
                'accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
      }
    }

}
