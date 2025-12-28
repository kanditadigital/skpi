<?php

use App\Models\AlumniActivity;
use App\Models\AlumniProfile;
use App\Models\SkpiDocument;
use App\Models\SkpiRequest;
use App\Models\User;
use App\Services\AlumniSkpiService;
use App\Services\SkpiPdfService;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Assert;
use function Pest\Laravel\actingAs;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'alumni']);
    Role::create(['name' => 'admin']);
});

test('alumni can progress through profile/activities validation and admin can forward SKPI to leadership', function () {
    $alumni = User::factory()->create(['role' => 'alumni']);
    $alumni->assignRole('alumni');

    $profile = AlumniProfile::create([
        'user_id' => $alumni->id,
        'nim' => '210001',
        'nama_lengkap' => 'Test Alumni',
        'prodi' => 'Teknik Informatika',
        'fakultas' => 'Fakultas Teknik',
        'tahun_lulus' => now()->year,
        'ipk' => 3.75,
        'validasi' => true,
        'konfirmasi' => true,
        'skpi_submitted' => false,
    ]);

    AlumniActivity::create([
        'user_id' => $alumni->id,
        'jenis_aktivitas' => 'organisasi',
        'nama_aktivitas' => 'Himpunan Informatika',
        'tahun' => now()->year,
        'bukti_file' => 'storage/alumni-activities/test.pdf',
        'status' => 'disetujui',
        'validasi' => true,
        'konfirmasi' => true,
    ]);

    $service = app(AlumniSkpiService::class);
    expect($service->canRequestSkpi($alumni))->toBeTrue();

    $service->submit($alumni);

    expect($profile->refresh()->skpi_submitted)->toBeTrue();

    $admin = User::factory()->create(['role' => 'admin_prodi']);
    $admin->assignRole('admin');

    actingAs($admin)
        ->post(route('admin.submit.skpi', $profile->id))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('skpi_submissions', [
        'alumni_profile_id' => $profile->id,
        'submitted_by' => $admin->id,
        'status' => 'pending',
    ]);
});

test('admin cannot forward SKPI to leadership while there is still pending aktivitas', function () {
    $alumni = User::factory()->create(['role' => 'alumni']);
    $alumni->assignRole('alumni');

    $profile = AlumniProfile::create([
        'user_id' => $alumni->id,
        'nim' => '210002',
        'nama_lengkap' => 'Pending Alumni',
        'prodi' => 'Sistem Informasi',
        'fakultas' => 'Fakultas Teknik',
        'tahun_lulus' => now()->year,
        'ipk' => 3.60,
        'validasi' => true,
        'konfirmasi' => true,
        'skpi_submitted' => false,
    ]);

    AlumniActivity::create([
        'user_id' => $alumni->id,
        'jenis_aktivitas' => 'seminar_workshop',
        'nama_aktivitas' => 'Workshop Testing',
        'tahun' => now()->year,
        'bukti_file' => 'storage/alumni-activities/pending.pdf',
        'status' => 'disetujui',
        'validasi' => true,
        'konfirmasi' => true,
    ]);

    AlumniActivity::create([
        'user_id' => $alumni->id,
        'jenis_aktivitas' => 'kepanitiaan',
        'nama_aktivitas' => 'Panitia Seminar',
        'tahun' => now()->year,
        'bukti_file' => 'storage/alumni-activities/pending2.pdf',
        'status' => 'diajukan',
        'validasi' => false,
        'konfirmasi' => true,
    ]);

    $service = app(AlumniSkpiService::class);
    $service->submit($alumni);

    $admin = User::factory()->create(['role' => 'admin_prodi']);
    $admin->assignRole('admin');

    actingAs($admin)
        ->post(route('admin.submit.skpi', $profile->id))
        ->assertSessionHas('error', 'Masih ada aktivitas yang belum diperiksa.');

    $this->assertDatabaseMissing('skpi_submissions', [
        'alumni_profile_id' => $profile->id,
        'submitted_by' => $admin->id,
    ]);
});

test('admin can generate SKPI document after approval flow', function () {
    $alumni = User::factory()->create(['role' => 'alumni']);
    $alumni->assignRole('alumni');

    $profile = AlumniProfile::create([
        'user_id' => $alumni->id,
        'nim' => '210003',
        'nama_lengkap' => 'Generate Alumni',
        'prodi' => 'Teknik Elektro',
        'fakultas' => 'Fakultas Teknik',
        'tahun_lulus' => now()->year,
        'ipk' => 3.90,
        'validasi' => true,
        'konfirmasi' => true,
        'skpi_submitted' => false,
    ]);

    AlumniActivity::create([
        'user_id' => $alumni->id,
        'jenis_aktivitas' => 'organisasi',
        'nama_aktivitas' => 'Unit Kegiatan',
        'tahun' => now()->year,
        'bukti_file' => 'storage/alumni-activities/generate.pdf',
        'status' => 'disetujui',
        'validasi' => true,
        'konfirmasi' => true,
    ]);

    $service = app(AlumniSkpiService::class);
    $service->submit($alumni);

    $admin = User::factory()->create(['role' => 'admin_prodi']);
    $admin->assignRole('admin');

    actingAs($admin)
        ->post(route('admin.submit.skpi', $profile->id))
        ->assertSessionHas('success');

    $submission = \App\Models\SkpiSubmission::where('alumni_profile_id', $profile->id)
        ->where('submitted_by', $admin->id)
        ->first();
    $submission->update([
        'status' => 'approved',
        'approved_by' => $admin->id,
        'approved_at' => now(),
    ]);

    $issuedAt = now();
    $pdfResult = [
        'path' => 'skpi/generate-test.pdf',
        'nomor' => 'SKPI/2026/210003/0001',
        'issued_at' => $issuedAt,
        'content' => 'dummy-pdf-bytes',
        'hash' => 'mocked-verification-hash',
    ];

    $mock = \Mockery::mock(SkpiPdfService::class);
    $mock->shouldReceive('generateFor')->once()->andReturn($pdfResult);
    app()->instance(SkpiPdfService::class, $mock);

    actingAs($admin)
        ->post(route('admin.generate.skpi', $profile->id))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('skpi_requests', [
        'user_id' => $alumni->id,
        'status' => 'approved',
        'approved_by' => $admin->id,
    ]);

    $this->assertDatabaseHas('skpi_documents', [
        'nomor_skpi' => $pdfResult['nomor'],
        'pdf_path' => $pdfResult['path'],
        'hash' => $pdfResult['hash'],
        'issued_at' => $issuedAt,
    ]);
});

test('alumni can download generated SKPI after admin creates document', function () {
    Storage::fake('local');

    $alumni = User::factory()->create(['role' => 'alumni']);
    $alumni->assignRole('alumni');

    AlumniProfile::create([
        'user_id' => $alumni->id,
        'nim' => '210004',
        'nama_lengkap' => 'Download Alumni',
        'prodi' => 'Teknik Mesin',
        'fakultas' => 'Fakultas Teknik',
        'tahun_lulus' => now()->year,
        'ipk' => 3.80,
        'validasi' => true,
        'konfirmasi' => true,
        'skpi_submitted' => true,
    ]);

    $admin = User::factory()->create(['role' => 'admin_prodi']);
    $admin->assignRole('admin');

    $issuedAt = now();
    Storage::disk('local')->put('skpi/download-test.pdf', 'dummy-content');

    $skpiRequest = SkpiRequest::create([
        'user_id' => $alumni->id,
        'status' => 'approved',
        'approved_by' => $admin->id,
        'approved_at' => $issuedAt,
    ]);

    $document = SkpiDocument::create([
        'skpi_request_id' => $skpiRequest->id,
        'nomor_skpi' => 'SKPI/2026/210004/0001',
        'pdf_path' => 'skpi/download-test.pdf',
        'hash' => hash('sha256', 'dummy-content'),
        'issued_at' => $issuedAt,
    ]);

    $response = actingAs($alumni)
        ->get(route('alumni.skpi.download'))
        ->assertOk();

    $expectedFileName = 'SKPI-' . str_replace(['/', ' '], '-', $document->nomor_skpi) . '.pdf';
    Assert::assertStringContainsString($expectedFileName, $response->headers->get('content-disposition'));
});
