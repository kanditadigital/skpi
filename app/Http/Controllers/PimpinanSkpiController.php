<?php

namespace App\Http\Controllers;

use App\Models\SkpiDocument;
use App\Models\SkpiRequest;
use App\Services\SkpiPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PimpinanSkpiController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:pimpinan');
    }

    public function approve(SkpiRequest $skpiRequest, SkpiPdfService $skpiPdfService): RedirectResponse
    {
        DB::transaction(function () use ($skpiRequest, $skpiPdfService) {
            if ($skpiRequest->status !== 'pending') {
                abort(409, 'Permintaan SKPI sudah diproses.');
            }

            $skpiRequest->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            $skpiRequest->refresh();

            $pdfResult = $skpiPdfService->generateFor($skpiRequest);

            SkpiDocument::create([
                'skpi_request_id' => $skpiRequest->id,
                'nomor_skpi' => $pdfResult['nomor'],
                'pdf_path' => $pdfResult['path'],
                'hash' => $pdfResult['hash'],
                'issued_at' => $pdfResult['issued_at'],
            ]);
        });

        return redirect()->back()->with('success', 'SKPI disetujui dan dokumen resmi telah dibuat.');
    }
}
