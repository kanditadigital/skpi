@extends('layouts.main')

@section('title', 'Data Alumni')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <h4>Data Alumni</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($alumniProfiles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>NIM</th>
                                        <th>Nama Lengkap</th>
                                        <th>Program Studi</th>
                                        <th>Status Validasi</th>
                                        <th>Status SKPI</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alumniProfiles as $alumni)
                                        <tr>
                                            <td>{{ $alumni->nim }}</td>
                                            <td>{{ $alumni->nama_lengkap }}</td>
                                            <td>{{ $alumni->prodi }}</td>
                                            <td>
                                                @if($alumni->validasi)
                                                    <span class="badge badge-success">Valid</span>
                                                @elseif($alumni->konfirmasi)
                                                    <span class="badge badge-warning">Menunggu Validasi</span>
                                                @else
                                                    <span class="badge badge-secondary">Belum Konfirmasi</span>
                                                @endif
                                            </td>
                                            <td>
                                            @if($alumni->skpiSubmissions && $alumni->skpiSubmissions->count() > 0)
                                                @php
                                                    $skpiSubmission = $alumni->skpiSubmissions->sortByDesc('submitted_at')->first();
                                                @endphp
                                                    @if($skpiSubmission->status === 'approved')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Disetujui
                                                        </span>
                                                    @elseif($skpiSubmission->status === 'rejected')
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-times"></i> Ditolak
                                                        </span>
                                                    @else
                                                        <span class="badge badge-info">
                                                            <i class="fas fa-clock"></i> Menunggu Approval
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-light">Belum Diajukan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <x-button size="sm" variant="secondary" type="button" data-toggle="modal" data-target="#modalDetail{{ $alumni->id }}">
                                                    Preview
                                                </x-button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($alumniProfiles->hasPages())
                            <div class="mt-3 d-flex justify-content-end">
                                {{ $alumniProfiles->links() }}
                            </div>
                        @endif
                    @else
                        <p>Tidak ada data alumni.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for alumni details -->
    @foreach($alumniProfiles as $alumni)
    <div class="modal fade" id="modalDetail{{ $alumni->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel{{ $alumni->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel{{ $alumni->id }}">Detail Data Alumni - {{ $alumni->nama_lengkap }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Section 1: Validasi Data Diri dan Akademik -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">1. Validasi Data Diri dan Akademik</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    @if($alumni->pas_foto)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $alumni->pas_foto) }}" alt="Pas Foto" class="img-thumbnail" style="max-width: 150px; max-height: 200px;">
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
                                                <tr>
                                                    <td width="30%"><strong>NIM</strong></td>
                                                    <td>: {{ $alumni->nim }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nama Lengkap</strong></td>
                                                    <td>: {{ $alumni->nama_lengkap }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tempat Lahir</strong></td>
                                                    <td>: {{ $alumni->tempat_lahir }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Lahir</strong></td>
                                                    <td>: {{ $alumni->tanggal_lahir ? $alumni->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nomor WA</strong></td>
                                                    <td>: {{ $alumni->nomor_wa }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="border-bottom pb-2 mb-3">Data Akademik</h6>
                                            <table class="table table-borderless table-sm">
                                                <tr>
                                                    <td width="30%"><strong>Program Studi</strong></td>
                                                    <td>: {{ $alumni->prodi }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Fakultas</strong></td>
                                                    <td>: {{ $alumni->fakultas }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tahun Lulus</strong></td>
                                                    <td>: {{ $alumni->tahun_lulus }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>IPK</strong></td>
                                                    <td>: {{ $alumni->ipk }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nomor Ijazah</strong></td>
                                                    <td>: {{ $alumni->nomor_ijazah }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Gelar Akademik</strong></td>
                                                    <td>: {{ $alumni->gelar_akademik }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                    <div class="text-center mt-3">
                                        @if(!$alumni->validasi && $alumni->konfirmasi)
                                            <form action="{{ route('admin.validation.approve', $alumni->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <x-button type="submit" variant="primary">
                                                    Setujui Validasi Data
                                                </x-button>
                                            </form>
                                            <form action="{{ route('admin.validation.reject', $alumni->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <x-button type="submit" variant="danger" onclick="return confirm('Apakah Anda yakin ingin menolak validasi data ini?')">
                                                    Tolak Validasi Data
                                                </x-button>
                                            </form>
                                        @elseif($alumni->validasi)
                                            <span class="badge badge-success p-2"><i class="fas fa-check-circle"></i> Data Sudah Divalidasi</span>
                                            <form action="{{ route('admin.validation.reject', $alumni->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <x-button size="sm" variant="secondary" type="submit" class="ml-2" onclick="return confirm('Apakah Anda yakin ingin membatalkan validasi ini?')">
                                                    Batalkan Validasi
                                                </x-button>
                                            </form>
                                        @else
                                            <span class="badge badge-secondary p-2">Belum Mengajukan Validasi Data</span>
                                        @endif
                                    </div>
                        </div>
                    </div>

                    <!-- Section 2: Validasi Setiap Aktivitas -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">2. Validasi Setiap Aktivitas</h6>
                        </div>
                        <div class="card-body">
                            @if($alumni->user && $alumni->user->alumniActivities->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Jenis Aktivitas</th>
                                                <th>Nama Aktivitas</th>
                                                <th>Tahun</th>
                                                <th>Status</th>
                                                <th>Dokumen</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($alumni->user->alumniActivities as $activity)
                                                <tr>
                                                    <td>
                                                        @switch($activity->jenis_aktivitas)
                                                            @case('organisasi')
                                                                Organisasi
                                                                @break
                                                            @case('seminar_workshop')
                                                                Seminar/Workshop
                                                                @break
                                                            @case('kepanitiaan')
                                                                Kepanitiaan
                                                                @break
                                                            @case('lomba_kompetisi')
                                                                Lomba/Kompetisi
                                                                @break
                                                            @case('magang_ppl')
                                                                Magang/PPL
                                                                @break
                                                            @case('pengabdian_masyarakat')
                                                                Pengabdian Masyarakat
                                                                @break
                                                            @case('keagamaan')
                                                                Keagamaan
                                                                @break
                                                            @default
                                                                {{ ucfirst($activity->jenis_aktivitas) }}
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $activity->nama_aktivitas }}</td>
                                                    <td>{{ $activity->tahun }}</td>
                                                    <td>
                                                        @if(!$activity->konfirmasi)
                                                            <span class="badge badge-secondary">Belum Konfirmasi</span>
                                                        @elseif($activity->validasi)
                                                            <span class="badge badge-success">Disetujui</span>
                                                        @elseif($activity->status === 'ditolak')
                                                            <span class="badge badge-danger">Ditolak</span>
                                                        @else
                                                            <span class="badge badge-warning">Menunggu Validasi</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($activity->bukti_file)
                                                            <x-button size="sm" variant="secondary" type="button" onclick='previewDocument(@json(\Illuminate\Support\Facades\Storage::disk("s3")->url($activity->bukti_file)), @json($activity->nama_aktivitas))'>
                                                                <i class="fas fa-eye"></i> Preview
                                                            </x-button>
                                                        @else
                                                            <span class="text-muted">Tidak ada dokumen</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!$activity->konfirmasi)
                                                            <form action="{{ route('admin.activity.confirm', $activity->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                <x-button size="sm" variant="secondary" type="submit" title="Konfirmasi Aktivitas">
                                                                    <i class="fas fa-check-double"></i> Konfirmasi
                                                                </x-button>
                                                            </form>
                                                        @elseif(!$activity->validasi && $activity->status !== 'ditolak')
                                                            <form action="{{ route('admin.activity.approve', $activity->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                <x-button size="sm" variant="primary" type="submit" title="Setujui Aktivitas">
                                                                    <i class="fas fa-check"></i>
                                                                </x-button>
                                                            </form>
                                                            <form action="{{ route('admin.activity.reject', $activity->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                <x-button size="sm" variant="danger" type="submit" title="Tolak Aktivitas" onclick="return confirm('Apakah Anda yakin ingin menolak aktivitas ini?')">
                                                                    <i class="fas fa-times"></i>
                                                                </x-button>
                                                            </form>
                                                        @elseif($activity->validasi)
                                                            <span class="text-success"><i class="fas fa-check-circle"></i> Tervalidasi</span>
                                                        @else
                                                            <span class="text-danger"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Belum ada aktivitas yang diajukan.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Section 3: Ajukan SKPI ke Pimpinan -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">3. Ajukan SKPI ke Pimpinan</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                @php
                                    $hasValidatedActivities = $alumni->user && $alumni->user->alumniActivities->where('status', 'disetujui')->count() > 0;
                                    $alumniUserId = $alumni->user?->id;
                                    $alumniSubmission = $alumniUserId
                                        ? $alumni->skpiSubmissions
                                            ->where('submitted_by', $alumniUserId)
                                            ->where('status', '!=', 'rejected')
                                            ->sortByDesc('submitted_at')
                                            ->first()
                                        : null;
                                    $adminSubmission = $alumniUserId
                                        ? $alumni->skpiSubmissions
                                            ->where('submitted_by', '!=', $alumniUserId)
                                            ->where('status', '!=', 'rejected')
                                            ->sortByDesc('submitted_at')
                                            ->first()
                                        : null;
                                    $latestSkpiRequest = $alumni->user
                                        ? $alumni->user->skpiRequests->sortByDesc('created_at')->first()
                                        : null;
                                    $latestSkpiDocument = $latestSkpiRequest?->document;
                                @endphp

                                @if($adminSubmission)
                                    @if($adminSubmission->status === 'pending')
                                        <p class="text-info mb-3">
                                            <i class="fas fa-clock"></i> SKPI sudah diajukan ke pimpinan pada {{ $adminSubmission->submitted_at->format('d/m/Y H:i') }}
                                            dan sedang menunggu approval.
                                        </p>
                                    @elseif($adminSubmission->status === 'approved')
                                        <p class="text-success mb-3">
                                            <i class="fas fa-check-circle"></i> SKPI sudah disetujui pimpinan pada {{ $adminSubmission->approved_at->format('d/m/Y H:i') }}
                                        </p>
                                        @if($latestSkpiDocument)
                                            <p class="text-muted mb-2 small">
                                                Dokumen SKPI terakhir dibuat pada {{ $latestSkpiDocument->issued_at->format('d/m/Y H:i') }}
                                                dengan nomor <strong>{{ $latestSkpiDocument->nomor_skpi }}</strong>.
                                            </p>
                                        @endif
                                        <form action="{{ route('admin.generate.skpi', $alumni->id) }}" method="POST">
                                            @csrf
                                            <x-button type="submit" variant="primary" class="btn-lg">
                                                <i class="fas fa-print"></i> Generate SKPI
                                            </x-button>
                                        </form>
                                    @else
                                        <p class="text-danger">
                                            <i class="fas fa-times-circle"></i> SKPI ditolak pimpinan
                                            @if($adminSubmission->notes)
                                                <br><small>Catatan: {{ $adminSubmission->notes }}</small>
                                            @endif
                                        </p>
                                    @endif
                                @elseif($alumniSubmission)
                                    <p class="text-info mb-3">
                                        <i class="fas fa-info-circle"></i> Alumni telah mengajukan SKPI ke admin pada {{ $alumniSubmission->submitted_at->format('d/m/Y H:i') }}.
                                        Silakan tinjau data di atas sebelum meneruskan ke pimpinan.
                                    </p>
                                    @if($alumni->validasi && $hasValidatedActivities)
                                        <form action="{{ route('admin.submit.skpi', $alumni->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <x-button type="submit" variant="primary" class="btn-lg" onclick="return confirm('Apakah Anda yakin ingin mengajukan SKPI alumni ini ke pimpinan?')">
                                                <i class="fas fa-paper-plane"></i> Ajukan SKPI ke Pimpinan
                                            </x-button>
                                        </form>
                                    @else
                                        <p class="text-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Data diri atau aktivitas belum lengkap divalidasi atau belum selesai diperiksa keasliannya.
                                            Pastikan seluruh aktivitas telah diverifikasi terlebih dahulu sebelum diteruskan ke pimpinan.
                                        </p>
                                    @endif
                                @else
                                    <p class="text-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Alumni belum mengajukan SKPI ke admin.
                                        Mohon konfirmasi data terlebih dahulu agar pengajuan bisa dilanjutkan.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
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
    // Create modal for document preview
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

    // Remove existing modal if any
    $('#documentModal').remove();

    // Add modal to body
    $('body').append(modalHtml);

    // Show modal
    $('#documentModal').modal('show');
}
</script>
@endsection
