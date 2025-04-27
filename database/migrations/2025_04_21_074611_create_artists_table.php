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
        Schema::create('artists', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 30);
            $table->boolean('registered')->nullable()->default(false);
            $table->string('artist_pic', 400)->nullable()->default('https://www.onlinelogomaker.com/blog/wp-content/uploads/2017/06/music-logo-design.jpg');
            $table->bigInteger('user_id')->nullable()->index('user_id');
            $table->string('description', 1000)->nullable()->default('');
            $table->string('info', 1000)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
