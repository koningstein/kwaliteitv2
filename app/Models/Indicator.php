<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Indicator extends Model
{
    /** @use HasFactory<\Database\Factories\IndicatorFactory> */
    use HasFactory;

    protected $fillable = ['criterion_id', 'text', 'sort_order'];

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class);
    }
}
