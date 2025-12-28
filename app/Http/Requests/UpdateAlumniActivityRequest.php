<?php

namespace App\Http\Requests;

use App\Models\AlumniActivity;
use Illuminate\Validation\Rule;

class UpdateAlumniActivityRequest extends StoreAlumniActivityRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['bukti_file'] = ['sometimes', 'file', 'mimes:pdf', 'max:2048'];
        $rules['status'] = ['sometimes', Rule::in(AlumniActivity::STATUS_OPTIONS)];

        return $rules;
    }
}
