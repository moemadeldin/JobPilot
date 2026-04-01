<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ApplicationAnalyticFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string|null $job_vacancy_id
 * @property Carbon|null $activity_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read JobVacancy|null $jobVacancy
 */
final class ApplicationAnalytic extends Model
{
    /** @use HasFactory<ApplicationAnalyticFactory> */
    use HasFactory;

    use HasUuids;
    use SoftDeletes;

    /**
     * @return BelongsTo<JobVacancy, $this>
     */
    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'job_vacancies_id' => 'string',
            'activity_date' => 'datetime',
        ];
    }
}
