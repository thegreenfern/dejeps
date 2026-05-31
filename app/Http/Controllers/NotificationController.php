<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $role      = session('role');
        $traineeId = session('trainee_id');

        if ($role === 'instructor') {
            $notifications = AppNotification::forInstructor()
                ->with('trainee')
                ->latest()
                ->get();

            AppNotification::forInstructor()->whereNull('read_at')->update(['read_at' => now()]);
        } elseif ($role === 'trainee' && $traineeId) {
            $notifications = AppNotification::forTrainee($traineeId)
                ->with('trainee')
                ->latest()
                ->get();

            AppNotification::forTrainee($traineeId)->whereNull('read_at')->update(['read_at' => now()]);
        } else {
            return redirect()->route('home');
        }

        return view('notifications.index', compact('notifications', 'role'));
    }

    public function markReadAndRedirect(AppNotification $notification): RedirectResponse
    {
        $role      = session('role');
        $traineeId = session('trainee_id');

        // Verify the notification belongs to the current session user
        if ($role === 'instructor' && $notification->recipient_type !== 'instructor') {
            abort(403);
        }
        if ($role === 'trainee' && (
            $notification->recipient_type !== 'trainee' ||
            (int) $notification->recipient_id !== (int) $traineeId
        )) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        if ($notification->type === 'seance_added') {
            return redirect(route('instructor.trainee.show', $notification->trainee_id) . '#seances');
        }

        if ($notification->type === 'review_request') {
            return redirect(route('instructor.trainee.show', $notification->trainee_id) . '#uc12');
        }

        if ($notification->type === 'project_feedback') {
            return redirect(route('trainee.dashboard') . '#uc12');
        }

        return redirect(route('trainee.dashboard') . '#seances');
    }
}
