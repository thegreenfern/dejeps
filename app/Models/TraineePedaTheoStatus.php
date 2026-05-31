<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraineePedaTheoStatus extends Model
{
    protected $table = 'trainee_peda_theo_status';
    protected $fillable = ['trainee_id', 'level', 'status'];

    public const LEVELS = ['n1', 'n2', 'n3', 'n4'];

    public const LEVEL_LABELS = [
        'n1' => 'N1',
        'n2' => 'N2',
        'n3' => 'N3',
        'n4' => 'N4',
    ];

    public const STATUSES = ['nt', 'en_cours', 'valide'];

    public const STATUS_LABELS = [
        'nt'       => 'NT',
        'en_cours' => 'En cours',
        'valide'   => 'Validé',
    ];

    // DEJEPS topic slugs grouped by level
    public const LEVEL_TOPICS = [
        'n1' => ['pression_n1', 'prevention_n1'],
        'n2' => ['desaturation_n2', 'flottabilite_n2', 'ordinateur_n2', 'accidents_n2'],
        'n3' => ['essoufflement_n3', 'organisation_n3', 'narcose_n3'],
        'n4' => ['froid_n4', 'oreille_n4', 'detendeur_n4', 'surpression_n4', 'physique_n4'],
    ];

    public static function statusIndex(string $status): int
    {
        return array_search($status, self::STATUSES) ?: 0;
    }

    // Compute status from topic_progress for a given level.
    // NT → En cours: any séance at that level rated A or ECA (DEJEPS or "autre")
    // En cours → Validé: A count on DEJEPS topics >= ceil(total * 0.6)
    public static function computeStatus(array $topicProgress, string $level): string
    {
        $slugs      = self::LEVEL_TOPICS[$level] ?? [];
        $total      = count($slugs);
        $levelUpper = strtoupper($level);

        // Count A grades on DEJEPS topics (drives Validé threshold)
        $aCount = 0;
        foreach ($slugs as $slug) {
            if (self::normalizeRating($topicProgress[$slug]['global_rating'] ?? null) === '3') $aCount++;
        }

        // Any rated séance at this level (DEJEPS or autre) drives NT → En cours
        $anyRated = $aCount > 0;
        if (!$anyRated) {
            foreach ($slugs as $slug) {
                $r = $topicProgress[$slug]['global_rating'] ?? null;
                if (self::normalizeRating($r) === '2') { $anyRated = true; break; }
            }
        }
        if (!$anyRated) {
            foreach ($topicProgress as $slug => $entry) {
                if (!str_starts_with($slug, 'autre__')) continue;
                if (strtoupper($entry['session_level'] ?? '') !== $levelUpper) continue;
                $r = $entry['global_rating'] ?? null;
                $rn = self::normalizeRating($r);
                if ($rn === '3' || $rn === '2') { $anyRated = true; break; }
            }
        }

        if (!$anyRated) return 'nt';

        if ($total > 0 && $aCount >= (int) ceil($total * 0.6)) return 'valide';

        return 'en_cours';
    }

    // Returns per-topic data for display: slug → ['label', 'rating']
    public static function normalizeRating(?string $r): ?string
    {
        return match($r) { 'A' => '3', 'ECA' => '2', 'NT' => null, default => $r };
    }

    public static function topicDetails(array $topicProgress, string $level): array
    {
        $slugs  = self::LEVEL_TOPICS[$level] ?? [];
        $topics = collect(TraineeUc3::topics())->keyBy('slug');
        $result = [];

        foreach ($slugs as $slug) {
            $entry  = $topicProgress[$slug] ?? null;
            $result[$slug] = [
                'label'  => $topics[$slug]['label'] ?? $slug,
                'rating' => self::normalizeRating($entry['global_rating'] ?? null),
                'date'   => $entry['session_date'] ?? null,
            ];
        }

        return $result;
    }

    // Returns all autre__ slugs for a level (informational only).
    // Matches on session_level (e.g. "N2") rather than slug prefix, because the
    // slug encodes the full select-option value ("N2 · PE40" → "n2___pe40").
    public static function autreSeances(array $topicProgress, string $level): array
    {
        $levelUpper = strtoupper($level); // 'n2' → 'N2'
        $result = [];

        foreach ($topicProgress as $slug => $entry) {
            if (!str_starts_with($slug, 'autre__')) continue;
            $sessionLevel = strtoupper($entry['session_level'] ?? '');
            if ($sessionLevel !== $levelUpper) continue;
            $result[$slug] = [
                'label'  => $entry['session_label'] ?? $slug,
                'rating' => self::normalizeRating($entry['global_rating'] ?? null),
                'date'   => $entry['session_date'] ?? null,
            ];
        }

        return $result;
    }
}
