<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained()->cascadeOnDelete();
            // Daily-rotating anonymous hash (no IP or personal data is stored)
            $table->string('visitor_hash', 64);
            $table->string('referrer_host')->nullable();
            $table->timestamp('created_at');

            $table->index(['link_id', 'created_at']);
            $table->index(['link_id', 'visitor_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
