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
        Schema::table('mis_office_inventory', function (Blueprint $table) {
            $table->string('usable_notes')->nullable()->after('condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mis_office_inventory', function (Blueprint $table) {
            $table->dropColumn('usable_notes');
        });
    }
};
