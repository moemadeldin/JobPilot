<?php

declare(strict_types=1);

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
        Schema::create('companies', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('name')->index()->nullable();
            $table->string('industry')->index()->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
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
        Schema::dropIfExists('companies');
    }
};
