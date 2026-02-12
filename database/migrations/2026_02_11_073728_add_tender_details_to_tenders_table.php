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
            $table->string('tender_no')->nullable()->after('id');
            $table->string('department')->nullable()->after('description');
            $table->string('state')->default('Assam')->after('department');
            $table->date('publish_date')->nullable()->after('state');
            $table->date('closing_date')->nullable()->after('publish_date');
            $table->decimal('value', 15, 2)->nullable()->after('closing_date');
        });

        // Generate tender_no for existing records
        $tenders = \App\Models\Tender::whereNull('tender_no')->get();
        foreach ($tenders as $index => $tender) {
            $tender->tender_no = 'TND-' . str_pad($tender->id, 5, '0', STR_PAD_LEFT);
            $tender->save();
        }

        // Now make tender_no unique
        Schema::table('tenders', function (Blueprint $table) {
            $table->unique('tender_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn(['tender_no', 'department', 'state', 'publish_date', 'closing_date', 'value']);
        });
    }
};
