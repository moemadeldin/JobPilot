<?php

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
            $table->unsignedTinyInteger('status')
            ->index()
            ->default(JobApplicationStatus::PENDING->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
