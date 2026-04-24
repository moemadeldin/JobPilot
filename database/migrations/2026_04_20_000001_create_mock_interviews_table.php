<?php

declare(strict_types=1);

use App\Enums\MockInterviewStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_interviews', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('status')
                ->default(MockInterviewStatus::SUGGESTED->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
