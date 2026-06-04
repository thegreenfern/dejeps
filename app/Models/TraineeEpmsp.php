<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraineeEpmsp extends Model
{
    protected $table = 'trainee_epmsp';

    protected $fillable = [
        'trainee_id', 'type', 'evaluated_at', 'status', 'ratings', 'note_globale', 'instructor_notes',
    ];

    protected $casts = [
        'ratings'      => 'array',
        'evaluated_at' => 'date',
    ];

    public static function competencies(string $type): array
    {
        if ($type === '25m') {
            return [
                'respiration' => ['label' => "Créer et maintenir les conditions permettant la respiration sous-marine de l'accidenté", 'mandatory' => true],
                'remontee'    => ['label' => "Assurer une remontée à une vitesse sécuritaire sans se mettre en difficulté (10–17 m/min)", 'mandatory' => true],
                'rassurer'    => ['label' => "Rassurer le pratiquant (signe et contact visuel à minima à 2 reprises lors de la remontée)", 'mandatory' => false],
                'arret'       => ['label' => "Assurer un arrêt (≥ 5 s) entre 3 et 5 mètres et un tour d'horizon", 'mandatory' => true],
                'surface'     => ['label' => "Stabiliser la personne en surface puis réaliser le signe de détresse", 'mandatory' => true],
            ];
        }

        return [
            'identifier_besoins' => ['label' => "Identifier les besoins du pratiquant et proposer une séance adaptée aux prérogatives visées", 'mandatory' => true],
            'prise_en_charge'    => ['label' => "Prendre en charge le pratiquant : brief/debrief, animation, gestion du temps et de l'espace, posture professionnelle", 'mandatory' => false],
            'securite'           => ['label' => "Assurer la sécurité de la séance : identifier les risques, proposer des méthodes adaptées, sécuriser avant/pendant/après", 'mandatory' => true],
            'outils'             => ['label' => "Utiliser les outils et moyens pédagogiques appropriés à la mise en place de la séance", 'mandatory' => false],
            'entretien'          => ['label' => "Lors de l'entretien : justifier les choix, appliquer la réglementation, assurer la sécurité par l'organisation, maîtriser les secours", 'mandatory' => true],
        ];
    }

    public function computeNoteGlobale(): ?float
    {
        $comps = self::competencies($this->type);
        $total = 0;
        $count = 0;
        foreach ($comps as $key => $comp) {
            $val = $this->ratings[$key] ?? null;
            if ($val === null || $val === '') continue;
            $total += (int) $val;
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
