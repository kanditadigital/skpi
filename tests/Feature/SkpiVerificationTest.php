<?php

use App\Models\AlumniProfile;
use App\Models\SkpiDocument;
use App\Models\SkpiRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'alumni']);
    Role::firstOrCreate(['name' => 'admin']);
});

test('skpi verification route renders the view when the hash exists', function () {
    $alumni = User::factory()->create(['role' => 'alumni']);
    $alumni->assignRole('alumni');

    $profile = AlumniProfile::create([
        'user_id' => $alumni->id,
        'nim' => '210010',
        'nama_lengkap' => 'Verifikasi Alumni',
        'prodi' => 'Teknik Informatika',
        'fakultas' => 'Fakultas Teknik',
        'tahun_lulus' => now()->year,
        'ipk' => 3.50,
        'validasi' => true,
        'konfirmasi' => true,
        'skpi_submitted' => true,
    ]);

    $requestModel = SkpiRequest::create([
        'user_id' => $alumni->id,
        'status' => 'approved',
    ]);

    $document = SkpiDocument::create([
        'skpi_request_id' => $requestModel->id,
        'nomor_skpi' => 'SKPI/2026/210010/0001',
        'pdf_path' => 'skpi/verifikasi-test.pdf',
        'hash' => hash('sha256', Str::uuid()->toString()),
        'issued_at' => now(),
    ]);

    $response = $this->get('/skpi/verify/'.$document->hash);

    $response->assertOk();
    $response->assertViewIs('skpi.verify');
    $response->assertViewHas('document', fn ($viewDocument) => $viewDocument->id === $document->id);
    $response->assertViewHas('profile', fn ($viewProfile) => $viewProfile->id === $profile->id);
});

test('skpi verification route returns 404 when the hash is invalid', function () {
    $response = $this->get(route('skpi.verify', ['hash' => 'invalid-hash']));

    $response->assertNotFound();
});
