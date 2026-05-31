<?php

namespace App\Data;

/**
 * 120 IPIP Big Five questions (French), 24 per trait.
 * Each item: [trait, direction (+1 or -1), text]
 * Scoring: response 1–5, score = direction * (response - 3) + 50 base.
 * Final trait score = average of 24 items, mapped to 0–100.
 */
class BigFiveQuestions
{
    public static function all(): array
    {
        return [
            // ── Ouverture (O) ──────────────────────────────────────────────
            ['trait' => 'O', 'dir' => +1, 'text' => 'J\'ai une imagination débordante.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'Je m\'intéresse à de nombreux sujets différents.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'J\'aime réfléchir à des idées abstraites.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'J\'apprécie la beauté de la nature et des arts.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'Je cherche à comprendre les choses en profondeur.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'J\'aime expérimenter de nouvelles façons de faire les choses.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'Je me passionne pour la philosophie et les grandes questions.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'Je suis fasciné(e) par les différentes cultures.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'J\'explore volontiers mes émotions et celles des autres.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'Les œuvres d\'art, la musique ou la littérature me touchent profondément.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'J\'aime les discussions intellectuelles stimulantes.'],
            ['trait' => 'O', 'dir' => +1, 'text' => 'Je remarque facilement la beauté dans les choses ordinaires.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je préfère les sujets concrets aux théories abstraites.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je n\'apprécie pas particulièrement la poésie ou la philosophie.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je me tiens à ce qui est familier plutôt qu\'à ce qui est nouveau.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je n\'aime pas les activités créatives ou artistiques.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je m\'intéresse peu à l\'art et à la culture.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je préfère les choses simples et directes.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je n\'aime guère changer mes habitudes.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je trouve les sujets théoriques peu intéressants.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je préfère les routines aux nouvelles expériences.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je ne m\'intéresse pas aux questions philosophiques.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'J\'évite les débats complexes sur des idées abstraites.'],
            ['trait' => 'O', 'dir' => -1, 'text' => 'Je préfère les méthodes éprouvées aux nouvelles approches.'],

            // ── Conscienciosité (C) ────────────────────────────────────────
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je fais toujours les choses avec soin et méthode.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je respecte mes engagements et tiens mes promesses.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je planifie mes activités à l\'avance.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je travaille dur pour atteindre mes objectifs.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je suis ordonné(e) et organisé(e) dans mon travail.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je veille à ce que les tâches soient bien réalisées.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je respecte les règles et les procédures.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je persévère même face aux difficultés.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'J\'aime avoir un plan clair avant d\'agir.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je suis ponctuel(le) et fiable.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je finis ce que j\'ai commencé.'],
            ['trait' => 'C', 'dir' => +1, 'text' => 'Je prends soin des détails importants.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je laisse souvent les choses en désordre.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je remets souvent les tâches au lendemain.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'J\'oublie souvent de ranger mes affaires à leur place.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je n\'aime pas suivre un planning rigide.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je me laisse facilement distraire de mes objectifs.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je prends souvent des décisions à la dernière minute.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je néglige parfois mes responsabilités.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je fais souvent des erreurs par inattention.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je travaille de façon désorganisée.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je manque parfois de persévérance.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je ne me fixe pas toujours des objectifs clairs.'],
            ['trait' => 'C', 'dir' => -1, 'text' => 'Je fais parfois les choses à moitié.'],

            // ── Extraversion (E) ───────────────────────────────────────────
            ['trait' => 'E', 'dir' => +1, 'text' => 'Je me sens à l\'aise dans les situations sociales.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'J\'aime rencontrer de nouvelles personnes.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'Je prends facilement la parole dans un groupe.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'Je suis souvent l\'âme des fêtes et des réunions.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'J\'aime être entouré(e) de beaucoup de personnes.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'Je parle facilement à des inconnus.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'Je me sens plein(e) d\'énergie quand je suis avec d\'autres.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'J\'aime les activités de groupe.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'J\'exprime facilement mes émotions positives.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'Je ris et plaisante souvent avec les autres.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'Je prends des initiatives dans les interactions sociales.'],
            ['trait' => 'E', 'dir' => +1, 'text' => 'Je me sens bien dans les environnements animés et bruyants.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je préfère passer du temps seul(e) plutôt qu\'avec des groupes.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je me sens épuisé(e) après de longs moments en société.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je n\'aime pas être le centre de l\'attention.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je parle peu dans les réunions.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je garde souvent mes pensées pour moi.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'J\'évite les grandes réunions sociales.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je préfère observer plutôt que participer.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je me sens mal à l\'aise dans les foules.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je suis plutôt discret(e) dans les groupes.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je n\'aime pas me retrouver au premier plan.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je préfère les conversations en tête-à-tête aux discussions de groupe.'],
            ['trait' => 'E', 'dir' => -1, 'text' => 'Je recharge mon énergie dans la solitude.'],

            // ── Agréabilité (A) ────────────────────────────────────────────
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je me soucie du bien-être des autres.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je fais confiance aux gens facilement.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je suis prêt(e) à aider les autres même si c\'est contraignant.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je suis généreux(se) envers les autres.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'J\'ai de l\'empathie pour les gens qui souffrent.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je cherche à éviter les conflits.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je pense que les gens ont généralement de bonnes intentions.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je suis patient(e) avec les autres.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je pardonne facilement les offenses.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'J\'essaie de voir les choses du point de vue des autres.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je traite tout le monde avec respect.'],
            ['trait' => 'A', 'dir' => +1, 'text' => 'Je suis attentif(ve) aux besoins des personnes qui m\'entourent.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je ne me gêne pas pour critiquer les autres.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je peux être dur(e) et intransigeant(e).'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je me méfie des intentions des autres.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je me bats pour ce qui m\'appartient, même au détriment des autres.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je dis ce que je pense sans me préoccuper des susceptibilités.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je préfère l\'efficacité à la gentillesse.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je suis parfois froid(e) et distant(e).'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je perds facilement patience avec les autres.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je pense que la plupart des gens ne méritent pas qu\'on leur fasse confiance.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je m\'intéresse peu aux problèmes des autres.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je tiens fermement à mes positions même si cela crée des tensions.'],
            ['trait' => 'A', 'dir' => -1, 'text' => 'Je manque parfois de tact.'],

            // ── Névrosisme (N) ─────────────────────────────────────────────
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je me sens souvent stressé(e) ou sous pression.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je me fais beaucoup de souci pour des choses qui pourraient mal tourner.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Mon humeur change fréquemment.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je me sens facilement dépassé(e) par les événements.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je ressens fréquemment de l\'anxiété ou de la nervosité.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je suis facilement irrité(e) ou agacé(e).'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je ressens souvent de la tristesse ou du découragement.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je me sens souvent coupable ou honteux(se) sans raison précise.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je suis sensible à la critique et aux remarques négatives.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'J\'ai tendance à me concentrer sur mes erreurs et mes échecs.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je cède facilement à mes impulsions dans les situations difficiles.'],
            ['trait' => 'N', 'dir' => +1, 'text' => 'Je panique facilement en situation de crise.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je reste calme dans les situations stressantes.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je me remets facilement des contrariétés.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je suis rarement de mauvaise humeur.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je ne m\'inquiète pas excessivement.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je garde mon calme même sous la pression.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je suis stable émotionnellement.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je ne me laisse pas facilement déstabiliser.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je gère bien mes émotions négatives.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je maintiens une humeur positive même dans l\'adversité.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je résiste bien à la frustration.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je me sens rarement submergé(e) par mes émotions.'],
            ['trait' => 'N', 'dir' => -1, 'text' => 'Je ne suis pas particulièrement anxieux(se) dans ma vie quotidienne.'],
        ];
    }

    /**
     * Compute Big Five scores (0–100 per trait) from a responses array.
     * $responses: array of 120 integers (1–5), indexed 0–119, matching all() order.
     */
    public static function computeScores(array $responses): array
    {
        $questions = self::all();
        $sums   = ['O' => 0, 'C' => 0, 'E' => 0, 'A' => 0, 'N' => 0];
        $counts = ['O' => 0, 'C' => 0, 'E' => 0, 'A' => 0, 'N' => 0];

        foreach ($questions as $i => $q) {
            $response = (int) ($responses[$i] ?? 3);
            $response = max(1, min(5, $response));
            $trait = $q['trait'];

            // Convert to 0–100 scale: positive item: 1→0, 3→50, 5→100
            // negative item: 1→100, 3→50, 5→0
            $score = $q['dir'] === +1
                ? ($response - 1) * 25
                : (5 - $response) * 25;

            $sums[$trait]   += $score;
            $counts[$trait] += 1;
        }

        $scores = [];
        foreach ($sums as $trait => $sum) {
            $scores[$trait] = $counts[$trait] > 0
                ? (int) round($sum / $counts[$trait])
                : 50;
        }

        return $scores;
    }
}
