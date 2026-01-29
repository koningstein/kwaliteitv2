<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportingPeriod extends Model
{
    /** @use HasFactory<\Database\Factories\ReportingPeriodFactory> */
    use HasFactory;

    protected $guarded = ['id', 'slug', 'label', 'is_active', 'sort_order'];

    public function scores(): HasMany
    {
        return $this->hasMany(CriterionScore::class);
    }
}
