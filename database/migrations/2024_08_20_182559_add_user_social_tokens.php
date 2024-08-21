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
            $table->string('facebook_id')->nullable()->unique();
            $table->string('facebook_token', 265)->nullable();
            $table->string('facebook_refresh_token')->nullable();

            $table->string('apple_id')->nullable()->unique();
            $table->string('apple_token')->nullable();
            $table->string('apple_refresh_token')->nullable();

            $table->string('google_id')->nullable()->unique();
            $table->string('google_token')->nullable();
            $table->string('google_refresh_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_id', 'facebook_token', 'facebook_refresh_token',
                'apple_id', 'apple_token', 'apple_refresh_token',
                'google_id', 'google_token', 'google_refresh_token',
            ]);
        });
    }
};
