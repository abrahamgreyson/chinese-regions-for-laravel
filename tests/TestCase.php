<?php

namespace ChineseRegionsForLaravel\ChineseRegionsForLaravel\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use ChineseRegionsForLaravel\ChineseRegionsForLaravel\ChineseRegionsForLaravelServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'ChineseRegionsForLaravel\\ChineseRegionsForLaravel\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ChineseRegionsForLaravelServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_chinese-regions-for-laravel_table.php.stub';
        $migration->up();
        */
    }
}
