<?php

namespace Abe\ChineseRegionsForLaravel\Tests\Unit;

it('can get all provinces', function () {
    $chineseRegion = new \Abe\ChineseRegionsForLaravel\ChineseRegion();
    $provinces = $chineseRegion->provinces;
    dd($provinces);
    expect(true)->toBeTrue();
})->group('unit');
