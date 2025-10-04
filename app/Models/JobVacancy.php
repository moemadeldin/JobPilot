<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmploymentType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class JobVacancy extends Model
{
    public const NUMBER_OF_PAGINATED_JOB_VACANCIES = 6;
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function jobAnalytics(): HasMany
    {
        return $this->hasMany(JobAnalytic::class);
    }

    public function applicationAnalytics(): HasMany
    {
        return $this->hasMany(ApplicationAnalytic::class);
    }

    #[Scope]
    protected function filterJobCategory(Builder $query, mixed $jobCategory): void
    {
        if (! empty($jobCategory)) {
            $query->where('job_category_id', $jobCategory);
        }
    }

    #[Scope]
    protected function filterEmploymentType(Builder $query, mixed $employmentType): void
    {
        if (! empty($employmentType)) {
            $query->where('employment_type', $employmentType);
        }
    }

    #[Scope]
    protected function filterStatus(Builder $query, mixed $status): void
    {
        if (! empty($status)) {
            $query->where('is_active', $status);
        }
    }

    #[Scope]
    protected function filterLocation(Builder $query, mixed $location): void
    {
        if (! empty($location)) {
            $query->where('location', 'LIKE', "%{$location}%");
        }
    }

    protected function casts(): array
    {
        return [
            'title' => 'string',
            'job_category_id' => 'string',
            'company_id' => 'string',
            'description' => 'string',
            'location' => 'string',
            'expected_salary' => 'string',
            'employment_type' => EmploymentType::class,
            'is_active' => Status::class,
        ];
    }
}
