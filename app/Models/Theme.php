<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    /** @use HasFactory<\Database\Factories\ThemeFactory> */
    use HasFactory;

    protected $fillable = ['code', 'name', 'color', 'is_deletable'];

    protected function casts(): array
    {
        return [
            'is_deletable' => 'boolean',
        ];
    }

    public function standards(): HasMany
    {
        return $this->hasMany(Standard::class);
    }
}
