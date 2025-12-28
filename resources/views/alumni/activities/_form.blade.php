@props([
    'action',
    'method' => 'POST',
    'buttonText' => 'Simpan',
    'activity' => null,
    'jenisOptions' => [],
    'statusOptions' => [],
])

@php
    $konfirmasiChecked = filter_var(old('konfirmasi', $activity?->konfirmasi), FILTER_VALIDATE_BOOLEAN);
@endphp
<form action="{{ $action }}" method="POST" class="mb-0 js-require-konfirmasi" enctype="multipart/form-data">
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="row">
        <x-form-group label="Nama Aktivitas" for="nama_aktivitas" class="col-md-6">
            <input id="nama_aktivitas" name="nama_aktivitas" type="text"
                class="form-control @error('nama_aktivitas') is-invalid @enderror"
                value="{{ old('nama_aktivitas', $activity?->nama_aktivitas) }}">
            @error('nama_aktivitas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </x-form-group>
        <x-form-group label="Jenis Aktivitas" for="jenis_aktivitas" class="col-md-6">
            <select id="jenis_aktivitas" name="jenis_aktivitas"
                class="form-control custom-select @error('jenis_aktivitas') is-invalid @enderror">
                <option value="">Pilih:</option>
                @foreach ($jenisOptions as $option)
                    <option value="{{ $option }}"
                        @selected(old('jenis_aktivitas', $activity?->jenis_aktivitas) === $option)>
                        {{ \Illuminate\Support\Str::headline(str_replace('_', ' ', $option)) }}
                    </option>
                @endforeach
            </select>
            @error('jenis_aktivitas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </x-form-group>
        <x-form-group label="Tahun" for="tahun" class="col-md-6">
            <input id="tahun" name="tahun" type="number" min="1900" max="2099"
                class="form-control @error('tahun') is-invalid @enderror"
                value="{{ old('tahun', $activity?->tahun) }}">
            @error('tahun')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </x-form-group>
        <x-form-group label="Bukti" for="bukti_file" class="col-md-6">
            <input id="bukti_file" name="bukti_file" type="file"
                class="form-control @error('bukti_file') is-invalid @enderror">
            @error('bukti_file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if ($activity?->bukti_file)
                <small class="form-text text-muted">
                    PDF sekarang: <a href="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url($activity->bukti_file) }}" target="_blank">Buka</a>
                </small>
            @endif
        </x-form-group>
    </div>
    <div class="form-group">
        <div class="form-check">
            <input type="hidden" name="konfirmasi" value="0">
            <input class="form-check-input @error('konfirmasi') is-invalid @enderror" type="checkbox"
                id="konfirmasi" name="konfirmasi" value="1"
                {{ $konfirmasiChecked ? 'checked' : '' }}>
            <label class="form-check-label" for="konfirmasi">
                Saya menyatakan bahwa data aktivitas ini adalah asli dan benar.
            </label>
            @error('konfirmasi')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <x-button type="submit" variant="primary">
        {{ $buttonText }}
    </x-button>
</form>
@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form.js-require-konfirmasi').forEach(function (form) {
                const checkbox = form.querySelector('input[name="konfirmasi"][type="checkbox"]');
                const submitButton = form.querySelector('button[type="submit"]');
                if (!checkbox || !submitButton) {
                    return;
                }

                const updateButton = function () {
                    submitButton.disabled = !checkbox.checked;
                };

                checkbox.addEventListener('change', updateButton);
                updateButton();
            });
        });
    </script>
@endonce
