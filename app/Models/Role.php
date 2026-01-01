<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Roles;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property Roles $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, User> $users
 */
final class Role extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    protected function casts(): array
    {
        return [
            'name' => Roles::class,
        ];
    }
}
