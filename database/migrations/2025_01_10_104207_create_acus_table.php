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
        Schema::create('acus', function (Blueprint $table) {
            $table->id();
            $table->string("code", 50);
            $table->string('capacity', 100)->nullable(); //HP TR
            $table->string('type', 20)->nullable(); //WACU STACU PACU
            $table->string('installation_type', 30)->nullable(); // Wall mounted / Ceiling Mounted / floor mounted
            $table->string('operation_type', 30)->nullable(); // Normal / Inventer
            $table->string("manufacturer_id");
            $table->string("brand");
            $table->string("model");
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
        Schema::dropIfExists('acus');
    }
};
