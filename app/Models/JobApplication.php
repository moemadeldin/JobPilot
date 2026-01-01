<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JobApplicationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string|null $user_id
 * @property string|null $job_vacancy_id
 * @property string|null $resume_id
 * @property string|null $cover_letter
 * @property numeric|null $compatibility_score
 * @property array<array-key, mixed>|null $feedback
 * @property string|null $improvement_suggestions
 * @property JobApplicationStatus $status
 * @property Carbon|null $applied_at
 * @property Carbon|null $reviewed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read JobVacancy|null $jobVacancy
 * @property-read Resume|null $resume
 * @property-read User|null $user
 */
final class JobApplication extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

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
            'compatibility_score' => 'decimal:2',
            'feedback' => 'array',
            'improvement_suggestions' => 'string',
            'applied_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }
}
