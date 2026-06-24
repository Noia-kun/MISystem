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
        Schema::create('sister_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('propertynum');
            $table->string('serialnum')->nullable();
            $table->string('category');
            $table->string('item_set')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->enum('condition', ['Good', 'Usable', 'For Replacement/Disposal'])->default('Good');
            $table->string('usable_notes')->nullable();
            $table->date('date_purchased');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sister_inventory');
    }
};
