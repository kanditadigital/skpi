<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAlumniProfileRequest;
use App\Services\AlumniProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumniProfileController extends Controller
{
    public function edit(Request $request, AlumniProfileService $service): View
    {
        $profile = $service->getForUser($request->user());

        return view('alumni.profile', [
            'title' => 'Profil Alumni',
            'profile' => $profile,
        ]);
    }

    public function update(UpdateAlumniProfileRequest $request, AlumniProfileService $service): RedirectResponse
    {
        $profile = $service->getForUser($request->user());

        if ($profile?->skpi_submitted) {
            abort(403, 'Profil alumni tidak dapat diubah karena SKPI telah diajukan.');
        }

        $service->updateForUser($request->user(), $request->validated());

        return back()->with('success', 'Profil alumni berhasil disimpan.');
    }
}
