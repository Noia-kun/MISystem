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
        Schema::create('borrow_logs', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->unsignedBigInteger('inventory_item_id');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->string('borrower_name');
            $table->timestamp('borrowed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_logs');
    }
};
