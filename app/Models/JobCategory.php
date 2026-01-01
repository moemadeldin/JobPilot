<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string|null $name
 * @property string|null $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, JobVacancy> $vacancies
 */
final class JobCategory extends Model
{
    use HasFactory;
    use HasUuids;
    use Sluggable;
    use SoftDeletes;

    public function vacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'slug' => 'string',
        ];
    }
}
