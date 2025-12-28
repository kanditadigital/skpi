<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAlumniProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $profileId = $this->user()->alumniProfile?->id;

        return [
            'nim' => [
                'required',
                'string',
                'max:50',
                Rule::unique('alumni_profiles', 'nim')->ignore($profileId),
            ],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'nomor_wa' => ['nullable', 'string', 'max:30'],
            'prodi' => ['required', 'string', 'max:255'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'tahun_lulus' => ['required', 'digits:4'],
            'ipk' => ['required', 'numeric', 'min:0', 'max:4'],
            'nomor_ijazah' => ['nullable', 'string', 'max:255'],
            'gelar_akademik' => ['nullable', 'string', 'max:255'],
            'konfirmasi' => ['required', 'boolean'],
            'pas_foto' => ['nullable', 'file', 'image', 'max:2048'],
        ];
    }
}
