<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlumniActivityRequest;
use App\Http\Requests\UpdateAlumniActivityRequest;
use App\Models\AlumniActivity;
use App\Models\User;
use App\Services\AlumniActivityService;
use App\Services\AlumniProfileService;
use App\Services\AlumniSkpiService;
use Illuminate\Http\Request;

class AlumniActivityController extends Controller
{
    public function index(Request $request, AlumniActivityService $service, AlumniProfileService $profileService, AlumniSkpiService $skpiService)
    {
        $user = $request->user();
        $canAccessActivities = $profileService->hasValidatedProfile($user);
        $activities = null;

        if ($canAccessActivities) {
            $activities = $service->listForUser($user, $request->only([
                'jenis_aktivitas',
                'status',
                'search',
                'per_page',
            ]));
        }

        $skpiSubmitted = $skpiService->isSkpiSubmitted($user);

        return view('alumni.activities.index', [
            'title' => 'Aktivitas Alumni',
            'activities' => $activities,
            'jenisOptions' => AlumniActivity::JENIS_OPTIONS,
            'statusOptions' => AlumniActivity::STATUS_OPTIONS,
            'canAccessActivities' => $canAccessActivities,
            'skpiSubmitted' => $skpiSubmitted,
        ]);
    }

    public function store(StoreAlumniActivityRequest $request, AlumniActivityService $service, AlumniProfileService $profileService, AlumniSkpiService $skpiService)
    {
        $this->ensureProfileIsValidated($request->user(), $profileService);
        $this->ensureSkpiNotSubmitted($request->user(), $skpiService);

        $service->createForUser($request->user(), $request->validated());

        return back()->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function edit(Request $request, AlumniActivity $activity, AlumniActivityService $service, AlumniProfileService $profileService, AlumniSkpiService $skpiService)
    {
        $this->ensureProfileIsValidated($request->user(), $profileService);
        $this->ensureSkpiNotSubmitted($request->user(), $skpiService);

        $activity = $service->findForUser($request->user(), $activity);

        return view('alumni.activities.edit', [
            'title' => 'Perbarui Aktivitas',
            'activity' => $activity,
            'jenisOptions' => AlumniActivity::JENIS_OPTIONS,
            'statusOptions' => AlumniActivity::STATUS_OPTIONS,
        ]);
    }

    public function update(UpdateAlumniActivityRequest $request, AlumniActivity $activity, AlumniActivityService $service, AlumniProfileService $profileService, AlumniSkpiService $skpiService)
    {
        $this->ensureProfileIsValidated($request->user(), $profileService);
        $this->ensureSkpiNotSubmitted($request->user(), $skpiService);

        $service->updateForUser($request->user(), $activity, $request->validated());

        return redirect()->route('alumni.activities.index')->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(Request $request, AlumniActivity $activity, AlumniActivityService $service, AlumniProfileService $profileService, AlumniSkpiService $skpiService)
    {
        $this->ensureProfileIsValidated($request->user(), $profileService);
        $this->ensureSkpiNotSubmitted($request->user(), $skpiService);

        $service->deleteForUser($request->user(), $activity);

        return back()->with('success', 'Aktivitas berhasil dihapus.');
    }

    public function toggleConfirmation(Request $request, AlumniActivity $activity, AlumniActivityService $service, AlumniProfileService $profileService, AlumniSkpiService $skpiService)
    {
        $this->ensureProfileIsValidated($request->user(), $profileService);
        $this->ensureSkpiNotSubmitted($request->user(), $skpiService);

        $data = $request->validate([
            'konfirmasi' => ['required', 'boolean'],
        ]);

        $service->updateConfirmationForUser($request->user(), $activity, (bool) $data['konfirmasi']);

        return back()->with('success', 'Status konfirmasi aktivitas berhasil diperbarui.');
    }

    protected function ensureProfileIsValidated(User $user, AlumniProfileService $profileService): void
    {
        if (! $profileService->hasValidatedProfile($user)) {
            abort(403, 'Selesaikan profil alumni/tunggu proses validasi profil dari admin prodi');
        }
    }

    protected function ensureSkpiNotSubmitted(User $user, AlumniSkpiService $skpiService): void
    {
        if ($skpiService->isSkpiSubmitted($user)) {
            abort(403, 'Data tidak dapat diubah karena SKPI telah diajukan.');
        }
    }
}
