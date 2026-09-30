<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code', 30)->unique();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available')->index();
            $table->timestamps();

            $table->index(['game_id', 'status'], 'accounts_game_status_index');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
