<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

use App\Models\AppNotification;
use App\Models\InitialAssessment;
use App\Models\Trainee;
use App\Models\TraineeEpmsp;
use App\Models\TraineePedaStatus;
use App\Models\TraineePedaTheoStatus;
use App\Models\TraineeProfile;
use App\Models\TraineeUc3;
use App\Models\TraineeUcProgress;

class DejepsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Suppression des données existantes...');

        AppNotification::query()->delete();
        InitialAssessment::query()->delete();
        TraineePedaStatus::query()->delete();
        TraineePedaTheoStatus::query()->delete();
        TraineeEpmsp::query()->delete();
        TraineeUcProgress::query()->delete();
        TraineeUc3::query()->delete();
        TraineeProfile::query()->delete();
        Trainee::query()->delete();

        $this->command->info('Base vidée. Création des stagiaires...');

        // ── ÉTAPE 2 : Thomas Renard ─────────────────────────────────────────
        $thomas = Trainee::create([
            'name'          => 'Thomas Renard',
            'email'         => 'thomas.renard@gendarmerie-reconversion.fr',
            'phone'         => '06 12 34 56 78',
            'date_of_birth' => '1989-03-14',
        ]);

        TraineeProfile::create([
            'trainee_id'       => $thomas->id,
            'onboarding_step'  => 3,
            'completed_at'     => Carbon::parse('2026-04-28 10:00:00'),
            'ice_breaking'     => [
                'motivation' => 'Reconversion après 12 ans de service. Souhaite enseigner en structure civile.',
                'strengths'  => 'Maîtrise technique, gestion du stress, leadership, rigueur opérationnelle.',
                'challenges' => 'Pédagogie civile, rapport aux débutants, outils informatiques, réglementation fédérale.',
            ],
            'prior_experiences' => [
                'has_other_jobs'  => true,
                'has_diving_work' => false,
                'has_guided'      => true,
                'has_taught'      => false,
                'diving_level'    => 'N4GP',
                'teaching'        => 'Aucune expérience civile. Encadrement opérationnel militaire uniquement.',
                'other'           => '12 ans comme démineur subaquatique à la Gendarmerie Nationale.',
            ],
            'big5_scores'      => ['O' => 42, 'C' => 81, 'E' => 38, 'A' => 44, 'N' => 22],
            'big5_completed_at'=> Carbon::parse('2026-04-28 10:30:00'),
        ]);

        $this->seedAssessments($thomas->id, [
            // Accueil du public
            1 => 1, 2 => 1, 3 => 2, 4 => 1,
            // Gestion d'équipe
            5 => 2, 6 => 3, 7 => 3, 8 => 3, 9 => 3,
            // Maitrise des outils informatiques
            10 => 2, 11 => 1, 12 => 1, 13 => 1,
            // Utilisation du matériel de plongée
            14 => 1, 15 => 2, 16 => 1, 17 => 1, 18 => 3,
            // Utilisation d'un navire
            19 => 3, 20 => 2, 21 => 3, 22 => 3,
            // Conduite de palanquée
            23 => 1, 24 => 1, 25 => 1, 26 => 1, 27 => 1,
            // La formation de plongeur
            28 => 1, 29 => 1, 30 => 1, 31 => 1, 32 => 1, 33 => 1, 34 => 1,
            // Randonnée subaquatique
            35 => 1, 36 => 1, 37 => 1, 38 => 1,
            // La direction de plongée
            39 => 1, 40 => 1, 41 => 1, 42 => 1, 43 => 1, 44 => 1,
            // Le tutorat
            45 => 1, 46 => 1, 47 => 1,
        ]);

        TraineeUc3::create([
            'trainee_id'             => $thomas->id,
            'topic_progress'         => $this->thomasTopicProgress(),
            'trainee_topic_progress' => $this->thomasTraineeTopicProgress(),
        ]);

        TraineeEpmsp::create([
            'trainee_id'       => $thomas->id,
            'type'             => '25m',
            'status'           => 'in_progress',
            'ratings'          => null,
            'instructor_notes' => 'Techniques de sauvetage excellentes. Travailler le contact visuel et la communication verbale pendant la remontée.',
        ]);
        TraineeEpmsp::create([
            'trainee_id' => $thomas->id,
            'type'       => 'pedagogie',
            'status'     => 'in_progress',
        ]);

        TraineeUcProgress::create([
            'trainee_id'         => $thomas->id,
            'uc'                 => 'uc1',
            'status'             => 'in_progress',
            'milestone_progress' => [
                'diagnostic'               => 'done',
                'validation_problematique' => 'done',
                'conception_planification' => 'in_progress',
                'phase_test_analyse'       => 'not_done',
                'redaction_dossier'        => 'not_done',
                'depot_dossier'            => 'not_done',
                'oral_blanc'               => 'not_done',
            ],
        ]);

        AppNotification::create([
            'recipient_type' => 'trainee', 'recipient_id' => $thomas->id, 'trainee_id' => $thomas->id,
            'type' => 'project_feedback', 'slug' => 'project_feedback',
            'data' => ['feedback_text' => 'Bon diagnostic, bien ancré dans la réalité du club. Attention à sourcer les données financières.', 'trainee_name' => 'Thomas Renard'],
            'read_at' => Carbon::parse('2026-02-06 09:00:00'),
            'created_at' => Carbon::parse('2026-02-05 17:00:00'), 'updated_at' => Carbon::parse('2026-02-05 17:00:00'),
        ]);
        AppNotification::create([
            'recipient_type' => 'trainee', 'recipient_id' => $thomas->id, 'trainee_id' => $thomas->id,
            'type' => 'project_feedback', 'slug' => 'project_feedback',
            'data' => ['feedback_text' => 'Problématique validée. La question du développement de l\'activité randonnée subaquatique est pertinente pour Hippocampe.', 'trainee_name' => 'Thomas Renard'],
            'read_at' => Carbon::parse('2026-03-01 08:00:00'),
            'created_at' => Carbon::parse('2026-02-28 16:00:00'), 'updated_at' => Carbon::parse('2026-02-28 16:00:00'),
        ]);

        $this->command->info('Thomas Renard créé.');

        // ── ÉTAPE 3 : Léa Fontaine ──────────────────────────────────────────
        $lea = Trainee::create([
            'name'          => 'Léa Fontaine',
            'email'         => 'lea.fontaine@club-plongee-bordeaux.fr',
            'phone'         => '06 98 76 54 32',
            'date_of_birth' => '1995-07-22',
        ]);

        TraineeProfile::create([
            'trainee_id'       => $lea->id,
            'onboarding_step'  => 3,
            'completed_at'     => Carbon::parse('2026-04-29 14:00:00'),
            'ice_breaking'     => [
                'motivation' => 'Passer de bénévole à professionnelle en zone touristique.',
                'strengths'  => 'Pédagogie intuitive, excellent contact débutants/enfants, très organisée, à l\'aise en public.',
                'challenges' => 'Plongées profondes N3/N4, direction de plongée en conditions difficiles, gestion du stress.',
            ],
            'prior_experiences' => [
                'has_other_jobs'  => true,
                'has_diving_work' => true,
                'has_guided'      => true,
                'has_taught'      => true,
                'diving_level'    => 'N4GP',
                'teaching'        => '4 ans monitrice fédérale bénévole (baptêmes, N1, N2). Animatrice BAFA.',
                'other'           => 'Éducatrice sportive scolaire à mi-temps. Très à l\'aise avec les outils numériques.',
            ],
            'big5_scores'      => ['O' => 72, 'C' => 68, 'E' => 74, 'A' => 82, 'N' => 71],
            'big5_completed_at'=> Carbon::parse('2026-04-29 14:30:00'),
        ]);

        $this->seedAssessments($lea->id, [
            1 => 3, 2 => 3, 3 => 3, 4 => 3,
            5 => 2, 6 => 2, 7 => 2, 8 => 2, 9 => 2,
            10 => 3, 11 => 3, 12 => 3, 13 => 3,
            14 => 2, 15 => 2, 16 => 2, 17 => 2, 18 => 2,
            19 => 1, 20 => 1, 21 => 1, 22 => 1,
            23 => 2, 24 => 2, 25 => 2, 26 => 2, 27 => 2,
            28 => 3, 29 => 3, 30 => 3, 31 => 3, 32 => 3, 33 => 3, 34 => 3,
            35 => 2, 36 => 2, 37 => 2, 38 => 2,
            39 => 1, 40 => 1, 41 => 1, 42 => 1, 43 => 1, 44 => 1,
            45 => 1, 46 => 1, 47 => 1,
        ]);

        TraineeUc3::create([
            'trainee_id'     => $lea->id,
            'topic_progress' => $this->leaTopicProgress(),
        ]);

        TraineeEpmsp::create([
            'trainee_id'       => $lea->id,
            'type'             => '25m',
            'status'           => 'ready',
            'ratings'          => ['respiration' => '3', 'remontee' => '3', 'rassurer' => '2', 'arret' => '3', 'surface' => '3'],
            'instructor_notes' => 'Techniques propres, communication rassurante. Vitesse de remontée légèrement trop lente à corriger.',
        ]);
        TraineeEpmsp::create([
            'trainee_id' => $lea->id,
            'type'       => 'pedagogie',
            'status'     => 'in_progress',
        ]);

        TraineeUcProgress::create([
            'trainee_id'         => $lea->id,
            'uc'                 => 'uc1',
            'status'             => 'in_progress',
            'milestone_progress' => [
                'diagnostic'               => 'done',
                'validation_problematique' => 'done',
                'conception_planification' => 'done',
                'phase_test_analyse'       => 'in_progress',
                'redaction_dossier'        => 'not_done',
                'depot_dossier'            => 'not_done',
                'oral_blanc'               => 'not_done',
            ],
        ]);

        foreach ([
            ['2026-02-06 17:00:00', 'Diagnostic très complet, bonne maîtrise des outils d\'analyse.'],
            ['2026-03-01 10:00:00', 'Problématique validée. Angle communication digitale très pertinent pour une structure associative.'],
            ['2026-03-20 15:00:00', 'Plan d\'action solide. Phase test à lancer dès ouverture de saison.'],
        ] as [$date, $text]) {
            AppNotification::create([
                'recipient_type' => 'trainee', 'recipient_id' => $lea->id, 'trainee_id' => $lea->id,
                'type' => 'project_feedback', 'slug' => 'project_feedback',
                'data' => ['feedback_text' => $text, 'trainee_name' => 'Léa Fontaine'],
                'read_at' => Carbon::parse($date)->addDay(),
                'created_at' => Carbon::parse($date), 'updated_at' => Carbon::parse($date),
            ]);
        }

        $this->command->info('Léa Fontaine créée.');
        $this->command->info('Seeder terminé.');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function seedAssessments(int $traineeId, array $scores): void
    {
        foreach ($scores as $competencyId => $score) {
            InitialAssessment::create([
                'trainee_id'    => $traineeId,
                'competency_id' => $competencyId,
                'trainee_score' => $score,
            ]);
        }
    }

    private function n(int $v): ?string
    {
        return $v === 1 ? null : (string) $v;
    }

    private function session(
        string $date,
        string $label,
        string $level,
        string $situation,
        string $globalRating,
        ?int $cObj = null,
        ?int $cJus = null,
        ?int $cStr = null,
        ?int $rAni = null,
        ?int $rMoe = null,
        ?int $rSec = null,
        ?int $eEva = null,
        array $notes = []
    ): array {
        return array_merge([
            'session_date'        => $date,
            'session_label'       => $label,
            'session_level'       => $level,
            'situation'           => $situation,
            'global_rating'       => $globalRating,
            'c_objectifs'         => $cObj ? $this->n($cObj) : null,
            'c_cursus'            => null,
            'c_justification'     => $cJus ? $this->n($cJus) : null,
            'c_strategie'         => $cStr ? $this->n($cStr) : null,
            'r_accueil'           => null,
            'r_animation'         => $rAni ? $this->n($rAni) : null,
            'r_mise_en_oeuvre'    => $rMoe ? $this->n($rMoe) : null,
            'r_securite'          => $rSec ? $this->n($rSec) : null,
            'e_evaluation'        => $eEva ? $this->n($eEva) : null,
            'session_note'        => null,
            'global_comment'      => null,
            'exercises_done'      => null,
        ], $notes);
    }

    private function obs(string $date, string $label, string $level, string $sessionNote = ''): array
    {
        return $this->session($date, $label, $level, 'observation', '3',
            null, null, null, null, null, null, null,
            ['session_note' => $sessionNote ?: null]
        );
    }

    // ── Thomas topic_progress ───────────────────────────────────────────────
    private function thomasTopicProgress(): array
    {
        $p = [];

        // ── BAPT ──────────────────────────────────────────────────────────
        $p['pratique_bapt_s1'] = $this->obs('2026-05-05', 'Séance 1', 'BAPT',
            'Première séance d\'observation. Thomas prend des notes méthodiques, très attentif aux procédures de sécurité. Son regard reste analytique, peu tourné vers les interactions avec les élèves.'
        );

        $p['pratique_bapt_s2'] = $this->obs('2026-05-07', 'Séance 2', 'BAPT',
            'Deuxième observation. Thomas commence à poser des questions précises sur la logique de progression. Sa curiosité est technique plutôt que pédagogique — cohérent avec son parcours militaire.'
        );

        $p['pratique_bapt_s3'] = $this->session(
            '2026-05-14', 'Séance 3', 'BAPT', 'supervision_directe', '2',
            2, 2, 2, 1, 1, 2, 1,
            [
                'global_comment'         => 'Première prise en main : la rigueur militaire crée de la distance avec les débutants.',
                'c_objectifs_note'       => 'Objectifs corrects mais formulés dans un registre trop technique pour des débutants.',
                'c_justification_note'   => 'Justification logique, sans lien avec les besoins réels du groupe.',
                'c_strategie_note'       => 'Progression trop linéaire, peu d\'ajustements face aux hésitations des élèves.',
                'r_securite_note'        => 'Procédures respectées. Le briefing sécurité est exhaustif mais intimidant.',
                'session_note'           => 'Thomas maîtrise les procédures mais peine à adapter son langage à un public novice. La rigidité militaire transparaît dans l\'animation. À travailler : le registre de communication et la lecture des réactions émotionnelles du groupe.',
            ]
        );

        $p['pratique_bapt_s4'] = $this->session(
            '2026-05-19', 'Séance 4', 'BAPT', 'supervision_directe', '2',
            2, 2, 2, 2, 2, 2, 1,
            [
                'global_comment'         => 'Légère amélioration : Thomas fait des efforts visibles d\'adaptation.',
                'c_objectifs_note'       => 'Meilleure formulation des objectifs, encore perfectible sur l\'aspect accessible.',
                'c_justification_note'   => 'Commence à prendre en compte le niveau du groupe dans ses justifications.',
                'c_strategie_note'       => 'Légère amélioration de la flexibilité, mais la progression reste trop balisée.',
                'r_animation_note'       => 'L\'animation progresse : Thomas fait des efforts visibles pour sourire et encourager.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre ordonnée. Quelques imprévus mal gérés par excès de contrôle.',
                'r_securite_note'        => 'Sécurité toujours bien gérée. Début de pédagogie sécuritaire positive.',
                'session_note'           => 'Progression nette par rapport à la S3. Thomas prend conscience de l\'importance du contact humain. Encore trop dans le contrôle, mais les efforts d\'adaptation sont réels. La rigueur reste un point fort à canaliser.',
            ]
        );

        $p['pratique_bapt_s5'] = $this->session(
            '2026-05-23', 'Séance 5', 'BAPT', 'supervision_directe', '3',
            3, 2, 3, 3, 2, 3, 2,
            [
                'global_comment'         => 'Belle séance : Thomas a trouvé un meilleur équilibre entre rigueur et accessibilité.',
                'c_objectifs_note'       => 'Objectifs clairs, adaptés au niveau du groupe. Belle progression.',
                'c_justification_note'   => 'Justification cohérente, encore un peu formelle mais juste.',
                'c_strategie_note'       => 'Stratégie bien pensée, avec une vraie attention au rythme des apprenants.',
                'r_animation_note'       => 'Thomas était plus détendu ce jour, l\'atmosphère dans le groupe était positive.',
                'r_mise_en_oeuvre_note'  => 'La mise en œuvre suit globalement le plan. Quelques ajustements en cours de séance à perfectionner.',
                'r_securite_note'        => 'Sécurité impeccable. C\'est le point fort constant de Thomas.',
                'e_evaluation_note'      => 'Évaluation présente mais un peu binaire. À enrichir pour mieux guider l\'élève.',
                'session_note'           => 'Séance A bien méritée. Thomas a trouvé un meilleur équilibre entre rigueur et accessibilité. Sa progression est régulière — typique de sa conscience professionnelle. Encourager à lâcher encore un peu les rênes et à s\'adapter en temps réel.',
            ]
        );

        $p['pratique_bapt_s6'] = $this->session(
            '2026-05-26', 'Séance 6', 'BAPT', 'supervision_directe', '3',
            3, 3, 3, 3, 3, 3, 3,
            [
                'global_comment'         => 'Excellente séance : Thomas consolide sa montée en compétence pédagogique.',
                'c_objectifs_note'       => 'Objectifs parfaitement formulés et adaptés au groupe.',
                'c_justification_note'   => 'Très bonne justification pédagogique, ancrage dans le référentiel.',
                'c_strategie_note'       => 'Stratégie dynamique, adaptée et ajustée en temps réel.',
                'r_animation_note'       => 'Animation vivante et bienveillante. Thomas a clairement progressé sur ce point.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre fluide, bonne gestion du temps.',
                'r_securite_note'        => 'Dispositif de sécurité exemplaire.',
                'e_evaluation_note'      => 'Évaluation formative bien menée, retours constructifs et individualisés.',
                'session_note'           => 'Thomas consolide sa maîtrise du BAPT en SD. Sa rigueur est devenue un atout plutôt qu\'un obstacle. Deuxième A consécutive — critères remplis pour l\'avancement en supervision indirecte.',
            ]
        );

        $p['pratique_bapt_s7'] = $this->session(
            '2026-06-10', 'Séance 7', 'BAPT', 'supervision_indirecte', '3',
            3, 3, 3, 3, 3, 3, 3,
            [
                'global_comment'         => 'Très belle séance en SI : Thomas démontre une vraie autonomie sur le baptême.',
                'c_objectifs_note'       => 'Objectifs formulés avec clarté et ambition adaptée à la SI.',
                'c_justification_note'   => 'Justification pédagogique solide, montre une vraie réflexion sur ses choix.',
                'c_strategie_note'       => 'Stratégie autonome, bien conduite sans appui direct.',
                'r_animation_note'       => 'Animation maîtrisée, bon rythme, groupe en confiance.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre en SI irréprochable — Thomas gère les imprévus avec calme.',
                'r_securite_note'        => 'Dispositif de sécurité parfaitement cadré et discret.',
                'e_evaluation_note'      => 'Évaluation pertinente des progrès, retours individualisés.',
                'session_note'           => 'Belle séance en SI. Thomas démontre une vraie autonomie sur le baptême. Sa transformation pédagogique depuis la S3 est remarquable. La rigueur opérationnelle s\'est convertie en sécurité pédagogique.',
            ]
        );

        // ── PE20 (N1) ──────────────────────────────────────────────────────
        $p['pratique_pe20_s1'] = $this->obs('2026-05-12', 'Séance 1', 'PE20',
            'Observation PE20. Thomas est attentif aux spécificités du N1 par rapport au BAPT. Il prend note des différences de complexité technique et commence à s\'interroger sur l\'adaptation nécessaire.'
        );

        $p['pratique_pe20_s2'] = $this->obs('2026-05-16', 'Séance 2', 'PE20',
            'Deuxième observation PE20. Thomas note les similitudes avec le BAPT et les exigences supplémentaires. Il commence à esquisser une structure de séance. Sa préparation est toujours méthodique.'
        );

        $p['pratique_pe20_s3'] = $this->session(
            '2026-05-28', 'Séance 3', 'PE20', 'supervision_directe', '2',
            2, 2, 2, 2, 2, 2, 1,
            [
                'global_comment'         => 'Transition BAPT → N1 difficile : Thomas retrouve ses réflexes de contrôle.',
                'c_objectifs_note'       => 'Objectifs trop ambitieux pour le niveau réel du groupe ce jour.',
                'c_justification_note'   => 'Cohérente mais ne tient pas compte des difficultés rencontrées en temps réel.',
                'c_strategie_note'       => 'Bonne sur le papier, manque de réactivité face aux situations imprévues.',
                'r_animation_note'       => 'Animation contrainte. Thomas retrouve sa réserve face à un groupe plus autonome.',
                'r_mise_en_oeuvre_note'  => 'La mise en œuvre suit le plan sans adaptation dynamique.',
                'r_securite_note'        => 'Gestion de la sécurité correcte, protocoles respectés.',
                'session_note'           => 'La transition BAPT → N1 est difficile. Un public plus autonome et plus exigeant remet Thomas dans ses anciens réflexes de contrôle. Nécessite des observations supplémentaires avant de retenter en SD sur ce niveau.',
            ]
        );

        $p['pratique_pe20_s4'] = $this->obs('2026-06-02', 'Séance 4', 'PE20',
            'Retour en observation après la S3 décevante — choix pertinent. Thomas affine sa lecture des attentes d\'un groupe PE20 et commence à anticiper les moments critiques de la séance.'
        );

        $p['pratique_pe20_s5'] = $this->obs('2026-06-06', 'Séance 5', 'PE20',
            'Dernière observation avant une nouvelle tentative en SD. Thomas est méthodique dans sa préparation : il a élaboré une fiche de séance détaillée avec points de vigilance. Sa conscience professionnelle compense son manque de spontanéité.'
        );

        $p['pratique_pe20_s6'] = $this->session(
            '2026-06-13', 'Séance 6', 'PE20', 'supervision_directe', '2',
            2, 2, 2, 2, 2, 2, 1,
            [
                'global_comment'         => 'Progrès réels depuis la S3, mais le N1 en SD reste un défi.',
                'c_objectifs_note'       => 'Objectifs mieux calibrés que lors de la S3, différenciation à poursuivre.',
                'c_justification_note'   => 'Meilleure prise en compte du profil du groupe.',
                'c_strategie_note'       => 'Plus flexible qu\'en S3, mais la gestion des imprévus reste à développer.',
                'r_animation_note'       => 'Animation plus naturelle — le travail réalisé depuis la S3 paie.',
                'r_mise_en_oeuvre_note'  => 'Correcte. Manque encore de fluidité dans les transitions entre activités.',
                'r_securite_note'        => 'Sécurité rigoureuse, point fort constant de Thomas.',
                'session_note'           => 'Progrès réels depuis la S3, mais Thomas n\'est pas encore à l\'aise en SD sur le PE20. La complexité technique du niveau le ramène à ses anciens réflexes de contrôle. Travail à poursuivre sur la flexibilité et la spontanéité pédagogique.',
            ]
        );

        // ── PA20 ──────────────────────────────────────────────────────────
        $p['pratique_pa20_s1'] = $this->obs('2026-05-21', 'Séance 1', 'PA20',
            'Première observation PA20. Thomas est dans son élément technique : profondeur, physiologie, protocoles. Très attentif à la gestion des paramètres. Son aisance sur le fond est manifeste.'
        );

        $p['pratique_pa20_s2'] = $this->session(
            '2026-06-04', 'Séance 2', 'PA20', 'supervision_directe', '2',
            2, 2, 2, 2, 2, 2, 1,
            [
                'global_comment'         => 'Thomas est plus à l\'aise sur ce niveau technique, mais la pédagogie reste à travailler.',
                'c_objectifs_note'       => 'Objectifs adaptés au contexte PA20, mais la communication reste trop technique.',
                'c_justification_note'   => 'Justification rigoureuse — Thomas maîtrise le fond mais peine à le rendre accessible.',
                'c_strategie_note'       => 'Stratégie méthodique. Ajustement au ressenti des élèves en temps réel à développer.',
                'r_animation_note'       => 'Plus à l\'aise ici car le contenu est dans son domaine d\'expertise. Cela se sent.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre propre. Quelques imprévus mal absorbés.',
                'r_securite_note'        => 'Gestion de la sécurité solide. Procédures PA impeccables.',
                'session_note'           => 'Le contexte plus technique rassure Thomas et lui redonne de l\'assurance. C\'est quand le contenu est dans son expertise qu\'il est le plus convaincant. La pédagogie pour rendre accessible ce contenu complexe reste à développer.',
            ]
        );

        // ── THÉORIQUE ─────────────────────────────────────────────────────
        $p['pression_n1'] = $this->session(
            '2026-05-08', 'La pression et ses influences sur le plongeur', 'N1',
            'supervision_directe', '2',
            2, 2, 2, 2, 2, null, 1,
            [
                'global_comment'         => 'Sujet maîtrisé, présentation trop académique et peu interactive.',
                'c_objectifs_note'       => 'Objectifs bien définis sur le fond, mais le vocabulaire physiologique est trop dense pour le N1.',
                'c_justification_note'   => 'Thomas justifie ses choix avec précision, sans lien avec les implications pratiques pour les élèves.',
                'c_strategie_note'       => 'Stratégie pédagogique trop frontale. Peu d\'interaction avec les élèves.',
                'r_animation_note'       => 'Animation scolaire. Thomas récite plus qu\'il n\'échange. Manque de questions ouvertes.',
                'r_mise_en_oeuvre_note'  => 'Bonne maîtrise du contenu, mais le cours manque de dynamisme.',
                'e_evaluation_note'      => 'Évaluation trop légère. Difficile de s\'assurer que les élèves ont vraiment compris.',
                'session_note'           => 'Thomas doit apprendre à vulgariser sans trahir la rigueur scientifique. Utiliser des analogies concrètes plutôt que des équations. Son perfectionnisme (trait consciencieux) le pousse à tout couvrir au détriment de la clarté.',
            ]
        );

        $p['autre__n1__milieu_aquatique'] = $this->session(
            '2026-05-15', 'Le milieu aquatique', 'N1',
            'supervision_directe', '3',
            3, 3, 3, 3, 3, null, 3,
            [
                'global_comment'         => 'Thomas à son meilleur : il s\'appuie sur son vécu terrain pour captiver le groupe.',
                'c_objectifs_note'       => 'Très bons objectifs, bien ancrés dans la réalité du plongeur.',
                'c_justification_note'   => 'Excellente justification — Thomas a su lier théorie et pratique de terrain.',
                'c_strategie_note'       => 'Belle utilisation de son expérience professionnelle pour illustrer les concepts.',
                'r_animation_note'       => 'Animation vivante : Thomas partageait des anecdotes de plongées opérationnelles. Les élèves étaient captivés.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre fluide et bien rythmée.',
                'e_evaluation_note'      => 'Bonne évaluation orale en fin de séance, retours ciblés.',
                'session_note'           => 'Excellente séance. Thomas a trouvé le bon angle : s\'appuyer sur son vécu professionnel pour rendre le sujet vivant. C\'est quand il incarne l\'expert du terrain qu\'il est le plus convaincant. À reproduire systématiquement.',
            ]
        );

        $p['desaturation_n2'] = $this->session(
            '2026-05-22', 'La désaturation', 'N2',
            'supervision_directe', '2',
            2, 2, 2, 2, 2, null, 2,
            [
                'global_comment'         => 'Sujet trop chargé : Thomas surcharge la séance au détriment de la clarté.',
                'c_objectifs_note'       => 'Objectifs trop nombreux pour une seule séance. Cohérents mais non priorisés.',
                'c_justification_note'   => 'Justification technique irréprochable — Thomas connaît parfaitement ce sujet.',
                'c_strategie_note'       => 'Stratégie trop chargée. Mieux vaut deux séances courtes et ciblées qu\'une longue exhaustive.',
                'r_animation_note'       => 'L\'animation souffre de la densité du contenu — Thomas se perd parfois dans les détails.',
                'r_mise_en_oeuvre_note'  => 'Correcte mais manque de synthèses intermédiaires pour ancrer les acquis.',
                'e_evaluation_note'      => 'L\'évaluation montre que les élèves ont retenu l\'essentiel, pas les nuances.',
                'session_note'           => 'Sujet très technique où Thomas est dans son élément, mais il surcharge la séance. Son perfectionnisme (trait consciencieux) peut nuire à la clarté pédagogique. Apprendre à sélectionner et hiérarchiser l\'information est un axe clé.',
            ]
        );

        $p['autre__n1__equipement_plongeur'] = $this->session(
            '2026-05-29', "L'équipement du plongeur", 'N1',
            'supervision_indirecte', '3',
            3, 3, 3, 3, 3, null, 3,
            [
                'global_comment'         => 'Très belle séance en SI : l\'expertise technique de Thomas pleinement au service de la pédagogie.',
                'c_objectifs_note'       => 'Objectifs clairs et progressifs, parfaitement calibrés pour une SI.',
                'c_justification_note'   => 'Choix pédagogiques très bien justifiés, avec référence au référentiel.',
                'c_strategie_note'       => 'Stratégie autonome et bien adaptée, Thomas guide sans intervenir.',
                'r_animation_note'       => 'Bonne animation en SI — Thomas gère le groupe avec confiance et recul.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre maîtrisée, bonnes réponses aux questions imprévues des élèves.',
                'e_evaluation_note'      => 'Évaluation solide, retours personnalisés et constructifs à chaque élève.',
                'session_note'           => 'Très belle séance en supervision indirecte. Thomas démontre une maîtrise complète du sujet et sait désormais la transmettre avec clarté et confiance. Sa montée en compétence pédagogique sur les sujets techniques est nette.',
            ]
        );

        return $p;
    }

    // ── Thomas trainee_topic_progress (self-evaluation) ────────────────────
    private function thomasTraineeTopicProgress(): array
    {
        return [
            'pratique_bapt_s1' => ['session_date'=>'2026-05-05','session_label'=>'Séance 1','session_level'=>'BAPT','situation'=>'observation','global_rating'=>'3','global_comment'=>null,'session_note'=>'Bonne observation. J\'ai pris note de l\'organisation générale et des procédures de sécurité. Je me sens à l\'aise avec les protocoles.'],
            'pratique_bapt_s2' => ['session_date'=>'2026-05-07','session_label'=>'Séance 2','session_level'=>'BAPT','situation'=>'observation','global_rating'=>'3','global_comment'=>null,'session_note'=>'Deuxième observation. Je commence à comprendre la logique de progression du baptême. J\'ai esquissé une structure de séance.'],
            'pratique_bapt_s3' => ['session_date'=>'2026-05-14','session_level'=>'BAPT','situation'=>'supervision_directe','global_rating'=>'2','global_comment'=>'Séance correcte sur le fond. Je dois adapter mon registre de communication.','c_objectifs'=>'3','c_objectifs_note'=>'Objectifs clairs et bien structurés selon moi. La progression était logique.','c_justification'=>'2','c_justification_note'=>'Justification solide techniquement. Je réalise que je ne l\'ai pas assez reliée au ressenti des élèves.','c_strategie'=>'2','c_strategie_note'=>'Stratégie cohérente sur le papier, mais peu de marge pour les imprévus.','r_securite'=>'3','r_securite_note'=>'Dispositif de sécurité rigoureux. Aucune faille sur ce point.','session_note'=>'Première séance en supervision directe. Les objectifs étaient clairs pour moi, mais j\'ai senti que certains élèves n\'étaient pas totalement à l\'aise. La gestion de la sécurité est un point fort, je dois travailler la communication.'],
            'pratique_bapt_s4' => ['session_date'=>'2026-05-19','session_level'=>'BAPT','situation'=>'supervision_directe','global_rating'=>'2','global_comment'=>'Des progrès sur l\'animation. La rigueur reste mon point fort, l\'accessibilité reste à travailler.','c_objectifs'=>'3','c_objectifs_note'=>'Objectifs mieux formulés que la S3.','c_justification'=>'2','c_justification_note'=>'Meilleure que la S3, je commence à penser aux besoins du groupe.','c_strategie'=>'2','c_strategie_note'=>'Légère amélioration, encore trop rigide dans la structure.','r_animation'=>'2','r_animation_note'=>'J\'ai fait un effort conscient pour encourager davantage. Pas encore naturel.','r_mise_en_oeuvre'=>'2','r_mise_en_oeuvre_note'=>'Mise en œuvre ordonnée. Quelques moments où j\'aurais dû m\'adapter plus vite.','r_securite'=>'3','r_securite_note'=>'Sécurité toujours au niveau. C\'est un point sur lequel je ne fais pas de compromis.','session_note'=>'Progrès mesurables. J\'ai fait des efforts sur l\'animation et l\'encouragement. La rigueur reste bien présente — c\'est positif — mais je travaille à la rendre moins distante.'],
            'pratique_bapt_s5' => ['session_date'=>'2026-05-23','session_level'=>'BAPT','situation'=>'supervision_directe','global_rating'=>'3','global_comment'=>'Bonne séance. J\'ai trouvé un meilleur équilibre entre cadre et bienveillance.','c_objectifs'=>'3','c_objectifs_note'=>'Objectifs bien calibrés et formulés de manière accessible.','c_justification'=>'2','c_justification_note'=>'Justification correcte mais je sens que je peux encore mieux relier théorie et pratique.','c_strategie'=>'3','c_strategie_note'=>'Bonne stratégie, avec une vraie attention au rythme du groupe.','r_animation'=>'3','r_animation_note'=>'L\'animation était plus naturelle ce jour. Je me suis senti plus à l\'aise.','r_mise_en_oeuvre'=>'2','r_mise_en_oeuvre_note'=>'Correcte. Quelques transitions à améliorer.','r_securite'=>'3','r_securite_note'=>'Impeccable comme toujours.','e_evaluation'=>'2','e_evaluation_note'=>'J\'ai évalué mais les retours aux élèves étaient encore trop binaires.','session_note'=>'Meilleure séance en BAPT. J\'ai trouvé un meilleur équilibre. L\'évaluation reste un axe de progrès — je dois apprendre à formuler des retours plus nuancés.'],
            'pratique_bapt_s6' => ['session_date'=>'2026-05-26','session_level'=>'BAPT','situation'=>'supervision_directe','global_rating'=>'3','global_comment'=>'Séance maîtrisée. Je me sens prêt pour la supervision indirecte sur ce niveau.','c_objectifs'=>'3','c_objectifs_note'=>'Objectifs parfaitement formulés.','c_justification'=>'3','c_justification_note'=>'Bonne justification pédagogique, référence au référentiel.','c_strategie'=>'3','c_strategie_note'=>'Stratégie fluide, bien ajustée en cours de séance.','r_animation'=>'3','r_animation_note'=>'Animation vivante. Je me suis senti en confiance.','r_mise_en_oeuvre'=>'3','r_mise_en_oeuvre_note'=>'Mise en œuvre fluide, bonne gestion du temps.','r_securite'=>'3','r_securite_note'=>'Dispositif exemplaire.','e_evaluation'=>'3','e_evaluation_note'=>'Retours individualisés et constructifs. Progression nette depuis S3.','session_note'=>'Très satisfait de cette séance. La progression depuis la S3 est réelle. Je me sens prêt à passer en SI sur le BAPT.'],
            'pratique_bapt_s7' => ['session_date'=>'2026-06-10','session_level'=>'BAPT','situation'=>'supervision_indirecte','global_rating'=>'3','global_comment'=>'Bonne séance en SI. Je me sens autonome sur le baptême.','c_objectifs'=>'3','c_objectifs_note'=>'Objectifs bien définis, autonomie complète sur ce niveau.','c_justification'=>'3','c_justification_note'=>'Justification solide et réfléchie.','c_strategie'=>'3','c_strategie_note'=>'Stratégie bien conduite sans appui.','r_animation'=>'3','r_animation_note'=>'Animation maîtrisée, groupe en confiance.','r_mise_en_oeuvre'=>'3','r_mise_en_oeuvre_note'=>'Gestion des imprévus satisfaisante.','r_securite'=>'3','r_securite_note'=>'Sécurité parfaitement tenue.','e_evaluation'=>'3','e_evaluation_note'=>'Retours pertinents et individualisés.','session_note'=>'Première séance en SI réussie. La transformation depuis la S3 est notable. Je me sens vraiment autonome sur le baptême maintenant.'],
            'pratique_pe20_s1' => ['session_date'=>'2026-05-12','session_level'=>'PE20','situation'=>'observation','global_rating'=>'3','global_comment'=>null,'session_note'=>'Observation PE20. Le niveau N1 est plus complexe. Je prends note des différences avec le BAPT.'],
            'pratique_pe20_s2' => ['session_date'=>'2026-05-16','session_level'=>'PE20','situation'=>'observation','global_rating'=>'3','global_comment'=>null,'session_note'=>'Deuxième observation. Je me prépare méthodiquement. J\'ai élaboré une fiche de séance détaillée.'],
            'pratique_pe20_s3' => ['session_date'=>'2026-05-28','session_level'=>'PE20','situation'=>'supervision_directe','global_rating'=>'2','global_comment'=>'Séance difficile. Le groupe PE20 est plus exigeant, j\'ai eu du mal à m\'adapter en temps réel.','c_objectifs'=>'3','c_objectifs_note'=>'Mes objectifs étaient bien préparés. Le problème vient de l\'adaptation au groupe.','c_justification'=>'2','c_justification_note'=>'Justification correcte mais pas assez ancrée dans la réalité du moment.','c_strategie'=>'2','c_strategie_note'=>'Stratégie solide en amont mais manque de réactivité face aux imprévus.','r_animation'=>'2','r_animation_note'=>'L\'animation a souffert — je me suis retrouvé dans mes anciens réflexes.','r_mise_en_oeuvre'=>'2','r_mise_en_oeuvre_note'=>'Mise en œuvre trop rigide. Le plan était bon mais il fallait s\'en écarter.','r_securite'=>'3','r_securite_note'=>'Sécurité rigoureuse. C\'est le point que je maîtrise le mieux.','session_note'=>'Séance décevante. Je retombe dans mes réflexes de contrôle face à un groupe plus autonome. Je décide de refaire des observations avant de retenter.'],
            'pratique_pe20_s4' => ['session_date'=>'2026-06-02','session_level'=>'PE20','situation'=>'observation','global_rating'=>'3','global_comment'=>null,'session_note'=>'Retour en observation après la S3. Je cible les moments critiques où j\'ai décroché.'],
            'pratique_pe20_s5' => ['session_date'=>'2026-06-06','session_level'=>'PE20','situation'=>'observation','global_rating'=>'3','global_comment'=>null,'session_note'=>'Dernière observation avant une nouvelle SD. Fiche de séance refaite avec des points de vigilance spécifiques.'],
            'pratique_pe20_s6' => ['session_date'=>'2026-06-13','session_level'=>'PE20','situation'=>'supervision_directe','global_rating'=>'2','global_comment'=>'Des progrès réels depuis la S3. Je ne suis pas encore à l\'aise mais j\'évolue.','c_objectifs'=>'2','c_objectifs_note'=>'Objectifs mieux calibrés que lors de la S3.','c_justification'=>'2','c_justification_note'=>'Meilleure prise en compte du profil du groupe.','c_strategie'=>'2','c_strategie_note'=>'Plus flexible qu\'en S3, l\'adaptation reste un travail conscient.','r_animation'=>'2','r_animation_note'=>'L\'animation progresse, plus naturelle. Encore perfectible.','r_mise_en_oeuvre'=>'2','r_mise_en_oeuvre_note'=>'Mise en œuvre correcte, transitions à améliorer.','r_securite'=>'3','r_securite_note'=>'Sécurité toujours bien tenue.','session_note'=>'Progrès mesurables par rapport à la S3. Je ne suis pas encore fluide sur ce niveau mais la progression est là. Je dois continuer à travailler la souplesse pédagogique.'],
            'pratique_pa20_s1' => ['session_date'=>'2026-05-21','session_level'=>'PA20','situation'=>'observation','global_rating'=>'3','global_comment'=>null,'session_note'=>'Observation PA20. Je suis dans mon domaine technique. La gestion de la profondeur et des paramètres physiologiques est une seconde nature pour moi.'],
            'pratique_pa20_s2' => ['session_date'=>'2026-06-04','session_level'=>'PA20','situation'=>'supervision_directe','global_rating'=>'2','global_comment'=>'Je maîtrise le contenu technique. Rendre ce contenu accessible reste mon axe de travail.','c_objectifs'=>'3','c_objectifs_note'=>'Objectifs adaptés au contexte PA20, bien construits.','c_justification'=>'3','c_justification_note'=>'Justification solide — je connais ce sujet de fond en comble.','c_strategie'=>'2','c_strategie_note'=>'Stratégie correcte mais j\'ai eu du mal à ajuster face aux hésitations du groupe.','r_animation'=>'2','r_animation_note'=>'Plus à l\'aise car le contenu est dans mon domaine, mais l\'animation reste à travailler.','r_mise_en_oeuvre'=>'2','r_mise_en_oeuvre_note'=>'Correcte. Quelques imprévus gérés avec un peu trop de contrôle.','r_securite'=>'3','r_securite_note'=>'Procédures PA impeccables. C\'est mon point fort.','session_note'=>'Séance en PA20 : je suis à l\'aise sur le fond technique. Le défi reste de rendre ce contenu dense et accessible pour des plongeurs moins expérimentés.'],
            'pression_n1' => ['session_date'=>'2026-05-08','session_label'=>'La pression et ses influences sur le plongeur','session_level'=>'N1','situation'=>'supervision_directe','global_rating'=>'2','global_comment'=>'Sujet que je maîtrise. La présentation était trop dense — je dois apprendre à sélectionner.','c_objectifs'=>'3','c_objectifs_note'=>'Objectifs bien définis, couverture complète du sujet.','c_justification'=>'2','c_justification_note'=>'Justification correcte mais trop académique pour ce public.','c_strategie'=>'2','c_strategie_note'=>'J\'ai voulu tout couvrir — c\'était une erreur de stratégie.','r_animation'=>'2','r_animation_note'=>'Animation trop frontale. Je dois introduire plus d\'échanges.','r_mise_en_oeuvre'=>'2','r_mise_en_oeuvre_note'=>'Contenu bien maîtrisé, mais le cours manquait de dynamisme.','session_note'=>'Je connais parfaitement ce sujet, ce qui m\'a poussé à trop détailler. J\'ai compris que la maîtrise du contenu ne suffit pas — il faut aussi choisir ce qu\'on transmet et comment.'],
            'autre__n1__milieu_aquatique' => ['session_date'=>'2026-05-15','session_label'=>'Le milieu aquatique','session_level'=>'N1','situation'=>'supervision_directe','global_rating'=>'3','global_comment'=>'Très bonne séance. M\'appuyer sur mon vécu opérationnel a été la bonne approche.','c_objectifs'=>'3','c_objectifs_note'=>'Objectifs bien ancrés dans la réalité terrain.','c_justification'=>'3','c_justification_note'=>'J\'ai réussi à lier théorie et pratique grâce à mes expériences.','c_strategie'=>'3','c_strategie_note'=>'Bonne utilisation d\'anecdotes professionnelles pour illustrer les concepts.','r_animation'=>'3','r_animation_note'=>'Animation vivante. Le groupe était réactif et posait des questions.','r_mise_en_oeuvre'=>'3','r_mise_en_oeuvre_note'=>'Séance bien rythmée, bon équilibre théorie/exemples.','e_evaluation'=>'3','e_evaluation_note'=>'Retours oraux pertinents en fin de séance.','session_note'=>'Excellente séance. J\'ai compris que mon vécu de terrain est un vrai atout pédagogique quand je l\'utilise bien. À reproduire systématiquement.'],
            'desaturation_n2' => ['session_date'=>'2026-05-22','session_label'=>'La désaturation','session_level'=>'N2','situation'=>'supervision_directe','global_rating'=>'2','global_comment'=>'Contenu très maîtrisé de ma part. J\'ai eu du mal à synthétiser — j\'ai voulu tout couvrir.','c_objectifs'=>'2','c_objectifs_note'=>'Objectifs ambitieux — peut-être trop pour une seule séance.','c_justification'=>'3','c_justification_note'=>'Je maîtrise ce sujet en profondeur, la justification technique est solide.','c_strategie'=>'1','c_strategie_note'=>'Stratégie trop chargée. Aurait fallu découper en deux séances.','r_animation'=>'2','r_animation_note'=>'L\'animation a souffert de la densité du contenu.','r_mise_en_oeuvre'=>'2','r_mise_en_oeuvre_note'=>'Correcte mais sans synthèses intermédiaires suffisantes.','session_note'=>'Je savais que ce sujet était dans mon domaine et j\'ai sans doute trop voulu le montrer. En débriefant, je réalise que les élèves n\'ont pas tout retenu. Je dois apprendre à prioriser et à structurer en blocs plus courts.'],
            'autre__n1__equipement_plongeur' => ['session_date'=>'2026-05-29','session_label'=>'L\'équipement du plongeur','session_level'=>'N1','situation'=>'supervision_indirecte','global_rating'=>'3','global_comment'=>'Séance en SI réussie. Je me suis senti autonome et à l\'aise.','c_objectifs'=>'3','c_objectifs_note'=>'Objectifs clairs et progressifs.','c_justification'=>'3','c_justification_note'=>'Justification pédagogique solide et bien référencée.','c_strategie'=>'3','c_strategie_note'=>'Stratégie autonome, bien adaptée sans appui.','r_animation'=>'3','r_animation_note'=>'Animation maîtrisée, groupe confiant.','r_mise_en_oeuvre'=>'3','r_mise_en_oeuvre_note'=>'Mise en œuvre fluide, bonnes réponses aux questions imprévues.','e_evaluation'=>'3','e_evaluation_note'=>'Retours personnalisés et constructifs à chaque élève.','session_note'=>'Très satisfait de cette séance en SI. La progression depuis la désaturation est nette. Je commence à trouver mon style d\'enseignant.'],
        ];
    }

    // ── Léa topic_progress ──────────────────────────────────────────────────
    private function leaTopicProgress(): array
    {
        $p = [];

        // ── BAPT ──────────────────────────────────────────────────────────
        $p['pratique_bapt_s1'] = $this->obs('2026-05-05', 'Séance 1', 'BAPT',
            'Première séance d\'observation. Léa observe avec enthousiasme et empathie. Elle note spontanément les réactions émotionnelles des élèves, naturellement tournée vers le groupe plutôt que vers les procédures.'
        );

        $p['pratique_bapt_s2'] = $this->obs('2026-05-07', 'Séance 2', 'BAPT',
            'Deuxième observation. Léa anticipe déjà la structure d\'une séance type et imagine des variantes adaptées. Très à l\'aise dans cet environnement qu\'elle connaît bien depuis 4 ans de bénévolat.'
        );

        $p['pratique_bapt_s3'] = $this->session(
            '2026-05-12', 'Séance 3', 'BAPT', 'supervision_directe', '3',
            3, 3, 3, 3, 3, 3, 3,
            [
                'global_comment'         => 'Première SD remarquable : Léa est immédiatement dans son élément avec les débutants.',
                'c_objectifs_note'       => 'Objectifs très bien formulés, adaptés et motivants pour des débutants.',
                'c_justification_note'   => 'Justification naturelle et pertinente — Léa montre une belle intuition pédagogique.',
                'c_strategie_note'       => 'Stratégie ludique et progressive, excellent dosage entre sécurité et découverte.',
                'r_animation_note'       => 'Animation remarquable. Léa est naturellement à l\'aise face au groupe, elle met les gens à l\'aise immédiatement.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre très fluide, gestion des imprévus impeccable.',
                'r_securite_note'        => 'Sécurité intégrée naturellement dans la séance, sans alarmisme.',
                'e_evaluation_note'      => 'Bonne évaluation, encourageante et constructive.',
                'session_note'           => 'Première SD remarquable. L\'expérience de monitrice bénévole transparaît immédiatement. Léa adapte son langage et ses encouragements avec une spontanéité rare. Sa progression rapide au BAPT est cohérente avec son profil.',
            ]
        );

        $p['pratique_bapt_s4'] = $this->session(
            '2026-05-14', 'Séance 4', 'BAPT', 'supervision_directe', '3',
            3, 3, 3, 3, 3, 3, 3,
            [
                'global_comment'         => 'Confirmation de la S3 : Léa est clairement à son aise en SD baptême.',
                'c_objectifs_note'       => 'Objectifs affinés par rapport à la S3, encore plus pertinents.',
                'c_justification_note'   => 'Justification enrichie avec de bons arguments sur la progression des élèves.',
                'c_strategie_note'       => 'Belle variété dans les exercices proposés, adaptés aux besoins individuels.',
                'r_animation_note'       => 'Animation toujours aussi naturelle et chaleureuse. Le groupe est en confiance.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre solide, Léa anticipe mieux les besoins.',
                'r_securite_note'        => 'Sécurité très bien intégrée, discours positif et rassurant.',
                'e_evaluation_note'      => 'Retours individualisés et bienveillants, chaque élève se sent vu.',
                'session_note'           => 'Confirmation de la S3. Sa forte extraversion et son agréabilité élevée font de Léa une monitrice naturellement rassurante. Deuxième A consécutive — critères remplis pour progresser en supervision indirecte.',
            ]
        );

        $p['pratique_bapt_s5'] = $this->session(
            '2026-05-19', 'Séance 5', 'BAPT', 'supervision_indirecte', '3',
            3, 3, 3, 3, 3, 3, 3,
            [
                'global_comment'         => 'Excellente séance en SI : Léa a rapidement compris la logique de la supervision indirecte.',
                'c_objectifs_note'       => 'Objectifs ambitieux et pertinents pour la SI, bien différenciés de la SD.',
                'c_justification_note'   => 'Justification autonome et rigoureuse.',
                'c_strategie_note'       => 'Stratégie pensée pour responsabiliser les élèves, excellent choix.',
                'r_animation_note'       => 'Animation en retrait, laisse les élèves prendre de l\'initiative — exactement ce qu\'il faut en SI.',
                'r_mise_en_oeuvre_note'  => 'Supervision à distance avec confiance, intervient uniquement quand nécessaire.',
                'r_securite_note'        => 'Dispositif de sécurité discret mais efficace, toujours présente.',
                'e_evaluation_note'      => 'Évaluation fine des acquis et des points à consolider.',
                'session_note'           => 'Léa a compris et intégré la logique de la SI : guider sans intervenir. Son BAPT est validé en SI en un temps record. Cohérent avec son parcours préexistant et son niveau élevé d\'ouverture et d\'adaptabilité.',
            ]
        );

        // ── PE20 (N1) ──────────────────────────────────────────────────────
        $p['pratique_pe20_s1'] = $this->obs('2026-05-09', 'Séance 1', 'PE20',
            'Observation PE20. Léa est attentive aux spécificités du N1 par rapport au BAPT. Elle commence à s\'interroger sur comment adapter son approche très relationnelle à un public plus autonome et plus technique.'
        );

        $p['pratique_pe20_s2'] = $this->session(
            '2026-05-16', 'Séance 2', 'PE20', 'supervision_directe', '3',
            3, 3, 3, 3, 3, 3, 3,
            [
                'global_comment'         => 'Belle transition vers le PE20 : Léa adapte naturellement son registre.',
                'c_objectifs_note'       => 'Objectifs bien pensés pour un public N1 plus autonome.',
                'c_justification_note'   => 'Bonne justification de la progression choisie.',
                'c_strategie_note'       => 'Stratégie adaptée au niveau et à l\'autonomie croissante des élèves.',
                'r_animation_note'       => 'Animation dynamique, Léa maintient un bon rythme et capte l\'attention.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre soignée, bonne gestion du temps.',
                'r_securite_note'        => 'Sécurité bien maîtrisée sur ce niveau.',
                'e_evaluation_note'      => 'Évaluation formative bien conduite, retours personnalisés.',
                'session_note'           => 'Belle transition vers le PE20. Léa adapte naturellement son registre pour un public plus autonome. Sa facilité de communication est un atout ici aussi. À confirmer sur les séances suivantes.',
            ]
        );

        $p['pratique_pe20_s3'] = $this->session(
            '2026-05-19', 'Séance 3', 'PE20', 'supervision_directe', '3',
            3, 3, 3, 3, 3, 3, 3,
            [
                'global_comment'         => 'Confirmation en PE20 SD : régularité et confiance croissante.',
                'c_objectifs_note'       => 'Objectifs de plus en plus précis et différenciés selon les élèves.',
                'c_justification_note'   => 'Justification bien articulée, références aux standards fédéraux.',
                'c_strategie_note'       => 'Stratégie progressive et maîtrisée.',
                'r_animation_note'       => 'Animation chaleureuse et efficace, la relation de confiance est établie.',
                'r_mise_en_oeuvre_note'  => 'Solide, bonne gestion des interactions dans le groupe.',
                'r_securite_note'        => 'Sécurité toujours très bien intégrée.',
                'e_evaluation_note'      => 'Évaluation précise, identifie bien les points de blocage individuels.',
                'session_note'           => 'Deuxième A consécutive en PE20 SD. Léa enchaîne avec régularité. Attention à ne pas trop s\'appuyer sur la relation pour compenser une préparation insuffisante dans les niveaux à venir (PA20, PE40).',
            ]
        );

        $p['pratique_pe20_s4'] = $this->session(
            '2026-05-23', 'Séance 4', 'PE20', 'supervision_indirecte', '2',
            2, 2, 2, 2, 2, 3, 2,
            [
                'global_comment'         => 'Léa a du mal à prendre du recul en SI — elle reste naturellement dans la relation directe.',
                'c_objectifs_note'       => 'Les objectifs pour la SI ne sont pas assez différenciés de ceux posés en SD.',
                'c_justification_note'   => 'Léa n\'ajuste pas encore sa posture à l\'exigence plus haute de la supervision indirecte.',
                'c_strategie_note'       => 'Stratégie trop interventionniste pour une SI — Léa a du mal à prendre du recul.',
                'r_animation_note'       => 'Léa intervient encore trop souvent, limitant l\'autonomie des élèves.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre correcte mais la supervision reste trop directe.',
                'r_securite_note'        => 'Bonne gestion de la sécurité, point fort constant.',
                'e_evaluation_note'      => 'Évaluation un peu générique, difficile de distinguer ce qui est bien et pourquoi.',
                'session_note'           => 'Son fort niveau d\'agréabilité la pousse à intervenir pour rassurer plutôt que laisser les élèves expérimenter. C\'est une limite en SI. Travailler sur la confiance accordée aux élèves et la tolérance à l\'inconfort de se mettre en retrait.',
            ]
        );

        $p['pratique_pe20_s5'] = $this->session(
            '2026-06-06', 'Séance 5', 'PE20', 'supervision_indirecte', '3',
            3, 3, 3, 3, 3, 3, 3,
            [
                'global_comment'         => 'Excellente réponse à la S4 : Léa a compris et corrigé.',
                'c_objectifs_note'       => 'Objectifs spécifiquement pensés pour la SI, bien différenciés.',
                'c_justification_note'   => 'Justification autonome et mûrie depuis la S4.',
                'c_strategie_note'       => 'Léa a bien intégré la leçon : s\'effacer au bon moment.',
                'r_animation_note'       => 'Animation en retrait mais disponible — parfait équilibre pour la SI.',
                'r_mise_en_oeuvre_note'  => 'Les élèves sont vraiment autonomes, Léa supervise à distance avec confiance.',
                'r_securite_note'        => 'Sécurité impeccable.',
                'e_evaluation_note'      => 'Évaluation différenciée et précise, Léa identifie finement les acquis.',
                'session_note'           => 'Excellente réponse à la S4. Léa a accepté l\'inconfort de se mettre en retrait. Sa sensibilité émotionnelle, qui pouvait être un frein, s\'est transformée en capacité à lire finement les besoins à distance. Très belle progression.',
            ]
        );

        // ── PA20 ──────────────────────────────────────────────────────────
        $p['pratique_pa20_s1'] = $this->obs('2026-05-21', 'Séance 1', 'PA20',
            'Première observation PA20. Léa est curieuse mais on perçoit une légère appréhension face aux enjeux de sécurité renforcés à 20m. Elle prend des notes méthodiques sur les protocoles spécifiques — bonne prise de conscience.'
        );

        $p['pratique_pa20_s2'] = $this->session(
            '2026-05-28', 'Séance 2', 'PA20', 'supervision_directe', '1',
            2, 2, 2, 2, 2, 1, 2,
            [
                'global_comment'         => 'NT : vérifications de sécurité incomplètes — point non négociable à reprendre.',
                'c_objectifs_note'       => 'Objectifs présents, mais la priorité sécuritaire spécifique au PA20 n\'a pas été intégrée.',
                'c_justification_note'   => 'La justification pédagogique est là, mais les paramètres de sécurité profondeur sont insuffisants.',
                'c_strategie_note'       => 'Stratégie trop calquée sur les séances BAPT/PE20, sans adaptation aux risques propres au PA20.',
                'r_animation_note'       => 'Animation correcte, mais le stress se perçoit — Léa est moins à l\'aise qu\'en surface.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre incomplète face à une situation d\'urgence potentielle.',
                'r_securite_note'        => 'Point critique : vérifications de sécurité pré-immersion incomplètes. NT impératif.',
                'e_evaluation_note'      => 'L\'évaluation n\'a pas abordé les aspects sécuritaires — problème majeur à corriger.',
                'session_note'           => 'Séance interrompue pour non-conformité sécuritaire. Léa a transféré son approche des niveaux moins profonds sans adapter aux exigences du PA20. Son anxiété face à ce contexte plus exigeant (neuroticisme élevé) a probablement contribué. Révision obligatoire des procédures PA avant toute nouvelle tentative en SD.',
            ]
        );

        // ── PE40 (N2) ─────────────────────────────────────────────────────
        $p['pratique_pe40_s1'] = $this->obs('2026-05-26', 'Séance 1', 'PE40',
            'Première observation PE40. Léa mesure l\'écart avec ce qu\'elle maîtrise. Studieuse, elle note les exigences techniques supplémentaires. Bonne prise de conscience de la distance à parcourir sur ce niveau.'
        );

        $p['pratique_pe40_s2'] = $this->obs('2026-06-02', 'Séance 2', 'PE40',
            'Deuxième observation PE40. Léa commence à se projeter sur une séance en SD. Elle pose des questions précises sur la gestion des groupes plus expérimentés. Progression dans sa lecture des situations complexes.'
        );

        // ── PA40 ──────────────────────────────────────────────────────────
        $p['pratique_pa40_s1'] = $this->obs('2026-06-10', 'Séance 1', 'PA40',
            'Première observation PA40. Léa est prudente — la bonne posture après l\'incident PA20. Elle prend le temps d\'analyser chaque étape du dispositif de sécurité. Cette vigilance acquise est un vrai progrès.'
        );

        // ── THÉORIQUE ─────────────────────────────────────────────────────
        $p['pression_n1'] = $this->session(
            '2026-05-06', 'La pression et ses influences sur le plongeur', 'N1',
            'supervision_directe', '3',
            3, 3, 3, 3, 3, null, 3,
            [
                'global_comment'         => 'Excellente séance théorique : Léa est dans son élément quand le sujet permet d\'interagir.',
                'c_objectifs_note'       => 'Objectifs clairs, progressifs et bien adaptés au niveau N1.',
                'c_justification_note'   => 'Solide — bons liens entre physiologie et pratique terrain.',
                'c_strategie_note'       => 'Stratégie interactive avec des questions et des mises en situation concrètes.',
                'r_animation_note'       => 'Très bonne animation. Léa utilise des analogies accessibles et l\'humour avec justesse.',
                'r_mise_en_oeuvre_note'  => 'Dynamique, bonne gestion du temps et du groupe.',
                'e_evaluation_note'      => 'Évaluation formative bien menée, retours immédiats et personnalisés.',
                'session_note'           => 'Léa s\'appuie sur son expérience de monitrice bénévole pour ancrer les concepts dans le concret. Sa pédagogie est intuitive et efficace. C\'est son terrain de jeu naturel.',
            ]
        );

        $p['autre__n1__milieu_aquatique'] = $this->session(
            '2026-05-13', 'Le milieu aquatique', 'N1',
            'supervision_directe', '3',
            3, 3, 3, 3, 3, null, 3,
            [
                'global_comment'         => 'Belle créativité pédagogique : Léa diversifie les supports avec naturel.',
                'c_objectifs_note'       => 'Bien ciblés, belle articulation avec la sortie de la semaine précédente.',
                'c_justification_note'   => 'Très bonne justification des choix de contenus.',
                'c_strategie_note'       => 'Utilise supports visuels, carte mentale et mises en situation. Léa enseigne bien par l\'image.',
                'r_animation_note'       => 'Animation très vivante — les élèves posent beaucoup de questions, ce qui montre l\'engagement.',
                'r_mise_en_oeuvre_note'  => 'Mise en œuvre soignée et rythmée.',
                'e_evaluation_note'      => 'Quiz en fin de séance — très bon choix pour ancrer les acquis.',
                'session_note'           => 'Léa montre une vraie créativité pédagogique en théorique. Elle diversifie les supports spontanément. C\'est sa façon naturelle d\'apprendre et d\'enseigner — cohérent avec son score d\'ouverture élevé.',
            ]
        );

        $p['desaturation_n2'] = $this->session(
            '2026-05-20', 'La désaturation', 'N2',
            'supervision_directe', '2',
            2, 2, 2, 2, 2, null, 2,
            [
                'global_comment'         => 'Léa atteint une limite sur ce sujet très technique : la base scientifique est insuffisante.',
                'c_objectifs_note'       => 'Objectifs un peu flous — Léa a du mal à identifier les points essentiels sur ce sujet complexe.',
                'c_justification_note'   => 'Incomplète sur les mécanismes physiologiques. Le fond scientifique est moins solide.',
                'c_strategie_note'       => 'Trop linéaire pour un sujet qui nécessite une approche spiralaire.',
                'r_animation_note'       => 'Moins assurée — Léa est moins à l\'aise quand elle ne maîtrise pas parfaitement le contenu.',
                'r_mise_en_oeuvre_note'  => 'Correcte mais des imprécisions factuelles à corriger avant la prochaine séance.',
                'e_evaluation_note'      => 'Trop légère, ne permet pas de s\'assurer de la compréhension réelle des élèves.',
                'session_note'           => 'Léa compense par sa pédagogie mais cela ne suffit pas sur un sujet aussi technique. Approfondir la physiologie de la décompression avant de retenter. Son anxiété face à l\'incertitude sur le contenu dégrade la qualité de l\'animation.',
            ]
        );

        $p['accidents_n2'] = $this->session(
            '2026-05-27', 'Les accidents de décompression', 'N2',
            'supervision_directe', '2',
            2, 2, 2, 2, 2, null, 2,
            [
                'global_comment'         => 'Deuxième séance difficile sur les thèmes N2 à forte composante médicale.',
                'c_objectifs_note'       => 'Objectifs trop généralistes pour un sujet qui nécessite de la précision médicale.',
                'c_justification_note'   => 'Léa peine à distinguer les différents types d\'accidents et leurs mécanismes.',
                'c_strategie_note'       => 'Manque de structuration — les sujets techniques nécessitent un plan rigoureux.',
                'r_animation_note'       => 'Plus hésitante que d\'habitude — le manque de maîtrise du sujet se ressent.',
                'r_mise_en_oeuvre_note'  => 'Quelques confusions factuelles dans la mise en œuvre. À corriger impérativement.',
                'e_evaluation_note'      => 'Ne discrimine pas suffisamment les acquis des lacunes.',
                'session_note'           => 'Deux séances ECA consécutives sur la physiologie N2. Léa doit approfondir ses connaissances théoriques dans ce domaine. Prévoir un accompagnement spécifique sur la préparation de ces sujets avant le prochain passage.',
            ]
        );

        $p['autre__n1__equipement_plongeur'] = $this->session(
            '2026-06-03', "L'équipement du plongeur", 'N1',
            'supervision_indirecte', '3',
            3, 3, 3, 3, 3, null, 3,
            [
                'global_comment'         => 'Léa retrouve toute sa confiance sur un sujet qu\'elle maîtrise.',
                'c_objectifs_note'       => 'Objectifs parfaitement définis pour une SI.',
                'c_justification_note'   => 'Justification rigoureuse et autonome.',
                'c_strategie_note'       => 'Belle stratégie pour faire découvrir le matériel de façon active.',
                'r_animation_note'       => 'Animation en retrait mais disponible — bonne maîtrise de la SI théorique.',
                'r_mise_en_oeuvre_note'  => 'Excellente. Les élèves sont acteurs de leur apprentissage.',
                'e_evaluation_note'      => 'Évaluation précise et individualisée.',
                'session_note'           => 'Léa retrouve toute sa confiance sur un sujet qu\'elle maîtrise et avec une progression pédagogique claire. Excellente séance en SI. Son rebond après les deux séances difficiles sur la décompression est très positif.',
            ]
        );

        $p['autre__n1__reglementation_plongee'] = $this->session(
            '2026-06-10', 'Réglementation et organisation de la plongée en France', 'N1',
            'supervision_indirecte', '3',
            3, 3, 3, 3, 3, null, 3,
            [
                'global_comment'         => 'Léa s\'appuie sur son terrain pour ancrer la réglementation dans le concret.',
                'c_objectifs_note'       => 'Très bons objectifs, bien ancrés dans la pratique réelle de monitrice.',
                'c_justification_note'   => 'Justification autonome et solide.',
                'c_strategie_note'       => 'Basée sur des cas pratiques vécus — très efficace pour un public de futurs moniteurs.',
                'r_animation_note'       => 'Animation confiante et vivante, Léa est dans son domaine.',
                'r_mise_en_oeuvre_note'  => 'Maîtrisée, bonne gestion des questions complexes.',
                'e_evaluation_note'      => 'Évaluation par mise en situation — excellent choix pédagogique.',
                'session_note'           => 'Belle façon de conclure le mois. Léa s\'appuie sur son expérience concrète de monitrice pour rendre la réglementation vivante. Deux séances SI consécutives A — très belle progression théorique.',
            ]
        );

        return $p;
    }
}
