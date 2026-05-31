<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function selectRole(Request $request)
    {
        $role = $request->input('role');

        if (! in_array($role, ['instructor', 'trainee'])) {
            return redirect()->route('home');
        }

        session(['role' => $role]);

        if ($role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        }

        // Always show the selection page; clear any previous identity
        $request->session()->forget('trainee_id');
        return redirect()->route('trainee.select');
    }
}
