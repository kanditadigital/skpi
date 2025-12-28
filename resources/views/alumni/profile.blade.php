@extends('stislaravel::layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="section-body">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h4>Data Profil</h4>
                        </div>
                        <div class="card-body">
                @php
                    $pasFotoUrl = $profile?->pas_foto
                        ? \Illuminate\Support\Facades\Storage::disk('s3')->url($profile->pas_foto)
                        : null;
                    $pasFotoFilename = $profile?->pas_foto ? basename($profile->pas_foto) : null;
                    $isReadOnly = $profile?->skpi_submitted;
                    $pasFotoDropzoneStyle = 'min-height: 140px; cursor: pointer;';

                    if ($isReadOnly) {
                        $pasFotoDropzoneStyle = 'min-height: 140px; cursor: not-allowed; pointer-events: none; opacity: 0.65;';
                    }
                @endphp

                <form action="{{ route('alumni.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($isReadOnly)
                        <div class="alert alert-info mb-3">
                            SKPI telah diajukan, data profil tidak dapat diubah kembali.
                        </div>
                    @endif

                    <div class="row">
                        <x-form-group label="NIM" for="nim" class="col-md-4">
                            <input id="nim" name="nim" type="text"
                                class="form-control @error('nim') is-invalid @enderror"
                                value="{{ old('nim', $profile?->nim) }}" @disabled($isReadOnly)>
                            @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>

                        <x-form-group label="Nama Lengkap" for="nama_lengkap" class="col-md-4">
                            <input id="nama_lengkap" name="nama_lengkap" type="text"
                                class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $profile?->nama_lengkap) }}" @disabled($isReadOnly)>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>

                        <x-form-group label="Program Studi" for="prodi" class="col-md-4">
                            <input id="prodi" name="prodi" type="text"
                                class="form-control @error('prodi') is-invalid @enderror"
                                value="{{ old('prodi', $profile?->prodi) }}" @disabled($isReadOnly)>
                            @error('prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>
                    </div>

                    <div class="row">
                        <x-form-group label="Fakultas" for="fakultas" class="col-md-4">
                            <input id="fakultas" name="fakultas" type="text"
                                class="form-control @error('fakultas') is-invalid @enderror"
                                value="{{ old('fakultas', $profile?->fakultas) }}" @disabled($isReadOnly)>
                            @error('fakultas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>

                        <x-form-group label="Tahun Lulus" for="tahun_lulus" class="col-md-4">
                            <input id="tahun_lulus" name="tahun_lulus" type="number" min="1900" max="2099" step="1"
                                class="form-control @error('tahun_lulus') is-invalid @enderror"
                                value="{{ old('tahun_lulus', $profile?->tahun_lulus) }}" @disabled($isReadOnly)>
                            @error('tahun_lulus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>

                        <x-form-group label="IPK" for="ipk" class="col-md-4">
                            <input id="ipk" name="ipk" type="text" min="0" max="3" step="0.01"
                                class="form-control @error('ipk') is-invalid @enderror"
                                value="{{ old('ipk', $profile?->ipk) }}" @disabled($isReadOnly)>
                            @error('ipk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>
                    </div>

                    <div class="row">
                        <x-form-group label="Nomor Ijazah" for="nomor_ijazah" class="col-md-6">
                            <input id="nomor_ijazah" name="nomor_ijazah" type="text"
                                class="form-control @error('nomor_ijazah') is-invalid @enderror"
                                value="{{ old('nomor_ijazah', $profile?->nomor_ijazah) }}" @disabled($isReadOnly)>
                            @error('nomor_ijazah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>

                        <x-form-group label="Gelar Akademik" for="gelar_akademik" class="col-md-6">
                            <input id="gelar_akademik" name="gelar_akademik" type="text"
                                class="form-control @error('gelar_akademik') is-invalid @enderror"
                                value="{{ old('gelar_akademik', $profile?->gelar_akademik) }}" @disabled($isReadOnly)>
                            @error('gelar_akademik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>
                    </div>

                    <div class="row">
                        <x-form-group label="Tempat Lahir" for="tempat_lahir" class="col-md-4">
                            <input id="tempat_lahir" name="tempat_lahir" type="text"
                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                value="{{ old('tempat_lahir', $profile?->tempat_lahir) }}" @disabled($isReadOnly)>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>

                        <x-form-group label="Tanggal Lahir" for="tanggal_lahir" class="col-md-4">
                            <input id="tanggal_lahir" name="tanggal_lahir" type="date"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir', optional($profile?->tanggal_lahir)->format('Y-m-d')) }}" @disabled($isReadOnly)>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>

                        <x-form-group label="Nomor WA" for="nomor_wa" class="col-md-4">
                            <input id="nomor_wa" name="nomor_wa" type="text"
                                class="form-control @error('nomor_wa') is-invalid @enderror"
                                value="{{ old('nomor_wa', $profile?->nomor_wa) }}" @disabled($isReadOnly)>
                            @error('nomor_wa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>
                    </div>

                    <x-form-group label="Pas Foto 3x4" for="pas_foto">
                        <div class="row">
                            <div class="col-md-9">
                                <div id="pas_foto_dropzone"
                                    class="border border-dashed rounded d-flex flex-column justify-content-center align-items-start p-3 text-secondary"
                                    style="{{ $pasFotoDropzoneStyle }}">
                                    <strong class="mb-1 text-dark">Tarik dan lepas file di sini</strong>
                                    <span class="small text-muted">atau klik untuk memilih pas foto.</span>
                                    <span class="small text-muted mt-1">Format JPG/PNG, maksimal 2 MB.</span>
                                </div>
                                <input id="pas_foto" name="pas_foto" type="file" accept="image/*"
                                    class="d-none @error('pas_foto') is-invalid @enderror" @disabled($isReadOnly)>
                                @error('pas_foto')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <p class="small text-muted mt-2 mb-0" id="pas_foto_filename"
                                    style="{{ $pasFotoFilename ? '' : 'display:none;' }}">
                                    Nama file: <span id="pas_foto_filename_text">{{ $pasFotoFilename ?? '' }}</span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <div id="pas_foto_preview_container"
                                    style="{{ $pasFotoUrl ? '' : 'display:none;' }}">
                                    <p class="text-muted small mb-1">Pratinjau pas foto</p>
                                    <img id="pas_foto_preview"
                                        src="{{ $pasFotoUrl ?? '' }}"
                                        data-default-src="{{ $pasFotoUrl ?? '' }}"
                                        alt="Pas foto 3x4"
                                        class="img-thumbnail"
                                        style="width: 120px; height: 150px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </x-form-group>
                    <div class="form-group form-check mb-4">
                        <input type="hidden" name="konfirmasi" value="0">
                        <input class="form-check-input" type="checkbox" name="konfirmasi" id="confirm_authenticity"
                            value="1"
                            {{ old('konfirmasi', $profile?->konfirmasi) ? 'checked' : '' }} @disabled($isReadOnly)>
                        <label class="form-check-label" for="confirm_authenticity">
                            Saya memastikan data yang saya masukkan adalah asli dan valid.
                        </label>
                    </div>

                    <x-button type="submit" variant="primary" id="profile_save_btn" disabled>
                        Simpan Profil
                    </x-button>
                </form>

                @unless($isReadOnly)
                    <script>
                        (() => {
                            const checkbox = document.getElementById('confirm_authenticity');
                            const submitBtn = document.getElementById('profile_save_btn');

                            if (!checkbox || !submitBtn) {
                                return;
                            }

                            const toggleButton = () => {
                                submitBtn.disabled = !checkbox.checked;
                            };

                            checkbox.addEventListener('change', toggleButton);
                            toggleButton();

                            checkbox.closest('form')?.addEventListener('submit', (event) => {
                                if (!checkbox.checked) {
                                    event.preventDefault();
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Konfirmasi diperlukan',
                                        text: 'Silakan konfirmasi keaslian data terlebih dahulu.',
                                        confirmButtonText: 'Mengerti'
                                    });
                                }
                            });
                        })();
                    </script>

                    <script>
                        (() => {
                            const fileInput = document.getElementById('pas_foto');
                            const dropzone = document.getElementById('pas_foto_dropzone');
                            const preview = document.getElementById('pas_foto_preview');
                            const previewContainer = document.getElementById('pas_foto_preview_container');
                            const filenameEl = document.getElementById('pas_foto_filename');
                            const filenameText = document.getElementById('pas_foto_filename_text');

                            if (!fileInput || !dropzone || !preview || !previewContainer) {
                                return;
                            }

                            const defaultSrc = preview.dataset.defaultSrc || '';
                            const defaultFilename = filenameText?.textContent?.trim() || '';

                            const showFilename = (name) => {
                                if (!filenameEl || !filenameText) {
                                    return;
                                }

                                if (name) {
                                    filenameText.textContent = name;
                                    filenameEl.style.display = '';
                                } else {
                                    filenameEl.style.display = 'none';
                                }
                            };

                            const showPreview = (src, filename) => {
                                if (src) {
                                    preview.src = src;
                                    previewContainer.style.display = '';
                                } else {
                                    previewContainer.style.display = 'none';
                                }

                                showFilename(filename);
                            };

                            const handleFiles = (file) => {
                                if (!file) {
                                    showPreview(defaultSrc, defaultFilename);
                                    return;
                                }

                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);
                                fileInput.files = dataTransfer.files;

                                const url = URL.createObjectURL(file);
                                showPreview(url, file.name);
                            };

                            dropzone.addEventListener('click', () => fileInput.click());

                            ['dragenter', 'dragover'].forEach((event) => {
                                dropzone.addEventListener(event, (e) => {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    dropzone.classList.add('border-primary');
                                });
                            });

                            ['dragleave', 'drop'].forEach((event) => {
                                dropzone.addEventListener(event, () => {
                                    dropzone.classList.remove('border-primary');
                                });
                            });

                            dropzone.addEventListener('drop', (event) => {
                                event.preventDefault();
                                event.stopPropagation();
                                const file = event.dataTransfer?.files?.[0];
                                handleFiles(file);
                            });

                            fileInput.addEventListener('change', () => {
                                const file = fileInput.files?.[0];
                                handleFiles(file);
                            });
                        })();
                    </script>
                @endunless
            </div> {{-- card-body --}}
        </div> {{-- card --}}
    </div> {{-- section-body --}}
</div> {{-- col-12 --}}
</div> {{-- row --}}
</div> {{-- container-fluid --}}
@endsection
