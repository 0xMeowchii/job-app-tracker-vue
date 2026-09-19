<?php

namespace Database\Factories;

use App\Models\JobApplication;
use App\Models\JobSource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => fake()->company(),
            'job_title' => fake()->jobTitle(),
            'location' => fake()->city(),
            'application_date' => fake()->date(),
            'application_status' => fake()->randomElement(JobApplication::STATUSES),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (JobApplication $application): void {
            if ($application->job_source_id) {
                return;
            }

            $source = JobSource::factory()->create([
                'user_id' => $application->user_id,
            ]);

            $application->update([
                'job_source_id' => $source->id,
            ]);
        });
    }
}
