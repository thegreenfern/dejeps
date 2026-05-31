<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InitialAssessment extends Model
{
    protected $fillable = [
        'trainee_id',
        'competency_id',
        'trainee_score',
        'trainee_evidence',
        'tutor_score',
        'tutor_notes',
        'hours_target',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(Competency::class);
    }
}
