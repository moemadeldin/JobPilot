<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class JobCategory extends Model
{
    use SoftDeletes, HasUuids;

    protected $guarded = ['id'];

    public function vacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }
}
