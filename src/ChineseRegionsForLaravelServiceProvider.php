<?php

namespace Abe\ChineseRegions;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Abe\ChineseRegions\Commands\ChineseRegionsCommand;

class ChineseRegionsServiceProvider extends PackageServiceProvider
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
            ->hasMigration('create_chinese_regions_table')
            ->hasCommand(ChineseRegionsCommand::class);
    }
}
