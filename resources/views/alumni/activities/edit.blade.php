@extends('stislaravel::layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="section-body">
                    <div class="section-title">
                        <h2>Perbarui Aktivitas</h2>
                        <p>Sesuaikan kembali data aktivitas alumni Anda.</p>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>{{ $activity->nama_aktivitas }}</h4>
                            <x-button size="sm" variant="secondary" href="{{ route('alumni.activities.index') }}">
                                Kembali ke daftar
                            </x-button>
                        </div>
                        <div class="card-body">
                            @include('alumni.activities._form', [
                                'action' => route('alumni.activities.update', $activity),
                                'method' => 'PUT',
                                'buttonText' => 'Perbarui Aktivitas',
                                'activity' => $activity,
                                'jenisOptions' => $jenisOptions,
                                'statusOptions' => $statusOptions,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
