<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DirectionPlongeeEvaluation extends Model
{
    protected $fillable = [
        'trainee_id', 'evaluated_at', 'status',
        'comp_palanquees', 'comp_consignes', 'comp_reglementation',
        'comp_site', 'comp_navigation', 'comp_secours',
        'note_globale', 'instructor_notes',
    ];

    protected $casts = [
        'evaluated_at' => 'date',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }

    public static function competencies(): array
    {
        return [
            'comp_palanquees'    => ['label' => "Composer les palanquées en tenant compte des caractéristiques des pratiquants"],
            'comp_consignes'     => ['label' => "Établir et communiquer les consignes et procédures de sécurité adaptées au contexte"],
            'comp_reglementation'=> ['label' => "Respecter la réglementation et assurer les vérifications plongeurs et matériels"],
            'comp_site'          => ['label' => "Choisir un site adapté à la sécurité et au bon déroulement de l'activité"],
            'comp_navigation'    => ['label' => "Organiser la sortie et les manœuvres de navigation de façon adaptée"],
            'comp_secours'       => ['label' => "Mettre en œuvre la chaîne des secours avec le matériel et les ressources disponibles"],
        ];
    }

    public function computeNoteGlobale(): ?float
    {
        $total = 0;
        $count = 0;
        foreach (array_keys(self::competencies()) as $key) {
            $val = $this->$key;
            if ($val === null) continue;
            $total += $val;
            $count++;
        }
        return $count > 0 ? round($total / $count, 2) : null;
    }

    public static function statusLabel(string $status): string
    {
        return match($status) {
            'en_cours' => 'En cours',
            'valide'   => 'Validé',
            'echec'    => 'Échec',
            default    => $status,
        };
    }

    public static function statusColor(string $status): string
    {
        return match($status) {
            'en_cours' => 'bg-amber-50 text-amber-700 border-amber-200',
            'valide'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'echec'    => 'bg-red-50 text-red-700 border-red-200',
            default    => 'bg-slate-100 text-slate-500 border-slate-200',
        };
    }
}
