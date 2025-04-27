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
        Schema::create('albums', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 100);
            $table->bigInteger('artist_id')->index('artist_id');
            $table->string('cover', 300)->nullable();
            $table->integer('release_year')->nullable();
            $table->integer('average_rating')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
