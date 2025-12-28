@extends('layouts.main')

@section('title', 'Pengajuan SKPI')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <h4>Daftar Pengajuan SKPI ke Pimpinan</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($skpiSubmissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal Pengajuan</th>
                                        <th>NIM</th>
                                        <th>Nama Alumni</th>
                                        <th>Program Studi</th>
                                        <th>Status</th>
                                        <th>Diajukan Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($skpiSubmissions as $submission)
                                        <tr>
                                            <td>{{ $submission->submittedAtFormatted ?? '-' }}</td>
                                            <td>{{ $submission->alumniProfile->nim }}</td>
                                            <td>{{ $submission->alumniProfile->nama_lengkap }}</td>
                                            <td>{{ $submission->alumniProfile->prodi }}</td>
                                            <td>
                                                <x-status-badge :status="$submission->status" />
                                            </td>
                                            <td>{{ $submission->submitter->name }}</td>
                                            <td>
                                                <x-button size="sm" variant="secondary" type="button" data-toggle="modal" data-target="#modalDetail{{ $submission->id }}">
                                                    Detail
                                                </x-button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($skpiSubmissions->hasPages())
                            <div class="mt-3 d-flex justify-content-end">
                                {{ $skpiSubmissions->links() }}
                            </div>
                        @endif
                    @else
                        <p>Belum ada pengajuan SKPI.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach($skpiSubmissions as $submission)
        @php
            $profile = $submission->alumniProfile;
            $validatedActivities = $profile->user?->alumniActivities ?? collect();
        @endphp
        <div class="modal fade" id="modalDetail{{ $submission->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel{{ $submission->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailLabel{{ $submission->id }}">Review Pengajuan SKPI - {{ $profile->nama_lengkap }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <x-admin.card-section title="1. Data Diri dan Akademik Alumni">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    @if($profile->photo_url)
                                        <div class="mb-3">
                                            <img src="{{ $profile->photo_url }}" alt="Pas Foto" class="img-thumbnail" style="max-width: 150px; max-height: 200px;">
                                            <p class="mt-2"><small class="text-muted">Pas Foto Formal</small></p>
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 200px; margin: 0 auto; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                                                <i class="fas fa-user fa-3x text-muted"></i>
                                            </div>
                                            <p class="mt-2"><small class="text-muted">Pas Foto Formal</small></p>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="border-bottom pb-2 mb-3">Data Pribadi</h6>
                                            <table class="table table-borderless table-sm">
                                                <x-admin.info-row label="NIM">
                                                    {{ $profile->nim }}
                                                </x-admin.info-row>
                                                <x-admin.info-row label="Nama Lengkap">
                                                    {{ $profile->nama_lengkap }}
                                                </x-admin.info-row>
                                                <x-admin.info-row label="Tempat Lahir">
                                                    {{ $profile->tempat_lahir }}
                                                </x-admin.info-row>
                                                <x-admin.info-row label="Tanggal Lahir">
                                                    {{ $profile->formatted_tanggal_lahir }}
                                                </x-admin.info-row>
                                                <x-admin.info-row label="Nomor WA">
                                                    {{ $profile->nomor_wa }}
                                                </x-admin.info-row>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="border-bottom pb-2 mb-3">Data Akademik</h6>
                                            <table class="table table-borderless table-sm">
                                                <x-admin.info-row label="Program Studi">
                                                    {{ $profile->prodi }}
                                                </x-admin.info-row>
                                                <x-admin.info-row label="Fakultas">
                                                    {{ $profile->fakultas }}
                                                </x-admin.info-row>
                                                <x-admin.info-row label="Tahun Lulus">
                                                    {{ $profile->tahun_lulus }}
                                                </x-admin.info-row>
                                                <x-admin.info-row label="IPK">
                                                    {{ $profile->ipk }}
                                                </x-admin.info-row>
                                                <x-admin.info-row label="Nomor Ijazah">
                                                    {{ $profile->nomor_ijazah }}
                                                </x-admin.info-row>
                                                <x-admin.info-row label="Gelar Akademik">
                                                    {{ $profile->gelar_akademik }}
                                                </x-admin.info-row>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <span class="badge badge-success p-2">
                                    <i class="fas fa-check-circle"></i> Data Sudah Divalidasi Admin
                                </span>
                            </div>
                        </x-admin.card-section>

                        <x-admin.card-section title="2. Aktivitas yang Sudah Divalidasi">
                            @if($validatedActivities->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Jenis Aktivitas</th>
                                                <th>Nama Aktivitas</th>
                                                <th>Tahun</th>
                                                <th>Status Validasi</th>
                                                <th>Dokumen</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($validatedActivities as $activity)
                                                <tr>
                                                    <td>{{ $activity->jenis_label }}</td>
                                                    <td>{{ $activity->nama_aktivitas }}</td>
                                                    <td>{{ $activity->tahun }}</td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check-circle"></i> Divalidasi
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($activity->document_url)
                                                            <x-button size="sm" variant="secondary" type="button" onclick="previewDocument(@json($activity->document_url), @json($activity->nama_aktivitas))">
                                                                <i class="fas fa-eye"></i> Preview
                                                            </x-button>
                                                        @else
                                                            <span class="text-muted">Tidak ada dokumen</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Tidak ada aktivitas yang divalidasi.</p>
                            @endif
                        </x-admin.card-section>

                        <x-admin.card-section title="3. Informasi Pengajuan SKPI">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <x-admin.info-row label="Tanggal Pengajuan">
                                            {{ $submission->submittedAtFormatted ?? '-' }}
                                        </x-admin.info-row>
                                        <x-admin.info-row label="Diajukan Oleh">
                                            {{ $submission->submitter->name }}
                                        </x-admin.info-row>
                                        <x-admin.info-row label="Status Saat Ini">
                                            <x-status-badge :status="$submission->status" />
                                        </x-admin.info-row>
                                        @if($submission->approved_at)
                                            <x-admin.info-row label="Tanggal Approval">
                                                {{ $submission->approvedAtFormatted }}
                                            </x-admin.info-row>
                                            <x-admin.info-row label="Diapprove Oleh">
                                                {{ $submission->approver->name ?? 'N/A' }}
                                            </x-admin.info-row>
                                        @endif
                                        @if($submission->notes)
                                            <x-admin.info-row label="Catatan">
                                                {{ $submission->notes }}
                                            </x-admin.info-row>
                                        @endif
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    @if($submission->status === 'pending')
                                        <div class="text-center">
                                            <p class="text-info mb-3">Pengajuan SKPI ini menunggu approval dari pimpinan.</p>
                                            <form action="{{ route('admin.approve.skpi', $submission->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <x-button type="submit" variant="primary" class="btn-lg" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan SKPI ini?')">
                                                    <i class="fas fa-check"></i> Setujui SKPI
                                                </x-button>
                                            </form>
                                            <form id="rejectForm{{ $submission->id }}" action="{{ route('admin.reject.skpi', $submission->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <div class="mt-2">
                                                    <textarea name="notes" class="form-control" rows="2" placeholder="Catatan penolakan (opsional)" style="display: none;" id="rejectNotes{{ $submission->id }}"></textarea>
                                                </div>
                                                <x-button type="button" variant="danger" class="btn-lg mt-2" onclick="showRejectForm({{ $submission->id }})">
                                                    <i class="fas fa-times"></i> Tolak SKPI
                                                </x-button>
                                                <x-button type="submit" variant="danger" class="btn-lg mt-2" id="submitReject{{ $submission->id }}" style="display: none;" onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan SKPI ini?')">
                                                    <i class="fas fa-paper-plane"></i> Kirim Penolakan
                                                </x-button>
                                            </form>
                                        </div>
                                    @elseif($submission->status === 'approved')
                                        <div class="text-center text-success">
                                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                                            <p>SKPI telah disetujui dan dapat dicetak oleh alumni.</p>
                                        </div>
                                    @else
                                        <div class="text-center text-danger">
                                            <i class="fas fa-times-circle fa-3x mb-3"></i>
                                            <p>SKPI ditolak.</p>
                                            @if($submission->notes)
                                                <p><strong>Alasan:</strong> {{ $submission->notes }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </x-admin.card-section>
                    </div>
                    <div class="modal-footer">
                        <x-button type="button" variant="secondary" data-dismiss="modal">
                            Tutup
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
function previewDocument(url, title) {
    var modalHtml = `
        <div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="documentModalLabel">Preview Dokumen: ${title}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <iframe src="${url}" width="100%" height="500px" style="border: none;"></iframe>
                    </div>
                    <div class="modal-footer">
                        <a href="${url}" target="_blank" class="btn btn-primary">Buka di Tab Baru</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    $('#documentModal').remove();

    $('body').append(modalHtml);

    $('#documentModal').modal('show');
}

function showRejectForm(submissionId) {
    var textarea = document.getElementById('rejectNotes' + submissionId);
    var rejectBtn = document.querySelector('#rejectForm' + submissionId + ' button[type="button"]');
    var submitBtn = document.getElementById('submitReject' + submissionId);

    if (textarea.style.display === 'none' || textarea.style.display === '') {
        textarea.style.display = 'block';
        rejectBtn.style.display = 'none';
        submitBtn.style.display = 'inline-block';
    }
}
</script>
@endsection
