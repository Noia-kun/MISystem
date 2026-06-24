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
        Schema::create('sister_inventory_usable_notes_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('sister_inventory')->onDelete('cascade');
            $table->string('usable_notes')->nullable();
            $table->date('fixed_date');
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sister_inventory_usable_notes_histories');
    }
};
