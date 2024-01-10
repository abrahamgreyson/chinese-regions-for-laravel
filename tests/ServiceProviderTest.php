<?php

namespace Abe\ChineseRegionsForLaravel\Tests;

test('service provider is discovered', function () {
    $providers = config('app.providers');
    expect($providers)->toContain('Abe\ChineseRegionsForLaravel\ChineseRegionsServiceProvider');
});

test('commands are registered', function () {
    $commands = app('Illuminate\Contracts\Console\Kernel')->all();
    expect($commands)->toHaveKey('chinese-regions:import');
});

test('migration is registered', function () {
    $path = app('migrator')->paths();
    $names = array_map(function ($path) {
        return basename($path);
    }, $path);
    expect($names)->toContain('create_chinese_regions_table.php');
});
