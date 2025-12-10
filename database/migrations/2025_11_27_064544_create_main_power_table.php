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
        Schema::create('main_power', function (Blueprint $table) {
            $table->id();
            $table->String('main_power_device_id');
            $table->boolean('main_power_status')->default(false);
            $table->foreignId('gedung_id')->constrained('gedung')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_power');
    }
};
