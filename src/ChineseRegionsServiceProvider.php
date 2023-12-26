<?php

namespace Abe\ChineseRegionsForLaravel;

use Abe\ChineseRegions\Commands\ChineseRegionsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasMigration('create_chinese_regions_table')
            ->hasCommand(ChineseRegionsCommand::class);
    }
}
