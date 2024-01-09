<?php

namespace Abe\ChineseRegionsForLaravel\Tests\Unit;

use Abe\ChineseRegionsForLaravel\ChineseRegion;
use Illuminate\Support\Collection;
use Workbench\Database\Seeders\ChineseRegionsTestSeeder;

beforeEach(function () {
    $this->seed(ChineseRegionsTestSeeder::class);
});

test('can get all provinces', function () {
    $chineseRegion = new ChineseRegion();
    $provinces = $chineseRegion->provinces;
    expect($provinces)->toBeInstanceOf(Collection::class);
    $this->assertCount(3, $provinces);
    $provinces = $provinces->sortBy('code')->values();
    $this->assertEquals('黑龙江省', $provinces[2]->name);
});

test('province do not have parent', function () {
    $chineseRegion = new ChineseRegion();
    $province = $chineseRegion->provinces->first();
    expect(($province))->toBeInstanceOf(ChineseRegion::class);
    $this->assertNull($province->parent);
});

test('can get cities', function () {
    $chineseRegion = new ChineseRegion();
    $cities = $chineseRegion->cities->toArray();
    expect($cities)->toBeEmpty();
    $province = $chineseRegion->where('code', 1)->orderBy('code', 'desc')->first();
    $cities = $province->cities;
    $this->assertCount(3, $cities);
});

test('city has parent', function () {
    $chineseRegion = new ChineseRegion();
    $province = $chineseRegion->where('code', 23)->first();
    $city = $province->cities->first();
    expect($city->parent)->toBeInstanceOf(ChineseRegion::class);
    $this->assertEquals($province->code, $city->parent->code);
});

test('can get areas', function () {
    $chineseRegion = new ChineseRegion();
    $jixi = $chineseRegion->where('code', 2303)->first();
    $areas = $jixi->areas;
    expect($areas)->toHaveCount(2);
});

test('can get streets', function () {
    $chineseRegion = new ChineseRegion();
    $jixi = $chineseRegion->where('code', 2303)->first();
    $area = $jixi->areas->first();
    $streets = $area->streets;
    expect($streets)->toHaveCount(2);
    expect($streets->pluck('name')->toArray())->toMatchArray(['连珠山镇', '黑台镇']);
});

test('can get villages', function () {
    $street = ChineseRegion::where('code', 230382101)->first();
    $villages = $street->villages;
    expect($villages)->toHaveCount(2);
});

test('can get children', function () {
    // todo
    $street = ChineseRegion::where('code', 230382101)->first();
    dd($street->children);
});

test('village does not have children', function () {
    $village = ChineseRegion::where('code', 230382101205)->first();
    dd($village->children);
});
