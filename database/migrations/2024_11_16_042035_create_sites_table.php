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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string("code", 50);
            $table->string('name', 100)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('category', 30)->nullable();
            $table->string('cabinet_type', 30)->nullable();
            $table->string('region', 30)->nullable();
            $table->string('province', 30)->nullable();
            $table->string('brgy', 60)->nullable();
            $table->string('street', 50)->nullable();
            $table->string('lot_no', 50)->nullable();
            $table->decimal('longitude', total:20, places:15)->nullable();
            $table->decimal('latitude', total:20, places:15)->nullable();
            $table->string('building_code', 50)->nullable();
            $table->string('building_floor', 50)->nullable();
            $table->string('exchange_code', 50)->nullable();
            $table->string('electric_company_code', 50)->nullable();
            $table->string('owner', 50)->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('changed_by', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
