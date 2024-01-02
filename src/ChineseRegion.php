<?php

namespace Abe\ChineseRegionsForLaravel;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChineseRegion extends Eloquent
{
    public function children(): HasMany
    {
        $foreignKey = match ($this->level) {
            1 => 'province_code',
            2 => 'city_code',
            3 => 'area_code',
            4 => 'street_code',
            default => null
        };

        return $this->hasMany(self::class, $foreignKey, 'code')
            ->where('level', $this->level + 1);
    }

    public function parent(): BelongsTo
    {
        $foreignKey = match ($this->level) {
            2 => 'province_code',
            3 => 'city_code',
            4 => 'area_code',
            5 => 'street_code',
            default => null
        };

        return $this->belongsTo(ChineseRegion::class, $foreignKey, 'code');
    }

    /**
     * 省份
     *
     * @return mixed
     */
    public function provinces(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->where('level', 1)->get()
        );
    }

    /**
     * 城市列表
     */
    public function cities(): HasMany
    {
        return $this->hasMany(self::class, 'province_code', 'code')
            ->where('level', 2)
            ->whereNull(['city_code', 'area_code', 'street_code']);
    }

    /**
     * 区列表
     */
    public function areas(): HasMany
    {
        return $this->hasMany(self::class, 'city_code', 'code')
            ->where('level', 3)
            ->whereNull(['area_code', 'street_code']);
    }

    /**
     * 区列表
     */
    public function streets(): HasMany
    {
        return $this->hasMany(self::class, 'area_code', 'code')
            ->where('level', 4)
            ->whereNull(['street_code']);
    }

    /**
     * 区列表
     */
    public function villages(): HasMany
    {
        return $this->hasMany(self::class, 'street_code', 'code')->where('level', 5);
    }
}
