<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['user_id', 'name']);
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreignId('job_source_id')
                ->nullable()
                ->after('application_status')
                ->constrained()
                ->restrictOnDelete();
        });

        $applications = DB::table('job_applications')->select('id', 'user_id', 'source')->get();
        $sourceIds = [];

        foreach ($applications as $application) {
            $name = filled($application->source) ? $application->source : 'Unknown';
            $key = $application->user_id.'|'.mb_strtolower($name);

            if (! isset($sourceIds[$key])) {
                $sourceIds[$key] = DB::table('job_sources')->insertGetId([
                    'user_id' => $application->user_id,
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('job_applications')->where('id', $application->id)->update([
                'job_source_id' => $sourceIds[$key],
            ]);
        }

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('source')->default('indeed')->after('application_status');
        });

        $applications = DB::table('job_applications')
            ->leftJoin('job_sources', 'job_sources.id', '=', 'job_applications.job_source_id')
            ->select('job_applications.id', 'job_sources.name')
            ->get();

        foreach ($applications as $application) {
            DB::table('job_applications')->where('id', $application->id)->update([
                'source' => $application->name ?? 'indeed',
            ]);
        }

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('job_source_id');
        });

        Schema::dropIfExists('job_sources');
    }
};
