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
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $application_id
 * @property MockInterviewStatus $status
 * @property-read CustomJobApplication $application
 * @property-read Collection<int, MockInterviewQuestion> $questions
 */
final class MockInterview extends Model
{
    /** @use HasFactory<MockInterviewFactory> */
    use HasFactory;

    use HasUuids;
    use SoftDeletes;

    public function application(): BelongsTo
    {
        return $this->belongsTo(CustomJobApplication::class, 'application_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(MockInterviewQuestion::class)->orderBy('order');
    }

    protected function casts(): array
    {
        return [
            'application_id' => 'string',
            'status' => MockInterviewStatus::class,
        ];
    }
}
