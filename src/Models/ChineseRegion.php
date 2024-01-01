<?php

namespace Abe\ChineseRegionsForLaravel\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChineseRegion extends Eloquent
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChineseRegion::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChineseRegion::class, 'parent_id');
    }
}
