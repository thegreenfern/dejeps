<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetencesAnnexes extends Model
{
    protected $table = 'competences_annexes';

    protected $fillable = [
        'trainee_id',
        'comp_accueil', 'comp_gonflage', 'comp_materiel_securite',
        'comp_bateau', 'comp_sites', 'comp_pmt', 'comp_rangement',
        'notes_formateur',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }

    public static function competencies(): array
    {
        return [
            'comp_accueil'           => ['label' => 'Accueil du public',    'description' => 'Accueillir et renseigner le public'],
            'comp_gonflage'          => ['label' => 'Gonflage',             'description' => 'Gonfler les blocs et vérifier les pressions'],
            'comp_materiel_securite' => ['label' => 'Matériel de sécurité', 'description' => 'Connaître le matériel de sécurité et savoir l\'utiliser'],
            'comp_bateau'            => ['label' => 'Bateau',               'description' => 'Piloter et amarrer le bateau'],
            'comp_sites'             => ['label' => 'Sites',                'description' => 'Connaître les sites et leurs spécificités'],
            'comp_pmt'               => ['label' => 'PMT / Snorkeling',     'description' => 'Animer des activités PMT et snorkeling'],
            'comp_rangement'         => ['label' => 'Rangement matériel',   'description' => 'Ranger et rincer le matériel en fin de journée'],
        ];
    }

    public function acquiredCount(): int
    {
        return collect(array_keys(self::competencies()))
            ->filter(fn($k) => $this->$k === 3)
            ->count();
    }

    public function globalStatus(): string
    {
        $keys    = array_keys(self::competencies());
        $allNull = collect($keys)->every(fn($k) => $this->$k === null);
        if ($allNull) return 'non_travaille';
        $allThree = collect($keys)->every(fn($k) => $this->$k === 3);
        if ($allThree) return 'valide';
        return 'en_cours';
    }

    public static function statusLabel(string $status): string
    {
        return match($status) {
            'non_travaille' => 'Non travaillé',
            'en_cours'      => 'En cours',
            'valide'        => 'Validé',
            default         => $status,
        };
    }

    public static function statusColor(string $status): string
    {
        return match($status) {
            'non_travaille' => 'bg-slate-100 text-slate-500 border-slate-200',
            'en_cours'      => 'bg-amber-50 text-amber-700 border-amber-200',
            'valide'        => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default         => 'bg-slate-100 text-slate-500 border-slate-200',
        };
    }
}
