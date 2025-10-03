<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Traits\Sluggable;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Company extends Model
{
    use HasFactory, HasUuids, Sluggable, SoftDeletes;

    public const NUMBER_OF_COMPANIES = 3;

    protected $guarded = ['id'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }

    #[Scope]
    protected function companies(Builder $query): Builder
    {
        return $query->with('owner');
    }

    #[Scope]
    protected function companiesByOwner(Builder $query, #[CurrentUser] User $user): Builder
    {
        return $query->companies()
            ->where('user_id', $user->id);
    }

    protected function casts(): array
    {
        return [
            'user_id' => 'string',
            'name' => 'string',
            'industry' => 'string',
            'address' => 'string',
            'website' => 'string',
            'is_active' => Status::class,
        ];
    }
}
