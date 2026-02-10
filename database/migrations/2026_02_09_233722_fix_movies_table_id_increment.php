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
        try {
            // Check current ID type and set to auto_increment
            \DB::statement('ALTER TABLE movies MODIFY COLUMN movieId INT AUTO_INCREMENT');
        } catch (\Exception $e) {
            // If INT fails, try BIGINT
            \DB::statement('ALTER TABLE movies MODIFY COLUMN movieId BIGINT AUTO_INCREMENT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement('ALTER TABLE movies MODIFY COLUMN movieId INT');
    }
};
