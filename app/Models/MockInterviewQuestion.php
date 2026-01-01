<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string|null $job_application_id
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
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    protected function casts(): array
    {
        return [
            'job_application_id' => 'string',
            'question' => 'string',
            'answer' => 'string',
            'order' => 'integer',
        ];
    }
}
