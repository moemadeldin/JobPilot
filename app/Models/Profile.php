<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string|null $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $avatar
 * @property string|null $phone
 * @property string|null $country
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User|null $user
 */
final class Profile extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'user_id' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'avatar' => 'string',
            'phone' => 'string',
            'country' => 'string',
        ];
    }
}
