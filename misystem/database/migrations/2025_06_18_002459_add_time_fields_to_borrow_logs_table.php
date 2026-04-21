<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::table('borrow_logs', function (Blueprint $table) {
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::table('borrow_logs', function (Blueprint $table) {
            $table->dropColumn('time_from');
            $table->dropColumn('time_to');
        });
    }
};
