<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StoreJobSourceRequest;
use App\Http\Requests\Settings\UpdateJobSourceRequest;
use App\Models\JobSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class JobSourceController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('settings/JobSources', [
            'sources' => $request->user()
                ->jobSources()
                ->withCount('jobApplications')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(StoreJobSourceRequest $request): RedirectResponse
    {
        $request->user()->jobSources()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Source created.')]);

        return to_route('job-sources.index');
    }

    public function update(UpdateJobSourceRequest $request, JobSource $jobSource): RedirectResponse
    {
        $jobSource->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Source updated.')]);

        return to_route('job-sources.index');
    }

    public function destroy(Request $request, JobSource $jobSource): RedirectResponse
    {
        abort_unless($jobSource->user_id === $request->user()->id, 403);

        if ($jobSource->jobApplications()->exists()) {
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => __('This source is used by existing applications and cannot be deleted.'),
            ]);

            return to_route('job-sources.index');
        }

        $jobSource->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Source deleted.')]);

        return to_route('job-sources.index');
    }
}
