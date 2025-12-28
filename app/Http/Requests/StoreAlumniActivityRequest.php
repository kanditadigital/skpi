<?php

namespace App\Http\Requests;

use App\Models\AlumniActivity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAlumniActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'jenis_aktivitas' => ['required', Rule::in(AlumniActivity::JENIS_OPTIONS)],
            'nama_aktivitas' => ['required', 'string', 'max:255'],
            'tahun' => ['required', 'digits:4'],
            'bukti_file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'status' => ['nullable', Rule::in(AlumniActivity::STATUS_OPTIONS)],
            'catatan_revisi' => ['nullable', 'string'],
            'konfirmasi' => ['accepted'],
        ];
    }
}
