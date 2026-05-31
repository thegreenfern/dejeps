<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineeProfile extends Model
{
    protected $fillable = [
        'trainee_id',
        'onboarding_step',
        'completed_at',
        'ice_breaking',
        'prior_experiences',
        'big5_answers',
        'big5_scores',
        'big5_completed_at',
        'trainee_comments',
    ];

    protected $casts = [
        'ice_breaking'       => 'array',
        'prior_experiences'  => 'array',
        'big5_answers'       => 'array',
        'big5_scores'        => 'array',
        'big5_completed_at'  => 'datetime',
        'completed_at'       => 'datetime',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }
}
