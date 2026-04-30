<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            if (!Schema::hasColumn('movies', 'video_filename')) {
                $table->string('video_filename')->nullable()->after('poster_filename');
            }
            if (!Schema::hasColumn('movies', 'description')) {
                $table->text('description')->nullable()->after('genres');
            }
            if (!Schema::hasColumn('movies', 'duration')) {
                $table->string('duration')->nullable()->after('description'); // e.g. "2h 49m"
            }
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn(['video_filename', 'description', 'duration']);
        });
    }
};
