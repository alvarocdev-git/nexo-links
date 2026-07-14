<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('platform', 20);
            $table->string('value');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['page_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
