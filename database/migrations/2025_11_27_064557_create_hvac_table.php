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
        Schema::create('hvac', function (Blueprint $table) {
            $table->id();
            $table->integer('hvac_type');
            $table->String('hvac_device_id');
            $table->string('hvac_brand');
            $table->float('hvac_pk');
            $table->boolean('hvac_status')->default(false);
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
        Schema::dropIfExists('hvac');
    }
};
