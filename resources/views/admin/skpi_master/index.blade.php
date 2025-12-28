@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Master Konten SKPI')

@section('content')
    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Konten Aktif SKPI Institusional</h4>
                <div class="card-header-action">
                    <x-button variant="primary" href="{{ route('admin.skpi-master.edit', $content) }}">
                        Edit Konten
                    </x-button>
                </div>
            </div>
            <div class="card-body">
                @php
                    $kkniItems = $content->kkniEntries();
                @endphp
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="text-uppercase text-muted mb-3">Keterangan Surat SKPI</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-muted small">Bahasa Indonesia</dt>
                                <dd class="col-sm-7 mb-2 text-break">{{ $content->opening_text_id ?? '-' }}</dd>
                                <dt class="col-sm-5 text-muted small">Bahasa Inggris</dt>
                                <dd class="col-sm-7 mb-2 text-break">{{ $content->opening_text_en ?? '-' }}</dd>
                            </dl>
                            <div class="mt-4">
                                <h6 class="text-uppercase text-muted mb-2">Kerangka KKNI</h6>
                                @if(count($kkniItems))
                                    <ul class="list-unstyled mb-0">
                                        @foreach($kkniItems as $item)
                                            <li class="mb-3">
                                                <div class="row g-2">
                                                    <div class="col-sm-6">
                                                        <p class="mb-1 text-muted small">Indonesia</p>
                                                        <p class="mb-0 text-break">{{ $item['id'] ?? '-' }}</p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p class="mb-1 text-muted small">English</p>
                                                        <p class="mb-0 text-break">{{ $item['en'] ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mb-0">Belum ada keterangan KKNI yang ditentukan.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="text-uppercase text-muted mb-3">Pengesahan SKPI</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-muted small">Kota</dt>
                                <dd class="col-sm-7 mb-2">{{ $content->city ?? '-' }}</dd>
                                <dt class="col-sm-5 text-muted small">Nama Pimpinan</dt>
                                <dd class="col-sm-7 mb-2 text-break">{{ $content->leader_name ?? '-' }}</dd>
                                <dt class="col-sm-5 text-muted small">Jabatan</dt>
                                <dd class="col-sm-7 mb-2 text-break">{{ $content->leader_title ?? '-' }}</dd>
                                <dt class="col-sm-5 text-muted small">NIDN</dt>
                                <dd class="col-sm-7">{{ $content->leader_nidn ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mt-2">
                    <div class="col-lg-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="text-uppercase text-muted mb-3">Kop Surat Kampus</h6>
                            <div class="d-flex justify-content-center align-items-center border rounded mb-3" style="min-height: 180px; background-color: #f8f9fa;">
                                @if($content->kop_surat_path)
                                    <img src="{{ Storage::disk('public')->url($content->kop_surat_path) }}" alt="Kop Surat" class="img-fluid">
                                @else
                                    <span class="text-muted">Belum ada kop surat diunggah.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="text-uppercase text-muted mb-3">Tanda Tangan Pimpinan</h6>
                            <div class="d-flex justify-content-center align-items-center border rounded mb-3" style="min-height: 180px; background-color: #f8f9fa;">
                                @if($content->leader_signature_path)
                                    <img src="{{ Storage::disk('public')->url($content->leader_signature_path) }}" alt="Tanda Tangan" class="img-fluid">
                                @else
                                    <span class="text-muted">Belum ada tanda tangan diunggah.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
