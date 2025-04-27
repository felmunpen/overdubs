<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name')->unique('name');
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->useCurrent();
            $table->string('password');
            $table->string('usertype', 20)->nullable()->default('User');
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('gender', 20)->nullable();
            $table->integer('year')->nullable();
            $table->boolean('blocked')->nullable()->default(false);
            $table->string('profile_pic', 300)->nullable()->default('https://isobarscience.com/wp-content/uploads/2020/09/default-profile-picture1.jpg');
            $table->string('bio', 500)->nullable()->default('Music lover | Exploring new sounds | Creating vibes | Always tuned in to the beat | Passionate about rhythm and melody | Let’s share the soundtrack of life!');
            $table->string('country', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
