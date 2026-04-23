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
        Schema::table('room_schedules', function (Blueprint $table) {
            $table->string('material')->nullable()->after('purpose');
        });

        Schema::table('request_logs', function (Blueprint $table) {
        $table->string('material')->nullable()->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_schedules', function (Blueprint $table) {
            $table->dropColumn('material');
        });
        Schema::table('request_logs', function (Blueprint $table) {
            $table->dropColumn('material');
        });
    }
};
