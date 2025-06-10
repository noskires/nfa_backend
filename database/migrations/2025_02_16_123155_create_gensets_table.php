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
        Schema::create('gensets', function (Blueprint $table) {
            $table->id();
            $table->string("code", 50);
            $table->string('capacity', 100)->nullable();
            $table->string('rating', 20)->nullable(); 
            $table->string('type', 20)->nullable(); 
            $table->string('percent_utilization', 30)->nullable();
            $table->string('status', 10)->nullable();
            $table->string("manufacturer_id", 3)->nullable();
            $table->string("brand", 50)->nullable();
            $table->string("model", 50)->nullable();
            $table->string("owner", 50)->nullable();
            $table->date("date_manufactured")->nullable();
            $table->date("date_installed")->nullable();
            $table->date("date_accepted")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gensets');
    }
};
