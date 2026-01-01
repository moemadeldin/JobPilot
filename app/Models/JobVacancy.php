<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmploymentType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string|null $title
 * @property string|null $job_category_id
 * @property string|null $company_id
 * @property string|null $description
 * @property string|null $responsibilities
 * @property string|null $requirements
 * @property string|null $skills_required
 * @property int|null $experience_years_min
 * @property int|null $experience_years_max
 * @property string|null $nice_to_have
 * @property string|null $location
 * @property string|null $expected_salary
 * @property EmploymentType $employment_type
 * @property Status $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, ApplicationAnalytic> $applicationAnalytics
 * @property-read Collection<int, JobApplication> $applications
 * @property-read JobCategory|null $category
 * @property-read Company|null $company
 * @property-read Collection<int, JobAnalytic> $jobAnalytics
 */
final class JobVacancy extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

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
    protected function filterJobCategory(Builder $query, mixed $category): void
    {
        if (! empty($category)) {
            $query->whereHas('category', function (Builder $q) use ($category): void {
                $q->where('slug', $category instanceof JobCategory ? $category->slug : $category);
            });
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
            $query->where('status', $status);
        }
    }

    #[Scope]
    protected function filterLocation(Builder $query, mixed $location): void
    {
        if (! empty($location)) {
            $query->where('location', 'LIKE', sprintf('%%%s%%', $location));
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
            'status' => Status::class,
            'responsibilities' => 'string',
            'requirements' => 'string',
            'skills_required' => 'string',
            'experience_years_min' => 'integer',
            'experience_years_max' => 'integer',
            'nice_to_have' => 'string',
        ];
    }
}
