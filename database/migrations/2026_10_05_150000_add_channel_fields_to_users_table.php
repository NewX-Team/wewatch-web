<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('handle')->nullable()->after('email');
            $table->text('bio')->nullable()->after('handle');
            $table->string('avatar_url')->nullable()->after('bio');
            $table->string('banner_url')->nullable()->after('avatar_url');
            $table->string('tagline')->nullable()->after('banner_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['handle', 'bio', 'avatar_url', 'banner_url', 'tagline']);
        });
    }
};
