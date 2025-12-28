@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Master Konten SKPI')

@section('content')
    <div class="section-body">
        <style>
            .skpi-section-header {
                background: rgba(255, 255, 255, 0.18);
                border-bottom: none;
                display: block;
                box-shadow: 0 0.5rem 1.2rem rgba(13, 110, 253, 0.1) inset;
            }
            .skpi-section-header .btn-primary {
                background-color: #007FE7;
                border-color: #007FE7;
                font-size: 1rem;
                font-weight: 600;
                color: #fff;
                justify-content: space-between;
                gap: 0.5rem;
            }
            .skpi-section-header .btn-primary small {
                color: rgba(255, 255, 255, 0.85);
            }
            .skpi-section-header .btn-primary:hover,
            .skpi-section-header .btn-primary:focus {
                color: #fff;
                background-color: #0b5ed7;
                border-color: #0a58ca;
                text-decoration: none;
            }
            .skpi-section-card .card-body {
                background: rgba(255, 255, 255, 0.85);
                border-radius: 0 0 0.5rem 0.5rem;
            }
        </style>
        @php
            $kkniItems = $content->kkniEntries();
            if (empty($kkniItems)) {
                $kkniItems[] = ['id' => '', 'en' => ''];
            }
            $institution = $content->institution_info_json ?? [];
        @endphp
        <div class="card shadow">
            <div class="card-header bg-primary">
                <h4>Edit Master Konten SKPI Institusional</h4>
            </div>
                <div class="card-body">
                    <form action="{{ route('admin.skpi-master.update', $content) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div id="skpiCollapseParent">
                            <div class="card mb-3 skpi-section-card">
                                <div class="card-header px-3 py-2 skpi-section-header">
                                    <button class="btn btn-primary btn-block text-left d-flex justify-content-between align-items-center collapsed p-2 rounded-0" type="button" data-toggle="collapse" data-target="#collapseKop" aria-expanded="true" aria-controls="collapseKop">
                                        <div>
                                            <div class="fw-semibold text-white">Kop Surat &amp; Tanda Tangan</div>
                                            <small class="text-white"><em>Unggah kop surat &amp; tanda tangan institusi.</em></small>
                                        </div>
                                        <i class="fas fa-image text-secondary"></i>
                                    </button>
                                </div>
                                <div id="collapseKop" class="collapse show" data-parent="#skpiCollapseParent">
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="border rounded-3 p-3 h-100">
                                                    <x-form-group label="Kop Surat Kampus (jpg|png)" for="kop_surat" class="mb-0">
                                                        <input type="file" name="kop_surat" id="kop_surat" class="form-control">
                                                        @if($content->kop_surat_path)
                                                            <div class="mt-3 border rounded d-flex justify-content-center align-items-center" style="min-height: 160px;">
                                                                <img src="{{ Storage::disk('public')->url($content->kop_surat_path) }}" alt="Kop Surat" class="img-fluid">
                                                            </div>
                                                        @else
                                                            <div class="mt-3 border rounded d-flex justify-content-center align-items-center text-white" style="min-height: 160px;">
                                                                Belum ada kop surat diunggah.
                                                            </div>
                                                        @endif
                                                    </x-form-group>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="border rounded-3 p-3 h-100">
                                                    <x-form-group label="Tanda Tangan (image)" for="leader_signature" class="mb-0">
                                                        <input type="file" name="leader_signature" id="leader_signature" class="form-control">
                                                        @if($content->leader_signature_path)
                                                            <div class="mt-3 border rounded d-flex justify-content-center align-items-center" style="min-height: 160px;">
                                                                <img src="{{ Storage::disk('public')->url($content->leader_signature_path) }}" alt="Tanda Tangan" class="img-fluid">
                                                            </div>
                                                        @else
                                                            <div class="mt-3 border rounded d-flex justify-content-center align-items-center text-white" style="min-height: 160px;">
                                                                Belum ada tanda tangan diunggah.
                                                            </div>
                                                        @endif
                                                    </x-form-group>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3 skpi-section-card">
                                <div class="card-header px-3 py-2 skpi-section-header">
                                    <button class="btn btn-primary btn-block text-left d-flex justify-content-between align-items-center collapsed p-2 rounded-0" type="button" data-toggle="collapse" data-target="#collapseOpening" aria-expanded="false" aria-controls="collapseOpening">
                                        <div>
                                            <div class="fw-semibold text-white">Keterangan Surat SKPI</div>
                                            <small class="text-white"><em>Teks pembuka SKPI dalam dua bahasa</em></small>
                                        </div>
                                        <i class="fas fa-quote-left text-secondary"></i>
                                    </button>
                                </div>
                                <div id="collapseOpening" class="collapse" data-parent="#skpiCollapseParent">
                                    <div class="card-body">
                                        <div class="border rounded-3 p-3">
                                            <div class="row g-3">
                                                <x-form-group label="Bahasa Indonesia" for="opening_text_id" class="col-md-6 mb-0">
                                                    <textarea name="opening_text_id" id="opening_text_id" class="form-control" style="min-height: 120px; height: 120px;">{{ old('opening_text_id', $content->opening_text_id) }}</textarea>
                                                </x-form-group>
                                                <x-form-group label="Bahasa Inggris" for="opening_text_en" class="col-md-6 mb-0">
                                                    <textarea name="opening_text_en" id="opening_text_en" class="form-control" style="min-height: 120px; height: 120px;">{{ old('opening_text_en', $content->opening_text_en) }}</textarea>
                                                </x-form-group>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3 skpi-section-card">
                                <div class="card-header px-3 py-2 skpi-section-header">
                                    <button class="btn btn-primary btn-block text-left d-flex justify-content-between align-items-center collapsed p-2 rounded-0" type="button" data-toggle="collapse" data-target="#collapseInstitution" aria-expanded="false" aria-controls="collapseInstitution">
                                        <div>
                                            <div class="fw-semibold text-white">Informasi Penyelenggara Program</div>
                                            <small class="text-white"><em>Detail perguruan tinggi, program, dan KKNI.</em></small>
                                        </div>
                                        <i class="fas fa-university text-secondary"></i>
                                    </button>
                                </div>
                                <div id="collapseInstitution" class="collapse" data-parent="#skpiCollapseParent">
                                    <div class="card-body">
                                        <div class="border rounded-3 p-3">
                                            <div class="row g-3">
                                                <x-form-group label="SK Pendirian Perguruan Tinggi" for="institution_sk_pendirian" class="col-md-6 mb-0">
                                                    <input type="text" name="institution_sk_pendirian" id="institution_sk_pendirian" class="form-control"
                                                        value="{{ old('institution_sk_pendirian', $institution['sk_pendirian'] ?? '') }}">
                                                </x-form-group>
                                                <x-form-group label="Nama Perguruan Tinggi" for="institution_name" class="col-md-6 mb-0">
                                                    <input type="text" name="institution_name" id="institution_name" class="form-control"
                                                        value="{{ old('institution_name', $institution['name'] ?? '') }}">
                                                </x-form-group>
                                                <x-form-group label="Program Studi" for="institution_program_studi" class="col-md-6 mb-0">
                                                    <input type="text" name="institution_program_studi" id="institution_program_studi" class="form-control"
                                                        value="{{ old('institution_program_studi', $institution['program_studi'] ?? '') }}">
                                                </x-form-group>
                                                <x-form-group label="Jenis & Jenjang Pendidikan" for="institution_jenis_pendidikan" class="col-md-6 mb-0">
                                                    <input type="text" name="institution_jenis_pendidikan" id="institution_jenis_pendidikan" class="form-control"
                                                        value="{{ old('institution_jenis_pendidikan', $institution['jenis_pendidikan'] ?? '') }}">
                                                </x-form-group>
                                                <x-form-group label="Level KKNI" for="institution_level_kkni" class="col-md-4 mb-0">
                                                    <input type="text" name="institution_level_kkni" id="institution_level_kkni" class="form-control"
                                                        value="{{ old('institution_level_kkni', $institution['level_kkni'] ?? '') }}">
                                                </x-form-group>
                                                <x-form-group label="Persyaratan Penerimaan" for="institution_persyaratan" class="col-md-4 mb-0">
                                                    <input type="text" name="institution_persyaratan" id="institution_persyaratan" class="form-control"
                                                        value="{{ old('institution_persyaratan', $institution['persyaratan_penerimaan'] ?? '') }}">
                                                </x-form-group>
                                                <x-form-group label="Bahasa Pengantar" for="institution_bahasa" class="col-md-4 mb-0">
                                                    <input type="text" name="institution_bahasa" id="institution_bahasa" class="form-control"
                                                        value="{{ old('institution_bahasa', $institution['bahasa_pengantar'] ?? '') }}">
                                                </x-form-group>
                                                <x-form-group label="Sistem Penilaian" for="institution_sistem_penilaian" class="col-md-6 mb-0">
                                                    <input type="text" name="institution_sistem_penilaian" id="institution_sistem_penilaian" class="form-control"
                                                        value="{{ old('institution_sistem_penilaian', $institution['sistem_penilaian'] ?? '') }}">
                                                </x-form-group>
                                                <x-form-group label="Lama Studi" for="institution_lama_studi" class="col-md-6 mb-0">
                                                    <input type="text" name="institution_lama_studi" id="institution_lama_studi" class="form-control"
                                                        value="{{ old('institution_lama_studi', $institution['lama_studi'] ?? '') }}">
                                                </x-form-group>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3 skpi-section-card">
                                <div class="card-header px-3 py-2 skpi-section-header">
                                    <button class="btn btn-primary btn-block text-left d-flex justify-content-between align-items-center collapsed p-2 rounded-0" type="button" data-toggle="collapse" data-target="#collapseWork" aria-expanded="false" aria-controls="collapseWork">
                                        <div>
                                            <div class="fw-semibold text-white">Kemampuan Kerja (A1)</div>
                                            <small class="text-white"><em>Kumpulan deskripsi kompetensi kerja.</em></small>
                                        </div>
                                        <i class="fas fa-briefcase text-secondary"></i>
                                    </button>
                                </div>
                                <div id="collapseWork" class="collapse" data-parent="#skpiCollapseParent">
                                    <div class="card-body">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div id="working-capabilities" class="d-flex flex-column gap-3">
                                                @foreach($content->working_capability_json ?? [] as $index => $item)
                                                    <div class="row g-2 align-items-end repeater-row">
                                                        <div class="col-md-5">
                                                            <label class="form-label small mb-1">ID</label>
                                                            <textarea name="working_capability_id[]" class="form-control" rows="3" style="min-height:120px;height:120px;">{{ old("working_capability_id.$index", $item['id'] ?? '') }}</textarea>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label small mb-1">EN</label>
                                                            <textarea name="working_capability_en[]" class="form-control" rows="3" style="min-height:120px;height:120px;">{{ old("working_capability_en.$index", $item['en'] ?? '') }}</textarea>
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <x-button type="button" variant="danger" block class="remove-row">
                                                                Hapus
                                                            </x-button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <x-button variant="secondary" type="button" id="add-working" class="mt-3">
                                                Tambah Kemampuan Kerja
                                            </x-button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3 skpi-section-card">
                                <div class="card-header px-3 py-2 skpi-section-header">
                                    <button class="btn btn-primary btn-block text-left d-flex justify-content-between align-items-center collapsed p-2 rounded-0" type="button" data-toggle="collapse" data-target="#collapseAttitude" aria-expanded="false" aria-controls="collapseAttitude">
                                        <div>
                                            <div class="fw-semibold text-white">Sikap Khusus (A2)</div>
                                            <small class="text-white"><em>Elemen sikap yang membedakan lulusan.</em></small>
                                        </div>
                                        <i class="fas fa-grin-beam text-secondary"></i>
                                    </button>
                                </div>
                                <div id="collapseAttitude" class="collapse" data-parent="#skpiCollapseParent">
                                    <div class="card-body">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div id="special-attitudes" class="d-flex flex-column gap-3">
                                                @foreach($content->special_attitude_json ?? [] as $index => $item)
                                                    <div class="row g-2 align-items-end repeater-row">
                                                        <div class="col-md-5">
                                                            <label class="form-label small mb-1">ID</label>
                                                            <textarea name="special_attitude_id[]" class="form-control" rows="3" style="min-height:120px;height:120px;">{{ old("special_attitude_id.$index", $item['id'] ?? '') }}</textarea>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label small mb-1">EN</label>
                                                            <textarea name="special_attitude_en[]" class="form-control" rows="3" style="min-height:120px;height:120px;">{{ old("special_attitude_en.$index", $item['en'] ?? '') }}</textarea>
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <x-button type="button" variant="danger" block class="remove-row">
                                                                Hapus
                                                            </x-button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <x-button variant="secondary" type="button" id="add-attitude" class="mt-3">
                                                Tambah Sikap Khusus
                                            </x-button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3 skpi-section-card">
                                <div class="card-header px-3 py-2 skpi-section-header">
                                    <button class="btn btn-primary btn-block text-left d-flex justify-content-between align-items-center collapsed p-2 rounded-0" type="button" data-toggle="collapse" data-target="#collapseKkni" aria-expanded="false" aria-controls="collapseKkni">
                                        <div>
                                            <div class="fw-semibold text-white">Kerangka KKNI</div>
                                            <small class="text-white"><em>Deskripsi level kualifikasi nasional.</em></small>
                                        </div>
                                        <i class="fas fa-layer-group text-secondary"></i>
                                    </button>
                                </div>
                                <div id="collapseKkni" class="collapse" data-parent="#skpiCollapseParent">
                                    <div class="card-body">
                                        <div class="border rounded-3 p-3">
                                            <div id="kkni-entries" class="d-flex flex-column gap-3">
                                                @foreach($kkniItems as $index => $item)
                                                    <div class="row g-2 align-items-end repeater-row">
                                                        <div class="col-md-5">
                                                            <label class="form-label small mb-1">Deskripsi Bahasa Indonesia</label>
                                                            <textarea name="kkni_id[]" class="form-control" rows="3" style="min-height: 120px; height: 120px;">{{ old("kkni_id.$index", $item['id'] ?? '') }}</textarea>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label small mb-1">Deskripsi Bahasa Inggris</label>
                                                            <textarea name="kkni_en[]" class="form-control" rows="3" style="min-height: 120px; height: 120px;">{{ old("kkni_en.$index", $item['en'] ?? '') }}</textarea>
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <x-button type="button" variant="danger" block class="remove-row">
                                                                Hapus
                                                            </x-button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <x-button variant="secondary" type="button" id="add-kkni" class="mt-3">
                                                Tambah Kerangka KKNI
                                            </x-button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3 skpi-section-card">
                                <div class="card-header px-3 py-2 skpi-section-header">
                                    <button class="btn btn-primary btn-block text-left d-flex justify-content-between align-items-center collapsed p-2 rounded-0" type="button" data-toggle="collapse" data-target="#collapseLegal" aria-expanded="false" aria-controls="collapseLegal">
                                        <div>
                                            <div class="fw-semibold text-white">Pengesahan SKPI</div>
                                            <small class="text-white"><em>Informasi legalisasi dokumen.</em></small>
                                        </div>
                                        <i class="fas fa-gavel text-secondary"></i>
                                    </button>
                                </div>
                                <div id="collapseLegal" class="collapse" data-parent="#skpiCollapseParent">
                                    <div class="card-body">
                                        <div class="border rounded-3 p-3">
                                            <div class="row g-3">
                                                <x-form-group label="Kota" for="city" class="col-md-4 mb-0">
                                                    <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $content->city) }}">
                                                </x-form-group>
                                                <x-form-group label="Nama Pimpinan" for="leader_name" class="col-md-4 mb-0">
                                                    <input type="text" name="leader_name" id="leader_name" class="form-control" value="{{ old('leader_name', $content->leader_name) }}">
                                                </x-form-group>
                                                <x-form-group label="Jabatan" for="leader_title" class="col-md-4 mb-0">
                                                    <input type="text" name="leader_title" id="leader_title" class="form-control" value="{{ old('leader_title', $content->leader_title) }}">
                                                </x-form-group>
                                                <x-form-group label="NIDN" for="leader_nidn" class="col-md-6 mb-0">
                                                    <input type="text" name="leader_nidn" id="leader_nidn" class="form-control" value="{{ old('leader_nidn', $content->leader_nidn) }}">
                                                </x-form-group>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <x-button type="submit" variant="primary">
                                Simpan Perubahan
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
    </div>

    <template id="repeater-row-template">
        <div class="row g-2 align-items-end repeater-row">
            <div class="col-md-5">
                <label class="form-label small mb-1">ID</label>
                <textarea class="form-control" rows="3" style="min-height:120px;height:120px;"></textarea>
            </div>
            <div class="col-md-5">
                <label class="form-label small mb-1">EN</label>
                <textarea class="form-control" rows="3" style="min-height:120px;height:120px;"></textarea>
            </div>
            <div class="col-md-2 text-end">
                <x-button type="button" variant="danger" block class="remove-row">
                    Hapus
                </x-button>
            </div>
        </div>
    </template>

    <template id="kkni-row-template">
        <div class="row g-2 align-items-end repeater-row">
            <div class="col-md-5">
                <label class="form-label small mb-1">Deskripsi Bahasa Indonesia</label>
                <textarea class="form-control" rows="3" style="min-height: 120px; height: 120px;" data-kkni-id></textarea>
            </div>
            <div class="col-md-5">
                <label class="form-label small mb-1">Deskripsi Bahasa Inggris</label>
                <textarea class="form-control" rows="3" style="min-height: 120px; height: 120px;" data-kkni-en></textarea>
            </div>
            <div class="col-md-2 text-end">
                <x-button type="button" variant="danger" block class="remove-row">
                    Hapus
                </x-button>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const template = document.getElementById('repeater-row-template');
            const kkniTemplate = document.getElementById('kkni-row-template');

            function addRow(containerId, nameId, nameEn) {
                const clone = template.content.cloneNode(true);
                const textareas = clone.querySelectorAll('textarea');
                if (textareas.length >= 2) {
                    textareas[0].setAttribute('name', `${nameId}[]`);
                    textareas[1].setAttribute('name', `${nameEn}[]`);
                }
                document.getElementById(containerId).appendChild(clone);
            }

            function addKkniRow() {
                const container = document.getElementById('kkni-entries');
                if (!kkniTemplate || !container) {
                    return;
                }
                const clone = kkniTemplate.content.cloneNode(true);
                const idField = clone.querySelector('[data-kkni-id]');
                const enField = clone.querySelector('[data-kkni-en]');
                if (idField) {
                    idField.setAttribute('name', 'kkni_id[]');
                }
                if (enField) {
                    enField.setAttribute('name', 'kkni_en[]');
                }
                container.appendChild(clone);
            }

            document.getElementById('add-working').addEventListener('click', function (event) {
                event.preventDefault();
                addRow('working-capabilities', 'working_capability_id', 'working_capability_en');
            });

            document.getElementById('add-attitude').addEventListener('click', function (event) {
                event.preventDefault();
                addRow('special-attitudes', 'special_attitude_id', 'special_attitude_en');
            });

            const addKkniButton = document.getElementById('add-kkni');
            if (addKkniButton) {
                addKkniButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    addKkniRow();
                });
            }

            document.addEventListener('click', function (event) {
                if (!event.target.closest('.remove-row')) {
                    return;
                }
                const row = event.target.closest('.repeater-row');
                if (row) {
                    row.remove();
                }
            });
        });
    </script>
@endsection
