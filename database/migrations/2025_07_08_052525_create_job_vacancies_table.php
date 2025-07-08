<?php

declare(strict_types=1);

use App\Enums\EmploymentType;
use App\Enums\Status;
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
        Schema::create('job_vacancies', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title')->nullable();
            $table->foreignUuid('job_category_id')
                ->nullable()
                ->constrained('job_categories')
                ->cascadeOnDelete();
            $table->foreignUuid('company_id')
                ->nullable()
                ->constrained('companies')
                ->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->string('location')->index()->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->unsignedTinyInteger('employment_type')
                ->index()
                ->default(EmploymentType::FULL_TIME);
            $table->unsignedTinyInteger('is_active')
                ->index()
                ->default(value: Status::ACTIVE->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
