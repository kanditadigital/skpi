<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SkpiSubmission;
use App\Models\User;

class AlumniProfile extends Model
{
    protected $fillable = [
        'user_id','nim','nama_lengkap','tempat_lahir',
        'tanggal_lahir','nomor_wa','prodi','fakultas',
        'tahun_lulus','ipk','nomor_ijazah','gelar_akademik','konfirmasi','validasi','pas_foto','skpi_submitted'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'ipk' => 'decimal:2',
        'konfirmasi' => 'boolean',
        'validasi' => 'boolean',
        'skpi_submitted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skpiSubmissions()
    {
        return $this->hasMany(SkpiSubmission::class);
    }

    public function getFormattedTanggalLahirAttribute(): string
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d/m/Y') : '-';
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->pas_foto ? asset('storage/' . $this->pas_foto) : null;
    }
}
