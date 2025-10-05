<?php

declare(strict_types=1);

use App\Enums\JobApplicationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignUuid('job_vacancy_id')
                ->nullable()
                ->constrained('job_vacancies')
                ->cascadeOnDelete();
            $table->foreignUuid('resume_id')
                ->nullable()
                ->constrained('resumes')
                ->cascadeOnDelete();
            $table->longText('cover_letter')->nullable();
            $table->decimal('compatibility_score', 5, 2)->nullable();
            $table->json('feedback')->nullable();
            $table->longText('improvement_suggestions')->nullable();
            $table->string('status')
                ->index()
                ->default(JobApplicationStatus::PENDING->value);
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
