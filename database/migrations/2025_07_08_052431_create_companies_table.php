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
            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('name')
                ->index()
                ->nullable();
            $table->string('slug')
                ->nullable()
                ->index()
                ->unique();
            $table->string('industry')
                ->index()
                ->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('is_active')
                ->index()
                ->default(Status::ACTIVE->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
