<?php

declare(strict_types=1);

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
        Schema::create('job_analytics', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('job_vacancy_id')
                ->nullable()
                ->constrained('job_vacancies')
                ->cascadeOnDelete();
            $table->date('activity_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
