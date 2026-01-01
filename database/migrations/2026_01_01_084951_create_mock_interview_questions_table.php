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
        Schema::create('mock_interview_questions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('job_application_id')
                ->index()
                ->nullable()
                ->constrained('job_applications')
                ->cascadeOnDelete();
            $table->longText('question')->nullable();
            $table->longText('answer')->nullable();
            $table->tinyInteger('order')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
