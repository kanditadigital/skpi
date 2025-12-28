@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
<style>
.dashboard-panel {
    background: #fff;
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: 0 25px 45px rgba(15, 23, 42, 0.15);
    border: 1px solid rgba(15, 23, 42, 0.08);
}
.dashboard-panel-header {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #fff;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.dashboard-panel-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
}
.dashboard-panel-meta {
    font-size: 0.85rem;
    background: rgba(255, 255, 255, 0.25);
    padding: 0.25rem 0.8rem;
    border-radius: 999px;
    letter-spacing: 0.05em;
}
.dashboard-panel-body {
    padding: 2rem;
}
.panel-number {
    font-size: 2.25rem;
    font-weight: 700;
    margin: 0;
    color: #0f172a;
}
.panel-label {
    font-size: 0.8rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(15, 23, 42, 0.65);
}
.panel-main {
    margin-bottom: 1.5rem;
}
.panel-row {
    display: flex;
    gap: 1rem;
}
.panel-card {
    flex: 1;
    border-radius: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #eef2ff, #fdf2f8);
    box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.2);
    text-align: center;
}
.panel-card strong {
    display: block;
    font-size: 1.5rem;
    color: #0f172a;
    margin-top: 0.35rem;
}
.panel-list {
    margin: 0;
    padding: 0;
    list-style: none;
}
.panel-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.85rem 0;
    border-bottom: 1px dashed rgba(15, 23, 42, 0.1);
}
.panel-list li:last-child {
    border-bottom: none;
}
.panel-list strong {
    font-size: 1rem;
}
@media (max-width: 767px) {
    .panel-row {
        flex-direction: column;
    }
    .dashboard-panel-body {
        padding: 1.5rem;
    }
}
</style>
<div class="section-header">
    <h1>Admin Dashboard</h1>
</div>

<div class="section-body">
    <div class="row g-4">
        <div class="col-lg-4 col-md-6">
            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h3 class="dashboard-panel-title">Validasi Biodata & Akademik</h3>
                    <span class="dashboard-panel-meta">Terupdate</span>
                </div>
                <div class="dashboard-panel-body">
                    <div class="panel-main text-center">
                        <p class="panel-label mb-1">Permintaan verifikasi</p>
                        <p class="panel-number mb-0">{{ number_format($totalVerificationRequests) }}</p>
                    </div>
                    <div class="panel-row">
                        <div class="panel-card">
                            <div class="panel-label">Sudah divalidasi</div>
                            <strong>{{ number_format($validatedProfilesCount) }}</strong>
                        </div>
                        <div class="panel-card">
                            <div class="panel-label">Menunggu</div>
                            <strong>{{ number_format($pendingProfilesCount) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h3 class="dashboard-panel-title">Validasi Aktivitas</h3>
                    <span class="dashboard-panel-meta">Sedang Berjalan</span>
                </div>
                <div class="dashboard-panel-body">
                    <div class="panel-main text-center">
                        <p class="panel-label mb-1">Antrean validasi</p>
                        <p class="panel-number mb-0">{{ number_format($activityValidationRequests) }}</p>
                    </div>
                    <div class="panel-row">
                        <div class="panel-card">
                            <div class="panel-label">Aktivitas divalidasi</div>
                            <strong class="text-success">{{ number_format($activityValidatedCount) }}</strong>
                        </div>
                        <div class="panel-card">
                            <div class="panel-label">Belum divalidasi</div>
                            <strong class="text-warning">{{ number_format($activityPendingCount) }}</strong>
                        </div>
                    </div>
                    @if($activityValidationApplicants->isNotEmpty())
                        <ul class="panel-list">
                            @foreach($activityValidationApplicants as $applicant)
                                <li>
                                    <div>
                                        <strong>{{ $applicant->nama_lengkap }}</strong><br>
                                        <small class="text-muted">{{ $applicant->nim }}</small>
                                    </div>
                                    <span class="text-primary small">{{ optional($applicant->user)->email ?? '-' }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if($activityValidationApplicants->count() === 5)
                            <small class="text-muted">Menampilkan 5 alumni terbaru.</small>
                        @endif
                    @else
                        <div class="text-muted small text-center">Tidak ada antrean saat ini.</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h3 class="dashboard-panel-title">Pengajuan SKPI Alumni</h3>
                    <span class="dashboard-panel-meta">Statistik</span>
                </div>
                <div class="dashboard-panel-body">
                    @php
                        $pendingSubmissions = $skpiSubmissionStats['pending'] ?? 0;
                        $approvedSubmissions = $skpiSubmissionStats['approved'] ?? 0;
                        $rejectedSubmissions = $skpiSubmissionStats['rejected'] ?? 0;
                    @endphp
                    <div class="panel-main">
                        <p class="panel-label">Total pengajuan SKPI</p>
                        <p class="panel-number">{{ number_format($skpiSubmissionsCount) }}</p>
                    </div>
                    <div class="panel-row">
                        <div class="panel-card">
                            <div class="panel-label">Menunggu approval</div>
                            <strong class="text-info">{{ number_format($pendingSubmissions) }}</strong>
                        </div>
                        <div class="panel-card">
                            <div class="panel-label">Hasil akhir</div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-success">Disetujui {{ number_format($approvedSubmissions) }}</span>
                                <span class="text-danger">Ditolak {{ number_format($rejectedSubmissions) }}</span>
                            </div>
                        </div>
                    </div>
                    @if($skpiSubmissionsCount === 0)
                        <p class="text-muted text-center mb-0 mt-3">Belum ada alumni yang mengajukan SKPI.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
