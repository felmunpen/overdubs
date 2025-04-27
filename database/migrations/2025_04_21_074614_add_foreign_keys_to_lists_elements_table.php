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
        Schema::table('lists_elements', function (Blueprint $table) {
            $table->foreign(['list_id'], 'lists_elements_ibfk_1')->references(['id'])->on('lists')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['album_id'], 'lists_elements_ibfk_2')->references(['id'])->on('albums')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lists_elements', function (Blueprint $table) {
            $table->dropForeign('lists_elements_ibfk_1');
            $table->dropForeign('lists_elements_ibfk_2');
        });
    }
};
