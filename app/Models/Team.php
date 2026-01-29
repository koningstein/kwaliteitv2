<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function leaders(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_leader_teams');
    }

    public function actionPoints(): HasMany
    {
        return $this->hasMany(ActionPoint::class);
    }
}
