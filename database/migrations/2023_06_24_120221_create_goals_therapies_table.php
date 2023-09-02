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
        Schema::create('goals_therapies', function (Blueprint $table) {
            $table->bigInteger('TherapyID');
            $table->bigInteger('ID')->primary();
            $table->string('Name')->index('goalName');

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
        Schema::dropIfExists('goals_therapies');
    }
};
