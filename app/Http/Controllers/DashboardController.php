<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $user = Auth::user();

        $applications = $user->jobApplications();
        $total = (clone $applications)->count();
        $statusCounts = (clone $applications)
            ->selectRaw('application_status, COUNT(*) as total')
            ->groupBy('application_status')
            ->pluck('total', 'application_status');

        $chartData = collect(JobApplication::STATUSES)->map(fn (string $status): array => [
            'status' => $status,
            'count' => (int) ($statusCounts[$status] ?? 0),
        ]);

        return Inertia::render('Dashboard', [
            'totals' => [
                'total' => $total,
                'hired' => (int) ($statusCounts['hired'] ?? 0),
                'rejected' => (int) ($statusCounts['rejected'] ?? 0),
                'waiting' => (int) ($statusCounts['waiting'] ?? 0),
                'no_response' => (int) ($statusCounts['no response'] ?? 0),
            ],
            'chartData' => $chartData,
        ]);
    }
}
