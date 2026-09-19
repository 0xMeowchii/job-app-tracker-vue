<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    public const STATUSES = [
        'no response',
        'withdraw',
        'waiting',
        'rejected',
        'hired',
    ];

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_name',
        'job_title',
        'location',
        'application_date',
        'application_status',
        'job_source_id',
        'job_description',
        'job_url',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
            'job_url' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobSource(): BelongsTo
    {
        return $this->belongsTo(JobSource::class);
    }
}
