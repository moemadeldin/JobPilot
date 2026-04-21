<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MockInterviewQuestionFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string|null $mock_interview_id
 * @property string|null $question
 * @property string|null $answer
 * @property string|null $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, JobApplication> $applications
 */
final class MockInterviewQuestion extends Model
{
    /** @use HasFactory<MockInterviewQuestionFactory> */
    use HasFactory;

    use HasUuids;
    use SoftDeletes;

    /**
     * @return BelongsTo<MockInterview, $this>
     */
    public function mockInterview(): BelongsTo
    {
        return $this->belongsTo(MockInterview::class, 'mock_interview_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mock_interview_id' => 'string',
            'question' => 'string',
            'answer' => 'string',
            'order' => 'integer',
        ];
    }
}
