<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chinese_regions', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('level')->comment('行政级别');
            $table->string('name')->comment('名称');
            $table->unsignedBigInteger('code')->comment('行政区划代码');

            // 上级省、自治区、直辖市、特别行政区
            $table->unsignedTinyInteger('province_code')->nullable()->comment('省级区划代码（上级）');
            // 上级地级市、自治州、盟、地区
            $table->unsignedSmallInteger('city_code')->nullable()->comment('地级行政区划代码（上级）');
            // 上级市辖区、县级市、县、自治县、旗、自治旗、特区、林区
            $table->unsignedTinyInteger('area_code')->nullable()->comment('县级行政区划代码（上级）');
            // 上级街道、镇、乡、民族乡、苏木、民族苏木、县辖区
            $table->unsignedBigInteger('street_code')->nullable()->comment('乡级行政区划代码（上级）');

            $table->timestamps();

            // for where
            $table->index('street_code');
            // for where
            $table->index('area_code');
            // for first(1)
            $table->index('code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chinese_regions');
    }
};
