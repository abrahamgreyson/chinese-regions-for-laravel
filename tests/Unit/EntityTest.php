<?php

namespace Abe\ChineseRegionsForLaravel\Tests\Unit;

it('can get all provinces', function () {
    $chineseRegion = new \Abe\ChineseRegionsForLaravel\ChineseRegion();
    $provinces = $chineseRegion->provinces;
    $this->assertCount(3, $provinces);
    expect(true)->toBeTrue();
})->group('unit');
