<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainee extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'date_of_birth', 'photo_path', 'cv_path'];

    protected $casts = ['date_of_birth' => 'date'];

    public function profile(): HasOne
    {
        return $this->hasOne(TraineeProfile::class);
    }

    public function initialAssessments(): HasMany
    {
        return $this->hasMany(InitialAssessment::class);
    }

    public function ucProgress(): HasMany
    {
        return $this->hasMany(TraineeUcProgress::class);
    }

    public function epmsp(): HasMany
    {
        return $this->hasMany(TraineeEpmsp::class);
    }

    public function uc3(): HasOne
    {
        return $this->hasOne(TraineeUc3::class);
    }

    public function pedaStatuses(): HasMany
    {
        return $this->hasMany(TraineePedaStatus::class);
    }

    public function pedaTheoStatuses(): HasMany
    {
        return $this->hasMany(TraineePedaTheoStatus::class);
    }

    public function hasCompletedOnboarding(): bool
    {
        return $this->profile?->completed_at !== null;
    }
}
