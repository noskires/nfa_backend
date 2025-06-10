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
        Schema::create('dc_panels', function (Blueprint $table) {
            $table->id();
            $table->string("code", 50);
            $table->string("site_id", 50)->nullable();
            $table->string("rectifier_id", 50)->nullable();
            $table->string("manufacturer_id", 50)->nullable();
            $table->string("index", 50)->nullable(); //should be index_no
            $table->string("model", 50)->nullable();
            $table->string("status", 50)->nullable();
            $table->string("maintainer", 50)->nullable();
            $table->string("fuse_breaker_number", 50)->nullable();
            $table->string("fuse_breaker_rating", 50)->nullable();
            $table->string("feed_source", 50)->nullable();
            $table->string("no_of_runs_and_cable_size", 50)->nullable();
            $table->string("source_voltage", 50)->nullable();
            $table->string("source_electric_current", 50)->nullable();
            $table->string("status_of_breakers", 50)->nullable();
            $table->date("date_decommissioned")->nullable();
            $table->date("date_installed")->nullable();
            $table->date("date_accepted")->nullable();
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
        Schema::dropIfExists('dc_panels');
    }
};
