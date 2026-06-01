<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\CalendarEvent;
use App\Models\ProgramSettings;
use App\Models\Trainee;
use App\Models\Uc12Document;
use App\Models\TraineeProfile;
use App\Models\TraineePedaStatus;
use App\Models\TraineePedaTheoStatus;
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

    public function peda()
    {
        $trainee = $this->requireTrainee();
        $trainee->load('uc3');

        $pedaData     = $this->buildPedaData($trainee);
        $pedaTheoData = $this->buildTheoData($trainee);

        return view('trainee.peda', compact('trainee', 'pedaData', 'pedaTheoData'));
    }

    private function buildPedaData(Trainee $trainee): array
    {
        $uc3           = $trainee->uc3;
        $topicProgress = $uc3?->topic_progress ?? [];
        $overrides     = $uc3?->peda_timeline_overrides ?? [];
        $today         = Carbon::today();

        $asanaBaseline    = $this->buildAsanaBaseline();
        $settings         = ProgramSettings::instance();
        $thresholds       = TraineePedaStatus::resolveThresholds($trainee->profile, $settings);
        $existingStatuses = TraineePedaStatus::where('trainee_id', $trainee->id)
            ->get()->keyBy('level');

        $pedaData = [];

        foreach (TraineePedaStatus::LEVELS as $level) {
            $record    = $existingStatuses->get($level);
            $persisted = $record?->status ?? 'nt';

            $aCounts    = TraineePedaStatus::countAGradesBySituation($topicProgress, $level);
            $autoStatus = TraineePedaStatus::computeAutoStatus($aCounts, $thresholds);

            if (TraineePedaStatus::statusIndex($autoStatus) > TraineePedaStatus::statusIndex($persisted) && !($record?->is_manual ?? false)) {
                $persisted = $autoStatus;
            }

            $prefixes = TraineePedaStatus::LEVEL_SLUG_PREFIXES[$level];
            $counts   = array_fill_keys(
                ['observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'],
                ['total' => 0, '3' => 0, '2' => 0]
            );
            foreach ($topicProgress as $slug => $data) {
                $matches = false;
                foreach ($prefixes as $prefix) {
                    if (str_starts_with($slug, $prefix)) { $matches = true; break; }
                }
                if (!$matches) continue;
                $sit = $data['situation'] ?? null;
                $rat = match($data['global_rating'] ?? null) { 'A' => '3', 'ECA' => '2', 'NT' => null, default => $data['global_rating'] ?? null };
                if ($sit && isset($counts[$sit])) {
                    $counts[$sit]['total']++;
                    if ($rat === '3') $counts[$sit]['3']++;
                    if ($rat === '2') $counts[$sit]['2']++;
                }
            }

            $levelTimeline = [];
            $statusIdx     = TraineePedaStatus::statusIndex($persisted);
            foreach (['observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'] as $sit) {
                $base = $asanaBaseline[$level][$sit] ?? null;
                if (!$base) { $levelTimeline[$sit] = null; continue; }

                $dueStr   = $overrides[$level][$sit . '_due'] ?? $base['due'];
                $due      = $dueStr ? Carbon::parse($dueStr) : null;
                $achieved = TraineePedaStatus::statusIndex($sit) <= $statusIdx;
                $daysLeft = $due ? $today->diffInDays($due, false) : null;
                $atRisk   = !$achieved && $daysLeft !== null && $daysLeft <= 3;

                $levelTimeline[$sit] = [
                    'due'       => $due?->format('Y-m-d'),
                    'days_left' => $daysLeft,
                    'achieved'  => $achieved,
                    'at_risk'   => $atRisk,
                ];
            }

            $pedaData[$level] = [
                'label'    => TraineePedaStatus::LEVEL_LABELS[$level],
                'status'   => $persisted,
                'counts'   => $counts,
                'timeline' => $levelTimeline,
            ];
        }

        $examEvent = CalendarEvent::where('section', 'UC3 / UC4')
            ->where('event_type', 'Examen')
            ->where('name', 'like', '%édagogie pratique%')
            ->first();

        return [
            'levels'    => $pedaData,
            'exam_date' => $examEvent?->due_on?->format('Y-m-d') ?? '2026-10-02',
        ];
    }

    private function buildAsanaBaseline(): array
    {
        $nameMap = [
            'Baptême - Observation'               => ['bapteme', 'observation'],
            'Baptême - Supervision directe'       => ['bapteme', 'supervision_directe'],
            'Baptême - Supervision indirecte'     => ['bapteme', 'supervision_indirecte'],
            'Baptême - Autonomie'                 => ['bapteme', 'autonomie'],
            'Pratique N1 - Observation'           => ['n1', 'observation'],
            'Pratique N1 - Supervision directe'   => ['n1', 'supervision_directe'],
            'Pratique N1 - Supervision indirecte' => ['n1', 'supervision_indirecte'],
            'Pratique N1 - Autonomie'             => ['n1', 'autonomie'],
            'Pratique N2 - Observation'           => ['n2', 'observation'],
            'Pratique N2 - Supervision directe'   => ['n2', 'supervision_directe'],
            'Pratique N2 - Supervision indirecte' => ['n2', 'supervision_indirecte'],
            'Pratique N3 - Observation'           => ['n3', 'observation'],
            'Pratique N3 - Supervision directe'   => ['n3', 'supervision_directe'],
        ];

        $baseline = ['bapteme' => [], 'n1' => [], 'n2' => [], 'n3' => []];

        CalendarEvent::where('section', 'UC3 / UC4')
            ->whereIn('event_type', ['Observation', 'Supervision directe', 'Supervision indirecte', 'Autonomie'])
            ->get()
            ->each(function ($event) use (&$baseline, $nameMap) {
                if (isset($nameMap[$event->name])) {
                    [$level, $sit] = $nameMap[$event->name];
                    $baseline[$level][$sit] = [
                        'start' => $event->start_on?->format('Y-m-d'),
                        'due'   => $event->due_on?->format('Y-m-d'),
                    ];
                }
            });

        return $baseline;
    }

    private function buildTheoData(Trainee $trainee): array
    {
        $uc3           = $trainee->uc3;
        $topicProgress = $uc3?->topic_progress ?? [];
        $overrides     = $uc3?->peda_theo_timeline_overrides ?? [];
        $today         = Carbon::today();

        $asanaNameMap = [
            'Théorie N1: Simulation, Observation, Supervision directe' => 'n1',
            'Théorie N2: Simulation, Observation, Supervision directe' => 'n2',
            'Théorie N3: Simulation'                                   => 'n3',
            'Théorie N4'                                               => 'n4',
        ];

        $baseline = [];
        CalendarEvent::where('section', 'UC3 / UC4')
            ->where('event_type', 'Formation')
            ->whereIn('name', array_keys($asanaNameMap))
            ->get()
            ->each(function ($event) use (&$baseline, $asanaNameMap) {
                $level = $asanaNameMap[$event->name];
                $baseline[$level] = [
                    'start' => $event->start_on?->format('Y-m-d'),
                    'due'   => $event->due_on?->format('Y-m-d'),
                ];
            });

        $allDejepsSlugs = array_merge(...array_values(TraineePedaTheoStatus::LEVEL_TOPICS));

        $coveredSlugs = array_filter($allDejepsSlugs, function ($slug) use ($topicProgress) {
            $entry  = $topicProgress[$slug] ?? null;
            $rating = match($entry['global_rating'] ?? null) { 'A' => '3', 'ECA' => '2', 'NT' => null, default => $entry['global_rating'] ?? null };
            return $rating === '3' || $rating === '2';
        });
        $coverage = [
            'covered' => count($coveredSlugs),
            'total'   => count($allDejepsSlugs),
        ];

        $theoSits     = ['observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'];
        $sitOverrides = $uc3?->theo_sit_overrides ?? [];
        $levels       = [];

        foreach (TraineePedaTheoStatus::LEVELS as $level) {
            $computedStatus = TraineePedaTheoStatus::computeStatus($topicProgress, $level);

            $sitCounts = array_fill_keys($theoSits, 0);
            foreach (TraineePedaTheoStatus::LEVEL_TOPICS[$level] ?? [] as $slug) {
                $sit = $topicProgress[$slug]['situation'] ?? null;
                if ($sit && isset($sitCounts[$sit])) $sitCounts[$sit]++;
            }
            foreach ($topicProgress as $slug => $entry) {
                if (!str_starts_with($slug, 'autre__')) continue;
                if (strtolower($entry['session_level'] ?? '') !== strtolower($level)) continue;
                $sit = $entry['situation'] ?? null;
                if ($sit && isset($sitCounts[$sit])) $sitCounts[$sit]++;
            }
            $sitStatus = 'nt';
            foreach (array_reverse($theoSits) as $sit) {
                if ($sitCounts[$sit] > 0) { $sitStatus = $sit; break; }
            }

            $base     = $baseline[$level] ?? null;
            $dueStr   = $overrides[$level . '_due'] ?? $base['due'] ?? null;
            $due      = $dueStr ? Carbon::parse($dueStr) : null;
            $achieved = $computedStatus === 'valide';
            $daysLeft = $due ? $today->diffInDays($due, false) : null;
            $atRisk   = !$achieved && $daysLeft !== null && $daysLeft <= 3;

            $levels[$level] = [
                'label'      => TraineePedaTheoStatus::LEVEL_LABELS[$level],
                'status'     => $computedStatus,
                'sit_status' => $sitOverrides[$level] ?? $sitStatus,
                'topics'     => TraineePedaTheoStatus::topicDetails($topicProgress, $level),
                'autre'      => TraineePedaTheoStatus::autreSeances($topicProgress, $level),
                'timeline'   => $due ? [
                    'due'       => $due->format('Y-m-d'),
                    'days_left' => $daysLeft,
                    'achieved'  => $achieved,
                    'at_risk'   => $atRisk,
                ] : null,
            ];
        }

        $examEvent = CalendarEvent::where('section', 'UC3 / UC4')
            ->where('event_type', 'Examen')
            ->where('name', 'like', '%édag%théor%')
            ->first();

        return [
            'levels'    => $levels,
            'coverage'  => $coverage,
            'exam_date' => $examEvent?->due_on?->format('Y-m-d') ?? '2026-09-18',
        ];
    }

    public function parcours()
    {
        $trainee  = $this->requireTrainee();
        $settings = ProgramSettings::instance();
        $timeline = $this->buildTimeline($trainee, $settings);
        return view('trainee.parcours', compact('trainee', 'timeline'));
    }

    private function buildTimeline(Trainee $trainee, ProgramSettings $settings): array
    {
        $events = [];
        $today  = Carbon::today();

        $deadlines = [
            ['key' => 'uc1_submission_deadline', 'label' => 'Dépôt dossier UC1', 'icon' => 'document'],
            ['key' => 'uc1_jury_date',           'label' => 'Jury UC1',           'icon' => 'star'],
            ['key' => 'uc2_submission_deadline', 'label' => 'Dépôt dossier UC2', 'icon' => 'document'],
            ['key' => 'uc2_jury_date',           'label' => 'Jury UC2',           'icon' => 'star'],
            ['key' => 'epmsp_date',              'label' => 'EPMSP',              'icon' => 'flag'],
        ];

        foreach ($deadlines as $dl) {
            $date = $settings->{$dl['key']};
            if (!$date) continue;
            $carbon = Carbon::instance($date);
            $events[] = [
                'type'  => 'deadline',
                'date'  => $carbon->format('Y-m-d'),
                'label' => $dl['label'],
                'icon'  => $dl['icon'],
                'past'  => $carbon->lt($today),
                'days'  => (int) $today->diffInDays($carbon, false),
            ];
        }

        $topicProgress = $trainee->uc3?->topic_progress ?? [];
        $topicsById    = collect(TraineeUc3::topics())->keyBy('slug');

        foreach ($topicProgress as $slug => $entry) {
            if (empty($entry['session_date'])) continue;
            $topicInfo = $topicsById->get($slug);
            $label     = $topicInfo ? $topicInfo['label'] : ($entry['session_label'] ?? $slug);
            $level     = $topicInfo ? strtoupper($topicInfo['level']) : strtoupper($entry['session_level'] ?? '');
            $rawGrade  = $entry['global_rating'] ?? null;
            $rating    = match($rawGrade) {
                '3', 'A'   => 'A',
                '2', 'ECA' => 'ECA',
                '1', 'NT'  => 'NT',
                default    => null,
            };
            $events[] = [
                'type'           => 'session',
                'date'           => $entry['session_date'],
                'label'          => $label,
                'level'          => $level ?: null,
                'situation'      => $entry['situation'] ?? null,
                'rating'         => $rating,
                'global_comment' => $entry['global_comment'] ?? null,
                'past'           => true,
                'slug'           => $slug,
                'is_autre'       => str_starts_with($slug, 'autre__'),
            ];
        }

        usort($events, fn($a, $b) => strcmp($a['date'], $b['date']));

        $ongoing = CalendarEvent::where('start_on', '<=', $today)
            ->where('due_on', '>=', $today)
            ->orderBy('due_on')
            ->get(['name', 'event_type', 'due_on'])
            ->map(fn($e) => [
                'name'      => $e->name,
                'type'      => $e->event_type,
                'due'       => $e->due_on->format('Y-m-d'),
                'days_left' => (int) $today->diffInDays($e->due_on, false),
            ])
            ->values()
            ->all();

        return ['events' => $events, 'ongoing' => $ongoing];
    }

    private function requireTrainee(): Trainee
    {
        if (!session('trainee_id')) {
            abort(redirect()->route('trainee.select'));
        }

        return Trainee::with('profile')->findOrFail(session('trainee_id'));
    }
}
