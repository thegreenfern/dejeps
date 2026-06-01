<?php

namespace App\Http\Controllers;

use App\Data\BigFiveQuestions;
use App\Models\Competency;
use App\Models\InitialAssessment;
use App\Models\Trainee;
use App\Models\TraineeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    // ── Step 1: Profil & Ice-Breaking ─────────────────────────────────────

    public function step1(Request $request)
    {
        $traineeId = session('trainee_id');
        $trainee   = $traineeId ? Trainee::find($traineeId) : null;

        return view('onboarding.step1', compact('trainee'));
    }

    public function saveStep1(Request $request)
    {
        $data = $request->validate([
            'name'                => ['required', 'string', 'max:200'],
            'email'               => ['required', 'email', 'max:200'],
            'phone'               => ['required', 'string', 'max:50'],
            'date_of_birth'       => ['required', 'date', 'before:today'],
            'photo'               => ['nullable', 'image', 'max:4096'],
            'cv'                  => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'ice_motivation'      => ['required', 'string', 'max:1000'],
            'ice_strengths'       => ['required', 'string', 'max:1000'],
            'ice_challenges'      => ['required', 'string', 'max:1000'],
            'prior_has_other_jobs'  => ['nullable', 'boolean'],
            'prior_has_diving_work' => ['nullable', 'boolean'],
            'prior_has_guided'      => ['nullable', 'boolean'],
            'prior_has_taught'      => ['nullable', 'boolean'],
            'prior_diving_level'    => ['required', 'string', 'max:200'],
            'prior_teaching'        => ['required', 'string', 'max:1000'],
            'prior_other'           => ['nullable', 'string', 'max:1000'],
            'trainee_comments'      => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $traineeId = session('trainee_id');
            $trainee   = $traineeId ? Trainee::find($traineeId) : null;

            // Handle file uploads
            $photoPath = null;
            $cvPath    = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            }
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
            }

            $traineeData = [
                'name'          => $data['name'],
                'email'         => $data['email'],
                'phone'         => $data['phone'],
                'date_of_birth' => $data['date_of_birth'],
            ];
            if ($photoPath) $traineeData['photo_path'] = $photoPath;
            if ($cvPath)    $traineeData['cv_path']    = $cvPath;

            if (!$trainee) {
                $trainee = Trainee::create($traineeData);
                session(['trainee_id' => $trainee->id]);
            } else {
                $trainee->update($traineeData);
            }

            $profile = $trainee->profile ?? new TraineeProfile(['trainee_id' => $trainee->id]);

            $profile->fill([
                'onboarding_step'    => 2,
                'trainee_comments'   => $data['trainee_comments'] ?? null,
                'ice_breaking'       => [
                    'motivation' => $data['ice_motivation'],
                    'strengths'  => $data['ice_strengths'],
                    'challenges' => $data['ice_challenges'],
                ],
                'prior_experiences'  => [
                    'has_other_jobs'  => isset($data['prior_has_other_jobs'])  ? (bool)$data['prior_has_other_jobs']  : null,
                    'has_diving_work' => isset($data['prior_has_diving_work']) ? (bool)$data['prior_has_diving_work'] : null,
                    'has_guided'      => isset($data['prior_has_guided'])      ? (bool)$data['prior_has_guided']      : null,
                    'has_taught'      => isset($data['prior_has_taught'])       ? (bool)$data['prior_has_taught']      : null,
                    'diving_level'    => $data['prior_diving_level'],
                    'teaching'        => $data['prior_teaching'],
                    'other'           => $data['prior_other'] ?? '',
                ],
            ])->save();
        });

        return redirect()->route('onboarding.step2');
    }

    // ── Step 2: Big Five ──────────────────────────────────────────────────

    public function step2()
    {
        $this->requireTrainee();
        $questions = BigFiveQuestions::all();

        return view('onboarding.step2', compact('questions'));
    }

    public function saveStep2(Request $request)
    {
        $this->requireTrainee();

        $request->validate([
            'responses'     => ['required', 'array', 'size:120'],
            'responses.*'   => ['required', 'integer', 'between:1,5'],
        ]);

        $responses = array_map('intval', $request->input('responses'));
        $scores    = BigFiveQuestions::computeScores($responses);

        $trainee = Trainee::findOrFail(session('trainee_id'));
        $profile = $trainee->profile;

        $profile->update([
            'onboarding_step'   => 3,
            'big5_answers'      => $responses,
            'big5_scores'       => $scores,
            'big5_completed_at' => now(),
        ]);

        return redirect()->route('onboarding.step3');
    }

    // ── Step 3: Auto-évaluation des compétences ───────────────────────────

    public function step3()
    {
        $this->requireTrainee();

        $trainee     = Trainee::findOrFail(session('trainee_id'));
        $priorExp    = $trainee->profile?->prior_experiences ?? [];

        $hiddenCategories = $this->hiddenCategories($priorExp);

        $competencies = Competency::positioning()->get()->groupBy('category');

        return view('onboarding.step3', compact('competencies', 'hiddenCategories'));
    }

    public function saveStep3(Request $request)
    {
        $this->requireTrainee();

        $trainee     = Trainee::findOrFail(session('trainee_id'));
        $priorExp    = $trainee->profile?->prior_experiences ?? [];
        $hiddenCategories = $this->hiddenCategories($priorExp);

        $hiddenIds = Competency::positioning()
            ->whereIn('category', $hiddenCategories)
            ->pluck('id')
            ->toArray();

        // Pre-fill hidden competencies with score=1 so validation passes
        $scores = $request->input('scores', []);
        foreach ($hiddenIds as $id) {
            $scores[$id] = 1;
        }
        $request->merge(['scores' => $scores]);

        $competencyIds = Competency::positioning()->pluck('id')->toArray();

        $rules = [];
        foreach ($competencyIds as $id) {
            $rules["scores.{$id}"]   = ['required', 'integer', 'between:1,3'];
            $rules["evidence.{$id}"] = ['nullable', 'string', 'max:500'];
        }
        $request->validate($rules);

        $traineeId = session('trainee_id');

        DB::transaction(function () use ($request, $traineeId, $competencyIds) {
            foreach ($competencyIds as $id) {
                InitialAssessment::updateOrCreate(
                    ['trainee_id' => $traineeId, 'competency_id' => $id],
                    [
                        'trainee_score'    => $request->input("scores.{$id}"),
                        'trainee_evidence' => $request->input("evidence.{$id}"),
                    ]
                );
            }

            $trainee = Trainee::findOrFail($traineeId);
            $trainee->profile->update([
                'onboarding_step' => 4,
                'completed_at'    => now(),
            ]);
        });

        return redirect()->route('onboarding.confirmation');
    }

    private function hiddenCategories(array $priorExp): array
    {
        $hidden = [];

        if (($priorExp['has_other_jobs'] ?? null) === false) {
            array_push($hidden, 'Accueil du public', 'Gestion d\'équipe');
        }
        if (($priorExp['has_diving_work'] ?? null) === false) {
            $hidden[] = 'Utilisation du matériel de plongée';
        }
        if (($priorExp['has_guided'] ?? null) === false) {
            $hidden[] = 'Conduite de palanquée (zone 0–40m)';
        }
        if (($priorExp['has_taught'] ?? null) === false) {
            array_push($hidden, 'La formation de plongeur', 'La direction de plongée');
        }
        if (($priorExp['diving_level'] ?? null) !== 'Instructeur-trainer RSTC') {
            $hidden[] = 'Le tutorat';
        }

        return $hidden;
    }

    // ── Step 4: Confirmation ──────────────────────────────────────────────

    public function confirmation()
    {
        $this->requireTrainee();
        $trainee = Trainee::with('profile')->findOrFail(session('trainee_id'));

        // Load assessments grouped by competency category
        $assessments = InitialAssessment::with('competency')
            ->where('trainee_id', $trainee->id)
            ->get()
            ->groupBy('competency.category');

        return view('onboarding.confirmation', compact('trainee', 'assessments'));
    }

    // ─────────────────────────────────────────────────────────────────────

    private function requireTrainee(): void
    {
        if (!session('trainee_id')) {
            abort(redirect()->route('onboarding.step1'));
        }
    }
}
