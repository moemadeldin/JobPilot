<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Roles;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Role extends Model
{
    use SoftDeletes, HasUuids;

    protected $guarded = ['id'];
    protected function casts(): array
    {
        return [
            'name' => Roles::class,
        ];
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
