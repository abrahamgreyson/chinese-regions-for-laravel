<?php

namespace Workbench\Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ChineseRegionsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('chinese_regions')->insert([
            // province level
            ['name' => '黑龙江省', 'code' => 23, 'level' => 1, 'province_code' => null, 'city_code' => null, 'area_code' => null, 'street_code' => null],
            ['name' => '北京市', 'code' => 11, 'level' => 1, 'province_code' => null, 'city_code' => null, 'area_code' => null, 'street_code' => null],
            ['name' => '辽宁省', 'code' => 21, 'level' => 1,  'province_code' => null, 'city_code' => null, 'area_code' => null, 'street_code' => null],
            // city level
            ['name' => '鸡西市', 'code' => 2303, 'level' => 2, 'province_code' => 23, 'city_code' => null, 'area_code' => null, 'street_code' => null],
            ['name' => '鹤岗市', 'code' => 2304, 'level' => 2, 'province_code' => 23, 'city_code' => null, 'area_code' => null, 'street_code' => null],
            ['name' => '黑河市', 'code' => 2311, 'level' => 2, 'province_code' => 23, 'city_code' => null, 'area_code' => null, 'street_code' => null],
            // area level
            ['name' => '密山市', 'code' => 230382, 'level' => 3, 'province_code' => 23, 'city_code' => 2303, 'area_code' => null, 'street_code' => null],
            ['name' => '鸡东县', 'code' => 230321, 'level' => 3, 'province_code' => 23, 'city_code' => 2303, 'area_code' => null, 'street_code' => null],
            // street level
            ['name' => '连珠山镇', 'code' => 230382101, 'level' => 4, 'province_code' => 23, 'city_code' => 2303, 'area_code' => 230382, 'street_code' => null],
            ['name' => '黑台镇', 'code' => 230382104, 'level' => 4, 'province_code' => 23, 'city_code' => 2303, 'area_code' => 230382, 'street_code' => null],
            // village level
            ['name' => '永泉村委会', 'code' => 230382101205, 'level' => 5, 'province_code' => 23, 'city_code' => 2303, 'area_code' => 230382, 'street_code' => 230382101],
            ['name' => '连珠山村委会', 'code' => 230382101210, 'level' => 5, 'province_code' => 23, 'city_code' => 2303, 'area_code' => 230382, 'street_code' => 230382101],
            ['name' => '榆树村委会', 'code' => 230382104209, 'level' => 5, 'province_code' => 23, 'city_code' => 2303, 'area_code' => 230382, 'street_code' => 230382104],
        ]);
    }
}
