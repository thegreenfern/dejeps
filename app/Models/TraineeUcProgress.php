<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineeUcProgress extends Model
{
    protected $fillable = [
        'trainee_id',
        'uc',
        'dossier_url',
        'status',
        'rating',
        'instructor_notes',
        'milestone_progress',
    ];

    protected $casts = [
        'milestone_progress' => 'array',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }

    /**
     * The 7 tracked project milestones (excludes Oral final, Remédiation, Rattrapage).
     * $submissionDate: Y-m-d string from ProgramSettings, defaults to 2026-06-12.
     */
    public static function milestoneDefinitions(?string $submissionDate = null): array
    {
        return [
            ['slug' => 'diagnostic',               'label' => 'Diagnostic de la structure',     'date' => '2026-01-05', 'color' => 'slate'],
            ['slug' => 'validation_problematique', 'label' => 'Validation de la problématique', 'date' => '2026-02-27', 'color' => 'violet'],
            ['slug' => 'conception_planification', 'label' => 'Conception et planification',    'date' => '2026-03-02', 'color' => 'slate'],
            ['slug' => 'phase_test_analyse',       'label' => 'Phase de test & analyse',        'date' => '2026-05-11', 'color' => 'slate'],
            ['slug' => 'redaction_dossier',        'label' => 'Rédaction du dossier',           'date' => '2026-05-30', 'color' => 'amber'],
            ['slug' => 'depot_dossier',            'label' => 'Dépôt du dossier',              'date' => $submissionDate ?? '2026-06-12', 'color' => 'red'],
            ['slug' => 'oral_blanc',               'label' => 'Oral blanc (préparation)',       'date' => '2026-06-13', 'color' => 'slate'],
        ];
    }

    public static function milestoneStatusLabel(string $status): string
    {
        return match($status) {
            'in_progress' => 'En cours',
            'done'        => 'Terminé',
            default       => 'Non fait',
        };
    }

    public static function statusLabel(string $status): string
    {
        return match($status) {
            'not_started' => 'Non commencé',
            'in_progress' => 'En cours',
            'submitted'   => 'Dossier déposé',
            'evaluated'   => 'Évalué',
            default       => $status,
        };
    }

    public static function statusColor(string $status): string
    {
        return match($status) {
            'not_started' => 'slate',
            'in_progress' => 'amber',
            'submitted'   => 'sky',
            'evaluated'   => 'emerald',
            default       => 'slate',
        };
    }
}
