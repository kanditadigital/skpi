<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkpiDocument extends Model
{
    protected $fillable = [
        'skpi_request_id',
        'nomor_skpi',
        'pdf_path',
        'hash',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(SkpiRequest::class);
    }
}
