<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    /** @use HasFactory<\Database\Factories\ThemeFactory> */
    use HasFactory;

    protected $guarded = ['id', 'code', 'name', 'color', 'created_at', 'updated_at'];

    public function standards(): HasMany
    {
        return $this->hasMany(Standard::class);
    }
}
