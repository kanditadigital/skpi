<?php

namespace App\Services;

use App\Models\AlumniActivity;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class AlumniActivityService
{
    private const DEFAULT_PER_PAGE = 15;
    private const STORAGE_DISK = 's3';
    private const FILE_DIRECTORY = 'alumni-activities';

    public function __construct(
        protected AlumniActivity $alumniActivity,
    ) {
    }

    /**
     * Get paginated list of alumni activities for a user with optional filters.
     */
    public function listForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $user->alumniActivities()->latest('created_at');

        $this->applyFilters($query, $filters);

        $perPage = $this->getPerPageFromFilters($filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new alumni activity for the user.
     */
    public function createForUser(User $user, array $attributes): AlumniActivity
    {
        $attributes = $this->prepareFileAttribute($attributes);
        $payload = $this->buildPayload($attributes, $user->id);

        return $this->alumniActivity->create($payload);
    }

    /**
     * Update an existing alumni activity for the user.
     */
    public function updateForUser(User $user, AlumniActivity $activity, array $attributes): AlumniActivity
    {
        $activity = $this->ensureOwnership($user, $activity);
        $this->ensureEditable($activity);

        $attributes = $this->prepareFileAttribute($attributes, $activity->bukti_file);
        $activity->update($this->buildPayload($attributes));

        return $activity;
    }

    /**
     * Update the confirmation status of an alumni activity.
     */
    public function updateConfirmationForUser(User $user, AlumniActivity $activity, bool $isConfirmed): AlumniActivity
    {
        $activity = $this->ensureOwnership($user, $activity);
        $activity->update(['konfirmasi' => $isConfirmed]);

        return $activity;
    }

    /**
     * Delete an alumni activity for the user.
     */
    public function deleteForUser(User $user, AlumniActivity $activity): void
    {
        $activity = $this->ensureOwnership($user, $activity);
        $activity->delete();
    }

    /**
     * Find a specific alumni activity for the user.
     */
    public function findForUser(User $user, AlumniActivity $activity): AlumniActivity
    {
        return $this->ensureOwnership($user, $activity);
    }

    /**
     * Apply filters to the query.
     */
    private function applyFilters($query, array $filters): void
    {
        if ($activityType = Arr::get($filters, 'jenis_aktivitas')) {
            $query->where('jenis_aktivitas', $activityType);
        }

        if ($status = Arr::get($filters, 'status')) {
            $query->where('status', $status);
        }

        if ($search = Arr::get($filters, 'search')) {
            $query->where('nama_aktivitas', 'like', '%' . $search . '%');
        }
    }

    /**
     * Get per page value from filters with validation.
     */
    private function getPerPageFromFilters(array $filters): int
    {
        $perPage = (int) Arr::get($filters, 'per_page', self::DEFAULT_PER_PAGE);

        return max(1, $perPage);
    }

    /**
     * Build payload array for creating/updating activity.
     */
    private function buildPayload(array $attributes, ?int $userId = null): array
    {
        $fillableFields = $this->getFillableFields();
        $payload = Arr::only($attributes, $fillableFields);

        if ($userId) {
            $payload['user_id'] = $userId;
            $payload['status'] = $payload['status'] ?? AlumniActivity::STATUS_OPTIONS[0];
        }

        return $payload;
    }

    /**
     * Get fillable fields excluding user_id.
     */
    private function getFillableFields(): array
    {
        return collect($this->alumniActivity->getFillable())
            ->reject(fn ($field) => $field === 'user_id')
            ->values()
            ->all();
    }

    /**
     * Prepare file attribute for storage.
     */
    private function prepareFileAttribute(array $attributes, ?string $currentPath = null): array
    {
        if (!array_key_exists('bukti_file', $attributes)) {
            return $attributes;
        }

        $file = $attributes['bukti_file'];

        if ($file instanceof UploadedFile) {
            $attributes['bukti_file'] = $this->storeFile($file, $currentPath);
        } else {
            unset($attributes['bukti_file']);
        }

        return $attributes;
    }

    /**
     * Store uploaded file and clean up old file if exists.
     */
    private function storeFile(UploadedFile $file, ?string $currentPath = null): string
    {
        $path = Storage::disk(self::STORAGE_DISK)->putFile(self::FILE_DIRECTORY, $file);

        if ($currentPath && Storage::disk(self::STORAGE_DISK)->exists($currentPath)) {
            Storage::disk(self::STORAGE_DISK)->delete($currentPath);
        }

        return $path;
    }

    /**
     * Ensure the user owns the activity.
     *
     * @throws AuthorizationException
     */
    private function ensureOwnership(User $user, AlumniActivity $activity): AlumniActivity
    {
        if ($activity->user_id !== $user->id) {
            throw new AuthorizationException('This alumni activity does not belong to the authenticated user.');
        }

        return $activity;
    }

    /**
     * Ensure the activity is editable (not confirmed).
     *
     * @throws AuthorizationException
     */
    private function ensureEditable(AlumniActivity $activity): void
    {
        if ($activity->konfirmasi) {
            throw new AuthorizationException('Aktivitas yang sudah dikonfirmasi tidak dapat diubah.');
        }
    }
}
