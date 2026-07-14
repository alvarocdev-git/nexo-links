<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('link_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('reason', 20);
            $table->string('details', 500)->nullable();
            $table->string('status', 10)->default('open');
            // Same anonymous daily hash used by analytics; limits duplicates
            $table->string('visitor_hash', 64);
            $table->timestamps();

            $table->index(['page_id', 'status']);
            $table->index(['page_id', 'visitor_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
