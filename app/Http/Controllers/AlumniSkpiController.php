<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitAlumniSkpiRequest;
use App\Services\AlumniSkpiService;
use App\Models\SkpiDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AlumniSkpiController extends Controller
{
    public function index(Request $request, AlumniSkpiService $skpiService): View
    {
        $user = $request->user();

        if (! $skpiService->canViewSkpiPage($user)) {
            abort(403, 'SKPI belum dapat diajukan saat ini.');
        }

        $latestSkpiRequest = $user->skpiRequests()
            ->with('document')
            ->latest('created_at')
            ->first();

        $skpiDocument = $latestSkpiRequest?->document;

        return view('alumni.skpi', [
            'title' => 'Ajukan SKPI',
            'profile' => $user->alumniProfile,
            'activities' => $skpiService->listActivities($user),
            'skpiSubmitted' => $skpiService->isSkpiSubmitted($user),
            'skpiStatus' => $skpiService->getSkpiStatus($user),
            'latestSubmission' => $skpiService->getLatestSkpiSubmission($user),
            'canRequestSkpi' => $skpiService->canRequestSkpi($user),
            'skpiDocument' => $skpiDocument,
            'latestSkpiRequest' => $latestSkpiRequest,
        ]);
    }

    public function store(SubmitAlumniSkpiRequest $request, AlumniSkpiService $skpiService)
    {
        $skpiService->submit($request->user());

        return redirect()->route('alumni.skpi.index')
            ->with('success', 'SKPI berhasil diajukan. Tunggu pemberitahuan selanjutnya.');
    }

    public function download(Request $request)
    {
        $document = $this->getLatestDocument($request);
        $fileName = $this->buildFileName($document);

        return Storage::disk('local')->download($document->pdf_path, $fileName);
    }

    public function preview(Request $request)
    {
        $document = $this->getLatestDocument($request);
        $fileName = $this->buildFileName($document);
        return Storage::disk('local')->response($document->pdf_path, $fileName);
    }

    private function getLatestDocument(Request $request): SkpiDocument
    {
        $user = $request->user();

        $skpiRequest = $user->skpiRequests()
            ->with('document')
            ->whereHas('document')
            ->latest('created_at')
            ->first();

        $document = $skpiRequest?->document;

        if (! $document || ! Storage::disk('local')->exists($document->pdf_path)) {
            abort(404, 'Dokumen SKPI belum tersedia.');
        }

        return $document;
    }

    private function buildFileName(SkpiDocument $document): string
    {
        return 'SKPI-' . str_replace(['/', ' '], '-', $document->nomor_skpi) . '.pdf';
    }
}
