<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;

abstract class ApiTestCase extends BaseApiTestCase
{
    /**
     * Avoid AP 4.1 createClient() deprecation; AP 5 defaults to false.
     * Required with Hautelook RefreshDatabaseTrait so each createClient()
     * does not reboot the kernel and wipe the DB.
     *
     * @see https://github.com/api-platform/core/issues/6971
     */
    protected static ?bool $alwaysBootKernel = false;
}
