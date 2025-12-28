<?php

namespace App\Http\Controllers;

use App\Models\SkpiDocument;
use Illuminate\View\View;

class SkpiVerificationController extends Controller
{
    public function show(string $hash): View
    {
        $document = SkpiDocument::with(['request.user.alumniProfile'])->where('hash', $hash)->firstOrFail();
        $requestModel = $document->request;

        if (! $requestModel) {
            abort(404, 'Dokumen SKPI tidak terhubung dengan permintaan valid.');
        }

        $profile = $requestModel->user?->alumniProfile;

        return view('skpi.verify', [
            'document' => $document,
            'profile' => $profile,
        ]);
    }
}
