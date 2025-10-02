<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Profile extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

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
            'user_id' => 'integer',
            'first_name' => 'string',
            'last_name' => 'string',
            'avatar' => 'string',
            'phone' => 'string',
            'country' => 'string',
        ];
    }
}
