<?php

namespace App\Http\Controllers;

use App\Models\SkpiMasterContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SkpiMasterContentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $content = SkpiMasterContent::ensureActive();

        return view('admin.skpi_master.index', [
            'title'     => 'Master SKPI',
            'content' => $content,
        ]);
    }

    public function edit(SkpiMasterContent $skpiMasterContent)
    {
        return view('admin.skpi_master.edit', [
            'title'     => 'Edit Master SKPI',
            'content' => $skpiMasterContent,
        ]);
    }

    public function update(Request $request, SkpiMasterContent $skpiMasterContent)
    {
        $validated = $request->validate([
            'kop_surat' => ['nullable', 'image', 'max:3072'],
            'opening_text_id' => ['nullable', 'string'],
            'opening_text_en' => ['nullable', 'string'],
            'institution_sk_pendirian' => ['nullable', 'string'],
            'institution_name' => ['nullable', 'string'],
            'institution_program_studi' => ['nullable', 'string'],
            'institution_jenis_pendidikan' => ['nullable', 'string'],
            'institution_level_kkni' => ['nullable', 'string'],
            'institution_persyaratan' => ['nullable', 'string'],
            'institution_bahasa' => ['nullable', 'string'],
            'institution_sistem_penilaian' => ['nullable', 'string'],
            'institution_lama_studi' => ['nullable', 'string'],
            'working_capability_id' => ['nullable', 'array'],
            'working_capability_en' => ['nullable', 'array'],
            'special_attitude_id' => ['nullable', 'array'],
            'special_attitude_en' => ['nullable', 'array'],
            'kkni_id' => ['nullable', 'array'],
            'kkni_id.*' => ['nullable', 'string'],
            'kkni_en' => ['nullable', 'array'],
            'kkni_en.*' => ['nullable', 'string'],
            'kkni_text_id' => ['nullable', 'string'],
            'kkni_text_en' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'leader_name' => ['nullable', 'string'],
            'leader_title' => ['nullable', 'string'],
            'leader_nidn' => ['nullable', 'string'],
            'leader_signature' => ['nullable', 'image', 'max:3072'],
        ]);

        $institutionInfo = [
            'sk_pendirian' => $validated['institution_sk_pendirian'] ?? '',
            'name' => $validated['institution_name'] ?? '',
            'program_studi' => $validated['institution_program_studi'] ?? '',
            'jenis_pendidikan' => $validated['institution_jenis_pendidikan'] ?? '',
            'level_kkni' => $validated['institution_level_kkni'] ?? '',
            'persyaratan_penerimaan' => $validated['institution_persyaratan'] ?? '',
            'bahasa_pengantar' => $validated['institution_bahasa'] ?? '',
            'sistem_penilaian' => $validated['institution_sistem_penilaian'] ?? '',
            'lama_studi' => $validated['institution_lama_studi'] ?? '',
        ];

        $kkniItems = $this->buildBilingualList(
            $validated['kkni_id'] ?? [],
            $validated['kkni_en'] ?? []
        );

        $attributes = [
            'opening_text_id' => $validated['opening_text_id'] ?? '',
            'opening_text_en' => $validated['opening_text_en'] ?? '',
            'institution_info_json' => $institutionInfo,
            'working_capability_json' => $this->buildBilingualList(
                $validated['working_capability_id'] ?? [],
                $validated['working_capability_en'] ?? []
            ),
            'special_attitude_json' => $this->buildBilingualList(
                $validated['special_attitude_id'] ?? [],
                $validated['special_attitude_en'] ?? []
            ),
            'kkni_items_json' => $kkniItems,
            'kkni_text_id' => $kkniItems[0]['id'] ?? ($validated['kkni_text_id'] ?? ''),
            'kkni_text_en' => $kkniItems[0]['en'] ?? ($validated['kkni_text_en'] ?? ''),
            'city' => $validated['city'] ?? '',
            'leader_name' => $validated['leader_name'] ?? '',
            'leader_title' => $validated['leader_title'] ?? '',
            'leader_nidn' => $validated['leader_nidn'] ?? '',
            'is_active' => true,
        ];

        if ($request->hasFile('kop_surat')) {
            if ($skpiMasterContent->kop_surat_path) {
                Storage::disk('public')->delete($skpiMasterContent->kop_surat_path);
            }
            $attributes['kop_surat_path'] = $request->file('kop_surat')->store('skpi', 'public');
        }

        if ($request->hasFile('leader_signature')) {
            if ($skpiMasterContent->leader_signature_path) {
                Storage::disk('public')->delete($skpiMasterContent->leader_signature_path);
            }
            $attributes['leader_signature_path'] = $request->file('leader_signature')->store('skpi', 'public');
        }

        $skpiMasterContent->update($attributes);

        SkpiMasterContent::where('is_active', true)
            ->where('id', '!=', $skpiMasterContent->id)
            ->update(['is_active' => false]);

        return redirect()->route('admin.skpi-master.index')
            ->with('success', 'Master konten SKPI berhasil diperbarui.');
    }

    protected function buildBilingualList(array $idItems, array $enItems): array
    {
        $list = [];
        $length = max(count($idItems), count($enItems));

        for ($index = 0; $index < $length; $index++) {
            $valueId = $idItems[$index] ?? '';
            $valueEn = $enItems[$index] ?? '';
            $trimId = trim((string) $valueId);
            $trimEn = trim((string) $valueEn);
            if ($trimId === '' && $trimEn === '') {
                continue;
            }
            $list[] = [
                'id' => $trimId,
                'en' => $trimEn,
            ];
        }

        return $list;
    }
}
