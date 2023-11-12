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
        Schema::create('turns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game');
            $table->integer('round');
            $table->unsignedBigInteger('player');
            $table->unsignedBigInteger('hand');
            $table->timestamps();

            $table->foreign('game')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('player')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('hand')->references('id')->on('hands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turns');
    }
};
