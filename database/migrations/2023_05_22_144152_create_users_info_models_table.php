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
        Schema::create('UsersInfo', function (Blueprint $table) {
            $table->string('FIO', 100);
            $table->integer('PassportNum')->primary()->index();
            $table->integer('DiplomaNum')->unique()->index();
            $table->string('OrganizationName', 70)->nullable(); // Название организации
            $table->string('Specialization', 40); // Специализация в этой организации
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('UsersInfo');
    }
};
