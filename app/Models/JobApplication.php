<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JobApplicationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class JobApplication extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class);
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    protected function casts(): array
    {
        return [
            'user_id' => 'string',
            'job_vacancy_id' => 'string',
            'status' => JobApplicationStatus::class,
            'resume_id' => 'string',
            'cover_letter' => 'string',
            'compatibility_score' => 'decimal',
            'feedback' => 'json',
            'improvement_suggestions' => 'string',
            'applied_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }
}
