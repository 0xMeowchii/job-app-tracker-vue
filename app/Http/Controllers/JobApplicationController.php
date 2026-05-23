<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Http\Requests\UpdateJobApplicationRequest;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class JobApplicationController extends Controller
{
    public function index(Request $request): Response
    {
        $user = Auth::user();

        $search = $request->input('search');
        
        $applications = JobApplication::query()
            ->where('user_id', $user->id)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                        ->orWhere('job_title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('application_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return inertia('JobApplication/Index', [
            'application' => $applications,
            'statusOptions' => JobApplication::STATUSES,
            'sourceOptions' => JobApplication::SOURCES,
            'filters' => ['search' => $search],
        ]);
    }

    public function store(StoreJobApplicationRequest $request): RedirectResponse
    {
        $request->user()->jobApplications()->create($request->validated());

        return to_route('JobApplication.index')->with('success', 'Job Application created successfully.');
    }

    public function update(UpdateJobApplicationRequest $request, JobApplication $JobApplication): RedirectResponse
    {
        abort_unless($JobApplication->user_id === $request->user()->id, 403);

        $JobApplication->update($request->validated());

        return to_route('JobApplication.index')->with('success', 'Application updated successfully.');
    }

    public function destroy(JobApplication $JobApplication)
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($JobApplication->user_id === $user->id, 403);

        $JobApplication->delete();

        return to_route('JobApplication.index')->with('success', 'Application deleted successfully.');
    }
}
