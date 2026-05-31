<?php

namespace App\Http\Controllers;

use App\Models\Uc12Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function downloadUc12(Uc12Document $document)
    {
        if (!session('role')) {
            return redirect()->route('home');
        }

        abort_unless(Storage::exists($document->stored_path), 404);

        return Storage::download($document->stored_path, $document->original_name);
    }
}
