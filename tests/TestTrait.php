<?php 

namespace App\Tests;

trait TestTrait {
    private $prefix = '/api/v1/';

    protected function addPrefix(string $url) : string {
        return 'http://api-test.docksal.site' . rtrim($this->prefix, '/') . '/' . ltrim($url, '/');
    }
}