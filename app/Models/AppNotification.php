<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppNotification extends Model
{
    protected $table = 'app_notifications';

    protected $fillable = [
        'recipient_type',
        'recipient_id',
        'trainee_id',
        'type',
        'slug',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'read_at' => 'datetime',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    // ---------------------------------------------------------------

    public static function forInstructor(): \Illuminate\Database\Eloquent\Builder
    {
        return static::where('recipient_type', 'instructor');
    }

    public static function forTrainee(int $traineeId): \Illuminate\Database\Eloquent\Builder
    {
        return static::where('recipient_type', 'trainee')
                     ->where('recipient_id', $traineeId);
    }

    public static function unreadCountFor(string $role, ?int $traineeId): int
    {
        if ($role === 'instructor') {
            return static::forInstructor()->whereNull('read_at')->count();
        }
        if ($role === 'trainee' && $traineeId) {
            return static::forTrainee($traineeId)->whereNull('read_at')->count();
        }
        return 0;
    }

    // ---------------------------------------------------------------

    public static function notifyInstructor(int $traineeId, string $slug, array $data = []): void
    {
        static::create([
            'recipient_type' => 'instructor',
            'recipient_id'   => null,
            'trainee_id'     => $traineeId,
            'type'           => 'seance_added',
            'slug'           => $slug,
            'data'           => $data,
        ]);
    }

    public static function notifyTrainee(int $traineeId, string $slug, array $data = []): void
    {
        static::create([
            'recipient_type' => 'trainee',
            'recipient_id'   => $traineeId,
            'trainee_id'     => $traineeId,
            'type'           => 'evaluation_added',
            'slug'           => $slug,
            'data'           => $data,
        ]);
    }

    public static function notifyInstructorReviewRequest(int $traineeId, array $data = []): void
    {
        static::create([
            'recipient_type' => 'instructor',
            'recipient_id'   => null,
            'trainee_id'     => $traineeId,
            'type'           => 'review_request',
            'slug'           => 'review_request',
            'data'           => $data,
        ]);
    }

    public static function notifyTraineeFeedback(int $traineeId, array $data = []): void
    {
        static::create([
            'recipient_type' => 'trainee',
            'recipient_id'   => $traineeId,
            'trainee_id'     => $traineeId,
            'type'           => 'project_feedback',
            'slug'           => 'project_feedback',
            'data'           => $data,
        ]);
    }
}
