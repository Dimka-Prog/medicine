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
        Schema::create('Users', function (Blueprint $table) {
            $table->integer('PassportNum')->primary()->index();
            $table->string('Email', 255)->unique()->index();
            $table->timestamp('EmailVerifiedAt')->nullable(); // Дата подтверждения электронной почты
            $table->string('Password', 255)->index();
            $table->rememberToken()->nullable(); // Токен для опции "запомнить меня"
            $table->timestamps(); // Дата и время создания записи

            $table->foreign('PassportNum')
                ->references('PassportNum')
                ->on('UsersInfo')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Users');
    }
};
