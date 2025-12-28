<?php

namespace App\Http\Controllers;

use App\Services\AlumniSkpiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, AlumniSkpiService $skpiService)
    {
        $user = $request->user();
        $data = [
            'title' => 'Dashboard',
            'latestSkpiRequest' => null,
            'skpiDocument' => null,
        ];

        // Add SKPI information for alumni users
        if ($user && $user->hasRole('alumni')) {
            $data['skpiSubmitted'] = $skpiService->isSkpiSubmitted($user);
            $data['skpiStatus'] = $skpiService->getSkpiStatus($user);
            $data['latestSubmission'] = $skpiService->getLatestSkpiSubmission($user);
            $data['canRequestSkpi'] = $skpiService->canRequestSkpi($user);
            $latestSkpiRequest = $user->skpiRequests()
                ->with('document')
                ->latest('created_at')
                ->first();
            $data['latestSkpiRequest'] = $latestSkpiRequest;
            $data['skpiDocument'] = $latestSkpiRequest?->document;
        }

        return view('dashboard', $data);
    }
}
