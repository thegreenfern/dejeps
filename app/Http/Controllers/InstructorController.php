<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\CalendarEvent;
use App\Models\Competency;
use App\Models\CompetencesAnnexes;
use App\Models\DirectionPlongeeEvaluation;
use App\Models\InitialAssessment;
use App\Models\ProgramSettings;
use App\Models\Trainee;
use App\Models\TraineeEpmsp;
use App\Models\TraineePedaStatus;
use App\Models\TraineePedaTheoStatus;
use App\Models\TraineeUc3;
use App\Models\TraineeUcProgress;
use App\Models\Uc12Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class InstructorController extends Controller
{
    public function dashboard()
    {
        $trainees = Trainee::with('profile')
            ->orderBy('name')
            ->get();

        return view('instructor.dashboard', compact('trainees'));
    }

    public function show(Trainee $trainee)
    {
        $trainee->load(['profile', 'ucProgress', 'epmsp', 'uc3', 'directionPlongee']);

        $assessments = InitialAssessment::with('competency')
            ->where('trainee_id', $trainee->id)
            ->get()
            ->groupBy('competency.category');

        $settings = ProgramSettings::instance();

        $submissionDate = $settings->uc1_submission_deadline
            ? Carbon::parse($settings->uc1_submission_deadline)->format('Y-m-d')
            : null;

        $trackedMilestones = TraineeUcProgress::milestoneDefinitions($submissionDate);

        $uc1             = $trainee->ucProgress->firstWhere('uc', 'uc1');
        $milestoneProgress = $uc1?->milestone_progress ?? [];

        $pendingReviewRequest = AppNotification::forInstructor()
            ->where('trainee_id', $trainee->id)
            ->where('type', 'review_request')
            ->whereNull('read_at')
            ->latest()
            ->first();

        $pedaData     = $this->buildPedaData($trainee);
        $pedaTheoData = $this->buildTheoData($trainee);
        $timeline     = $this->buildTimeline($trainee, $settings);

        $feedbacks = AppNotification::where('trainee_id', $trainee->id)
            ->where('type', 'project_feedback')
            ->latest()
            ->get();

        $dpEvaluations   = $trainee->directionPlongee->sortByDesc('evaluated_at');
        $competencesRec  = CompetencesAnnexes::firstOrCreate(['trainee_id' => $trainee->id]);

        return view('instructor.trainee-show', compact(
            'trainee', 'assessments', 'settings',
            'trackedMilestones', 'milestoneProgress', 'pendingReviewRequest',
            'pedaData', 'pedaTheoData', 'feedbacks', 'timeline',
            'dpEvaluations', 'competencesRec'
        ));
    }

    public function uc12()
    {
        $settings = ProgramSettings::instance();

        $trainees = Trainee::with(['profile', 'ucProgress'])
            ->whereHas('profile', fn($q) => $q->whereNotNull('completed_at'))
            ->orderBy('name')
            ->get()
            ->map(function ($trainee) {
                $trainee->uc1 = $trainee->ucProgress->firstWhere('uc', 'uc1');
                $trainee->uc2 = $trainee->ucProgress->firstWhere('uc', 'uc2');
                return $trainee;
            });

        $documents = Uc12Document::orderBy('created_at', 'desc')->get();

        return view('instructor.uc12', compact('settings', 'trainees', 'documents'));
    }

    public function saveUc12Settings(Request $request)
    {
        $data = $request->validate([
            'uc1_submission_deadline' => ['nullable', 'date'],
            'uc1_jury_date'           => ['nullable', 'date'],
            'uc2_submission_deadline' => ['nullable', 'date'],
            'uc2_jury_date'           => ['nullable', 'date'],
            'epmsp_date'              => ['nullable', 'date'],
            'dc_name'                 => ['nullable', 'string', 'max:200'],
            'dc_address'              => ['nullable', 'string', 'max:300'],
            'dc_type'                 => ['nullable', 'string', 'max:100'],
            'dc_director'             => ['nullable', 'string', 'max:200'],
            'dc_email'                => ['nullable', 'email', 'max:200'],
            'dc_phone'                => ['nullable', 'string', 'max:50'],
            'dc_description'          => ['nullable', 'string', 'max:2000'],
            'dc_notes'                => ['nullable', 'string', 'max:2000'],
        ]);

        ProgramSettings::instance()->update($data);

        return back()->with('success', 'Informations mises à jour.');
    }

    public function addSession(Trainee $trainee)
    {
        return view('instructor.session-add', compact('trainee'));
    }

    public function editSession(Trainee $trainee, string $slug)
    {
        $uc3           = $trainee->uc3;
        $topicProgress = $uc3?->topic_progress ?? [];
        abort_if(!isset($topicProgress[$slug]), 404);

        $session   = $topicProgress[$slug];
        $topicInfo = collect(TraineeUc3::topics())->firstWhere('slug', $slug);

        $sessionLabel = $topicInfo ? $topicInfo['label'] : ($session['session_label'] ?? 'Thème libre');
        $sessionLevel = $topicInfo ? $topicInfo['level'] : ($session['session_level'] ?? null);
        $sessionNotes = $uc3?->session_notes;

        $isPratique     = str_starts_with($slug, 'pratique_');
        $pratiqueLevel  = null;
        $pratiqueComp   = null;
        if ($isPratique) {
            preg_match('/^pratique_([a-z0-9]+)_s\d+$/i', $slug, $m);
            $pratiqueLevel = strtoupper($m[1] ?? '');
            $pratiqueComp  = TraineeUc3::pratiqueCompetencies()[$pratiqueLevel] ?? null;
        }

        return view('instructor.session-edit', compact(
            'trainee', 'slug', 'session', 'sessionLabel', 'sessionLevel', 'sessionNotes',
            'isPratique', 'pratiqueLevel', 'pratiqueComp'
        ));
    }

    public function deleteSession(Trainee $trainee, string $slug)
    {
        $uc3 = $trainee->uc3;
        if ($uc3) {
            $progress = $uc3->topic_progress ?? [];
            unset($progress[$slug]);
            $uc3->update(['topic_progress' => $progress]);
        }

        return redirect()->route('instructor.trainee.show', $trainee)->with('success', 'Séance supprimée.');
    }

    public function saveUc3Seance(Request $request, Trainee $trainee)
    {
        $data = $request->validate([
            'slug'                 => ['required', 'string', 'max:150'],
            'session_date'         => ['nullable', 'date'],
            'session_label'        => ['nullable', 'string', 'max:200'],
            'session_level'        => ['nullable', 'string', 'max:10'],
            'situation'            => ['nullable', 'in:observation,supervision_directe,supervision_indirecte,autonomie'],
            'notations'            => ['nullable', 'array'],
            'notations.*'          => ['nullable', 'in:1,2,3'],
            'notes'                => ['nullable', 'array'],
            'notes.*'              => ['nullable', 'string', 'max:500'],
            'exercises'            => ['nullable', 'array'],
            'exercises.*'          => ['nullable', 'string', 'max:100'],
            'session_note'         => ['nullable', 'string', 'max:1000'],
            'session_notes_global' => ['nullable', 'string', 'max:2000'],
        ]);

        $slug       = $data['slug'];
        $allKeys    = TraineeUc3::allPointKeys();
        $situation  = $data['situation'] ?? 'observation';
        $notations  = $data['notations'] ?? [];
        $isPratique = str_starts_with($slug, 'pratique_');

        $progress = [
            'session_date'   => $data['session_date'] ?? null,
            'session_label'  => $data['session_label'] ?? null,
            'session_level'  => $data['session_level'] ?? null,
            'situation'      => $situation,
            'global_rating'  => $this->computeGlobalRating($situation, $notations, $isPratique),
            'global_comment' => null,
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
        $existing = $uc3->topic_progress ?? [];
        $existing[$slug] = $progress;

        $updates = ['topic_progress' => $existing];
        $globalNote = trim($data['session_notes_global'] ?? '');
        if ($globalNote !== '') {
            $updates['session_notes'] = $globalNote;
        }

        $uc3->update($updates);

        $topics    = TraineeUc3::topics();
        $topicInfo = collect($topics)->firstWhere('slug', $slug);
        AppNotification::notifyTrainee($trainee->id, $slug, [
            'session_label' => $topicInfo ? $topicInfo['label'] : ($progress['session_label'] ?? $slug),
            'session_level' => $topicInfo ? $topicInfo['level'] : ($progress['session_level'] ?? null),
        ]);

        return redirect()->route('instructor.trainee.show', $trainee)->with('success', 'Séance enregistrée.');
    }

    public function saveUc3(Request $request, Trainee $trainee)
    {
        $data = $request->validate([
            'ratings'   => ['nullable', 'array'],
            'ratings.*' => ['nullable', 'in:TI,I,S,M'],
        ]);

        $data['ratings'] = array_filter($data['ratings'] ?? [], fn($v) => $v !== null && $v !== '');
        if (empty($data['ratings'])) {
            $data['ratings'] = null;
        }

        TraineeUc3::updateOrCreate(
            ['trainee_id' => $trainee->id],
            $data
        );

        return back()->with('success', 'Évaluation UC3 mise à jour.');
    }

    public function positioning(Trainee $trainee)
    {
        $trainee->load('profile');

        $competencies = Competency::positioning()
            ->get()
            ->groupBy('category');

        $assessments = InitialAssessment::where('trainee_id', $trainee->id)
            ->get()
            ->keyBy('competency_id');

        return view('instructor.positioning', compact('trainee', 'competencies', 'assessments'));
    }

    public function saveInitialAutoeval(Request $request, Trainee $trainee)
    {
        $data = $request->validate([
            'scores'    => ['required', 'array'],
            'scores.*'  => ['required', 'in:1,2,3'],
            'evidence'  => ['nullable', 'array'],
            'evidence.*' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($data['scores'] as $competencyId => $score) {
            InitialAssessment::updateOrCreate(
                ['trainee_id' => $trainee->id, 'competency_id' => $competencyId],
                [
                    'trainee_score'    => $score,
                    'trainee_evidence' => trim($data['evidence'][$competencyId] ?? '') ?: null,
                ]
            );
        }

        return redirect(route('instructor.trainee.show', $trainee) . '#profil')
            ->with('success', 'Auto-évaluation mise à jour.');
    }

    public function savePositioning(Request $request, Trainee $trainee)
    {
        $data = $request->validate([
            'scores'   => ['required', 'array'],
            'scores.*' => ['required', 'in:1,2,3'],
            'notes'    => ['nullable', 'array'],
            'notes.*'  => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($data['scores'] as $competencyId => $score) {
            InitialAssessment::updateOrCreate(
                ['trainee_id' => $trainee->id, 'competency_id' => $competencyId],
                [
                    'tutor_score' => $score,
                    'tutor_notes' => trim($data['notes'][$competencyId] ?? '') ?: null,
                ]
            );
        }

        return redirect()->route('instructor.positioning-report', $trainee)
            ->with('success', 'Contre-évaluation enregistrée.');
    }

    public function positioningReport(Trainee $trainee)
    {
        $assessments = InitialAssessment::with('competency')
            ->where('trainee_id', $trainee->id)
            ->whereHas('competency', fn($q) => $q->where('framework', 'positioning'))
            ->get()
            ->groupBy('competency.category');

        return view('instructor.positioning-report', compact('trainee', 'assessments'));
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:20480'], // 20 MB
        ]);

        $file = $request->file('file');
        $path = $file->store('uc12-docs');

        Uc12Document::create([
            'original_name' => $file->getClientOriginalName(),
            'stored_path'   => $path,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
        ]);

        return back()->with('success', 'Document ajouté.');
    }

    public function deleteDocument(Uc12Document $document)
    {
        Storage::delete($document->stored_path);
        $document->delete();

        return back()->with('success', 'Document supprimé.');
    }

    public function saveTraineeUc(Request $request, Trainee $trainee, string $uc)
    {
        abort_unless(in_array($uc, ['uc1', 'uc2']), 404);

        $data = $request->validate([
            'status'           => ['required', 'in:not_started,in_progress,submitted,evaluated'],
            'instructor_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        TraineeUcProgress::updateOrCreate(
            ['trainee_id' => $trainee->id, 'uc' => $uc],
            $data
        );

        return back()->with('success', 'Suivi mis à jour.');
    }

    public function saveProjectMilestones(Request $request, Trainee $trainee)
    {
        $data = $request->validate([
            'milestone_statuses'   => ['nullable', 'array'],
            'milestone_statuses.*' => ['nullable', 'in:not_done,in_progress,done'],
            'feedback_text'        => ['nullable', 'string', 'max:2000'],
            'action'               => ['required', 'in:save,notify'],
        ]);

        $uc1 = TraineeUcProgress::firstOrCreate(
            ['trainee_id' => $trainee->id, 'uc' => 'uc1']
        );

        $existing = $uc1->milestone_progress ?? [];
        foreach ($data['milestone_statuses'] ?? [] as $slug => $status) {
            if ($status !== null) {
                $existing[$slug] = $status;
            }
        }
        $uc1->update(['milestone_progress' => $existing]);

        if ($data['action'] === 'notify' && trim($data['feedback_text'] ?? '') !== '') {
            // Mark any pending review request as read
            AppNotification::forInstructor()
                ->where('trainee_id', $trainee->id)
                ->where('type', 'review_request')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            AppNotification::notifyTraineeFeedback($trainee->id, [
                'feedback_text' => trim($data['feedback_text']),
                'trainee_name'  => $trainee->name,
            ]);
        }

        $message = $data['action'] === 'notify'
            ? 'Suivi mis à jour et retour envoyé au stagiaire.'
            : 'Suivi des étapes mis à jour.';

        return redirect()->route('instructor.trainee.show', $trainee)
            ->with('success', $message);
    }

    public function updateFeedback(Request $request, AppNotification $notification)
    {
        abort_if($notification->type !== 'project_feedback', 403);

        $data = $request->validate([
            'feedback_text' => ['required', 'string', 'max:2000'],
        ]);

        $notification->update([
            'data' => array_merge($notification->data ?? [], [
                'feedback_text' => trim($data['feedback_text']),
            ]),
        ]);

        return response()->json(['ok' => true]);
    }

    // ── Progression config ────────────────────────────────────────────────

    public function progressionConfig()
    {
        $settings  = ProgramSettings::instance();
        $trainees  = Trainee::with('profile')
            ->whereHas('profile', fn($q) => $q->whereNotNull('completed_at'))
            ->orderBy('name')
            ->get();

        return view('instructor.progression-config', compact('settings', 'trainees'));
    }

    public function saveProgressionConfig(Request $request)
    {
        $data = $request->validate([
            'threshold_obs_sd'  => ['required', 'integer', 'min:1', 'max:10'],
            'threshold_sd_si'   => ['required', 'integer', 'min:1', 'max:10'],
            'threshold_si_auto' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        ProgramSettings::instance()->update($data);

        return redirect()->route('instructor.progression-config')
            ->with('success', 'Paramètres globaux enregistrés.');
    }

    public function saveTraineeThresholds(Request $request, Trainee $trainee)
    {
        $data = $request->validate([
            'peda_threshold_obs_sd'  => ['nullable', 'integer', 'min:1', 'max:10'],
            'peda_threshold_sd_si'   => ['nullable', 'integer', 'min:1', 'max:10'],
            'peda_threshold_si_auto' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $profile = $trainee->profile ?? $trainee->profile()->create(['trainee_id' => $trainee->id]);
        $profile->update($data);

        return redirect()->route('instructor.progression-config')
            ->with('success', "Seuils de {$trainee->name} mis à jour.");
    }

    // ── Peda ──────────────────────────────────────────────────────────────

    public function savePedaStatus(Request $request, Trainee $trainee)
    {
        $data = $request->validate([
            'level'  => ['required', 'in:bapteme,n1,n2,n3'],
            'status' => ['required', 'in:nt,observation,supervision_directe,supervision_indirecte,autonomie'],
        ]);

        TraineePedaStatus::updateOrCreate(
            ['trainee_id' => $trainee->id, 'level' => $data['level']],
            ['status' => $data['status'], 'is_manual' => true]
        );

        return back()->with('peda_success', 'Statut mis à jour.');
    }

    private function buildPedaData(Trainee $trainee): array
    {
        $uc3           = $trainee->uc3;
        $topicProgress = $uc3?->topic_progress ?? [];
        $overrides     = $uc3?->peda_timeline_overrides ?? [];
        $today         = Carbon::today();

        $asanaBaseline = $this->buildAsanaBaseline();

        $settings   = ProgramSettings::instance();
        $thresholds = TraineePedaStatus::resolveThresholds($trainee->profile, $settings);

        $existingStatuses = TraineePedaStatus::where('trainee_id', $trainee->id)
            ->get()->keyBy('level');

        $pedaData = [];

        foreach (TraineePedaStatus::LEVELS as $level) {
            $record    = $existingStatuses->get($level);
            $persisted = $record?->status ?? 'nt';
            $isManual  = $record?->is_manual ?? false;

            $aCounts    = TraineePedaStatus::countAGradesBySituation($topicProgress, $level);
            $autoStatus = TraineePedaStatus::computeAutoStatus($aCounts, $thresholds);

            $pendingAuto = null;

            if (TraineePedaStatus::statusIndex($autoStatus) > TraineePedaStatus::statusIndex($persisted)) {
                if ($isManual) {
                    $pendingAuto = $autoStatus;
                } else {
                    TraineePedaStatus::updateOrCreate(
                        ['trainee_id' => $trainee->id, 'level' => $level],
                        ['status' => $autoStatus, 'is_manual' => false]
                    );
                    $persisted = $autoStatus;
                }
            }

            // Count séances by situation × rating
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

            // Build per-level timeline milestones
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
                'label'       => TraineePedaStatus::LEVEL_LABELS[$level],
                'status'      => $persisted,
                'auto_status' => $autoStatus,
                'is_manual'   => $isManual,
                'pending_auto' => $pendingAuto,
                'a_counts'    => $aCounts,
                'counts'      => $counts,
                'timeline'    => $levelTimeline,
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

    public function saveTheoSitOverride(Request $request, Trainee $trainee)
    {
        $level = $request->input('level');
        $sit   = $request->input('situation');

        $validSits = ['observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'];

        if (!in_array($level, TraineePedaTheoStatus::LEVELS) || !in_array($sit, $validSits)) {
            abort(422);
        }

        $uc3 = TraineeUc3::firstOrCreate(['trainee_id' => $trainee->id]);
        $overrides = $uc3->theo_sit_overrides ?? [];
        $overrides[$level] = $sit;
        $uc3->update(['theo_sit_overrides' => $overrides]);

        return back();
    }

    private function buildTheoData(Trainee $trainee): array
    {
        $uc3           = $trainee->uc3;
        $topicProgress = $uc3?->topic_progress ?? [];
        $overrides     = $uc3?->peda_theo_timeline_overrides ?? [];
        $today         = Carbon::today();

        // Asana baseline: one Formation event per level
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

        // Global coverage: how many distinct DEJEPS topic slugs have been rated at least once
        $coveredSlugs = array_filter($allDejepsSlugs, function ($slug) use ($topicProgress) {
            $entry  = $topicProgress[$slug] ?? null;
            $rating = match($entry['global_rating'] ?? null) { 'A' => '3', 'ECA' => '2', 'NT' => null, default => $entry['global_rating'] ?? null };
            return $rating === '3' || $rating === '2';
        });
        $coverage = [
            'covered' => count($coveredSlugs),
            'total'   => count($allDejepsSlugs),
        ];

        // Per-level data
        $theoSits     = ['observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'];
        $sitOverrides = $uc3?->theo_sit_overrides ?? [];
        $levels = [];
        foreach (TraineePedaTheoStatus::LEVELS as $level) {
            $computedStatus = TraineePedaTheoStatus::computeStatus($topicProgress, $level);

            // Persist computed status (always auto — no manual override)
            TraineePedaTheoStatus::updateOrCreate(
                ['trainee_id' => $trainee->id, 'level' => $level],
                ['status' => $computedStatus]
            );

            // Highest situation achieved across all sessions for this level
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

            // Timeline milestone for this level
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
                    'due'      => $due->format('Y-m-d'),
                    'days_left' => $daysLeft,
                    'achieved' => $achieved,
                    'at_risk'  => $atRisk,
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

    private function computeGlobalRating(string $situation, array $notations, bool $isPratique): string
    {
        if ($situation === 'observation') return '3';

        $standardKeys = ['c_objectifs', 'c_justification', 'c_strategie', 'r_animation', 'r_mise_en_oeuvre', 'e_evaluation'];

        if ($isPratique) {
            $securite = (int) ($notations['r_securite'] ?? 1);
            if ($securite === 1) return '1';
            if ($securite === 2) return '2';
            // securite === 3: fall through to average
        }

        $values = array_map(fn($k) => (int) ($notations[$k] ?? 1), $standardKeys);
        $avg    = array_sum($values) / count($values);

        return match ($situation) {
            'supervision_directe'   => $avg < 1.5 ? '1' : ($avg < 2.5 ? '2' : '3'),
            'supervision_indirecte' => $avg < 1.5 ? '1' : ($avg < 2.8 ? '2' : '3'),
            'autonomie'             => $avg < 1.5 ? '1' : ($avg < 3.0 ? '2' : '3'),
            default                 => '1',
        };
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
            $rawGrade = $entry['global_rating'] ?? null;
            $rating   = match($rawGrade) {
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

    public function aide()
    {
        return view('instructor.aide');
    }
}
