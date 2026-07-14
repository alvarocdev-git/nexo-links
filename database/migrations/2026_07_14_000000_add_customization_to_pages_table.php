<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('banner_path')->nullable()->after('avatar_path');
            $table->string('background_type', 10)->default('default')->after('theme');
            $table->string('background_start', 7)->nullable()->after('background_type');
            $table->string('background_end', 7)->nullable()->after('background_start');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['banner_path', 'background_type', 'background_start', 'background_end']);
        });
    }
};
