<?php

namespace App\Services;

use App\Models\AlumniProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class AlumniProfileService
{
    public function __construct(
        protected AlumniProfile $alumniProfile,
    ) {
    }

    public function getForUser(User $user): ?AlumniProfile
    {
        return $user->alumniProfile ?? $this->alumniProfile->where('user_id', $user->id)->first();
    }

    public function updateForUser(User $user, array $attributes): AlumniProfile
    {
        $profile = $this->getForUser($user);
        $attributes = $this->prepareFileAttribute($attributes, $profile?->pas_foto);

        $payload = $this->payload($attributes);
        $payload['user_id'] = $user->id;

        return $this->alumniProfile->updateOrCreate(['user_id' => $user->id], $payload);
    }

    public function hasValidatedProfile(User $user): bool
    {
        $profile = $this->getForUser($user);

        return $profile !== null && $profile->validasi;
    }

    /**
     * Keep only attributes that are fillable (excluding user_id).
     */
    protected function payload(array $attributes): array
    {
        $fillable = collect($this->alumniProfile->getFillable())
            ->reject(fn ($field) => $field === 'user_id')
            ->values()
            ->all();

        return Arr::only($attributes, $fillable);
    }

    protected function prepareFileAttribute(array $attributes, ?string $currentPath = null): array
    {
        if (! array_key_exists('pas_foto', $attributes)) {
            return $attributes;
        }

        $file = $attributes['pas_foto'];

        if ($file instanceof UploadedFile) {
            $attributes['pas_foto'] = $this->storeFile($file, $currentPath);
        } else {
            unset($attributes['pas_foto']);
        }

        return $attributes;
    }

    protected function storeFile(UploadedFile $file, ?string $currentPath = null): string
    {
        $path = Storage::disk('s3')->putFile('alumni-profiles', $file);

        if ($currentPath && Storage::disk('s3')->exists($currentPath)) {
            Storage::disk('s3')->delete($currentPath);
        }

        return $path;
    }
}
