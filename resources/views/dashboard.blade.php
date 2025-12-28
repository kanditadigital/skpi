@extends('stislaravel::layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="section-body">
                    @if(auth()->user()->hasRole('alumni'))
                        <!-- SKPI Status Card for Alumni -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card shadow">
                                    <div class="card-header bg-primary text-white">
                                        <h4><i class="fas fa-graduation-cap"></i> Status SKPI</h4>
                                    </div>
                                    <div class="card-body">
                                        @if($skpiSubmitted)
                                            @if($skpiStatus === 'approved')
                                                <div class="alert alert-success">
                                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                                    <h5>SKPI Disetujui!</h5>
                                                    <p class="mb-1">SKPI Anda telah disetujui pada {{ $latestSubmission->approved_at->format('d/m/Y H:i') }}</p>
                                                    @if($latestSubmission->approver)
                                                        <small>Oleh: {{ $latestSubmission->approver->name }}</small>
                                                    @endif
                                                </div>
                                            @elseif($skpiStatus === 'rejected')
                                                <div class="alert alert-danger">
                                                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                                                    <h5>SKPI Ditolak</h5>
                                                    <p class="mb-1">SKPI Anda ditolak pada {{ $latestSubmission->approved_at->format('d/m/Y H:i') }}</p>
                                                    @if($latestSubmission->notes)
                                                        <p class="mb-0"><strong>Alasan:</strong> {{ $latestSubmission->notes }}</p>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                                    <h5>Menunggu Approval</h5>
                                                    <p class="mb-1">SKPI Anda telah diajukan pada {{ $latestSubmission->submitted_at->format('d/m/Y H:i') }}</p>
                                                    <small>Sedang menunggu approval dari admin/pimpinan</small>
                                                </div>
                                            @endif
                                        @elseif($canRequestSkpi)
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                                <h5>Siap Mengajukan SKPI</h5>
                                                <p class="mb-2">Data profil dan aktivitas Anda telah lengkap. Anda dapat mengajukan SKPI sekarang.</p>
                                                <x-button variant="primary" href="{{ route('alumni.skpi.index') }}">
                                                    <i class="fas fa-paper-plane"></i> Ajukan SKPI
                                                </x-button>
                                            </div>
                                        @else
                                            <div class="alert alert-secondary">
                                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                <h5>SKPI Belum Dapat Diajukan</h5>
                                                <p class="mb-1">Pastikan data profil dan aktivitas Anda telah divalidasi oleh admin.</p>
                                                <small>Periksa menu "Profil Alumni" dan "Aktivitas Alumni" untuk melengkapi data.</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
