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
        Schema::create('dc_panel_items', function (Blueprint $table) {
            $table->id();
            $table->string("code", 50);
            $table->string("dc_panel_id", 50)->nullable();
            $table->string("ne_id", 50)->nullable();
            $table->string("breaker_no", 50)->nullable();
            $table->string("current", 50)->nullable();
            $table->text("remarks")->nullable();
            $table->string("created_by", 50)->nullable();
            $table->string("changed_by", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dc_panel_items');
    }
};
