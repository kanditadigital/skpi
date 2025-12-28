<?php

namespace App\Services;

use App\Models\SkpiSubmission;
use Illuminate\Pagination\LengthAwarePaginator;

class SkpiSubmissionService
{
    private const DEFAULT_PER_PAGE = 20;

    public function getLeadershipSubmissions(int $perPage = self::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return SkpiSubmission::with([
            'alumniProfile.user.alumniActivities' => function ($query) {
                $query->where('konfirmasi', true)->where('validasi', true);
            },
            'submitter',
            'approver',
        ])
            ->join('alumni_profiles', 'alumni_profiles.id', '=', 'skpi_submissions.alumni_profile_id')
            ->whereColumn('skpi_submissions.submitted_by', '!=', 'alumni_profiles.user_id')
            ->orderBy('skpi_submissions.submitted_at', 'desc')
            ->select('skpi_submissions.*')
            ->paginate($perPage);
    }
}
