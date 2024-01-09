<?php

namespace Abe\ChineseRegionsForLaravel\Tests;

use Abe\ChineseRegionsForLaravel\Providers\ChineseRegionsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\Database\Seeders\ChineseRegionsTestSeeder;

class TestCase extends Orchestra
{
    use RefreshDatabase, WithWorkbench;

    public function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}
