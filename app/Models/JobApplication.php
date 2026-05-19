<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    public const STATUSES = [
        'No Response',
        'withdraw',
        'waiting',
        'rejected',
        'hired',
    ];

    public const SOURCES = [
        'facebook',
        'indeed',
        'linkedin',
        'jobstreet',
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
        'source',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
