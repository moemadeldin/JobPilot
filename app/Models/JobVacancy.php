<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmploymentType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class JobVacancy extends Model
{
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
