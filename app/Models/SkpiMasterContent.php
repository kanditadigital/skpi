<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkpiMasterContent extends Model
{
    protected $fillable = [
        'kop_surat_path',
        'opening_text_id',
        'opening_text_en',
        'institution_info_json',
        'working_capability_json',
        'special_attitude_json',
        'kkni_items_json',
        'kkni_text_id',
        'kkni_text_en',
        'city',
        'leader_name',
        'leader_title',
        'leader_nidn',
        'leader_signature_path',
        'is_active',
    ];

    protected $casts = [
        'institution_info_json' => 'array',
        'working_capability_json' => 'array',
        'special_attitude_json' => 'array',
        'kkni_items_json' => 'array',
        'is_active' => 'boolean',
    ];

    public static function active(): ?self
    {
        return static::where('is_active', true)
            ->latest('updated_at')
            ->first();
    }

    public function kkniEntries(): array
    {
        $items = $this->kkni_items_json ?? [];

        if (empty($items) && ($this->kkni_text_id || $this->kkni_text_en)) {
            $items[] = [
                'id' => $this->kkni_text_id,
                'en' => $this->kkni_text_en,
            ];
        }

        $cleaned = [];

        foreach ($items as $item) {
            $id = trim((string) ($item['id'] ?? ''));
            $en = trim((string) ($item['en'] ?? ''));

            if ($id === '' && $en === '') {
                continue;
            }

            $cleaned[] = [
                'id' => $id,
                'en' => $en,
            ];
        }

        return $cleaned;
    }

    public static function ensureActive(): self
    {
        return static::active() ?? static::create(static::defaultAttributes());
    }

    protected static function defaultAttributes(): array
    {
        $institution = config('skpi.institution', []);

        return [
            'kop_surat_path' => null,
            'opening_text_id' => config('skpi.document.opening_text_id'),
            'opening_text_en' => config('skpi.document.opening_text_en'),
            'institution_info_json' => [
                'sk_pendirian' => $institution['sk_pendirian'] ?? '',
                'name' => $institution['name'] ?? '',
                'program_studi' => $institution['program_studi'] ?? '',
                'jenis_pendidikan' => $institution['jenis_pendidikan'] ?? '',
                'level_kkni' => $institution['level_kkni'] ?? '',
                'persyaratan_penerimaan' => $institution['persyaratan_penerimaan'] ?? '',
                'bahasa_pengantar' => $institution['bahasa_pengantar'] ?? '',
                'sistem_penilaian' => $institution['sistem_penilaian'] ?? '',
                'lama_studi' => $institution['lama_studi'] ?? '',
            ],
            'working_capability_json' => config('skpi.document.working_capability', []),
            'special_attitude_json' => config('skpi.document.special_attitude', []),
            'kkni_items_json' => [[
                'id' => config('skpi.document.kkni_text_id'),
                'en' => config('skpi.document.kkni_text_en'),
            ]],
            'kkni_text_id' => config('skpi.document.kkni_text_id'),
            'kkni_text_en' => config('skpi.document.kkni_text_en'),
            'city' => config('skpi.document.issued_place'),
            'leader_name' => config('skpi.leader.name'),
            'leader_title' => config('skpi.leader.title'),
            'leader_nidn' => config('skpi.leader.nidn'),
            'leader_signature_path' => null,
            'is_active' => true,
        ];
    }
}
