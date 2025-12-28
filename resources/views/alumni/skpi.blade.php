@extends('stislaravel::layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="section-body">
        @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Ajukan SKPI</h4>
                <p class="mb-0 small">Periksa kembali data profil, akademik, dan aktivitas sebelum mengajukan SKPI.</p>
            </div>
            <div class="card-body">
                @if ($skpiSubmitted)
                    @if($skpiStatus === 'approved')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong>SKPI Disetujui!</strong><br>
                            SKPI Anda telah disetujui pada {{ $latestSubmission->approved_at->format('d/m/Y H:i') }}
                            @if($latestSubmission->approver)
                                oleh {{ $latestSubmission->approver->name }}
                            @endif
                            <br>Silakan cetak SKPI Anda.
                        </div>
                    @elseif($skpiStatus === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i> <strong>SKPI Ditolak</strong><br>
                            SKPI Anda ditolak pada {{ $latestSubmission->approved_at->format('d/m/Y H:i') }}
                            @if($latestSubmission->notes)
                                <br><strong>Alasan:</strong> {{ $latestSubmission->notes }}
                            @endif
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-clock"></i> SKPI telah diajukan pada {{ $latestSubmission->submitted_at->format('d/m/Y H:i') }}
                            dan sedang menunggu approval dari admin/pimpinan.
                        </div>
                    @endif
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-header bg-light">
                                <strong>Data Alumni</strong>
                            </div>
                            <div class="card-body p-3">
                                <dl class="row mb-0">
                                    <dt class="col-5">NIM</dt>
                                    <dd class="col-7">{{ $profile?->nim ?? '-' }}</dd>
                                    <dt class="col-5">Nama Lengkap</dt>
                                    <dd class="col-7">{{ $profile?->nama_lengkap ?? '-' }}</dd>
                                    <dt class="col-5">Tempat Lahir</dt>
                                    <dd class="col-7">{{ $profile?->tempat_lahir ?? '-' }}</dd>
                                    <dt class="col-5">Tanggal Lahir</dt>
                                    <dd class="col-7">
                                        {{ optional($profile?->tanggal_lahir)->format('d-m-Y') ?? '-' }}
                                    </dd>
                                    <dt class="col-5">Nomor WA</dt>
                                    <dd class="col-7">{{ $profile?->nomor_wa ?? '-' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-header bg-light">
                                <strong>Data Akademik</strong>
                            </div>
                            <div class="card-body p-3">
                                <dl class="row mb-0">
                                    <dt class="col-5">Program Studi</dt>
                                    <dd class="col-7">{{ $profile?->prodi ?? '-' }}</dd>
                                    <dt class="col-5">Fakultas</dt>
                                    <dd class="col-7">{{ $profile?->fakultas ?? '-' }}</dd>
                                    <dt class="col-5">Tahun Lulus</dt>
                                    <dd class="col-7">{{ $profile?->tahun_lulus ?? '-' }}</dd>
                                    <dt class="col-5">IPK</dt>
                                    <dd class="col-7">{{ $profile?->ipk ?? '-' }}</dd>
                                    <dt class="col-5">Nomor Ijazah</dt>
                                    <dd class="col-7">{{ $profile?->nomor_ijazah ?? '-' }}</dd>
                                    <dt class="col-5">Gelar Akademik</dt>
                                    <dd class="col-7">{{ $profile?->gelar_akademik ?? '-' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border mb-3">
                    <div class="card-header bg-light">
                        <strong>Data Aktivitas</strong>
                    </div>
                    <div class="card-body">
                        @php
                            $statusClasses = [
                                'diajukan' => 'badge-warning',
                                'disetujui' => 'badge-success',
                                'ditolak' => 'badge-danger',
                            ];
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Jenis</th>
                                        <th>Nama Aktivitas</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>Validasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activities as $activity)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Str::headline(str_replace('_', ' ', $activity->jenis_aktivitas)) }}</td>
                                            <td>{{ $activity->nama_aktivitas }}</td>
                                            <td>{{ $activity->tahun }}</td>
                                            <td>
                                                <span class="badge {{ $statusClasses[$activity->status] ?? 'badge-secondary' }}">
                                                    {{ \Illuminate\Support\Str::headline($activity->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $activity->validasi ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $activity->validasi ? 'Tervalidasi' : 'Belum' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada aktivitas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if ($canRequestSkpi && ! $skpiSubmitted)
                    <form action="{{ route('alumni.skpi.store') }}" method="POST" class="mt-3" id="skpi-confirm-form">
                        @csrf
                        <div class="form-group form-check mb-3">
                            <input type="hidden" name="confirm_data" value="0">
                            <input class="form-check-input" type="checkbox" name="confirm_data" id="confirm_skpi_data"
                                value="1">
                            <label class="form-check-label" for="confirm_skpi_data">
                                Saya memastikan data yang akan diajukan ini adalah data sebenarnya.
                            </label>
                        </div>
                        <x-button type="submit" variant="primary" id="skpi-submit-btn" disabled>
                            Ajukan SKPI
                        </x-button>
                    </form>
                @endif

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const checkbox = document.getElementById('confirm_skpi_data');
                        const submitBtn = document.getElementById('skpi-submit-btn');

                        if (!checkbox || !submitBtn) {
                            return;
                        }

                        const toggleButton = function () {
                            submitBtn.disabled = !checkbox.checked;
                        };

                        checkbox.addEventListener('change', toggleButton);
                        toggleButton();
                    });
                </script>
            </div> {{-- section-body --}}
        </div> {{-- col-12 --}}
    </div> {{-- row --}}
</div> {{-- container-fluid --}}
@endsection
