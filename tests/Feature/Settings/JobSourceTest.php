<?php

use App\Models\JobApplication;
use App\Models\JobSource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('job sources page is displayed', function () {
    $user = User::factory()->create();
    $source = JobSource::factory()->create([
        'user_id' => $user->id,
        'name' => 'LinkedIn',
    ]);

    $this->actingAs($user)
        ->get(route('job-sources.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/JobSources')
            ->has('sources', 1)
            ->where('sources.0.id', $source->id)
            ->where('sources.0.name', 'LinkedIn')
        );
});

test('users can create a job source', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('job-sources.store'), [
            'name' => 'Indeed',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('job-sources.index'));

    expect($user->jobSources()->where('name', 'Indeed')->exists())->toBeTrue();
});

test('users cannot create duplicate job sources', function () {
    $user = User::factory()->create();
    JobSource::factory()->create([
        'user_id' => $user->id,
        'name' => 'Indeed',
    ]);

    $this->actingAs($user)
        ->from(route('job-sources.index'))
        ->post(route('job-sources.store'), [
            'name' => 'Indeed',
        ])
        ->assertSessionHasErrors('name');
});

test('users can update a job source', function () {
    $user = User::factory()->create();
    $source = JobSource::factory()->create([
        'user_id' => $user->id,
        'name' => 'Indeed',
    ]);

    $this->actingAs($user)
        ->patch(route('job-sources.update', $source), [
            'name' => 'JobStreet',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('job-sources.index'));

    expect($source->refresh()->name)->toBe('JobStreet');
});

test('users cannot update another users job source', function () {
    $user = User::factory()->create();
    $source = JobSource::factory()->create([
        'name' => 'Indeed',
    ]);

    $this->actingAs($user)
        ->patch(route('job-sources.update', $source), [
            'name' => 'Hacked',
        ])
        ->assertForbidden();

    expect($source->refresh()->name)->toBe('Indeed');
});

test('users can delete a job source that is not in use', function () {
    $user = User::factory()->create();
    $source = JobSource::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->delete(route('job-sources.destroy', $source))
        ->assertRedirect(route('job-sources.index'));

    expect(JobSource::query()->whereKey($source->id)->exists())->toBeFalse();
});

test('users cannot delete a job source that is in use', function () {
    $user = User::factory()->create();
    $source = JobSource::factory()->create([
        'user_id' => $user->id,
    ]);

    JobApplication::factory()->create([
        'user_id' => $user->id,
        'job_source_id' => $source->id,
    ]);

    $this->actingAs($user)
        ->delete(route('job-sources.destroy', $source))
        ->assertRedirect(route('job-sources.index'));

    expect(JobSource::query()->whereKey($source->id)->exists())->toBeTrue();
});

test('job application forms receive the users sources', function () {
    $user = User::factory()->create();
    $source = JobSource::factory()->create([
        'user_id' => $user->id,
        'name' => 'LinkedIn',
    ]);
    JobSource::factory()->create([
        'name' => 'Someone else',
    ]);

    $this->actingAs($user)
        ->get(route('JobApplication.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('JobApplication/Index')
            ->has('sourceOptions', 1)
            ->where('sourceOptions.0.id', $source->id)
            ->where('sourceOptions.0.name', 'LinkedIn')
        );
});
