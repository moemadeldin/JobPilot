<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MockInterviewStatus;
use Database\Factories\MockInterviewFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $user_id
 * @property string|null $interviewable_id
 * @property string|null $interviewable_type
 * @property MockInterviewStatus $status
 * @property-read User $user
 * @property-read Model|null $interviewable
 * @property-read Collection<int, MockInterviewQuestion> $questions
 */
final class MockInterview extends Model
{
    /** @use HasFactory<MockInterviewFactory> */
    use HasFactory;

    use HasUuids;
    use SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function questions(): HasMany
    {
        return $this->hasMany(MockInterviewQuestion::class)->orderBy('order');
    }

    protected static function newFactory(): MockInterviewFactory
    {
        return MockInterviewFactory::new();
    }

    protected function casts(): array
    {
        return [
            'status' => MockInterviewStatus::class,
        ];
    }
}
