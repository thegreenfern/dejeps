<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetencySeeder extends Seeder
{
    public function run(): void
    {
        $competencies = [

            // ─────────────────────────────────────────────────────────────────
            // FRAMEWORK: positioning
            // Source: Fiche de positionnement DEJEPS Plongée (Entretien mixte)
            // Scale stored as 1/2/3 = A/B/C
            //   A (1) = Je n'ai aucune notion
            //   B (2) = Je sais faire avec aide
            //   C (3) = Je maitrise de manière autonome
            // ─────────────────────────────────────────────────────────────────

            // ── Accueil du public ─────────────────────────────────────────
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Accueil du public',
             'code' => 'POS-ACC-01', 'sort_order' => 101,
             'label' => 'Accueillir et renseigner le public'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Accueil du public',
             'code' => 'POS-ACC-02', 'sort_order' => 102,
             'label' => 'Réceptionner des appels téléphoniques et prendre des rendez-vous'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Accueil du public',
             'code' => 'POS-ACC-03', 'sort_order' => 103,
             'label' => 'Prise de parole en public'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Accueil du public',
             'code' => 'POS-ACC-04', 'sort_order' => 104,
             'label' => 'Communiquer dans une langue étrangère'],

            // ── Gestion d'équipe ──────────────────────────────────────────
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Gestion d\'équipe',
             'code' => 'POS-EQU-01', 'sort_order' => 201,
             'label' => 'Organiser et planifier le fonctionnement d\'une équipe d\'encadrants au sein d\'une structure'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Gestion d\'équipe',
             'code' => 'POS-EQU-02', 'sort_order' => 202,
             'label' => 'Organiser une réunion avec des collaborateurs'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Gestion d\'équipe',
             'code' => 'POS-EQU-03', 'sort_order' => 203,
             'label' => 'Animer une équipe : hiérarchie, dialogue social, relations humaines, gestion de conflits'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Gestion d\'équipe',
             'code' => 'POS-EQU-04', 'sort_order' => 204,
             'label' => 'Organiser et superviser les interventions d\'une équipe'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Gestion d\'équipe',
             'code' => 'POS-EQU-05', 'sort_order' => 205,
             'label' => 'Elaborer et mettre en place un projet avec des collaborateurs au sein d\'une structure'],

            // ── Maitrise des outils informatiques ─────────────────────────
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Maitrise des outils informatiques',
             'code' => 'POS-INFO-01', 'sort_order' => 301,
             'label' => 'Utiliser Word ou tout autre logiciel de traitement de texte'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Maitrise des outils informatiques',
             'code' => 'POS-INFO-02', 'sort_order' => 302,
             'label' => 'Utiliser Excel ou tout autre logiciel de comptabilité'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Maitrise des outils informatiques',
             'code' => 'POS-INFO-03', 'sort_order' => 303,
             'label' => 'Utiliser PowerPoint ou tout autre logiciel de présentation'],
            ['uc' => 'UC2', 'framework' => 'positioning', 'category' => 'Maitrise des outils informatiques',
             'code' => 'POS-INFO-04', 'sort_order' => 304,
             'label' => 'Utiliser des outils d\'IA ou de dvpt informatique'],

            // ── Utilisation du matériel de plongée ────────────────────────
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Utilisation du matériel de plongée',
             'code' => 'POS-MAT-01', 'sort_order' => 401,
             'label' => 'Conseiller les clients sur les choix d\'acquisition d\'équipements individuels'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Utilisation du matériel de plongée',
             'code' => 'POS-MAT-02', 'sort_order' => 402,
             'label' => 'Diagnostiquer l\'état du matériel de la structure'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Utilisation du matériel de plongée',
             'code' => 'POS-MAT-03', 'sort_order' => 403,
             'label' => 'Assurer l\'hygiène et réaliser ou organiser la maintenance et l\'entretien courant des équipements individuels'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Utilisation du matériel de plongée',
             'code' => 'POS-MAT-04', 'sort_order' => 404,
             'label' => 'Organiser et gérer le stockage, la distribution et la restitution des équipements individuels aux plongeurs et aux randonneurs en fonction de leurs besoins'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Utilisation du matériel de plongée',
             'code' => 'POS-MAT-05', 'sort_order' => 405,
             'label' => 'Utiliser la station de gonflage et participer à sa maintenance'],

            // ── Utilisation d'un navire ────────────────────────────────────
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Utilisation d\'un navire',
             'code' => 'POS-NAV-01', 'sort_order' => 501,
             'label' => 'Piloter un navire support de plongée armé en plaisance'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Utilisation d\'un navire',
             'code' => 'POS-NAV-02', 'sort_order' => 502,
             'label' => 'Effectuer et mettre en œuvre des choix de navigation appliqués à la plongée (navigation, identification et repérage d\'un site)'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Utilisation d\'un navire',
             'code' => 'POS-NAV-03', 'sort_order' => 503,
             'label' => 'Communiquer efficacement à partir d\'un bateau de plongée (radio, VHF)'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Utilisation d\'un navire',
             'code' => 'POS-NAV-04', 'sort_order' => 504,
             'label' => 'Effectuer des manœuvres d\'accostage, d\'abordage et de positionnement d\'urgence à des fins sécuritaires'],

            // ── Conduite de palanquée (zone 0–40m) ───────────────────────
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Conduite de palanquée (zone 0–40m)',
             'code' => 'POS-PAL-01', 'sort_order' => 601,
             'label' => 'Construire un briefing et débriefing complet d\'une plongée en exploration'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Conduite de palanquée (zone 0–40m)',
             'code' => 'POS-PAL-02', 'sort_order' => 602,
             'label' => 'Communiquer avec les plongeurs de la palanquée pour organiser l\'immersion'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Conduite de palanquée (zone 0–40m)',
             'code' => 'POS-PAL-03', 'sort_order' => 603,
             'label' => 'Gérer le déroulement de l\'exploration de la mise à l\'eau au retour au sec, en évoluant dans la zone 0–40m, à l\'air ou au nitrox'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Conduite de palanquée (zone 0–40m)',
             'code' => 'POS-PAL-04', 'sort_order' => 604,
             'label' => 'Assurer la sécurité des plongeurs durant l\'immersion et savoir faire face à l\'imprévu en toute situation'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'Conduite de palanquée (zone 0–40m)',
             'code' => 'POS-PAL-05', 'sort_order' => 605,
             'label' => 'Maîtriser les techniques individuelles de plongée à l\'air et au nitrox dans la zone 0/40m, notamment les techniques d\'assistance d\'un plongeur en difficulté'],

            // ── La formation de plongeur ──────────────────────────────────
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'La formation de plongeur',
             'code' => 'POS-FOR-01', 'sort_order' => 701,
             'label' => 'Encadrer des plongeurs réalisant leur première immersion (baptême, découverte d\'un équipement nouveau)'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'La formation de plongeur',
             'code' => 'POS-FOR-02', 'sort_order' => 702,
             'label' => 'Conduire des séances de formation pratique de plongeurs de tous niveaux, à l\'air et au nitrox, dans la zone 0–40m'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'La formation de plongeur',
             'code' => 'POS-FOR-03', 'sort_order' => 703,
             'label' => 'Connaître les cursus de formation de niveau 1, 2, 3 mentionnés au code du sport'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'La formation de plongeur',
             'code' => 'POS-FOR-04', 'sort_order' => 704,
             'label' => 'Organiser et conduire des actions d\'évaluation des capacités des plongeurs en formation'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'La formation de plongeur',
             'code' => 'POS-FOR-05', 'sort_order' => 705,
             'label' => 'Prendre en compte les spécificités de publics particuliers (handicapés, mineurs…) et adapter sa démarche pédagogique'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'La formation de plongeur',
             'code' => 'POS-FOR-06', 'sort_order' => 706,
             'label' => 'Conduire une formation théorique pour des plongeurs en formation dans l\'espace 0–40m'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'La formation de plongeur',
             'code' => 'POS-FOR-07', 'sort_order' => 707,
             'label' => 'Conduire des formations théorique et pratique dans d\'autres cursus (PADI, SSI, RAID, NAUI)'],

            // ── Randonnée subaquatique ────────────────────────────────────
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'Randonnée subaquatique',
             'code' => 'POS-RANDO-01', 'sort_order' => 801,
             'label' => 'Organiser la sécurité des randonneurs sur le site de pratique'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'Randonnée subaquatique',
             'code' => 'POS-RANDO-02', 'sort_order' => 802,
             'label' => 'Conduire un groupe de randonneurs en pratique encadrée et animer la séance'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'Randonnée subaquatique',
             'code' => 'POS-RANDO-03', 'sort_order' => 803,
             'label' => 'Former spécifiquement des randonneurs à la connaissance, au respect et à la protection du milieu et de l\'environnement'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'Randonnée subaquatique',
             'code' => 'POS-RANDO-04', 'sort_order' => 804,
             'label' => 'Choisir et équiper des sites de pratique de la randonnée subaquatique'],

            // ── La direction de plongée ───────────────────────────────────
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'La direction de plongée',
             'code' => 'POS-DIR-01', 'sort_order' => 901,
             'label' => 'Choisir un site et des conditions adaptées'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'La direction de plongée',
             'code' => 'POS-DIR-02', 'sort_order' => 902,
             'label' => 'Définir les caractéristiques d\'évolution des palanquées et leur donner des consignes, des conseils et utiliser la fiche de sécurité'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'La direction de plongée',
             'code' => 'POS-DIR-03', 'sort_order' => 903,
             'label' => 'Définir des palanquées pour un groupe de plongeurs en désignant les guides de palanquée et encadrants'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'La direction de plongée',
             'code' => 'POS-DIR-04', 'sort_order' => 904,
             'label' => 'Installer, vérifier et entretenir le matériel de sécurité'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'La direction de plongée',
             'code' => 'POS-DIR-05', 'sort_order' => 905,
             'label' => 'Savoir gérer les premiers secours et utiliser le matériel d\'oxygénothérapie'],
            ['uc' => 'UC4', 'framework' => 'positioning', 'category' => 'La direction de plongée',
             'code' => 'POS-DIR-06', 'sort_order' => 906,
             'label' => 'Savoir déclencher l\'alerte par téléphone et VHF'],

            // ── Le tutorat ────────────────────────────────────────────────
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'Le tutorat',
             'code' => 'POS-TUT-01', 'sort_order' => 1001,
             'label' => 'Accueillir des stagiaires et planifier leurs interventions'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'Le tutorat',
             'code' => 'POS-TUT-02', 'sort_order' => 1002,
             'label' => 'Procéder à l\'évaluation de la progression des stagiaires et à l\'évaluation des compétences acquises au cours de leur stage'],
            ['uc' => 'UC3', 'framework' => 'positioning', 'category' => 'Le tutorat',
             'code' => 'POS-TUT-03', 'sort_order' => 1003,
             'label' => 'Assurer la mission de maître d\'apprentissage'],


            // ─────────────────────────────────────────────────────────────────
            // FRAMEWORK: certification
            // Critères des grilles d'évaluation officielles CREPS (TI/I/S/M)
            // ─────────────────────────────────────────────────────────────────

            // ── UC1 — Concevoir un projet d'action ──────────────────────────
            ['uc' => 'UC1', 'framework' => 'certification', 'category' => 'uc1_projet',
             'code' => 'UC1-P-01', 'sort_order' => 10001,
             'label' => 'Pertinence et exhaustivité de l\'analyse du contexte et des besoins'],
            ['uc' => 'UC1', 'framework' => 'certification', 'category' => 'uc1_projet',
             'code' => 'UC1-P-02', 'sort_order' => 10002,
             'label' => 'Cohérence des objectifs opérationnels avec les besoins identifiés'],
            ['uc' => 'UC1', 'framework' => 'certification', 'category' => 'uc1_projet',
             'code' => 'UC1-P-03', 'sort_order' => 10003,
             'label' => 'Qualité de la progression pédagogique et des contenus proposés'],
            ['uc' => 'UC1', 'framework' => 'certification', 'category' => 'uc1_projet',
             'code' => 'UC1-P-04', 'sort_order' => 10004,
             'label' => 'Pertinence des outils et modalités d\'évaluation des apprenants'],
            ['uc' => 'UC1', 'framework' => 'certification', 'category' => 'uc1_projet',
             'code' => 'UC1-P-05', 'sort_order' => 10005,
             'label' => 'Prise en compte des contraintes réglementaires, sécuritaires et environnementales'],
            ['uc' => 'UC1', 'framework' => 'certification', 'category' => 'uc1_projet',
             'code' => 'UC1-P-06', 'sort_order' => 10006,
             'label' => 'Clarté, qualité de présentation et capacité d\'argumentation du projet'],

            // ── UC2 — Coordonner et mettre en œuvre ─────────────────────────
            ['uc' => 'UC2', 'framework' => 'certification', 'category' => 'uc2_projet',
             'code' => 'UC2-P-01', 'sort_order' => 20001,
             'label' => 'Organisation et mobilisation des ressources humaines et matérielles'],
            ['uc' => 'UC2', 'framework' => 'certification', 'category' => 'uc2_projet',
             'code' => 'UC2-P-02', 'sort_order' => 20002,
             'label' => 'Coordination des différents acteurs impliqués dans le projet'],
            ['uc' => 'UC2', 'framework' => 'certification', 'category' => 'uc2_projet',
             'code' => 'UC2-P-03', 'sort_order' => 20003,
             'label' => 'Animation des temps collectifs (réunions, formations internes)'],
            ['uc' => 'UC2', 'framework' => 'certification', 'category' => 'uc2_projet',
             'code' => 'UC2-P-04', 'sort_order' => 20004,
             'label' => 'Capacité d\'adaptation et de régulation face aux imprévus'],
            ['uc' => 'UC2', 'framework' => 'certification', 'category' => 'uc2_projet',
             'code' => 'UC2-P-05', 'sort_order' => 20005,
             'label' => 'Évaluation des résultats et des effets du projet'],
            ['uc' => 'UC2', 'framework' => 'certification', 'category' => 'uc2_projet',
             'code' => 'UC2-P-06', 'sort_order' => 20006,
             'label' => 'Qualité du bilan écrit et de la restitution orale'],

            // ── UC3 — Épreuve écrite / QCM ───────────────────────────────────
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_ecrite',
             'code' => 'UC3-E-01', 'sort_order' => 30001,
             'label' => 'Score au QCM de connaissances théoriques (note sur 20)'],

            // ── UC3 — Pédagogie Pratique en plongée ──────────────────────────
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_pratique',
             'code' => 'UC3-PP-01', 'sort_order' => 30101,
             'label' => 'Préparation, organisation et mise en sécurité de la séance'],
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_pratique',
             'code' => 'UC3-PP-02', 'sort_order' => 30102,
             'label' => 'Qualité de la démonstration des savoir-faire techniques'],
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_pratique',
             'code' => 'UC3-PP-03', 'sort_order' => 30103,
             'label' => 'Pertinence et clarté des consignes et explications transmises'],
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_pratique',
             'code' => 'UC3-PP-04', 'sort_order' => 30104,
             'label' => 'Capacité à détecter les erreurs et à corriger efficacement'],
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_pratique',
             'code' => 'UC3-PP-05', 'sort_order' => 30105,
             'label' => 'Adaptation de la séance aux conditions et aux apprenants'],
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_pratique',
             'code' => 'UC3-PP-06', 'sort_order' => 30106,
             'label' => 'Évaluation des acquis et débriefing de fin de séance'],

            // ── UC3 — Pédagogie Théorique en surface ─────────────────────────
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_theorique',
             'code' => 'UC3-PT-01', 'sort_order' => 30201,
             'label' => 'Structure, logique et progression de l\'exposé théorique'],
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_theorique',
             'code' => 'UC3-PT-02', 'sort_order' => 30202,
             'label' => 'Pertinence et qualité des supports pédagogiques utilisés'],
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_theorique',
             'code' => 'UC3-PT-03', 'sort_order' => 30203,
             'label' => 'Exactitude et clarté des connaissances transmises'],
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_theorique',
             'code' => 'UC3-PT-04', 'sort_order' => 30204,
             'label' => 'Interaction avec le groupe, gestion des questions et vérification de la compréhension'],
            ['uc' => 'UC3', 'framework' => 'certification', 'category' => 'uc3_peda_theorique',
             'code' => 'UC3-PT-05', 'sort_order' => 30205,
             'label' => 'Adaptation du discours et du niveau au public cible'],

            // ── UC4 — Pédagogie Pratique de la Sécurité ──────────────────────
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_peda_pratique',
             'code' => 'UC4-PP-01', 'sort_order' => 40101,
             'label' => 'Identification et prévention des risques liés à l\'activité'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_peda_pratique',
             'code' => 'UC4-PP-02', 'sort_order' => 40102,
             'label' => 'Organisation et mise en œuvre des procédures de sécurité'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_peda_pratique',
             'code' => 'UC4-PP-03', 'sort_order' => 40103,
             'label' => 'Qualité de l\'enseignement des gestes et réflexes de sécurité'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_peda_pratique',
             'code' => 'UC4-PP-04', 'sort_order' => 40104,
             'label' => 'Gestion et résolution d\'une situation accidentelle simulée'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_peda_pratique',
             'code' => 'UC4-PP-05', 'sort_order' => 40105,
             'label' => 'Maîtrise et utilisation du matériel de secours (O₂, DSA, BAVU)'],

            // ── UC4 — Direction de Plongée ────────────────────────────────────
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_direction',
             'code' => 'UC4-DIR-01', 'sort_order' => 40201,
             'label' => 'Planification de la plongée (site, profil, palanquées, conditions)'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_direction',
             'code' => 'UC4-DIR-02', 'sort_order' => 40202,
             'label' => 'Qualité et exhaustivité du briefing sécurité'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_direction',
             'code' => 'UC4-DIR-03', 'sort_order' => 40203,
             'label' => 'Gestion des palanquées, des temps et des profondeurs'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_direction',
             'code' => 'UC4-DIR-04', 'sort_order' => 40204,
             'label' => 'Organisation et tenue de la sécurité de surface'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_direction',
             'code' => 'UC4-DIR-05', 'sort_order' => 40205,
             'label' => 'Réactivité et pertinence des décisions face aux imprévus'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_direction',
             'code' => 'UC4-DIR-06', 'sort_order' => 40206,
             'label' => 'Qualité du débriefing et de l\'analyse critique de la plongée'],

            // ── UC4 — Épreuve Physique Mannequin ─────────────────────────────
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_mannequin',
             'code' => 'UC4-MAN-01', 'sort_order' => 40301,
             'label' => '200m nage avec palmes, masque et tuba'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_mannequin',
             'code' => 'UC4-MAN-02', 'sort_order' => 40302,
             'label' => 'Remontée depuis 10m avec mannequin (seuil : < 5 min)'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_mannequin',
             'code' => 'UC4-MAN-03', 'sort_order' => 40303,
             'label' => 'Tractage du mannequin sur 100m en surface'],
            ['uc' => 'UC4', 'framework' => 'certification', 'category' => 'uc4_mannequin',
             'code' => 'UC4-MAN-04', 'sort_order' => 40304,
             'label' => 'Temps total de l\'épreuve (seuil éliminatoire : < 8 min)'],
        ];

        $now = now();
        foreach ($competencies as &$row) {
            $row['active']     = true;
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        DB::table('competencies')->insert($competencies);
    }
}
