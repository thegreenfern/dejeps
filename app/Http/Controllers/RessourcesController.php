<?php

namespace App\Http\Controllers;

use App\Models\ProgramSettings;
use App\Models\Ressource;
use App\Models\SitePlongee;
use App\Models\Trainee;
use App\Models\Uc12Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RessourcesController extends Controller
{
    public function index()
    {
        $settings   = ProgramSettings::instance();
        $documents  = Uc12Document::orderBy('created_at', 'desc')->get();
        $sections   = Ressource::sections();
        $ressources = Ressource::orderBy('section')->orderBy('ordre')->orderBy('id')->get()->groupBy('section');
        $sites      = SitePlongee::orderBy('nom')->get();
        $niveaux    = SitePlongee::niveaux();

        return view('instructor.ressources.index', compact('settings', 'documents', 'sections', 'ressources', 'sites', 'niveaux'));
    }

    public function indexTrainee()
    {
        if (! session('trainee_id')) {
            return redirect()->route('trainee.select');
        }

        $trainee    = Trainee::with('profile')->findOrFail(session('trainee_id'));
        $sections   = Ressource::sections();
        $ressources  = Ressource::orderBy('section')->orderBy('ordre')->orderBy('id')->get()->groupBy('section');
        $sites       = SitePlongee::orderBy('nom')->get();

        return view('trainee.ressources', compact('trainee', 'sections', 'ressources', 'sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section'        => ['required', 'in:' . implode(',', array_keys(Ressource::sections()))],
            'titre'          => ['required', 'string', 'max:255'],
            'fichier'        => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,webp', 'max:20480'],
            'lien_externe'   => ['nullable', 'url', 'max:500'],
        ]);

        $maxOrdre = Ressource::where('section', $request->section)->max('ordre') ?? 0;

        $data = [
            'section' => $request->section,
            'titre'   => $request->titre,
            'ordre'   => $maxOrdre + 1,
        ];

        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $data['fichier_path']          = $file->store('ressources');
            $data['fichier_nom_original']  = $file->getClientOriginalName();
        } elseif ($request->filled('lien_externe')) {
            $data['lien_externe'] = $request->lien_externe;
        }

        Ressource::create($data);

        return back()->with('success', 'Ressource ajoutée.');
    }

    public function destroy(Ressource $ressource)
    {
        if ($ressource->fichier_path) {
            Storage::delete($ressource->fichier_path);
        }
        $ressource->delete();

        return back()->with('success', 'Ressource supprimée.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach ($request->ids as $index => $id) {
            Ressource::where('id', $id)->update(['ordre' => $index + 1]);
        }

        return response()->json(['ok' => true]);
    }

    public function download(Ressource $ressource)
    {
        abort_unless($ressource->fichier_path && Storage::exists($ressource->fichier_path), 404);

        return Storage::download($ressource->fichier_path, $ressource->fichier_nom_original);
    }

    public function storeSite(Request $request)
    {
        $request->validate([
            'nom'           => ['required', 'string', 'max:255'],
            'latitude'      => ['required', 'numeric', 'between:-90,90'],
            'longitude'     => ['required', 'numeric', 'between:-180,180'],
            'profondeur_max' => ['nullable', 'integer', 'min:1', 'max:300'],
            'niveau_requis' => ['nullable', 'string', 'max:50'],
            'description'   => ['nullable', 'string', 'max:1000'],
        ]);

        SitePlongee::create($request->only('nom', 'latitude', 'longitude', 'profondeur_max', 'niveau_requis', 'description'));

        return back()->with('success', 'Site ajouté.');
    }

    public function updateSite(Request $request, SitePlongee $site)
    {
        $request->validate([
            'nom'           => ['required', 'string', 'max:255'],
            'latitude'      => ['required', 'numeric', 'between:-90,90'],
            'longitude'     => ['required', 'numeric', 'between:-180,180'],
            'profondeur_max' => ['nullable', 'integer', 'min:1', 'max:300'],
            'niveau_requis' => ['nullable', 'string', 'max:50'],
            'description'   => ['nullable', 'string', 'max:1000'],
        ]);

        $site->update($request->only('nom', 'latitude', 'longitude', 'profondeur_max', 'niveau_requis', 'description'));

        return back()->with('success', 'Site mis à jour.');
    }

    public function destroySite(SitePlongee $site)
    {
        $site->delete();

        return back()->with('success', 'Site supprimé.');
    }
}
