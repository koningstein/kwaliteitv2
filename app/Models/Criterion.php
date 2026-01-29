<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criterion extends Model
{
    /** @use HasFactory<\Database\Factories\CriterionFactory> */
    use HasFactory;

    protected $table = 'criteria';

    protected $guarded = [
        'id', 'standard_id', 'number', 'text', 'explanation', 'created_at', 'updated_at',
    ];

    public function standard(): BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(CriterionScore::class);
    }

    public function actionPoints(): HasMany
    {
        return $this->hasMany(ActionPoint::class);
    }
}
