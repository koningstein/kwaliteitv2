<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Standard extends Model
{
    /** @use HasFactory<\Database\Factories\StandardFactory> */
    use HasFactory;

    protected $fillable = ['theme_id', 'code', 'name', 'description'];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(Criterion::class);
    }
}
