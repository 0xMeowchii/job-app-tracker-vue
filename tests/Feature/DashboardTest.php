<?php

use App\Models\JobApplication;
use App\Models\JobSource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('dashboard includes application counts by source', function () {
    $user = User::factory()->create();
    $linkedin = JobSource::factory()->create([
        'user_id' => $user->id,
        'name' => 'LinkedIn',
    ]);
    $indeed = JobSource::factory()->create([
        'user_id' => $user->id,
        'name' => 'Indeed',
    ]);

    JobApplication::factory()->count(2)->create([
        'user_id' => $user->id,
        'job_source_id' => $linkedin->id,
    ]);
    JobApplication::factory()->create([
        'user_id' => $user->id,
        'job_source_id' => $indeed->id,
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('sourceChartData', 2)
            ->where('sourceChartData.0.source', 'Indeed')
            ->where('sourceChartData.0.count', 1)
            ->where('sourceChartData.1.source', 'LinkedIn')
            ->where('sourceChartData.1.count', 2)
            ->where('totals.total', 3)
        );
});
