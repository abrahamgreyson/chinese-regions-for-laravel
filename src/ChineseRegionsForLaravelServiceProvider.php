<?php

namespace ChineseRegionsForLaravel\ChineseRegionsForLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ChineseRegionsForLaravel\ChineseRegionsForLaravel\Commands\ChineseRegionsForLaravelCommand;

class ChineseRegionsForLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('chinese-regions-for-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_chinese-regions-for-laravel_table')
            ->hasCommand(ChineseRegionsForLaravelCommand::class);
    }
}
