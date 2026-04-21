<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Roles;
use App\Enums\Status;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * @property string $id
 * @property string|null $username
 * @property string|null $email
 * @property Carbon|null $email_verified_at
 * @property string|null $password
 * @property Status $status
 * @property string|null $verification_code
 * @property Carbon|null $verification_code_expire_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $access_token
 * @property-read Collection<int, UserAnalytic> $analytics
 * @property-read Collection<int, JobApplication> $applications
 * @property-read Collection<int, Company> $companies
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read Profile|null $profile
 * @property-read Resume|null $resume
 * @property-read Collection<int, Role> $roles
 * @property-read Collection<int, PersonalAccessToken> $tokens
 */
final class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasUuids;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return HasOne<Profile, $this>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * @return HasOne<Resume, $this>
     */
    public function resume(): HasOne
    {
        return $this->hasOne(Resume::class);
    }

    /**
     * @return HasMany<Company, $this>
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    /**
     * @return HasMany<JobApplication, $this>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * @return HasMany<UserAnalytic, $this>
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(UserAnalytic::class);
    }

    /**
     * @return HasMany<CustomJobVacancy, $this>
     */
    public function customJobVacancies(): HasMany
    {
        return $this->hasMany(CustomJobVacancy::class);
    }
    /**
     * @return HasMany<CustomJobApplication, $this>
     */
    public function customJobApplications(): HasMany
    {
        return $this->hasMany(CustomJobApplication::class);
    }

    public function isAdmin(): bool
    {
        return $this->roles->contains('name', Roles::ADMIN);
    }

    public function isOwner(): bool
    {
        return $this->roles->contains('name', Roles::OWNER);
    }

    public function isActive(): bool
    {
        return $this->status === Status::ACTIVE;
    }

    /**
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    #[Scope]
    protected function getUserByEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'username' => 'string',
            'email' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => Status::class,
            'verification_code' => 'string',
            'verification_code_expire_at' => 'datetime',
        ];
    }
}
