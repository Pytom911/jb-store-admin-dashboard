<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->boolean('is_cover')->default(false);
            $table->timestamps();

            // The cover is the one image every listing query filters on, so it is
            // indexed alongside account_id to keep that lookup a single index scan.
            $table->index(['account_id', 'is_cover']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_images');
    }
};
