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
            // Index for filtering by district + status (Common usage)
            $table->index(['district_id', 'status']);
            // Index for filtering by subdistrict + status
            $table->index(['subdistrict_id', 'status']);
            // Index for sorting quickly by ID within a district (Used in LocationExcelController)
            // (Note: district_id is already indexed via foreign key, but composite is faster for ORDER BY)
            $table->index(['district_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropIndex(['district_id', 'status']);
            $table->dropIndex(['subdistrict_id', 'status']);
            $table->dropIndex(['district_id', 'id']);
        });
    }
};
