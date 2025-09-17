<?php

use Orchestra\Testbench\TestCase;

function test_case(): TestCase {
    return new class extends TestCase {
        protected function getPackageProviders($app)
        {
            return [\Kazitoha\ActivityLogger\ActivityLoggerServiceProvider::class];
        }
    };
}
