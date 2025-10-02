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
        Schema::create('role_user', function (Blueprint $table): void {
            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignUuid('role_id')
                ->nullable()
                ->constrained('roles')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
