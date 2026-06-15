<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriterionRemark extends Model
{
    protected $fillable = ['criterion_id', 'team_id', 'user_id', 'remark'];

    public function criterion(): BelongsTo { return $this->belongsTo(Criterion::class); }
    public function team(): BelongsTo { return $this->belongsTo(Team::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
