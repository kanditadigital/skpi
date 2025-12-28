<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
            }

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => '',
                    'password' => bcrypt(uniqid()),
                ]);
            } else {
                $updated = false;

                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $updated = true;
                }

                if ($updated) {
                    $user->save();
                }
            }

            $this->syncGoogleAvatar($user, $googleUser->avatar);

            $roleName = 'alumni';
            Role::firstOrCreate(['name' => $roleName]);

            if (!$user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }

            Auth::login($user);

            return redirect('/dashboard');
        } catch (\Exception $e) {
            return redirect('/')->withErrors('Google login failed');
        }
    }

    private function syncGoogleAvatar(User $user, ?string $avatarUrl): void
    {
        if (!$avatarUrl) {
            return;
        }

        $avatarPath = $this->downloadGoogleAvatar($user, $avatarUrl);

        if (!$avatarPath) {
            return;
        }

        $url = Storage::disk('public')->url($avatarPath);

        if ($user->avatar !== $url) {
            $user->avatar = $url;
            $user->save();
        }
    }

    private function downloadGoogleAvatar(User $user, string $avatarUrl): ?string
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Accept' => 'image/*',
                ])
                ->get($avatarUrl);
        } catch (\Throwable $e) {
            return null;
        }

        if (!$response->successful() || ! $response->body()) {
            return null;
        }

        $extension = $this->guessExtensionFromContentType($response->header('Content-Type'));
        $filename = "avatar.{$extension}";
        $path = "avatars/{$user->id}/{$filename}";

        Storage::disk('public')->put($path, $response->body());

        return $path;
    }

    private function guessExtensionFromContentType(?string $contentType): string
    {
        if (! $contentType || strpos($contentType, '/') === false) {
            return 'jpg';
        }

        [, $subtype] = explode('/', $contentType, 2);
        $clean = strtolower(preg_replace('/[^a-z0-9]/', '', $subtype));

        return $clean ?: 'jpg';
    }
}
