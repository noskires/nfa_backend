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
        Schema::create('rectifiers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->nullable();
            $table->string('site_id', 50)->nullable();
            $table->string('manufacturer_id', 50)->nullable();
            $table->string('maintainer', 50)->nullable();
            $table->string('serial_no', 50)->nullable();
            $table->string('index_no', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->date("date_installed")->nullable();
            $table->date("date_accepted")->nullable();
            $table->string('rectifier_system_name', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('brand', 50)->nullable();
            $table->tinyInteger('no_of_existing_module')->nullable();
            $table->tinyInteger('no_of_slots')->nullable();
            $table->decimal('capacity_per_module', total:15, places:10)->nullable();
            $table->decimal('full_capacity', total:15, places:10)->nullable();
            $table->decimal('dc_voltage', total:15, places:10)->nullable();
            $table->decimal('total_actual_load', total:15, places:10)->nullable();
            $table->decimal('percent_utilization', total:15, places:10)->nullable();
            $table->string('external_alarm_activation', 50)->nullable();
            $table->string('no_of_runs_and_cable_size', 50)->nullable();
            $table->string('tvss_brand_rating', 50)->nullable();
            $table->string('rectifier_dc_breaker_brand', 50)->nullable();
            $table->string('rectifier_battery_slot', 50)->nullable();
            $table->string('dcpdb_equipment_load_assignment', 50)->nullable();
            $table->string('remarks', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rectifiers');
    }
};
