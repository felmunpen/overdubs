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
        Schema::create('lists_elements', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('list_id')->index('list_id');
            $table->bigInteger('album_id')->index('album_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lists_elements');
    }
};
