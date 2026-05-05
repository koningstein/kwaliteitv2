<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluationFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_by', 'updated_by'];

    protected static function booted(): void
    {
        static::creating(function (Evaluation $model) {
            $model->created_by = auth()->id();
        });

        static::updating(function (Evaluation $model) {
            $model->updated_by = auth()->id();
        });
    }

    public function actionPoint(): BelongsTo
    {
        return $this->belongsTo(ActionPoint::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
