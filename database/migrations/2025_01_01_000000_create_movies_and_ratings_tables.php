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
        if (!Schema::hasTable('movies')) {
            Schema::create('movies', function (Blueprint $table) {
                $table->increments('movieId');
                $table->string('title');
                $table->string('genres')->nullable();
                $table->string('poster_filename')->nullable();
            });
        }

        if (!Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->unsignedInteger('userId');
                $table->unsignedInteger('movieId');
                $table->float('rating');
                $table->bigInteger('timestamp')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('movies');
    }
};
