<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\ProgramSettings;
use App\Models\Trainee;
use App\Models\Uc12Document;
use App\Models\TraineeProfile;
use App\Models\TraineeUc3;
use App\Models\TraineeUcProgress;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TraineeController extends Controller
{
    public function select(Request $request)
    {
        if ($traineeId = session('trainee_id')) {
            $trainee = Trainee::find($traineeId);
            if ($trainee) {
                return $trainee->hasCompletedOnboarding()
                    ? redirect()->route('trainee.dashboard')
                    : redirect()->route('onboarding.step1');
            }
            $request->session()->forget('trainee_id');
        }

        $trainees = Trainee::orderBy('name')->get();

        return view('trainee.select', compact('trainees'));
    }

    public function identify(Request $request)
    {
        $data = $request->validate([
            'action'     => ['required', 'in:existing,new'],
            'trainee_id' => ['required_if:action,existing', 'nullable', 'exists:trainees,id'],
        ]);

        if ($data['action'] === 'existing') {
            $request->session()->put('trainee_id', $data['trainee_id']);
            $trainee = Trainee::findOrFail($data['trainee_id']);

            return $trainee->hasCompletedOnboarding()
                ? redirect()->route('trainee.dashboard')
                : redirect()->route('onboarding.step1');
        }

        $request->session()->forget('trainee_id');

        return redirect()->route('onboarding.step1');
    }

    public function dashboard()
    {
        $trainee = $this->requireTrainee();
        $trainee->load(['ucProgress', 'epmsp', 'uc3']);

        $settings    = ProgramSettings::instance();
        $uc          = $trainee->ucProgress->firstWhere('uc', 'uc1');
        $uc2         = $trainee->ucProgress->firstWhere('uc', 'uc2');
        $epmspData   = $trainee->epmsp;
        $uc12Docs    = Uc12Document::orderBy('created_at', 'desc')->get();

        $submissionDate    = $settings->uc1_submission_deadline
            ? Carbon::parse($settings->uc1_submission_deadline)->format('Y-m-d')
            : null;
        $trackedMilestones = TraineeUcProgress::milestoneDefinitions($submissionDate);
        $milestoneProgress = $uc?->milestone_progress ?? [];

        $pendingReviewSent = AppNotification::forInstructor()
            ->where('trainee_id', $trainee->id)
            ->where('type', 'review_request')
            ->whereNull('read_at')
            ->exists();

        $feedbacks = AppNotification::forTrainee($trainee->id)
            ->where('type', 'project_feedback')
            ->latest()
            ->get();

        return view('trainee.dashboard', compact(
            'trainee', 'settings', 'uc', 'uc2', 'epmspData', 'uc12Docs',
            'trackedMilestones', 'milestoneProgress', 'pendingReviewSent', 'feedbacks'
        ));
    }

    public function requestReview(Request $request)
    {
        $trainee = $this->requireTrainee();

        AppNotification::notifyInstructorReviewRequest($trainee->id, [
            'trainee_name' => $trainee->name,
        ]);

        return back()->with('success', 'Votre demande de retour a été envoyée au formateur.');
    }

    public function editProfile()
    {
        $trainee = $this->requireTrainee();

        return view('trainee.profile-edit', compact('trainee'));
    }

    public function updateProfile(Request $request)
    {
        $trainee = $this->requireTrainee();

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:200'],
            'email'              => ['required', 'email', 'max:200'],
            'phone'              => ['required', 'string', 'max:50'],
            'date_of_birth'      => ['required', 'date', 'before:today'],
            'photo'              => ['nullable', 'image', 'max:4096'],
            'cv'                 => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'ice_motivation'     => ['required', 'string', 'max:1000'],
            'ice_strengths'      => ['required', 'string', 'max:1000'],
            'ice_challenges'     => ['required', 'string', 'max:1000'],
            'prior_diving_level' => ['required', 'string', 'max:200'],
            'prior_teaching'     => ['required', 'string', 'max:1000'],
            'prior_other'        => ['nullable', 'string', 'max:1000'],
            'trainee_comments'   => ['nullable', 'string', 'max:2000'],
        ]);

        $traineeData = [
            'name'          => $data['name'],
            'email'         => $data['email'],
            'phone'         => $data['phone'],
            'date_of_birth' => $data['date_of_birth'],
        ];

        if ($request->hasFile('photo')) {
            $traineeData['photo_path'] = $request->file('photo')->store('photos', 'public');
        }
        if ($request->hasFile('cv')) {
            $traineeData['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        $trainee->update($traineeData);

        $profile = $trainee->profile ?? new TraineeProfile(['trainee_id' => $trainee->id]);
        $profile->fill([
            'ice_breaking' => [
                'motivation' => $data['ice_motivation'],
                'strengths'  => $data['ice_strengths'],
                'challenges' => $data['ice_challenges'],
            ],
            'prior_experiences' => [
                'diving_level' => $data['prior_diving_level'],
                'teaching'     => $data['prior_teaching'],
                'other'        => $data['prior_other'] ?? '',
            ],
            'trainee_comments' => $data['trainee_comments'] ?? null,
        ])->save();

        return redirect()->route('trainee.profile.edit')
            ->with('success', 'Profil mis à jour.');
    }

    public function saveDossierUrl(Request $request)
    {
        $trainee = $this->requireTrainee();

        $data = $request->validate([
            'dossier_url' => ['nullable', 'url', 'max:500'],
        ]);

        TraineeUcProgress::updateOrCreate(
            ['trainee_id' => $trainee->id, 'uc' => 'uc1'],
            ['dossier_url' => $data['dossier_url'] ?? null]
        );

        return back()->with('success', 'Lien du dossier mis à jour.');
    }

    public function addSeance()
    {
        $trainee = $this->requireTrainee();
        $dejepsTopics = TraineeUc3::topics();
        $compPoints   = TraineeUc3::competencyPoints();

        return view('trainee.session-add', compact('trainee', 'dejepsTopics', 'compPoints'));
    }

    public function editSeance(string $slug)
    {
        $trainee = $this->requireTrainee();
        $trainee->load('uc3');

        $traineeProgress = $trainee->uc3?->trainee_topic_progress ?? [];
        abort_if(!isset($traineeProgress[$slug]), 404);

        $session    = $traineeProgress[$slug];
        $topics     = TraineeUc3::topics();
        $compPoints = TraineeUc3::competencyPoints();
        $topicInfo  = collect($topics)->firstWhere('slug', $slug);

        $sessionLabel = $topicInfo ? $topicInfo['label'] : ($session['session_label'] ?? 'Thème libre');
        $sessionLevel = $topicInfo ? $topicInfo['level'] : ($session['session_level'] ?? null);

        $isPratique    = str_starts_with($slug, 'pratique_');
        $pratiqueLevel = null;
        $pratiqueComp  = null;
        if ($isPratique) {
            preg_match('/^pratique_([a-z0-9]+)_s\d+$/i', $slug, $m);
            $pratiqueLevel = strtoupper($m[1] ?? '');
            $pratiqueComp  = TraineeUc3::pratiqueCompetencies()[$pratiqueLevel] ?? null;
        }

        return view('trainee.session-edit', compact(
            'trainee', 'slug', 'session', 'sessionLabel', 'sessionLevel', 'compPoints',
            'isPratique', 'pratiqueLevel', 'pratiqueComp'
        ));
    }

    public function saveSeance(Request $request)
    {
        $trainee = $this->requireTrainee();
        $trainee->load('uc3');

        $data = $request->validate([
            'slug'           => ['required', 'string', 'max:150'],
            'session_date'   => ['nullable', 'date'],
            'session_label'  => ['nullable', 'string', 'max:200'],
            'session_level'  => ['nullable', 'string', 'max:10'],
            'global_rating'  => ['nullable', 'in:1,2,3'],
            'global_comment' => ['nullable', 'string', 'max:500'],
            'notations'      => ['nullable', 'array'],
            'notations.*'    => ['nullable', 'in:1,2,3'],
            'notes'          => ['nullable', 'array'],
            'notes.*'        => ['nullable', 'string', 'max:500'],
            'exercises'      => ['nullable', 'array'],
            'exercises.*'    => ['nullable', 'string', 'max:100'],
            'session_note'   => ['nullable', 'string', 'max:1000'],
        ]);

        $slug    = $data['slug'];
        $allKeys = TraineeUc3::allPointKeys();

        $globalRating = $data['global_rating'] ?? '1';
        $progress = [
            'session_date'   => $data['session_date'] ?? null,
            'session_label'  => $data['session_label'] ?? null,
            'session_level'  => $data['session_level'] ?? null,
            'global_rating'  => $globalRating !== '1' ? $globalRating : null,
            'global_comment' => trim($data['global_comment'] ?? '') ?: null,
        ];

        foreach ($allKeys as $key) {
            $notation        = $data['notations'][$key] ?? '1';
            $progress[$key]  = $notation !== '1' ? $notation : null;
            $note            = trim($data['notes'][$key] ?? '');
            $progress[$key . '_note'] = $note !== '' ? $note : null;
        }

        $exercisesDone = array_values(array_filter($data['exercises'] ?? [], fn($v) => !empty($v)));
        $progress['exercises_done'] = !empty($exercisesDone) ? $exercisesDone : null;

        $sessionNote = trim($data['session_note'] ?? '');
        $progress['session_note'] = $sessionNote !== '' ? $sessionNote : null;

        $uc3      = TraineeUc3::firstOrCreate(['trainee_id' => $trainee->id]);
        $existing = $uc3->trainee_topic_progress ?? [];
        $isNew    = !isset($existing[$slug]);
        $existing[$slug] = $progress;
        $uc3->update(['trainee_topic_progress' => $existing]);

        if ($isNew) {
            $topics    = TraineeUc3::topics();
            $topicInfo = collect($topics)->firstWhere('slug', $slug);
            AppNotification::notifyInstructor($trainee->id, $slug, [
                'trainee_name'  => $trainee->name,
                'session_label' => $topicInfo ? $topicInfo['label'] : ($progress['session_label'] ?? $slug),
                'session_level' => $topicInfo ? $topicInfo['level'] : ($progress['session_level'] ?? null),
            ]);
        }

        return redirect()->route('trainee.dashboard', ['#seances'])->with('success', 'Séance enregistrée.');
    }

    public function deleteSeance(string $slug)
    {
        $trainee = $this->requireTrainee();
        $trainee->load('uc3');

        $uc3 = $trainee->uc3;
        if ($uc3) {
            $progress = $uc3->trainee_topic_progress ?? [];
            unset($progress[$slug]);
            $uc3->update(['trainee_topic_progress' => $progress]);
        }

        return redirect()->route('trainee.dashboard')->with('success', 'Séance supprimée.');
    }

    private function requireTrainee(): Trainee
    {
        if (!session('trainee_id')) {
            abort(redirect()->route('trainee.select'));
        }

        return Trainee::with('profile')->findOrFail(session('trainee_id'));
    }
}
