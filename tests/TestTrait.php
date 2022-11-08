<?php 

namespace App\Tests;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\Response\CurlResponse;

trait TestTrait {
    private $prefix = '/api/v1/';
    private $http;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->http = new CurlHttpClient();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->http = NULL;
    }

    protected function addPrefix(string $url) : string {
        return 'http://api-test.docksal.site' . rtrim($this->prefix, '/') . '/' . ltrim($url, '/');
    }

    protected function getBody(CurlResponse $response) {
        return json_decode($response->getContent());
    }
}