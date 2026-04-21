<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CustomJobApplicationFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $user_id
 * @property string $custom_job_vacancy_id
 * @property int|null $compatibility_score
 * @property array|null $feedback
 * @property array|null $improvement_suggestions
 * @property string|null $cover_letter
 * @property-read User $user
 * @property-read CustomJobVacancy $customJobVacancy
 * @property-read MockInterview|null $mockInterview
 */
final class CustomJobApplication extends Model
{
    /** @use HasFactory<CustomJobApplicationFactory> */
    use HasFactory;

    use HasUuids;
    use SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customJobVacancy(): BelongsTo
    {
        return $this->belongsTo(CustomJobVacancy::class, 'custom_job_vacancy_id');
    }

    public function mockInterview(): HasOne
    {
        return $this->hasOne(MockInterview::class, 'interviewable_id')
            ->where('interviewable_type', self::class);
    }

    protected static function newFactory(): CustomJobApplicationFactory
    {
        return CustomJobApplicationFactory::new();
    }

    protected function casts(): array
    {
        return [
            'compatibility_score' => 'integer',
            'feedback' => 'array',
            'improvement_suggestions' => 'array',
        ];
    }
}
