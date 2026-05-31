<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraineeUc3 extends Model
{
    protected $table = 'trainee_uc3';

    protected $fillable = ['trainee_id', 'status', 'subject', 'ratings', 'topic_progress', 'trainee_topic_progress', 'session_notes', 'interview_notes', 'peda_timeline_overrides', 'peda_theo_timeline_overrides'];

    protected $casts = ['ratings' => 'array', 'topic_progress' => 'array', 'trainee_topic_progress' => 'array', 'peda_timeline_overrides' => 'array', 'peda_theo_timeline_overrides' => 'array'];

    public static function competencies(): array
    {
        return [
            'section_enseigner' => [
                'label' => "EC de conduire une démarche d'enseignement théorique de la plongée",
                'groups' => [
                    'identifier_besoins' => [
                        'label' => "EC d'identifier les besoins du pratiquant au regard du niveau préparé et des prérogatives qui y sont associées.",
                        'items' => [
                            'contenus_pertinents' => [
                                'label'     => "EC d'identifier les contenus pertinents au regard du niveau préparé et des prérogatives associées",
                                'mandatory' => true,
                            ],
                            'seance_besoins' => [
                                'label'     => "EC de proposer une séance qui se limite aux besoins du pratiquant",
                                'mandatory' => false,
                            ],
                        ],
                    ],
                    'conduire_seance' => [
                        'label' => "EC de conduire une séance d'enseignement de la théorie appliquée à la pratique.",
                        'items' => [
                            'informer_objectifs' => [
                                'label'     => "EC d'informer les participants des objectifs visés",
                                'mandatory' => false,
                            ],
                            'seance_realiste' => [
                                'label'     => "EC de proposer une séance réaliste et correspondant aux attentes d'un contexte professionnel (animation / gestion du temps et espace / savoir être)",
                                'mandatory' => true,
                            ],
                            'contenu_pratique' => [
                                'label'     => "EC de produire un contenu de séance orienté sur la pratique qui doit contenir impérativement des illustrations de terrain",
                                'mandatory' => true,
                            ],
                            'moyens_pedagogiques' => [
                                'label'     => "EC d'utiliser des moyens pédagogiques appropriés",
                                'mandatory' => false,
                            ],
                        ],
                    ],
                ],
            ],
            'section_expliciter' => [
                'label' => "EC d'expliciter, lors d'un entretien, la séance d'enseignement théorique proposée",
                'groups' => [
                    'justifier_choix' => [
                        'label' => "EC de justifier les choix effectués lors de la séance de théorie et de l'étendre à d'autres séances",
                        'items' => [
                            'contexte_seance' => [
                                'label'     => "EC d'identifier et de prendre en compte le contexte dans lequel se situe la séance (place dans la formation, acquis, prérequis)",
                                'mandatory' => true,
                            ],
                            'connaissances_niveaux' => [
                                'label'     => "EC de mobiliser les connaissances et les savoirs attendus à chaque niveau de pratiquant",
                                'mandatory' => false,
                            ],
                        ],
                    ],
                    'progression_pedagogique' => [
                        'label' => "EC de construire puis conduire une progression pédagogique",
                        'items' => [
                            'evolution_progression' => [
                                'label'     => "Propose une évolution / progression permettant l'atteinte des objectifs de la séance et du niveau préparé",
                                'mandatory' => false,
                            ],
                            'indicateurs_evaluation' => [
                                'label'     => "Identifie des indicateurs d'évaluation permettant de mesurer l'atteinte des objectifs de la séance",
                                'mandatory' => true,
                            ],
                            'plan_formation' => [
                                'label'     => "Propose un plan de formation théorique global (peut être élargi à tout niveau de pratiquant)",
                                'mandatory' => false,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function topics(): array
    {
        return [
            ['slug' => 'pression_n1',     'label' => 'La pression et ses influences sur le plongeur',         'level' => 'N1'],
            ['slug' => 'desaturation_n2', 'label' => 'La désaturation',                                       'level' => 'N2'],
            ['slug' => 'flottabilite_n2', 'label' => 'La flottabilité du plongeur',                            'level' => 'N2'],
            ['slug' => 'prevention_n1',   'label' => 'La prévention des accidents de plongée',                 'level' => 'N1'],
            ['slug' => 'froid_n4',        'label' => 'Le froid et la déshydratation',                          'level' => 'N4'],
            ['slug' => 'oreille_n4',      'label' => "L'oreille",                                              'level' => 'N4'],
            ['slug' => 'essoufflement_n3','label' => "L'essoufflement",                                        'level' => 'N3'],
            ['slug' => 'organisation_n3', 'label' => "L'organisation de plongée sans Directeur de plongée",    'level' => 'N3'],
            ['slug' => 'ordinateur_n2',   'label' => "L'ordinateur de plongée",                                'level' => 'N2'],
            ['slug' => 'detendeur_n4',    'label' => 'Le détendeur',                                           'level' => 'N4'],
            ['slug' => 'surpression_n4',  'label' => 'La surpression pulmonaire',                              'level' => 'N4'],
            ['slug' => 'accidents_n2',    'label' => 'Les accidents de décompression',                         'level' => 'N2'],
            ['slug' => 'physique_n4',     'label' => 'Un thème de physique',                                   'level' => 'N4'],
            ['slug' => 'narcose_n3',      'label' => 'La narcose',                                             'level' => 'N3'],
        ];
    }

    public static function competencyPoints(): array
    {
        return [
            'concevoir' => [
                'label' => 'CONCEVOIR',
                'items' => [
                    'c_objectifs'     => ['short' => 'Obj.',  'label' => 'Définir les objectifs et positionner la séance dans le cursus'],
                    'c_justification' => ['short' => 'Just.', 'label' => 'Justifier la séance'],
                    'c_strategie'     => ['short' => 'Str.',  'label' => "Définir une stratégie d'enseignement"],
                ],
            ],
            'realiser' => [
                'label' => 'RÉALISER',
                'items' => [
                    'r_animation'      => ['short' => 'Anim.', 'label' => 'Animer une séance et communiquer'],
                    'r_mise_en_oeuvre' => ['short' => 'M.œ.', 'label' => "Mettre en œuvre une stratégie de formation"],
                    'r_securite'       => ['short' => 'Séc.',  'label' => "Sécuriser l'activité", 'pratique_only' => true],
                ],
            ],
            'evaluer' => [
                'label' => 'ÉVALUER',
                'items' => [
                    'e_evaluation' => ['short' => 'Éval.', 'label' => "Réaliser une évaluation adaptée (initiale, finale, formative…)"],
                ],
            ],
        ];
    }

    public static function allPointKeys(): array
    {
        return ['c_objectifs', 'c_cursus', 'c_justification', 'c_strategie',
                'r_accueil', 'r_animation', 'r_mise_en_oeuvre', 'r_securite', 'e_evaluation'];
    }

    public static function pratiqueCompetencies(): array
    {
        return [
            'BAPT' => [
                'level'    => 'Baptême',
                'sessions' => 4,
                'label'    => 'Baptême',
                'keys' => [
                    'bapt_accueil'       => "Accueillir, équiper et préparer le baptisé",
                    'bapt_mise_a_leau'   => "Mettre à l'eau et accompagner l'immersion",
                    'bapt_guidage'       => "Guider et communiquer en immersion (signaux, réassurance)",
                    'bapt_securite'      => "Sécuriser le baptisé et gérer les imprévus",
                    'bapt_surface'       => "Retourner en surface en sécurité avec le baptisé",
                ],
            ],
            'PE20' => [
                'level'    => 'N1',
                'sessions' => 4,
                'label'    => 'Plongeur Encadré 20m',
                'keys' => [
                    'pe20_equipement'    => "S'équiper et se déséquiper avec aide",
                    'pe20_mise_a_leau'   => "Mise à l'eau depuis le bord ou l'embarcation",
                    'pe20_immersion'     => "S'immerger (canard, palmage ventral/dorsal)",
                    'pe20_propulsion'    => "Se propulser horizontalement et verticalement",
                    'pe20_ventilation'   => "Se ventiler correctement (embout, masque)",
                    'pe20_equilibre'     => "S'équilibrer et utiliser le gilet (SGS)",
                    'pe20_communication' => "Communiquer avec le guide de palanquée",
                    'pe20_securite'      => "Évoluer en sécurité dans la zone des 20m",
                    'pe20_milieu'        => "Respecter le milieu naturel",
                    'pe20_surface'       => "Retourner en surface en sécurité",
                ],
            ],
            'PA20' => [
                'level'    => 'N2',
                'sessions' => 4,
                'label'    => 'Plongeur Autonome 20m',
                'keys' => [
                    'pa20_equipement'    => "S'équiper, se déséquiper et mettre à l'eau",
                    'pa20_immersion'     => "S'immerger, se propulser et se ventiler",
                    'pa20_milieu'        => "Respecter le milieu naturel",
                    'pa20_materiel'      => "Connaître et vérifier le matériel des équipiers",
                    'pa20_autonomie'     => "Évoluer en autonomie dans la zone des 20m",
                    'pa20_planification' => "Planifier une plongée PA20",
                    'pa20_intervention'  => "Intervenir et porter assistance à un équipier",
                ],
            ],
            'PE40' => [
                'level'    => 'N2',
                'sessions' => 4,
                'label'    => 'Plongeur Encadré 40m',
                'keys' => [
                    'pe40_equipement'    => "S'équiper et mettre à l'eau",
                    'pe40_immersion'     => "S'immerger et se propulser jusqu'à 40m",
                    'pe40_milieu'        => "Respecter le milieu naturel",
                    'pe40_ventilation'   => "Se ventiler et s'équilibrer en VDM 20m",
                    'pe40_communication' => "Communiquer avec le guide de palanquée",
                    'pe40_surface'       => "Retourner en surface avec palier de désaturation",
                    'pe40_intervention'  => "Intervenir en relai auprès d'un plongeur en difficulté",
                ],
            ],
            'PA40' => [
                'level'    => 'N3',
                'sessions' => 4,
                'label'    => 'Plongeur Autonome 40m',
                'keys' => [
                    'pa40_planification' => "Planifier une plongée autonome PA40 (0-40m)",
                    'pa40_autonomie'     => "Évoluer en autonomie PA40 dans la zone des 40m",
                    'pa40_intervention'  => "Intervenir et porter assistance jusqu'à 40m",
                    'pa40_milieu'        => "Respecter le milieu naturel",
                ],
            ],
            'PE60' => [
                'level'    => 'N3',
                'sessions' => 2,
                'label'    => 'Plongeur Encadré 60m',
                'keys' => [
                    'pe60_profondeur' => "S'adapter à la profondeur entre 40 et 60m",
                    'pe60_milieu'     => "Respecter le milieu naturel",
                ],
            ],
            'PA60' => [
                'level'    => 'N3',
                'sessions' => 4,
                'label'    => 'Plongeur Autonome 60m',
                'keys' => [
                    'pa60_organisation' => "Organiser et planifier la plongée (0-60m)",
                    'pa60_autonomie'    => "Évoluer en autonomie dans la zone des 60m",
                    'pa60_rifa'         => "RIFA Plongée — premiers secours en plongée",
                    'pa60_milieu'       => "Respecter le milieu naturel",
                ],
            ],
        ];
    }

    public static function pratiqueAllKeys(string $level): array
    {
        $comps = self::pratiqueCompetencies();
        return isset($comps[$level]) ? array_keys($comps[$level]['keys']) : [];
    }

    public static function statusLabel(string $status): string
    {
        return match($status) {
            'not_started' => 'Non commencé',
            'in_progress' => 'En préparation',
            'ready'       => "Prêt pour l'évaluation",
            'evaluated'   => 'Évalué',
            default       => $status,
        };
    }

    public static function statusColor(string $status): string
    {
        return match($status) {
            'not_started' => 'bg-slate-50 text-slate-400 border-slate-200',
            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
            'ready'       => 'bg-sky-50 text-sky-700 border-sky-200',
            'evaluated'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default       => 'bg-slate-50 text-slate-400 border-slate-200',
        };
    }
}
