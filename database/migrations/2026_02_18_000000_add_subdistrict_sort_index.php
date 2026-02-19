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
        Schema::table('tenders', function (Blueprint $table) {
            // Index for sorting quickly by ID within a subdistrict
            // This is essential for "ORDER BY id DESC" queries filtered by subdistrict
            $table->index(['subdistrict_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropIndex(['subdistrict_id', 'id']);
        });
    }
};
