<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineePedaStatus extends Model
{
    protected $table = 'trainee_peda_status';

    protected $fillable = ['trainee_id', 'level', 'status', 'is_manual'];

    protected $casts = ['is_manual' => 'boolean'];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }

    // ── Constants ──────────────────────────────────────────────────────────

    public const LEVELS = ['bapteme', 'n1', 'n2', 'n3'];

    public const LEVEL_LABELS = [
        'bapteme' => 'Baptême',
        'n1'      => 'N1',
        'n2'      => 'N2',
        'n3'      => 'N3',
    ];

    // Slug prefixes in topic_progress that belong to each Peda level
    public const LEVEL_SLUG_PREFIXES = [
        'bapteme' => ['pratique_bapt_'],
        'n1'      => ['pratique_pe20_'],
        'n2'      => ['pratique_pa20_', 'pratique_pe40_'],
        'n3'      => ['pratique_pa40_', 'pratique_pe60_', 'pratique_pa60_'],
    ];

    public const STATUSES = ['nt', 'observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'];

    public const STATUS_LABELS = [
        'nt'                    => 'NT',
        'observation'           => 'Observation',
        'supervision_directe'   => 'Supervision directe',
        'supervision_indirecte' => 'Supervision indirecte',
        'autonomie'             => 'Autonomie',
    ];

    // Situation value in topic_progress that unlocks advancement to each status
    public const SITUATION_FOR_STATUS = [
        'observation'           => 'observation',
        'supervision_directe'   => 'supervision_directe',
        'supervision_indirecte' => 'supervision_indirecte',
        'autonomie'             => 'autonomie',
    ];

    public const A_GRADES_NEEDED = 2;

    // ── Helpers ────────────────────────────────────────────────────────────

    public static function statusIndex(string $status): int
    {
        return array_search($status, self::STATUSES) ?: 0;
    }

    public static function nextStatus(string $status): ?string
    {
        $idx = self::statusIndex($status);
        return self::STATUSES[$idx + 1] ?? null;
    }

    /**
     * Given a trainee's topic_progress JSON and a level slug,
     * count A-graded séances by situation for that level.
     * Returns ['observation' => int, 'supervision_directe' => int, ...]
     */
    public static function countAGradesBySituation(array $topicProgress, string $level): array
    {
        $prefixes = self::LEVEL_SLUG_PREFIXES[$level] ?? [];
        $counts   = array_fill_keys(['observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'], 0);

        foreach ($topicProgress as $slug => $data) {
            $matches = false;
            foreach ($prefixes as $prefix) {
                if (str_starts_with($slug, $prefix)) { $matches = true; break; }
            }
            if (!$matches) continue;

            $situation = $data['situation'] ?? null;
            $grade     = $data['global_rating'] ?? null;

            if ($grade === '3' && isset($counts[$situation])) {
                $counts[$situation]++;
            }
        }

        return $counts;
    }

    /**
     * Compute the status the automation engine would assign.
     * $thresholds: ['obs_sd' => int, 'sd_si' => int, 'si_auto' => int]
     * Defaults to A_GRADES_NEEDED for any missing key.
     */
    public static function computeAutoStatus(array $aGradesBySituation, array $thresholds = []): string
    {
        $needed = [
            'observation'           => $thresholds['obs_sd']   ?? self::A_GRADES_NEEDED,
            'supervision_directe'   => $thresholds['sd_si']    ?? self::A_GRADES_NEEDED,
            'supervision_indirecte' => $thresholds['si_auto']  ?? self::A_GRADES_NEEDED,
            'autonomie'             => 1,
        ];

        $status = 'nt';
        foreach (['observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'] as $situation) {
            if (($aGradesBySituation[$situation] ?? 0) >= $needed[$situation]) {
                $status = $situation;
            } else {
                break;
            }
        }
        return $status;
    }

    /**
     * Resolve effective thresholds for a trainee, falling back to global settings.
     */
    public static function resolveThresholds(?object $profile, object $settings): array
    {
        return [
            'obs_sd'  => $profile?->peda_threshold_obs_sd  ?? $settings->threshold_obs_sd  ?? self::A_GRADES_NEEDED,
            'sd_si'   => $profile?->peda_threshold_sd_si   ?? $settings->threshold_sd_si   ?? self::A_GRADES_NEEDED,
            'si_auto' => $profile?->peda_threshold_si_auto ?? $settings->threshold_si_auto ?? self::A_GRADES_NEEDED,
        ];
    }

    /**
     * Check if the automation engine would advance beyond current persisted status.
     */
    public static function checkAutomation(string $currentStatus, array $topicProgress, string $level, array $thresholds = []): ?string
    {
        $aGrades    = self::countAGradesBySituation($topicProgress, $level);
        $autoStatus = self::computeAutoStatus($aGrades, $thresholds);

        if (self::statusIndex($autoStatus) > self::statusIndex($currentStatus)) {
            return $autoStatus;
        }
        return null;
    }
}
