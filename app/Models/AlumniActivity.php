<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AlumniActivity extends Model
{
    public const JENIS_OPTIONS = [
        'organisasi',
        'seminar_workshop',
        'kepanitiaan',
        'lomba_kompetisi',
        'magang_ppl',
        'pengabdian_masyarakat',
        'keagamaan',
        'lainnya',
    ];

    public const STATUS_OPTIONS = [
        'diajukan',
        'disetujui',
        'ditolak',
    ];

    protected $fillable = [
        'user_id',
        'jenis_aktivitas',
        'nama_aktivitas',
        'tahun',
        'bukti_file',
        'status',
        'catatan_revisi',
        'validasi',
        'konfirmasi'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'konfirmasi' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis_aktivitas) {
            'organisasi' => 'Organisasi',
            'seminar_workshop' => 'Seminar/Workshop',
            'kepanitiaan' => 'Kepanitiaan',
            'lomba_kompetisi' => 'Lomba/Kompetisi',
            'magang_ppl' => 'Magang/PPL',
            'pengabdian_masyarakat' => 'Pengabdian Masyarakat',
            'keagamaan' => 'Keagamaan',
            'lainnya' => 'Lainnya',
            default => ucwords(str_replace('_', ' ', $this->jenis_aktivitas ?? '')),
        };
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->bukti_file ? Storage::disk('s3')->url($this->bukti_file) : null;
    }
}
