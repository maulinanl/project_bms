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
        Schema::create('single_power', function (Blueprint $table) {
            $table->id();
            $table->String('single_power_device_id');
            $table->float('single_power_limit_beban');
            $table->string('single_power_brand');
            $table->boolean('single_power_status')->default(false);
            $table->foreignId('ruangan_id')->constrained('ruangan')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('single_power');
    }
};
