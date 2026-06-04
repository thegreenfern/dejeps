<?php

namespace App\Http\Controllers;

use App\Models\CompetencesAnnexes;
use App\Models\Trainee;
use Illuminate\Http\Request;

class CompetencesAnnexesController extends Controller
{
    public function save(Request $request, Trainee $trainee)
    {
        $keys  = array_keys(CompetencesAnnexes::competencies());
        $rules = ['notes_formateur' => ['nullable', 'string', 'max:2000']];
        foreach ($keys as $key) {
            $rules[$key] = ['nullable', 'integer', 'min:1', 'max:3'];
        }

        $data = $request->validate($rules);

        CompetencesAnnexes::updateOrCreate(
            ['trainee_id' => $trainee->id],
            $data
        );

        return redirect(route('instructor.trainee.show', $trainee) . '#annexes')
            ->with('success', 'Compétences annexes enregistrées.');
    }
}
