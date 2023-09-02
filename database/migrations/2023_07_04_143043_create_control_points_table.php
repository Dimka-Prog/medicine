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
        Schema::create('control_points', function (Blueprint $table) {
            $table->bigInteger('TherapyID');
            $table->bigInteger('ID');
            $table->integer('ElapsedTime');
            $table->string('UnitMeasurement');
            $table->string('Symptom')->index('control_disease');
            $table->string('ValueSymptom');

            $table->primary(['ID', 'ElapsedTime']);
            $table->foreign('TherapyID')
                ->references('ID')
                ->on('therapies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_points');
    }
};
