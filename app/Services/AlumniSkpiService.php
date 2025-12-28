<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AlumniSkpiService
{
    /**
     * Check if user can request SKPI.
     */
    public function canRequestSkpi(User $user): bool
    {
        return $this->isProfileValidated($user)
            && !$this->isSkpiSubmitted($user)
            && $this->hasConfirmedActivities($user);
    }

    /**
     * Check if user can view SKPI page.
     */
    public function canViewSkpiPage(User $user): bool
    {
        return $this->isSkpiSubmitted($user) || $this->canRequestSkpi($user);
    }

    /**
     * Check if SKPI has been submitted.
     */
    public function isSkpiSubmitted(User $user): bool
    {
        return (bool) optional($user->alumniProfile)->skpi_submitted;
    }

    /**
     * Get the current SKPI status for user.
     */
    public function getSkpiStatus(User $user): ?string
    {
        $profile = $user->alumniProfile;

        if (!$profile) {
            return null;
        }

        $latestSubmission = $profile->skpiSubmissions()->latest()->first();

        return $latestSubmission?->status;
    }

    /**
     * Get the latest SKPI submission for user.
     */
    public function getLatestSkpiSubmission(User $user): ?Model
    {
        $profile = $user->alumniProfile;

        return $profile?->skpiSubmissions()->latest()->first();
    }

    /**
     * Check if user has confirmed activities.
     */
    public function hasConfirmedActivities(User $user): bool
    {
        return $user->alumniActivities()->where('konfirmasi', true)->exists();
    }

    /**
     * Get list of user activities ordered by creation date.
     */
    public function listActivities(User $user)
    {
        return $user->alumniActivities()->latest('created_at')->get();
    }

    /**
     * Submit SKPI request for admin review.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function submit(User $user): void
    {
        if (!$this->canRequestSkpi($user)) {
            abort(403, 'SKPI belum dapat diajukan.');
        }

        $profile = $user->alumniProfile;

        $this->createSkpiSubmission($profile, $user);
        $this->markProfileAsSubmitted($profile);
    }

    /**
     * Create SKPI submission record.
     */
    private function createSkpiSubmission($profile, User $user): void
    {
        $profile->skpiSubmissions()->create([
            'submitted_by' => $user->id,
            'submitted_at' => now(),
            'status' => 'pending',
        ]);
    }

    /**
     * Mark alumni profile as submitted.
     */
    private function markProfileAsSubmitted($profile): void
    {
        if ($profile && Schema::hasColumn('alumni_profiles', 'skpi_submitted')) {
            $profile->update(['skpi_submitted' => true]);
        }
    }

    /**
     * Check if alumni profile is validated.
     */
    private function isProfileValidated(User $user): bool
    {
        return (bool) optional($user->alumniProfile)->validasi;
    }
}
