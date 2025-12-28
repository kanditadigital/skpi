<?php

namespace App\Services;

use App\Models\SkpiMasterContent;
use App\Models\SkpiRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SkpiPdfService
{
    /**
     * Generate the SKPI PDF file for the approved request.
     *
     * @param SkpiRequest $skpiRequest
     * @return array{path:string,nomor:string,issued_at:\Illuminate\Support\Carbon,content:string}
     */
    public function generateFor(SkpiRequest $skpiRequest): array
    {
        $this->validateAlumniProfile($skpiRequest);

        $issuedAt = now();
        $nomorSkpi = $this->buildNomorSkpi($skpiRequest, $skpiRequest->user->alumniProfile, $issuedAt);

        $masterContent = SkpiMasterContent::active();
        $institution = $this->buildInstitutionData($masterContent);
        $leader = $this->buildLeaderData($masterContent);

        $verificationData = $this->generateVerificationData($skpiRequest, $skpiRequest->user->alumniProfile, $issuedAt);
        $documentPayload = $this->buildDocumentPayload($nomorSkpi, $issuedAt, $masterContent, $verificationData);

        $pdfContent = $this->renderPdfContent($skpiRequest, $institution, $leader, $documentPayload);
        $fileName = $this->storePdfFile($nomorSkpi, $pdfContent);

        return [
            'path' => $fileName,
            'nomor' => $nomorSkpi,
            'issued_at' => $issuedAt,
            'content' => $pdfContent,
            'hash' => $verificationData['hash'],
        ];
    }

    /**
     * Validate that the alumni profile exists.
     */
    private function validateAlumniProfile(SkpiRequest $skpiRequest): void
    {
        if (!$skpiRequest->user->alumniProfile) {
            throw new \RuntimeException('Profil alumni belum lengkap.');
        }
    }

    /**
     * Build institution data from master content.
     */
    private function buildInstitutionData(?SkpiMasterContent $masterContent): object
    {
        $institutionInfo = $masterContent?->institution_info_json ?? [];
        $institutionDefaults = config('skpi.institution', []);
        $institution = (object) array_merge($institutionDefaults, $institutionInfo);

        if ($masterContent?->kop_surat_path) {
            $institution->kop_surat_url = Storage::disk('public')->path($masterContent->kop_surat_path);
        }

        return $institution;
    }

    /**
     * Build leader data from master content.
     */
    private function buildLeaderData(?SkpiMasterContent $masterContent): object
    {
        $leaderDefaults = config('skpi.leader', []);
        $leader = (object) [
            'name' => $masterContent?->leader_name ?: $leaderDefaults['name'],
            'title' => $masterContent?->leader_title ?: $leaderDefaults['title'],
            'nidn' => $masterContent?->leader_nidn ?: $leaderDefaults['nidn'],
            'signature_path' => null,
        ];

        if ($masterContent?->leader_signature_path) {
            $leader->signature_path = Storage::disk('public')->path($masterContent->leader_signature_path);
        }

        return $leader;
    }

    /**
     * Generate verification hash, URL, and QR code.
     */
    private function generateVerificationData(SkpiRequest $skpiRequest, $profile, $issuedAt): array
    {
        $hash = $this->buildVerificationHash($skpiRequest, $profile, $issuedAt);
        $url = $this->buildVerificationUrl($hash);
        $qrCode = $this->generateQrCodeImage($url);

        return [
            'hash' => $hash,
            'url' => $url,
            'qr_code' => $qrCode,
        ];
    }

    /**
     * Build the document payload object.
     */
    private function buildDocumentPayload(string $nomorSkpi, $issuedAt, ?SkpiMasterContent $masterContent, array $verificationData): object
    {
        $kkniEntries = $masterContent?->kkniEntries() ?? [];
        $firstKkni = $kkniEntries[0] ?? null;

        return (object) [
            'nomor_skpi' => $nomorSkpi,
            'issued_at' => $issuedAt,
            'issued_place' => $masterContent->city ?? config('skpi.document.issued_place'),
            'learning_outcomes' => config('skpi.document.learning_outcomes', []),
            'learning_outcomes_en' => config('skpi.document.learning_outcomes_en', []),
            'working_capability' => $masterContent?->working_capability_json ?? config('skpi.document.working_capability', []),
            'special_attitude' => $masterContent?->special_attitude_json ?? config('skpi.document.special_attitude', []),
            'opening_text_id' => $masterContent?->opening_text_id ?? config('skpi.document.opening_text_id'),
            'opening_text_en' => $masterContent?->opening_text_en ?? config('skpi.document.opening_text_en'),
            'kkni_items' => $kkniEntries,
            'kkni_text_id' => $firstKkni['id'] ?? $masterContent?->kkni_text_id ?? config('skpi.document.kkni_text_id'),
            'kkni_text_en' => $firstKkni['en'] ?? $masterContent?->kkni_text_en ?? config('skpi.document.kkni_text_en'),
            'qr_code' => $verificationData['qr_code'],
            'verification_url' => $verificationData['url'],
            'verification_hash' => $verificationData['hash'],
        ];
    }

    /**
     * Render the PDF content using the view.
     */
    private function renderPdfContent(SkpiRequest $skpiRequest, object $institution, object $leader, object $documentPayload): string
    {
        $activities = $skpiRequest->user->alumniActivities()
            ->whereIn('status', ['disetujui', 'approved'])
            ->orderBy('tahun', 'asc')
            ->get();

        return Pdf::loadView('pdf.skpi', [
            'alumni' => $skpiRequest->user->alumniProfile,
            'activities' => $activities,
            'institution' => $institution,
            'leader' => $leader,
            'document' => $documentPayload,
            'skpiRequest' => $skpiRequest,
        ])
            ->setPaper('legal')
            ->output();
    }

    /**
     * Store the PDF file and return the filename.
     */
    private function storePdfFile(string $nomorSkpi, string $content): string
    {
        $fileName = 'skpi/' . Str::slug($nomorSkpi, '-') . '.pdf';
        Storage::disk('local')->put($fileName, $content);

        return $fileName;
    }

    /**
     * Build the SKPI number.
     */
    private function buildNomorSkpi(SkpiRequest $request, $profile, $issuedAt): string
    {
        $year = $issuedAt->format('Y');
        $nim = $profile->nim ?? '000000';
        $sequence = str_pad($request->id, 4, '0', STR_PAD_LEFT);

        return "SKPI/{$year}/{$nim}/{$sequence}";
    }

    /**
     * Build verification hash for the document.
     */
    private function buildVerificationHash(SkpiRequest $request, $profile, $issuedAt): string
    {
        $parts = [
            $request->id,
            $request->user_id,
            $profile->nim ?? '',
            $issuedAt->toIso8601String(),
        ];

        return hash('sha256', implode('|', $parts));
    }

    /**
     * Build verification URL from hash.
     */
    private function buildVerificationUrl(string $hash): string
    {
        if (!$hash) {
            return '';
        }

        try {
            return route('skpi.verify', ['hash' => $hash], true);
        } catch (\Exception $exception) {
            return rtrim(config('app.url', '/'), '/') . '/skpi/verify/' . $hash;
        }
    }

    /**
     * Generate QR code image from verification URL.
     */
    private function generateQrCodeImage(string $verificationUrl): string
    {
        if (empty($verificationUrl)) {
            return '';
        }

        $qrBinary = QrCode::format('png')
            ->size(150)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($verificationUrl);

        return base64_encode($qrBinary);
    }
}
