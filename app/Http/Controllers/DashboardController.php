<?php

namespace App\Http\Controllers;

use App\Services\AlumniSkpiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, AlumniSkpiService $skpiService)
    {
        $user = $request->user();
        $data = ['title' => 'Dashboard'];

        // Add SKPI information for alumni users
        if ($user && $user->hasRole('alumni')) {
            $data['skpiSubmitted'] = $skpiService->isSkpiSubmitted($user);
            $data['skpiStatus'] = $skpiService->getSkpiStatus($user);
            $data['latestSubmission'] = $skpiService->getLatestSkpiSubmission($user);
            $data['canRequestSkpi'] = $skpiService->canRequestSkpi($user);
        }

        return view('dashboard', $data);
    }
}
