<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class APITestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withHeader('Authorization', 'Bearer ' . "f");
    }
}
