<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActionPointStatus extends Model
{
    /** @use HasFactory<\Database\Factories\ActionPointStatusFactory> */
    use HasFactory;

    protected $guarded = ['id', 'name'];

    public function actionPoints(): HasMany
    {
        return $this->hasMany(ActionPoint::class);
    }
}
