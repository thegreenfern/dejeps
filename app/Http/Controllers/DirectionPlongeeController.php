<?php

namespace App\Http\Controllers;

use App\Models\DirectionPlongeeEvaluation;
use App\Models\Trainee;
use Illuminate\Http\Request;

class DirectionPlongeeController extends Controller
{
    public function create(Trainee $trainee)
    {
        return view('instructor.dp-form', ['trainee' => $trainee, 'dp' => null]);
    }

    public function store(Request $request, Trainee $trainee)
    {
        $data = $this->validated($request);

        $dp = new DirectionPlongeeEvaluation(array_merge($data, ['trainee_id' => $trainee->id]));
        $dp->note_globale = $dp->computeNoteGlobale();
        $dp->save();

        return redirect(route('instructor.trainee.show', $trainee) . '#dp')
            ->with('success', 'Évaluation ajoutée.');
    }

    public function edit(Trainee $trainee, DirectionPlongeeEvaluation $dp)
    {
        return view('instructor.dp-form', compact('trainee', 'dp'));
    }

    public function update(Request $request, Trainee $trainee, DirectionPlongeeEvaluation $dp)
    {
        $data = $this->validated($request);

        $dp->fill($data);
        $dp->note_globale = $dp->computeNoteGlobale();
        $dp->save();

        return redirect(route('instructor.trainee.show', $trainee) . '#dp')
            ->with('success', 'Évaluation mise à jour.');
    }

    public function destroy(Trainee $trainee, DirectionPlongeeEvaluation $dp)
    {
        $dp->delete();

        return redirect(route('instructor.trainee.show', $trainee) . '#dp')
            ->with('success', 'Évaluation supprimée.');
    }

    private function validated(Request $request): array
    {
        $comps = array_keys(DirectionPlongeeEvaluation::competencies());

        $rules = [
            'evaluated_at' => ['required', 'date'],
            'status'       => ['required', 'in:en_cours,valide,echec'],
            'instructor_notes' => ['nullable', 'string', 'max:2000'],
        ];

        foreach ($comps as $key) {
            $rules[$key] = ['nullable', 'integer', 'min:1', 'max:3'];
        }

        return $request->validate($rules);
    }
}
