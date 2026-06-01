@extends('layouts.app')
@section('title', 'Formateur · Aide')

@section('content')
@php
$imgV = fn(string $name): string => '?v=' . (filemtime(public_path('aide-screenshots/' . $name)) ?: time());
@endphp
<div style="max-width:1100px;margin:0 auto;padding:2rem 1.5rem;display:flex;gap:2.5rem;align-items:flex-start">

    {{-- ── Left navigation ──────────────────────────────────────────── --}}
    <aside id="aide-nav" style="width:210px;flex-shrink:0;position:sticky;top:2rem">
        <p style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem">Guide d'utilisation</p>
        <nav style="display:flex;flex-direction:column;gap:2px">
            @php
            $navItems = [
                ['href' => '#vue-ensemble',      'label' => 'Vue d\'ensemble'],
                ['href' => '#gestion-stagiaires','label' => 'Gestion des stagiaires'],
                ['href' => '#personnalite',        'label' => 'Test de personnalité'],
                ['href' => '#ajout-seance',        'label' => 'Ajout d\'une séance'],
                ['href' => '#peda-pratique',      'label' => 'Pédagogie pratique'],
                ['href' => '#peda-theorique',     'label' => 'Pédagogie théorique'],
                ['href' => '#automations',        'label' => 'Automations'],
                ['href' => '#uc12',               'label' => 'UC1 / UC2'],
                ['href' => '#epmsp',              'label' => 'EPMSP'],
                ['href' => '#parcours',           'label' => 'Parcours & jalons'],
                ['href' => '#positionnement',     'label' => 'Positionnement'],
                ['href' => '#calendrier',         'label' => 'Calendrier'],
                ['href' => '#vue-stagiaire',      'label' => 'Vue stagiaire'],
                ['href' => '#notifications',      'label' => 'Notifications'],
            ];
            @endphp
            @foreach($navItems as $item)
            <a href="{{ $item['href'] }}"
               style="display:block;padding:.35rem .7rem;border-radius:.4rem;font-size:.8rem;color:#475569;text-decoration:none;transition:background .15s,color .15s"
               onmouseenter="this.style.background='#f1f5f9';this.style.color='#6d28d9'"
               onmouseleave="this.style.background='transparent';this.style.color='#475569'"
               onclick="setActive(this)">
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>
    </aside>

    {{-- ── Main content ──────────────────────────────────────────────── --}}
    <main style="flex:1;min-width:0">

        {{-- Page header --}}
        <div style="margin-bottom:2.5rem">
            <h1 style="font-size:1.75rem;font-weight:700;color:#1e293b;margin:0 0 .35rem">Guide d'utilisation</h1>
            <p style="font-size:.9rem;color:#64748b;margin:0">Plateforme de suivi DEJEPS Plongée — interface formateur</p>
        </div>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 1 · Vue d'ensemble
        ───────────────────────────────────────────────────────────────── --}}
        <section id="vue-ensemble" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Vue d'ensemble
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                La plateforme de suivi DEJEPS Plongée centralise tout le pilotage de la formation : progression pédagogique pratique et théorique, séances réalisées, évaluations UC1/UC2, EPMSP et positionnement initial. Elle est utilisable depuis deux rôles distincts — <strong>formateur</strong> et <strong>stagiaire</strong> — accessibles depuis la page d'accueil.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/01-home.png{{ $imgV('01-home.png') }}" alt="Page d'accueil — sélection du rôle" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Page d'accueil — sélection du rôle (formateur ou stagiaire)</p>
            </div>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/02-dashboard.png{{ $imgV('02-dashboard.png') }}" alt="Tableau de bord formateur" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Tableau de bord formateur — liste des stagiaires et indicateurs de progression</p>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 2 · Gestion des stagiaires
        ───────────────────────────────────────────────────────────────── --}}
        <section id="gestion-stagiaires" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Gestion des stagiaires
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                La fiche stagiaire regroupe l'ensemble des outils de suivi en un seul endroit. Elle est accessible depuis le tableau de bord en cliquant sur le nom du stagiaire. Elle est organisée en cinq onglets : <strong>Profil</strong>, <strong>UC1/UC2</strong>, <strong>EPMSP</strong>, <strong>Pédagogie</strong> et <strong>Parcours</strong>.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/03-trainee-profil.png{{ $imgV('03-trainee-profil.png') }}" alt="Fiche stagiaire — onglet Profil" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Fiche stagiaire — onglet Profil</p>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION · Test de personnalité (Big Five)
        ───────────────────────────────────────────────────────────────── --}}
        <section id="personnalite" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Test de personnalité
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                Lors de l'onboarding, le stagiaire répond à un questionnaire de <strong>120 affirmations</strong> basé sur le modèle <strong>Big Five (OCEAN)</strong> — le modèle de personnalité le plus documenté en psychologie. Les résultats sont visibles par le formateur dans l'onglet <em>Profil → Personnalité</em> de la fiche stagiaire et servent à personnaliser l'accompagnement pédagogique.
            </p>
            <p style="font-size:.8rem;line-height:1.6;color:#94a3b8;margin-bottom:1.5rem">
                Il n'y a pas de profil idéal — ces tendances indiquent des points d'appui et des axes de travail, pas des jugements de valeur.
            </p>

            {{-- The 5 traits --}}
            @php
            $traits = [
                [
                    'key'   => 'O',
                    'label' => 'Ouverture',
                    'color' => '#38bdf8',
                    'what'  => 'Curiosité intellectuelle, créativité, goût pour les idées nouvelles et les expériences variées.',
                    'high'  => ['desc' => 'Grande curiosité et adaptabilité pédagogique spontanée. Apprécie l\'innovation dans ses méthodes d\'enseignement.', 'tip' => 'Peut manquer de structure. Encourager une préparation rigoureuse et un fil directeur clair par séance.'],
                    'mid'   => ['desc' => 'Équilibre entre méthodes éprouvées et ouverture à l\'innovation. S\'adapte bien aux différents contextes.', 'tip' => 'Profil polyvalent, à l\'aise dans la plupart des situations pédagogiques standard.'],
                    'low'   => ['desc' => 'Préfère des approches concrètes et des procédures établies. Fiable dans un cadre bien défini.', 'tip' => 'Peut avoir du mal à s\'adapter à des publics atypiques. Travailler la flexibilité et la différenciation pédagogique.'],
                ],
                [
                    'key'   => 'C',
                    'label' => 'Conscienciosité',
                    'color' => '#a78bfa',
                    'what'  => 'Organisation, rigueur, persévérance, respect des engagements et des procédures.',
                    'high'  => ['desc' => 'Organisation irréprochable, sens du détail, planification soignée des séances.', 'tip' => 'Peut avoir des difficultés à improviser. Travailler la gestion des imprévus et la tolérance à l\'ambiguïté.'],
                    'mid'   => ['desc' => 'Bonne organisation générale avec capacité à s\'adapter. Bon équilibre préparation / flexibilité.', 'tip' => 'Veiller à maintenir la rigueur sur les éléments de sécurité plongée.'],
                    'low'   => ['desc' => 'Spontané et flexible, préfère l\'action dans l\'instant à la planification.', 'tip' => 'À accompagner sur la planification pédagogique et le strict respect des protocoles de sécurité aquatique.'],
                ],
                [
                    'key'   => 'E',
                    'label' => 'Extraversion',
                    'color' => '#fbbf24',
                    'what'  => 'Aisance sociale, énergie en groupe, prise de parole spontanée, enthousiasme.',
                    'high'  => ['desc' => 'Naturellement à l\'aise devant un groupe. Dynamise les séances et crée facilement du lien.', 'tip' => 'Peut monopoliser l\'espace de parole. Travailler l\'écoute active et les temps de silence pédagogique.'],
                    'mid'   => ['desc' => 'S\'adapte aussi bien aux contextes collectifs qu\'individuels. Bon équilibre présence / écoute.', 'tip' => 'À l\'aise en grand groupe comme en suivi individuel. Profil polyvalent.'],
                    'low'   => ['desc' => 'Plus à l\'aise en accompagnement individuel ou en petits groupes. Peut paraître réservé devant un large public.', 'tip' => 'Travailler la prise de parole en groupe, l\'affirmation de soi et l\'animation de séances collectives.'],
                ],
                [
                    'key'   => 'A',
                    'label' => 'Agréabilité',
                    'color' => '#34d399',
                    'what'  => 'Empathie, coopération, confiance envers autrui, bienveillance, altruisme.',
                    'high'  => ['desc' => 'Très empathique. Crée un climat de confiance propice à l\'apprentissage et à la prise de risque.', 'tip' => 'Peut avoir du mal à formuler des feedbacks négatifs ou à maintenir l\'exigence. Travailler l\'assertivité.'],
                    'mid'   => ['desc' => 'Allie coopération et assertivité. Capable d\'un feedback équilibré, bienveillant et direct.', 'tip' => 'Bon équilibre pédagogique. S\'adapte à des apprenants aux profils variés.'],
                    'low'   => ['desc' => 'Direct et objectif dans ses évaluations. Peut paraître exigeant ou froid pour certains apprenants.', 'tip' => 'Travailler la chaleur relationnelle et l\'adaptation du discours selon la sensibilité de l\'apprenant.'],
                ],
                [
                    'key'   => 'N',
                    'label' => 'Stabilité émotionnelle',
                    'color' => '#f87171',
                    'what'  => 'Calme sous pression, résistance au stress, régulation émotionnelle. Affiché inversé : un score élevé = grande stabilité.',
                    'high'  => ['desc' => 'Grande stabilité émotionnelle, calme sous pression. Ressource précieuse pour la gestion de la sécurité en plongée.', 'tip' => 'Veiller à rester attentif aux signaux émotionnels des apprenants — la stabilité ne doit pas devenir froideur.'],
                    'mid'   => ['desc' => 'Réactivité émotionnelle dans la norme. Garde généralement son calme dans les situations standard.', 'tip' => 'Peut être affecté par les situations de forte pression. Accompagner sur la gestion du stress en conditions réelles.'],
                    'low'   => ['desc' => 'Sensible au stress et aux imprévus. Peut être affecté par les conflits ou la pression des évaluations.', 'tip' => 'Priorité : développer les stratégies de régulation émotionnelle, en particulier dans les contextes de sécurité aquatique.'],
                ],
            ];
            @endphp

            @foreach($traits as $t)
            <div style="border:1px solid #e2e8f0;border-radius:.6rem;padding:1.1rem 1.25rem;margin-bottom:.85rem;background:#fff">
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.5rem">
                    <div style="width:10px;height:10px;border-radius:50%;background:{{ $t['color'] }};flex-shrink:0"></div>
                    <span style="font-size:.9rem;font-weight:700;color:#1e293b">{{ $t['label'] }}</span>
                    <span style="font-size:.75rem;color:#94a3b8;font-style:italic">{{ $t['key'] }}</span>
                </div>
                <p style="font-size:.82rem;color:#64748b;margin:0 0 .75rem;line-height:1.6">{{ $t['what'] }}</p>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.5rem">
                    @foreach([['Élevé (≥ 65)', $t['high']], ['Modéré (35–64)', $t['mid']], ['Faible (< 35)', $t['low']]] as [$band, $info])
                    <div style="background:#f8fafc;border-radius:.4rem;padding:.65rem .75rem">
                        <p style="font-size:.75rem;font-weight:700;color:#1e293b;margin:0 0 .3rem">{{ $band }}</p>
                        <p style="font-size:.76rem;color:#475569;margin:0 0 .5rem;line-height:1.5">{{ $info['desc'] }}</p>
                        <div style="display:flex;gap:.4rem;align-items:flex-start">
                            <svg style="width:11px;height:11px;flex-shrink:0;margin-top:2px;color:#94a3b8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.347.347A3.5 3.5 0 0114.5 20h-5a3.5 3.5 0 01-2.475-1.025l-.347-.347z"/></svg>
                            <p style="font-size:.73rem;color:#64748b;margin:0;line-height:1.5">{{ $info['tip'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <h3 style="font-size:.95rem;font-weight:600;color:#1e293b;margin:1.5rem 0 .5rem">Comment est calculé le score ?</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Chaque trait est mesuré par 24 affirmations (12 dans le sens positif, 12 dans le sens négatif). Le stagiaire répond sur une échelle de 1 à 5 (Pas du tout d'accord → Tout à fait d'accord). Le score final est ramené sur 100 : 50 = neutre, 0 = très faible, 100 = très élevé.
            </p>
            <ul style="font-size:.875rem;line-height:1.8;color:#475569;margin:0 0 .75rem;padding-left:1.25rem">
                <li>Affirmation positive : score = (réponse − 1) × 25</li>
                <li>Affirmation négative (inversée) : score = (5 − réponse) × 25</li>
                <li>Score du trait = moyenne des 24 items (0–100)</li>
            </ul>
            <div style="background:#fef9c3;border:1px solid #fde68a;border-radius:.5rem;padding:.85rem 1rem;font-size:.82rem;color:#78350f;line-height:1.7">
                <strong>Attention — Stabilité émotionnelle (N) :</strong> le questionnaire mesure techniquement le <em>névrosisme</em> (tendance à l'anxiété). L'affichage l'inverse : un score <strong>élevé affiché</strong> signifie une grande stabilité émotionnelle (faible névrosisme). Un score <strong>faible affiché</strong> signifie une sensibilité émotionnelle élevée.
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 3 · Ajout d'une séance
        ───────────────────────────────────────────────────────────────── --}}
        <section id="ajout-seance" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Ajout d'une séance
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                Chaque séance d'enseignement (pratique ou théorique) est saisie via le formulaire d'ajout. Il permet de renseigner la date, le sujet/niveau, la <strong>situation pédagogique</strong> et les compétences évaluées. C'est la situation pédagogique choisie ici qui détermine comment la note globale est calculée et comment la progression automatique avance (voir <a href="#automations" style="color:#6d28d9">Automations</a>).
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/09-session-add.png{{ $imgV('09-session-add.png') }}" alt="Formulaire d'ajout de séance" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Formulaire d'ajout de séance — informations générales et situation pédagogique</p>
            </div>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/09b-session-add-situation.png{{ $imgV('09b-session-add-situation.png') }}" alt="Formulaire d'ajout — situation pédagogique" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Situation pédagogique — choix entre Observation, Supervision directe, Supervision indirecte et Autonomie</p>
            </div>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/09c-session-add-competences.png{{ $imgV('09c-session-add-competences.png') }}" alt="Formulaire d'ajout — compétences" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Grille d'évaluation des compétences (notation NT / ECA / A)</p>
            </div>

            <h3 style="font-size:.95rem;font-weight:600;color:#1e293b;margin:1.5rem 0 .5rem">Calcul de la note globale</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Une note globale est calculée automatiquement à la sauvegarde de chaque séance selon les règles suivantes :
            </p>
            <ul style="font-size:.875rem;line-height:1.8;color:#475569;margin:0 0 1rem;padding-left:1.25rem">
                <li><strong>Observation :</strong> la séance est toujours notée <strong>A</strong> — le stagiaire est en découverte, aucune compétence n'est encore attendue.</li>
                <li><strong>Supervision directe / indirecte / Autonomie :</strong> la note est calculée à partir de la <em>moyenne</em> des 6 compétences standard (objectifs, justification, stratégie, animation, mise en œuvre, évaluation). Les seuils varient selon la situation :
                    <ul style="margin-top:.25rem;padding-left:1.25rem">
                        <li>Supervision directe : moyenne &lt; 1,5 → NT · &lt; 2,5 → ECA · ≥ 2,5 → <strong>A</strong></li>
                        <li>Supervision indirecte : moyenne &lt; 1,5 → NT · &lt; 2,8 → ECA · ≥ 2,8 → <strong>A</strong></li>
                        <li>Autonomie : moyenne &lt; 1,5 → NT · &lt; 3,0 → ECA · = 3,0 → <strong>A</strong></li>
                    </ul>
                </li>
                <li><strong>Séances pratiques uniquement :</strong> la compétence <em>Sécurité</em> est évaluée en priorité. Si elle est notée NT (1) → la séance est NT. Si ECA (2) → la séance est ECA, quelle que soit la moyenne des autres compétences.</li>
            </ul>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 4 · Pédagogie pratique
        ───────────────────────────────────────────────────────────────── --}}
        <section id="peda-pratique" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Pédagogie pratique
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                L'onglet <strong>Pédagogie pratique</strong> présente pour chaque niveau (Baptême, N1, N2, N3) un calendrier de progression sous forme de stepper à quatre étapes : <em>Obs → SD → SI → Auto</em>. La bulle colorée indique le statut courant du stagiaire. Le tableau affiche également le nombre de séances réalisées à ce niveau et l'échéance prévue.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/06-peda-pratique.png{{ $imgV('06-peda-pratique.png') }}" alt="Onglet Pédagogie pratique" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Pédagogie pratique — calendrier de progression et liste des séances</p>
            </div>

            <h3 style="font-size:.95rem;font-weight:600;color:#1e293b;margin:1.5rem 0 .5rem">Avancement automatique du statut</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Le statut de chaque niveau avance <strong>automatiquement</strong> dès que le stagiaire accumule <strong>2 séances notées A</strong> dans une situation donnée. La progression suit l'ordre Obs → SD → SI → Auto et ne peut pas reculer automatiquement.
            </p>
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:.5rem;padding:1rem;margin-bottom:1rem;font-size:.82rem;line-height:1.8;color:#475569">
                <strong>Exemple :</strong> un stagiaire au niveau N1 a 2 séances A en Observation → le statut passe automatiquement à <em>Supervision directe</em>. Dès qu'il obtient 2 A en Supervision directe → il passe à <em>Supervision indirecte</em>. Et ainsi de suite.
            </div>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Rappel : les séances en <em>Observation</em> sont toujours notées A automatiquement, donc 2 séances d'observation suffisent pour déclencher le premier palier.
            </p>

            <h3 style="font-size:.95rem;font-weight:600;color:#1e293b;margin:1.5rem 0 .5rem">Surclassement manuel</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Le formateur peut <strong>cliquer sur n'importe quelle bulle</strong> du stepper pour forcer manuellement le statut à un palier donné. Une fenêtre de confirmation s'affiche avant d'enregistrer le changement. Une fois le statut défini manuellement, l'automatisation n'écrase plus ce choix — elle signale simplement en arrière-plan si elle aurait avancé davantage, sans l'appliquer.
            </p>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 5 · Pédagogie théorique
        ───────────────────────────────────────────────────────────────── --}}
        <section id="peda-theorique" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Pédagogie théorique
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                L'onglet <strong>Pédagogie théorique</strong> suit la progression sur quatre niveaux de contenu DEJEPS (N1–N4). Pour chaque niveau, le stepper Obs → SD → SI → Auto reflète la situation pédagogique la plus avancée observée dans les séances théoriques saisies. Un indicateur de couverture global indique combien de sujets DEJEPS ont été abordés au moins une fois.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/07-peda-theorique.png{{ $imgV('07-peda-theorique.png') }}" alt="Onglet Pédagogie théorique" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Pédagogie théorique — statut NT/En cours/Validé et stepper de situation pédagogique</p>
            </div>

            <h3 style="font-size:.95rem;font-weight:600;color:#1e293b;margin:1.5rem 0 .5rem">Statut NT / En cours / Validé</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Ce statut est calculé automatiquement à partir des séances théoriques saisies :
            </p>
            <ul style="font-size:.875rem;line-height:1.8;color:#475569;margin:0 0 1rem;padding-left:1.25rem">
                <li><strong>NT :</strong> aucune séance théorique enregistrée pour ce niveau.</li>
                <li><strong>En cours :</strong> au moins une séance notée ECA ou A sur un sujet DEJEPS de ce niveau (ou sur une séance "autre" du même niveau).</li>
                <li><strong>Validé :</strong> 60 % ou plus des sujets DEJEPS du niveau sont notés A. Seuils exacts : N1 = 2/2, N2 = 3/4, N3 = 2/3, N4 = 3/5.</li>
            </ul>

            <h3 style="font-size:.95rem;font-weight:600;color:#1e293b;margin:1.5rem 0 .5rem">Stepper de situation pédagogique</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Le stepper Obs → SD → SI → Auto sur la ligne théorique reflète la situation pédagogique la plus avancée jamais utilisée lors d'une séance théorique à ce niveau. Il n'y a pas de seuil en nombre de séances : une seule séance en Supervision indirecte suffit à allumer la bulle correspondante.
            </p>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Comme en pratique, le formateur peut <strong>cliquer sur une bulle pour forcer manuellement</strong> la situation pédagogique d'un niveau. Cela stocke une surcharge (override) indépendante du calcul automatique.
            </p>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 6 · Automations
        ───────────────────────────────────────────────────────────────── --}}
        <section id="automations" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Automations
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1.25rem">
                La plateforme calcule et met à jour plusieurs statuts automatiquement à chaque chargement de la fiche stagiaire. Voici le détail de chaque mécanique.
            </p>

            {{-- Pratique auto --}}
            <h3 style="font-size:1rem;font-weight:700;color:#6d28d9;margin:0 0 .75rem">Progression pratique — avancement automatique</h3>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:.5rem;margin-bottom:1.25rem;text-align:center">
                @foreach([
                    ['Observation','#10b981','Toujours notée A — 2 séances suffisent'],
                    ['Supervision directe','#3b82f6','2 séances notées A en SD'],
                    ['Supervision indirecte','#f59e0b','2 séances notées A en SI'],
                    ['Autonomie','#8b5cf6','2 séances notées A en Autonomie'],
                ] as [$label,$color,$rule])
                <div style="border:1px solid #e2e8f0;border-radius:.5rem;padding:.75rem .5rem;background:#fff">
                    <div style="width:12px;height:12px;border-radius:50%;background:{{ $color }};margin:0 auto .5rem"></div>
                    <p style="font-size:.78rem;font-weight:600;color:#1e293b;margin:0 0 .3rem">{{ $label }}</p>
                    <p style="font-size:.72rem;color:#64748b;margin:0">{{ $rule }}</p>
                </div>
                @endforeach
            </div>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                L'automatisation vérifie les niveaux en séquence (Obs → SD → SI → Auto) et s'arrête au premier palier où le stagiaire n'a pas encore 2 séances A. Le statut ne recule jamais automatiquement. Si le formateur a forcé manuellement un statut, l'automatisation n'écrase pas ce choix — elle continue de calculer le statut théorique en arrière-plan sans l'appliquer.
            </p>

            <div style="background:#fef9c3;border:1px solid #fde68a;border-radius:.5rem;padding:.85rem 1rem;margin-bottom:1.5rem;font-size:.82rem;color:#78350f;line-height:1.7">
                <strong>Important :</strong> les séances en <em>Observation</em> sont automatiquement notées A à la sauvegarde. Deux séances d'observation suffisent donc pour franchir le premier palier et faire passer le statut à <em>Supervision directe</em>.
            </div>

            {{-- Pratique rating --}}
            <h3 style="font-size:1rem;font-weight:700;color:#6d28d9;margin:0 0 .75rem">Calcul de la note globale d'une séance</h3>

            <div style="overflow-x:auto;margin-bottom:1.5rem">
                <table style="width:100%;border-collapse:collapse;font-size:.82rem">
                    <thead>
                        <tr style="background:#f8fafc">
                            <th style="text-align:left;padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">Situation</th>
                            <th style="text-align:left;padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">Sécurité (pratique)</th>
                            <th style="text-align:left;padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">Seuil NT</th>
                            <th style="text-align:left;padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">Seuil ECA</th>
                            <th style="text-align:left;padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">Seuil A</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">Observation</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">—</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">—</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">—</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#10b981">Toujours A</td>
                        </tr>
                        <tr style="background:#f8fafc">
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">Supervision directe</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">NT/ECA bloque la note</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">moy &lt; 1,5</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">moy &lt; 2,5</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">moy ≥ 2,5</td>
                        </tr>
                        <tr>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">Supervision indirecte</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">NT/ECA bloque la note</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">moy &lt; 1,5</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">moy &lt; 2,8</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">moy ≥ 2,8</td>
                        </tr>
                        <tr style="background:#f8fafc">
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">Autonomie</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">NT/ECA bloque la note</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">moy &lt; 1,5</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">moy &lt; 3,0</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">moy = 3,0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p style="font-size:.8rem;line-height:1.6;color:#64748b;margin-bottom:1.5rem">
                La moyenne est calculée sur 6 compétences : Objectifs, Justification pédagogique, Stratégie, Animation, Mise en œuvre, Évaluation. Pour les séances pratiques, la compétence Sécurité est évaluée en priorité : si elle est NT → la note globale est NT ; si elle est ECA → la note globale est ECA, quelle que soit la moyenne des autres compétences.
            </p>

            {{-- Théorique auto --}}
            <h3 style="font-size:1rem;font-weight:700;color:#6d28d9;margin:0 0 .75rem">Progression théorique — NT / En cours / Validé</h3>

            <div style="overflow-x:auto;margin-bottom:1.25rem">
                <table style="width:100%;border-collapse:collapse;font-size:.82rem">
                    <thead>
                        <tr style="background:#f8fafc">
                            <th style="text-align:left;padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">Niveau</th>
                            <th style="text-align:left;padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">Sujets DEJEPS</th>
                            <th style="text-align:left;padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">NT → En cours</th>
                            <th style="text-align:left;padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">En cours → Validé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            ['N1','Pression, Prévention','1 séance ECA ou A','2 / 2 sujets à A (100 %)'],
                            ['N2','Désaturation, Flottabilité, Ordinateur, Accidents','1 séance ECA ou A','3 / 4 sujets à A (75 %)'],
                            ['N3','Essoufflement, Organisation, Narcose','1 séance ECA ou A','2 / 3 sujets à A (67 %)'],
                            ['N4','Froid, Oreille, Détendeur, Surpression, Physique','1 séance ECA ou A','3 / 5 sujets à A (60 %)'],
                        ] as $i => [$lvl,$sujets,$enCours,$valide])
                        <tr style="{{ $i % 2 === 1 ? 'background:#f8fafc' : '' }}">
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;font-weight:600;color:#1e293b">{{ $lvl }}</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">{{ $sujets }}</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">{{ $enCours }}</td>
                            <td style="padding:.5rem .75rem;border:1px solid #e2e8f0;color:#475569">{{ $valide }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p style="font-size:.8rem;line-height:1.6;color:#64748b;margin-bottom:1.5rem">
                Les séances "autre" (sujets libres) comptent pour passer de NT à En cours, mais pas pour le seuil de validation. Seuls les sujets DEJEPS officiels du niveau entrent dans le calcul du seuil A.
            </p>

            {{-- Override --}}
            <h3 style="font-size:1rem;font-weight:700;color:#6d28d9;margin:0 0 .75rem">Override manuel (pratique et théorique)</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Dans les deux calendriers de progression (pratique et théorique), <strong>chaque bulle du stepper est cliquable</strong>. Un clic ouvre une fenêtre de confirmation indiquant le nouveau statut qui sera appliqué. Après confirmation :
            </p>
            <ul style="font-size:.875rem;line-height:1.8;color:#475569;margin:0 0 1rem;padding-left:1.25rem">
                <li>En <strong>pratique</strong> : le statut est sauvegardé avec le flag <code>is_manual = true</code>. L'automatisation continue de tourner mais n'écrase plus ce choix — elle indique uniquement si elle aurait avancé davantage.</li>
                <li>En <strong>théorique</strong> : la situation pédagogique (Obs/SD/SI/Auto) est sauvegardée dans un champ d'override distinct. Le statut NT/En cours/Validé reste, lui, toujours calculé automatiquement et n'est pas affecté par l'override de situation.</li>
            </ul>
            <p style="font-size:.875rem;line-height:1.7;color:#475569">
                Il n'y a pas de bouton "réinitialiser" : pour revenir au calcul automatique en pratique, il suffit de cliquer sur la bulle correspondant au statut calculé, ce qui synchronise le statut manuel avec la réalité et laisse l'automatisation reprendre la main.
            </p>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 7 · UC1 / UC2
        ───────────────────────────────────────────────────────────────── --}}
        <section id="uc12" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                UC1 / UC2
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                Le suivi des UC1 et UC2 permet de consigner les résultats des évaluations certificatives, de joindre les liens vers les dossiers des stagiaires et de gérer les documents partagés (ressources pédagogiques, sujets, exemples de mémoires…). Les dates limites de dépôt et de jury sont configurées dans les paramètres du programme et apparaissent dans le calendrier.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/04-uc12-tab.png{{ $imgV('04-uc12-tab.png') }}" alt="Onglet UC1/UC2 stagiaire" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Fiche stagiaire — onglet UC1/UC2</p>
            </div>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/11-uc12-global.png{{ $imgV('11-uc12-global.png') }}" alt="Page UC1/UC2 globale" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Vue globale UC1/UC2 — tableau de bord de tous les stagiaires</p>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 8 · EPMSP
        ───────────────────────────────────────────────────────────────── --}}
        <section id="epmsp" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                EPMSP
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                L'onglet EPMSP (Épreuve Pratique de Mise en Situation Professionnelle) permet de saisir les notes et appréciations pour les deux épreuves du diplôme : l'épreuve sur 25 m et l'épreuve de pédagogie. Le statut global (Non débuté / En cours / Prêt / Évalué) est défini manuellement par le formateur.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/05-epmsp-tab.png{{ $imgV('05-epmsp-tab.png') }}" alt="Onglet EPMSP" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Fiche stagiaire — onglet EPMSP</p>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 9 · Parcours & jalons
        ───────────────────────────────────────────────────────────────── --}}
        <section id="parcours" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Parcours &amp; jalons
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                L'onglet Parcours présente la chronologie de la formation avec les jalons clés liés au dossier UC1 (plan, brouillon, version finale, retour formateur…). Le formateur coche les étapes au fur et à mesure et peut envoyer un retour écrit directement au stagiaire depuis cet onglet — ce retour génère une notification côté stagiaire.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/08-parcours.png{{ $imgV('08-parcours.png') }}" alt="Onglet Parcours" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Fiche stagiaire — onglet Parcours &amp; jalons</p>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 10 · Positionnement
        ───────────────────────────────────────────────────────────────── --}}
        <section id="positionnement" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Positionnement
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                La page de positionnement permet d'évaluer les acquis du stagiaire à l'entrée en formation sur l'ensemble des compétences du référentiel. Le stagiaire s'auto-évalue lors de l'onboarding ; le formateur complète une contre-évaluation. Les deux scores sont comparés dans un rapport de positionnement.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/12-positioning.png{{ $imgV('12-positioning.png') }}" alt="Page de positionnement" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Positionnement initial — contre-évaluation formateur</p>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 11 · Calendrier
        ───────────────────────────────────────────────────────────────── --}}
        <section id="calendrier" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Calendrier
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                Le calendrier offre une vue d'ensemble des séances planifiées et des échéances importantes issues d'Asana (jalons de progression, dates de jury, EPMSP…). Il peut être synchronisé manuellement avec Asana depuis le bouton de synchronisation.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/13-calendrier.png{{ $imgV('13-calendrier.png') }}" alt="Page Calendrier" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Calendrier — vue des séances et échéances</p>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 12 · Vue stagiaire
        ───────────────────────────────────────────────────────────────── --}}
        <section id="vue-stagiaire" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Vue stagiaire
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                La vue stagiaire présente l'interface telle qu'elle est perçue par le stagiaire. Le stagiaire s'identifie par son nom depuis la page de sélection, sans authentification par mot de passe. Il accède à son tableau de bord, peut saisir ses propres séances, consulter les retours du formateur et suivre sa progression pédagogique.
            </p>

            {{-- ── Onboarding ── --}}
            <h3 style="font-size:1rem;font-weight:600;color:#1e293b;margin-bottom:.75rem;margin-top:1.5rem">Processus d'onboarding</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                Lors de leur première connexion, les stagiaires complètent un parcours d'onboarding en <strong>3 étapes</strong>. Ces informations alimentent le profil visible par le formateur et servent de base au positionnement initial.
            </p>

            <h4 style="font-size:.875rem;font-weight:600;color:#334155;margin-bottom:.5rem;margin-top:1.25rem">Étape 1 · Profil &amp; expériences</h4>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Le stagiaire renseigne ses <strong>informations personnelles</strong> (nom, date de naissance, e-mail, téléphone, photo, CV), ses <strong>expériences antérieures</strong> (plongée, enseignement, travail en structure) et répond à <strong>3 questions brise-glace</strong> — motivation, points forts, difficultés anticipées. Un champ de commentaires libres complète l'étape.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:.75rem">
                <img src="/aide-screenshots/20-onboarding-step1.png{{ $imgV('20-onboarding-step1.png') }}" alt="Onboarding étape 1 — informations personnelles" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Étape 1 — informations personnelles et photo</p>
            </div>
            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:.75rem">
                <img src="/aide-screenshots/20b-onboarding-step1-experiences.png{{ $imgV('20b-onboarding-step1-experiences.png') }}" alt="Onboarding étape 1 — expériences antérieures" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Étape 1 — expériences antérieures en plongée et niveau actuel</p>
            </div>
            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/20c-onboarding-step1-icebreaking.png{{ $imgV('20c-onboarding-step1-icebreaking.png') }}" alt="Onboarding étape 1 — questions brise-glace" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Étape 1 — questions brise-glace et commentaires libres</p>
            </div>

            <h4 style="font-size:.875rem;font-weight:600;color:#334155;margin-bottom:.5rem;margin-top:1.25rem">Étape 2 · Test de personnalité Big Five</h4>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Le stagiaire répond à <strong>120 affirmations</strong> sur une échelle de 1 à 5 (Pas du tout d'accord → Tout à fait d'accord). Le questionnaire mesure les cinq dimensions OCEAN : Ouverture, Consciencieux, Extraversion, Agréabilité, Névrosisme. Environ 10 minutes.
            </p>
            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:.75rem">
                <img src="/aide-screenshots/21-onboarding-step2.png{{ $imgV('21-onboarding-step2.png') }}" alt="Onboarding étape 2 — test Big Five" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Étape 2 — introduction et début du questionnaire Big Five (120 questions)</p>
            </div>
            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/21b-onboarding-step2-questions.png{{ $imgV('21b-onboarding-step2-questions.png') }}" alt="Onboarding étape 2 — questions" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Étape 2 — questions individuelles avec échelle de Likert</p>
            </div>

            <h4 style="font-size:.875rem;font-weight:600;color:#334155;margin-bottom:.5rem;margin-top:1.25rem">Étape 3 · Auto-évaluation des compétences</h4>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Le stagiaire s'auto-évalue sur <strong>l'ensemble des compétences du référentiel DEJEPS</strong> regroupées en 10 catégories (accueil du public, gestion d'équipe, outils informatiques, matériel de plongée, navire, palanquée, formation, randonnée, direction de plongée, tutorat). Certaines catégories sont <strong>masquées automatiquement</strong> selon les réponses de l'étape 1 — par exemple, la catégorie « Conduite de palanquée » est masquée si le stagiaire n'a jamais guidé. Pour chaque compétence, trois niveaux : <em>1 — Aucune notion</em>, <em>2 — Avec aide</em>, <em>3 — Autonome</em>. Un champ optionnel permet d'illustrer avec un exemple concret.
            </p>
            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:.75rem">
                <img src="/aide-screenshots/22-onboarding-step3.png{{ $imgV('22-onboarding-step3.png') }}" alt="Onboarding étape 3 — auto-évaluation" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Étape 3 — légende de notation et début de l'auto-évaluation des compétences</p>
            </div>
            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:.75rem">
                <img src="/aide-screenshots/22b-onboarding-step3-competences.png{{ $imgV('22b-onboarding-step3-competences.png') }}" alt="Onboarding étape 3 — compétences détaillées" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Étape 3 — cartes de compétences avec notation 1/2/3 et champ d'exemple</p>
            </div>

            <h4 style="font-size:.875rem;font-weight:600;color:#334155;margin-bottom:.5rem;margin-top:1.25rem">Confirmation</h4>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Une fois les 3 étapes complétées, le stagiaire accède à son tableau de bord. Le formateur reçoit une notification et peut consulter le profil complet depuis la fiche stagiaire.
            </p>
            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1.5rem">
                <img src="/aide-screenshots/23-onboarding-confirmation.png{{ $imgV('23-onboarding-confirmation.png') }}" alt="Onboarding — confirmation" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Confirmation — onboarding terminé, accès au tableau de bord stagiaire</p>
            </div>

            {{-- ── Tableau de bord & navigation stagiaire ── --}}
            <h3 style="font-size:1rem;font-weight:600;color:#1e293b;margin-bottom:.75rem;margin-top:1.5rem">Tableau de bord &amp; navigation</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:.75rem">
                Après l'onboarding, le stagiaire accède à son espace depuis la page de sélection en choisissant son nom. Son tableau de bord affiche les onglets <strong>UC1/UC2</strong> et <strong>EPMSP</strong>, ainsi que des boutons d'accès rapide en haut à droite : <strong>Parcours</strong> (chronologie lecture seule), <strong>Pédagogie</strong> (progression lecture seule) et <strong>Mon profil</strong>.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/15-trainee-select.png{{ $imgV('15-trainee-select.png') }}" alt="Sélection du stagiaire" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Interface stagiaire — sélection de l'identité</p>
            </div>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/16-trainee-dashboard.png{{ $imgV('16-trainee-dashboard.png') }}" alt="Tableau de bord stagiaire" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Tableau de bord stagiaire — avec les boutons Parcours, Pédagogie et Mon profil</p>
            </div>

            <h3 style="font-size:1rem;font-weight:600;color:#1e293b;margin-bottom:.75rem;margin-top:1.5rem">Vue Pédagogie (lecture seule)</h3>
            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                Le stagiaire peut consulter sa progression pédagogique en lecture seule via le bouton <strong>Pédagogie</strong> présent dans l'en-tête du tableau de bord. Cette vue affiche le calendrier de progression avec le stepper Obs → SD → SI → Auto, ainsi que le détail de toutes les séances pratiques et théoriques évaluées par le formateur. <strong>Aucune modification n'est possible depuis cette vue</strong> — seul le formateur peut modifier les statuts et les évaluations.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/17-trainee-peda-pratique.png{{ $imgV('17-trainee-peda-pratique.png') }}" alt="Vue pédagogie pratique — stagiaire" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Vue Pédagogie pratique — lecture seule (onglet stagiaire)</p>
            </div>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/18-trainee-peda-theorique.png{{ $imgV('18-trainee-peda-theorique.png') }}" alt="Vue pédagogie théorique — stagiaire" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Vue Pédagogie théorique — couverture des sujets DEJEPS et statuts (lecture seule)</p>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SECTION 13 · Notifications
        ───────────────────────────────────────────────────────────────── --}}
        <section id="notifications" style="margin-bottom:3rem;scroll-margin-top:1.5rem">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1e293b;border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;margin-bottom:1.25rem">
                Notifications
            </h2>

            <p style="font-size:.875rem;line-height:1.7;color:#475569;margin-bottom:1rem">
                Les notifications informent le formateur des événements importants : demande de retour d'un stagiaire sur son dossier UC1, séances enregistrées. Elles sont accessibles depuis l'icône cloche dans la barre de navigation. Un badge rouge indique le nombre de notifications non lues. Cliquer sur une notification la marque comme lue et redirige vers la page concernée.
            </p>

            <div style="border-radius:.6rem;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:1rem">
                <img src="/aide-screenshots/14-notifications.png{{ $imgV('14-notifications.png') }}" alt="Page Notifications" style="width:100%;display:block">
                <p style="font-size:.75rem;color:#94a3b8;padding:.5rem .75rem;margin:0;background:#f8fafc">Centre de notifications</p>
            </div>
        </section>

    </main>
</div>

<script>
function setActive(el) {
    document.querySelectorAll('#aide-nav a').forEach(a => {
        a.style.background = 'transparent';
        a.style.color = '#475569';
        a.style.fontWeight = '400';
    });
    el.style.background = '#ede9fe';
    el.style.color = '#6d28d9';
    el.style.fontWeight = '600';
}

const sections = document.querySelectorAll('section[id]');
const navLinks  = document.querySelectorAll('#aide-nav a');

const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            navLinks.forEach(a => {
                const active = a.getAttribute('href') === '#' + entry.target.id;
                a.style.background  = active ? '#ede9fe' : 'transparent';
                a.style.color       = active ? '#6d28d9' : '#475569';
                a.style.fontWeight  = active ? '600' : '400';
            });
        }
    });
}, { rootMargin: '-20% 0px -70% 0px' });

sections.forEach(s => observer.observe(s));
</script>
@endsection
