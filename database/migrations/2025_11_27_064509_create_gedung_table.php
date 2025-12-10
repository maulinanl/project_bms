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
        Schema::create('gedung', function (Blueprint $table) {
            $table->id();
            $table->integer('gateway_id');
            $table->string('building_name');
            $table->string('building_adress');
            $table->string('building_longitude');
            $table->string('building_latitude');
            $table->integer('building_daya');
            $table->boolean('gateway_status');
            $table->softDeletes();
            $table->timestamps();
            $table->string('foto_building')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gedung');
    }
};
