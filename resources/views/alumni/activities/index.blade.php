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

        @if ($canAccessActivities)
            @if (! $skpiSubmitted)
                <div class="card shadow">
                    <div class="card-header bg-primary">
                        <h4>Tambah Aktivitas Baru</h4>
                    </div>
                    <div class="card-body">
                        @include('alumni.activities._form', [
                            'action' => route('alumni.activities.store'),
                            'method' => 'POST',
                            'buttonText' => 'Simpan Aktivitas',
                            'jenisOptions' => $jenisOptions,
                            'statusOptions' => $statusOptions,
                        ])
                    </div>
                </div>
            @else
                <div class="card shadow">
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            SKPI telah diajukan, data aktivitas tidak dapat diubah atau ditambahkan lagi.
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                    <h4>Daftar Aktivitas</h4>
                    <span class="badge badge-warning">{{ $activities->total() }} aktivitas</span>
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
                        <table class="table table-bordered table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Nama Aktivitas</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    @unless($skpiSubmitted)
                                        <th>Aksi</th>
                                    @endunless
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
                                        @unless($skpiSubmitted)
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        @unless($activity->konfirmasi)
                                                            <x-button size="sm" variant="secondary" class="mb-1" href="{{ route('alumni.activities.edit', $activity) }}">
                                                                Edit
                                                            </x-button>
                                                            <form action="{{ route('alumni.activities.confirmation', $activity) }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="konfirmasi" value="1">
                                                                <x-button size="sm" variant="primary" class="mb-1" type="submit"
                                                                    onclick="return confirm('Konfirmasi aktivitas ini?')">
                                                                    Konfirmasi
                                                                </x-button>
                                                            </form>
                                                        @endunless
                                                    </div>
                                                    <form action="{{ route('alumni.activities.destroy', $activity) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-button size="sm" variant="danger" type="submit"
                                                            onclick="return confirm('Hapus aktivitas ini?')">
                                                            Hapus
                                                        </x-button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endunless
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $skpiSubmitted ? 4 : 5 }}" class="text-center text-muted">Belum ada aktivitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $activities->onEachSide(1)->links() }}
                </div>
            </div>
        @else
            <div class="card shadow">
                <div class="card-body">
                    <div class="alert alert-warning text-center mb-0">
                        Selesaikan profil alumni/tunggu proses validasi profil dari admin prodi
                    </div>
                </div>
            </div>
        @endif
                </div>
            </div>
        </div>
    </div>
@endsection
