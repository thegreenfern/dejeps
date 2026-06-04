<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use App\Models\TraineeEpmsp;
use Illuminate\Http\Request;

class EpmspController extends Controller
{
    public function create(Trainee $trainee, string $type)
    {
        abort_unless(in_array($type, ['25m', 'pedagogie']), 404);

        return view('instructor.epmsp-form', ['trainee' => $trainee, 'epmsp' => null, 'type' => $type]);
    }

    public function store(Request $request, Trainee $trainee)
    {
        $data = $this->validated($request);

        $epmsp = new TraineeEpmsp(array_merge($data, ['trainee_id' => $trainee->id]));
        $epmsp->note_globale = $epmsp->computeNoteGlobale();
        $epmsp->save();

        return redirect(route('instructor.trainee.show', $trainee) . '#epmsp')
            ->with('success', 'Évaluation ajoutée.');
    }

    public function edit(Trainee $trainee, TraineeEpmsp $epmsp)
    {
        return view('instructor.epmsp-form', ['trainee' => $trainee, 'epmsp' => $epmsp, 'type' => $epmsp->type]);
    }

    public function update(Request $request, Trainee $trainee, TraineeEpmsp $epmsp)
    {
        $data = $this->validated($request);

        $epmsp->fill($data);
        $epmsp->note_globale = $epmsp->computeNoteGlobale();
        $epmsp->save();

        return redirect(route('instructor.trainee.show', $trainee) . '#epmsp')
            ->with('success', 'Évaluation mise à jour.');
    }

    public function destroy(Trainee $trainee, TraineeEpmsp $epmsp)
    {
        $epmsp->delete();

        return redirect(route('instructor.trainee.show', $trainee) . '#epmsp')
            ->with('success', 'Évaluation supprimée.');
    }

    private function validated(Request $request): array
    {
        $type = $request->input('type');
        abort_unless(in_array($type, ['25m', 'pedagogie']), 422);

        $keys  = array_keys(TraineeEpmsp::competencies($type));
        $rules = [
            'type'             => ['required', 'in:25m,pedagogie'],
            'evaluated_at'     => ['required', 'date'],
            'status'           => ['required', 'in:en_cours,valide,echec'],
            'instructor_notes' => ['nullable', 'string', 'max:2000'],
        ];
        foreach ($keys as $key) {
            $rules["ratings.$key"] = ['nullable', 'in:1,2,3'];
        }

        $data           = $request->validate($rules);
        $data['ratings'] = array_filter($data['ratings'] ?? [], fn($v) => $v !== null && $v !== '');
        if (empty($data['ratings'])) {
            $data['ratings'] = null;
        }

        return $data;
    }
}
