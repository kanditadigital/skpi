<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkpiSubmission extends Model
{
    protected $fillable = [
        'alumni_profile_id',
        'submitted_by',
        'submitted_at',
        'status',
        'approved_by',
        'approved_at',
        'notes'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function alumniProfile()
    {
        return $this->belongsTo(AlumniProfile::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getSubmittedAtFormattedAttribute(): ?string
    {
        return $this->submitted_at ? $this->submitted_at->format('d/m/Y H:i') : null;
    }

    public function getApprovedAtFormattedAttribute(): ?string
    {
        return $this->approved_at ? $this->approved_at->format('d/m/Y H:i') : null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Approval',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status ?? 'status'),
        };
    }
}
