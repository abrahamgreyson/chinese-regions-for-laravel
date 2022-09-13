<?php

namespace ChineseRegionsForLaravel\ChineseRegionsForLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ChineseRegionsForLaravel\ChineseRegionsForLaravel\ChineseRegionsForLaravel
 */
class ChineseRegionsForLaravel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ChineseRegionsForLaravel\ChineseRegionsForLaravel\ChineseRegionsForLaravel::class;
    }
}
