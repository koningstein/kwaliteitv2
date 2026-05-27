<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActionPoint extends Model
{
    /** @use HasFactory<\Database\Factories\ActionPointFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_by', 'updated_by'];

    protected static function booted(): void
    {
        static::creating(function (ActionPoint $model) {
            $model->created_by = auth()->id();
        });

        static::updating(function (ActionPoint $model) {
            $model->updated_by = auth()->id();
        });
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ActionPointStatus::class, 'action_point_status_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'action_point_participants')->withTimestamps();
    }
}
