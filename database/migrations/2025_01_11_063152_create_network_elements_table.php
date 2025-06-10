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
        Schema::create('network_elements', function (Blueprint $table) {
            $table->id();
            $table->string("code", 50);
            $table->string("site_id", 50)->nullable();
            $table->string("manufacturer_id", 50)->nullable();
            $table->string('type_id', 50)->nullable();
            $table->string('status', 20)->nullable(); 
            $table->string('device_ip_address', 30)->nullable(); 
            $table->string('software_version', 30)->nullable();
            $table->string("foc_assignment_uplink1", 30)->nullable();
            $table->string("foc_assignment_cid1", 30)->nullable();
            $table->string("foc_assignment_uplink2", 30)->nullable();
            $table->string("foc_assignment_cid2", 30)->nullable();
            $table->string("hon_assignment_uplink_port1", 30)->nullable();
            $table->string("homing_node1", 30)->nullable();
            $table->string("hon_assignment_uplink_port2", 30)->nullable();
            $table->string("homing_node2", 30)->nullable();
            $table->string("new_node_name")->nullable();
            $table->date("date_decommissioned")->nullable();
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
        Schema::dropIfExists('network_elements');
    }
};
