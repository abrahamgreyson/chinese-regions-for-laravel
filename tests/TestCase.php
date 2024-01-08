<?php

namespace Abe\ChineseRegionsForLaravel\Tests;

use Abe\ChineseRegionsForLaravel\Providers\ChineseRegionsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JetBrains\PhpStorm\NoReturn;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ChineseRegionsServiceProvider::class,
        ];
    }

    #[NoReturn]
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        dd(config()->get('database'));
        $migration = include __DIR__.'/../database/migrations/create_chinese_regions_table.php';
        $migration->up();
    }
}
