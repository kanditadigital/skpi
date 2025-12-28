<?php

namespace App\Http\Controllers;

use App\Mail\NewPasswordGenerated;
use App\Models\AlumniActivity;
use App\Models\AlumniProfile;
use App\Models\SkpiDocument;
use App\Models\SkpiRequest;
use App\Models\SkpiSubmission;
use App\Models\User;
use App\Services\SkpiPdfService;
use App\Services\SkpiSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $validatedProfilesCount = AlumniProfile::where('konfirmasi', true)
            ->where('validasi', true)
            ->count();
        $pendingProfilesCount = AlumniProfile::where('konfirmasi', true)
            ->where('validasi', false)
            ->count();
        $totalVerificationRequests = AlumniProfile::where('konfirmasi', true)->count();
        $activityValidationRequests = AlumniActivity::where('status', 'diajukan')
            ->distinct('user_id')
            ->count('user_id');
        $activityValidationApplicants = AlumniProfile::whereHas('user.alumniActivities', function ($query) {
                $query->where('status', 'diajukan');
            })
            ->with('user')
            ->latest('updated_at')
            ->take(5)
            ->get();

        $activityValidatedCount = AlumniActivity::where('validasi', true)->count();
        $activityPendingCount = AlumniActivity::where('status', 'diajukan')
            ->where('validasi', false)
            ->count();

        $skpiSubmissionStats = SkpiSubmission::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $skpiSubmissionsCount = array_sum($skpiSubmissionStats);

        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'validatedProfilesCount' => $validatedProfilesCount,
            'pendingProfilesCount' => $pendingProfilesCount,
            'totalVerificationRequests' => $totalVerificationRequests,
            'activityValidationRequests' => $activityValidationRequests,
            'activityValidationApplicants' => $activityValidationApplicants,
            'activityValidatedCount' => $activityValidatedCount,
            'activityPendingCount' => $activityPendingCount,
            'skpiSubmissionStats' => $skpiSubmissionStats,
            'skpiSubmissionsCount' => $skpiSubmissionsCount,
        ]);
    }

    public function validationRequests()
    {
        $alumniProfiles = AlumniProfile::with([
            'user',
            'user.alumniActivities',
            'user.skpiRequests.document',
            'skpiSubmissions'
        ])
            ->orderBy('validasi', 'asc') // false (belum validasi) akan muncul duluan
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.validation-requests',[
            'title'     => 'Data Alumni',
            'alumniProfiles'    => $alumniProfiles
        ]);
    }

    public function approveValidation($id)
    {
        $profile = AlumniProfile::findOrFail($id);
        $profile->update(['validasi' => true]);

        return redirect()->back()->with('success', 'Validasi data alumni berhasil disetujui.');
    }

    public function rejectValidation($id)
    {
        $profile = AlumniProfile::findOrFail($id);
        $profile->update(['konfirmasi' => false]);

        return redirect()->back()->with('success', 'Permintaan validasi alumni berhasil ditolak.');
    }

    public function approveActivity($id)
    {
        $activity = AlumniActivity::findOrFail($id);
        $activity->update(['validasi' => true, 'status' => 'disetujui']);

        return redirect()->back()->with('success', 'Aktivitas alumni berhasil divalidasi.');
    }

    public function rejectActivity($id)
    {
        $activity = AlumniActivity::findOrFail($id);
        $activity->update(['validasi' => false, 'status' => 'ditolak']);

        return redirect()->back()->with('success', 'Aktivitas alumni berhasil ditolak.');
    }

    public function confirmActivity($id)
    {
        $activity = AlumniActivity::findOrFail($id);
        $activity->update(['konfirmasi' => true]);

        return redirect()->back()->with('success', 'Aktivitas alumni berhasil dikonfirmasi.');
    }

    public function submitSkpi($id)
    {
        $profile = AlumniProfile::findOrFail($id);

        // Check if alumni has submitted SKPI first
        $alumniSubmission = SkpiSubmission::where('alumni_profile_id', $id)
            ->where('submitted_by', $profile->user_id)
            ->where('status', '!=', 'rejected')
            ->orderBy('submitted_at', 'desc')
            ->first();
        if (!$alumniSubmission) {
            return redirect()->back()->with('error', 'Alumni belum mengajukan SKPI.');
        }

        // Check if profile is validated and has validated activities
        if (!$profile->validasi) {
            return redirect()->back()->with('error', 'Data alumni belum divalidasi.');
        }

        $pendingActivities = $profile->user->alumniActivities->where('status', 'diajukan')->count();
        if ($pendingActivities > 0) {
            return redirect()->back()->with('error', 'Masih ada aktivitas yang belum diperiksa.');
        }

        $validatedActivities = $profile->user->alumniActivities->where('status', 'disetujui')->count();
        if ($validatedActivities == 0) {
            return redirect()->back()->with('error', 'Belum ada aktivitas yang divalidasi.');
        }

        // Check if SKPI already submitted to leadership
        $adminSubmission = SkpiSubmission::where('alumni_profile_id', $id)
            ->where('submitted_by', '!=', $profile->user_id)
            ->where('status', '!=', 'rejected')
            ->first();
        if ($adminSubmission) {
            return redirect()->back()->with('error', 'SKPI sudah pernah diajukan ke pimpinan untuk alumni ini.');
        }

        // Create SKPI submission record
        SkpiSubmission::create([
            'alumni_profile_id' => $id,
            'submitted_by' => auth()->user()->id,
            'submitted_at' => now(),
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'SKPI berhasil diajukan ke pimpinan.');
    }

    public function approveSkpi($id)
    {
        $submission = SkpiSubmission::findOrFail($id);

        if ($submission->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan SKPI ini sudah diproses.');
        }

        $submission->update([
            'status' => 'approved',
            'approved_by' => auth()->user()->id,
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'SKPI berhasil disetujui.');
    }

    public function rejectSkpi($id)
    {
        $submission = SkpiSubmission::findOrFail($id);

        if ($submission->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan SKPI ini sudah diproses.');
        }

        $submission->update([
            'status' => 'rejected',
            'approved_by' => auth()->user()->id,
            'approved_at' => now(),
            'notes' => request('notes')
        ]);

        $profile = $submission->alumniProfile;
        if ($profile && Schema::hasColumn('alumni_profiles', 'skpi_submitted')) {
            $profile->update(['skpi_submitted' => false]);
        }

        return redirect()->back()->with('success', 'SKPI berhasil ditolak.');
    }

    public function skpiSubmissions(SkpiSubmissionService $submissionService)
    {
        $skpiSubmissions = $submissionService->getLeadershipSubmissions();

        return view('admin.skpi-submissions', [
            'title'     => 'Data Pengajuan SKPI',
            'skpiSubmissions' => $skpiSubmissions
        ]);
    }

    public function generateSkpi(AlumniProfile $alumniProfile, SkpiPdfService $skpiPdfService)
    {
        $user = $alumniProfile->user;

        if (! $user) {
            return redirect()->back()->with('error', 'Data akun alumni belum lengkap.');
        }

        $adminSubmission = $alumniProfile->skpiSubmissions()
            ->where('submitted_by', '!=', $user->id)
            ->where('status', 'approved')
            ->latest('approved_at')
            ->first();

        if (! $adminSubmission) {
            return redirect()->back()->with('error', 'SKPI belum disetujui pimpinan.');
        }

        DB::transaction(function () use ($user, $skpiPdfService) {
            $skpiRequest = SkpiRequest::create([
                'user_id' => $user->id,
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $pdfResult = $skpiPdfService->generateFor($skpiRequest);

            SkpiDocument::create([
                'skpi_request_id' => $skpiRequest->id,
                'nomor_skpi' => $pdfResult['nomor'],
                'pdf_path' => $pdfResult['path'],
                'hash' => $pdfResult['hash'],
                'issued_at' => $pdfResult['issued_at'],
            ]);
        });

        return redirect()->back()->with('success', 'Dokumen SKPI berhasil dibuat.');
    }

    public function users()
    {
        $users = User::with('roles')->get();

        return view('admin.users.index', [
            'title' => 'Kelola User',
            'users' => $users
        ]);
    }

    public function createUser()
    {
        $roles = Role::whereIn('name', ['admin', 'pimpinan'])->get();

        return view('admin.users.create', [
            'title' => 'Tambah User',
            'roles' => $roles
        ]);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,pimpinan',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        $roles = Role::whereIn('name', ['admin', 'pimpinan'])->get();

        return view('admin.users.edit', [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,pimpinan',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        if ($user->hasRole('super_admin')) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus user super admin.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(12);
        $user->update(['password' => Hash::make($newPassword)]);

        try {
            Mail::to($user->email)->send(new NewPasswordGenerated($user, $newPassword));
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()->with([
                'warning' => 'Password berhasil direset, tetapi pemberitahuan email gagal dikirim.',
            ]);
        }

        return redirect()->back()->with([
            'success' => 'Password berhasil direset. Silakan cek email untuk detail akun.',
        ]);
    }
}
