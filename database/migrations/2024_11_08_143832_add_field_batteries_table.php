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
        Schema::table('batteries', function (Blueprint $table) {
            $table->string("individual_cell_voltage", 25)->after("brand");
            $table->string("no_of_cells", 3)->after("brand");
            $table->string("cell_status", 25)->after("brand");
            $table->string("backup_time", 25)->after("brand");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batteries', function (Blueprint $table) {
            //
        });
    }
};
